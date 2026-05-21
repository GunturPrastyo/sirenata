/**
 * step3-goal-seek-ui.js — UI Goal-Seek / Adjustment Laju Pertumbuhan
 * RTK Makro v8 | Rahmad Saleh | 2026
 *
 * Mengintegrasikan GoalSeek (goal-seek.js) ke dalam tampilan Step 3:
 *  1. Tombol 🎯 pada baris "Selisih" di panel AK / PT / TPT
 *  2. Floating button "🎯 Goal-Seek" yang selalu tampil di Step 3
 *  3. Modal interaktif: isi target → preview laju → terapkan ke P.lajuOverrides
 *  4. Badge "Override Aktif" di header Step 3
 *
 * Load order: setelah step3-main.js (yang mendefinisikan buildStep3 &
 * _addTargetRows dari step3-panels-standard.js)
 */

'use strict';

/* ─────────────────────────────────────────────────────────────────────────
 * BAGIAN 1: CSS — injeksi satu kali ke <head>
 * ───────────────────────────────────────────────────────────────────────── */
(function _injectGoalSeekCSS() {
  if (document.getElementById('gs-style')) return;
  const s = document.createElement('style');
  s.id = 'gs-style';
  s.textContent = `
    /* Tombol kecil di sel Selisih */
    .gs-cell-btn {
      display:inline-block; margin-left:6px; cursor:pointer;
      background:#fef9c3; border:1px solid #fde047; border-radius:4px;
      padding:1px 5px; font-size:10px; color:#854d0e; font-weight:600;
      vertical-align:middle; white-space:nowrap;
      transition: background .15s;
    }
    .gs-cell-btn:hover { background:#fde047; }

    /* Badge override aktif di sel */
    .gs-override-badge {
      display:inline-block; margin-left:4px;
      background:#dcfce7; border:1px solid #86efac; border-radius:3px;
      padding:1px 4px; font-size:9px; color:#166534; font-weight:700;
      vertical-align:middle;
    }

    /* Overlay modal */
    #gs-overlay {
      display:none; position:fixed; inset:0; z-index:9000;
      background:rgba(15,23,42,.55); backdrop-filter:blur(2px);
    }
    #gs-overlay.open { display:flex; align-items:center; justify-content:center; }

    /* Modal card */
    #gs-modal {
      background:#fff; border-radius:12px; width:480px; max-width:96vw;
      max-height:92vh; overflow-y:auto;
      box-shadow:0 12px 40px rgba(0,0,0,.35);
      padding:22px 24px;
    }
    #gs-modal h3 { margin:0 0 14px; font-size:14px; color:#1e3a8a; display:flex; align-items:center; gap:8px; }
    .gs-form-row { margin-bottom:12px; }
    .gs-form-row label { display:block; font-size:11px; color:#475569; margin-bottom:3px; font-weight:600; }
    .gs-form-row select, .gs-form-row input[type=number], .gs-form-row input[type=text] {
      width:100%; padding:6px 9px; border:1px solid #cbd5e1;
      border-radius:6px; font-size:12px; color:#1e293b; box-sizing:border-box;
      background:#f8fafc;
    }
    .gs-form-row select:focus, .gs-form-row input:focus {
      outline:none; border-color:#6366f1; background:#fff;
    }
    #gs-result-box {
      background:#f0f9ff; border:1px solid #bae6fd; border-radius:8px;
      padding:12px 14px; margin:14px 0; font-size:12px; line-height:1.8;
      display:none;
    }
    #gs-result-box.show { display:block; }
    #gs-result-box.warn { background:#fffbeb; border-color:#fde047; }
    #gs-result-box.error { background:#fef2f2; border-color:#fca5a5; }
    .gs-laju-highlight { font-size:15px; font-weight:700; color:#1d4ed8; }
    .gs-btn-row { display:flex; gap:8px; margin-top:16px; }
    .gs-btn { padding:7px 16px; border:none; border-radius:6px; font-size:12px; font-weight:600; cursor:pointer; }
    .gs-btn-preview  { background:#e0e7ff; color:#3730a3; }
    .gs-btn-apply    { background:#16a34a; color:#fff; }
    .gs-btn-clear    { background:#fee2e2; color:#991b1b; }
    .gs-btn-close    { background:#f1f5f9; color:#475569; margin-left:auto; }
    .gs-btn:hover    { filter:brightness(.93); }
    .gs-divider      { border:none; border-top:1px solid #e2e8f0; margin:14px 0; }
    /* Override aktif list */
    #gs-override-list { margin-top:10px; }
    .gs-ov-row { display:flex; align-items:center; gap:8px; padding:5px 0; border-bottom:1px solid #f1f5f9; font-size:11px; }
    .gs-ov-row .gs-ov-lbl { flex:1; color:#334155; }
    .gs-ov-row .gs-ov-val { font-weight:700; color:#1d4ed8; }
    .gs-ov-row .gs-ov-del { cursor:pointer; color:#dc2626; padding:1px 6px; border-radius:4px; background:#fee2e2; font-size:10px; }
    .gs-ov-row .gs-ov-del:hover { background:#fca5a5; }
  `;
  document.head.appendChild(s);
})();


/* ─────────────────────────────────────────────────────────────────────────
 * BAGIAN 2: HELPER — ambil nilai dari computed
 * ───────────────────────────────────────────────────────────────────────── */

/** Cari histCode dari projCode via INDIKATOR_DEFS */
function _gsFindHistCode(projCode) {
  if (typeof INDIKATOR_DEFS === 'undefined') return null;
  for (var i = 0; i < INDIKATOR_DEFS.length; i++) {
    var ind = INDIKATOR_DEFS[i];
    if (!ind.chars) continue;
    for (var j = 0; j < ind.chars.length; j++) {
      var ch = ind.chars[j];
      if (ch.projCode === projCode) return ch.histCode || null;
    }
  }
  return null;
}

/** Helper internal: cari Jumlah dari satu kode di computed */
function _gsJumlahFromCode(code, year) {
  if (typeof computed === 'undefined' || !computed[code]) return null;
  var data = computed[code];
  if (!data.rows || !data.years) return null;
  var totRow = data.rows.find(function (r) {
    return r.label === 'Jumlah' || r.label === 'JUMLAH';
  });
  if (!totRow) return null;
  var idx = data.years.indexOf(Number(year));
  return idx >= 0 ? totRow.vals[idx] : null;
}

/**
 * Ambil nilai "Jumlah" — coba projCode dulu, lalu histCode.
 * Urutan prioritas tahun: year → P.hZ → P.td → yr-1
 */
function _gsGetJumlah(projCode, year) {
  var val = _gsJumlahFromCode(projCode, year);
  if (val !== null && val !== undefined) return val;
  var histCode = _gsFindHistCode(projCode);
  if (histCode) {
    val = _gsJumlahFromCode(histCode, year);
    if (val !== null && val !== undefined) return val;
  }
  return null;
}

/**
 * Deteksi nilai dasar terbaik untuk projCode: cek yr-1 di proj,
 * lalu P.hZ di hist, lalu P.td.
 */
function _gsAutoBase(projCode, yr) {
  var candidates = [yr - 1, P.hZ, P.td];
  for (var i = 0; i < candidates.length; i++) {
    var v = _gsGetJumlah(projCode, candidates[i]);
    if (v !== null && v !== undefined && v > 0) return { val: v, yr: candidates[i] };
  }
  return null;
}

/**
 * Ambil semua baris (bukan Jumlah) untuk proporsional.
 * Coba yr-1 (proj) → P.hZ (hist) → P.td.
 * Menyertakan histLaju (laju historis %) dari rataTambahanRows jika tersedia.
 */
function _gsGetBaseRows(projCode, yr) {
  var candidates = [yr - 1, P.hZ, P.td];

  // Ambil laju historis dari computed[projCode].rataTambahanRows atau dari histCode
  var histLajuMap = {};
  var histCode = _gsFindHistCode(projCode);
  // rataTambahanRows ada di computed proyeksi (3.A.*, 3.B.*, dll)
  var projData = (typeof computed !== 'undefined' && computed[projCode]) ? computed[projCode] : null;
  if (projData && projData.rataTambahanRows && projData.rows) {
    // rataTambahanRows sejajar dengan rows (non-Jumlah + Jumlah di akhir)
    var nonJmlRows = projData.rows.filter(function(r) { return r.label !== 'Jumlah' && r.label !== 'JUMLAH'; });
    for (var ri = 0; ri < nonJmlRows.length && ri < projData.rataTambahanRows.length; ri++) {
      histLajuMap[nonJmlRows[ri].label] = projData.rataTambahanRows[ri].laju;
    }
  }

  for (var ci = 0; ci < candidates.length; ci++) {
    var y = candidates[ci];
    var tryCode = function(code, year) {
      if (typeof computed === 'undefined' || !computed[code]) return [];
      var data = computed[code];
      if (!data.rows || !data.years) return [];
      var idx = data.years.indexOf(Number(year));
      if (idx < 0) return [];

      // Jika histLajuMap belum terisi, coba ambil dari data ini (histCode)
      if (Object.keys(histLajuMap).length === 0 && data.rataTambahanRows) {
        var nj = data.rows.filter(function(r) { return r.label !== 'Jumlah' && r.label !== 'JUMLAH'; });
        for (var k = 0; k < nj.length && k < data.rataTambahanRows.length; k++) {
          histLajuMap[nj[k].label] = data.rataTambahanRows[k].laju;
        }
      }

      return data.rows
        .filter(function (r) { return r.label !== 'Jumlah' && r.label !== 'JUMLAH'; })
        .map(function (r) {
          var row = { label: r.label, baseVal: r.vals[idx] };
          // Sertakan laju historis jika ada
          if (histLajuMap[r.label] !== undefined && histLajuMap[r.label] !== null) {
            row.histLaju = histLajuMap[r.label];
          }
          return row;
        })
        .filter(function (r) { return r.baseVal !== null && r.baseVal !== undefined; });
    };
    var rows = tryCode(projCode, y);
    if (!rows.length) rows = tryCode(histCode, y);
    if (rows.length > 0) return rows;
  }
  return [];
}

/**
 * Daftar projCode yang punya targetImports.
 */
function _gsTargetCodes() {
  if (!P || !P.targetImports) return [];
  return Object.keys(P.targetImports);
}

/**
 * Daftar tahun proyeksi yang punya target untuk projCode tertentu.
 */
function _gsTargetYears(projCode) {
  if (!P || !P.targetImports || !P.targetImports[projCode]) return [];
  return Object.keys(P.targetImports[projCode]).map(Number).sort(function (a, b) { return a - b; });
}

/**
 * Perbarui badge jumlah override di floating button.
 */
function _gsUpdateBadge() {
  var btn = document.getElementById('gs-float-btn');
  if (!btn) return;
  var report = GoalSeek.getReport();
  var badge = btn.querySelector('.gs-badge-count');
  if (report.length > 0) {
    if (!badge) {
      badge = document.createElement('span');
      badge.className = 'gs-badge-count';
      btn.appendChild(badge);
    }
    badge.textContent = report.length + ' aktif';
  } else {
    if (badge) badge.remove();
  }
}


/* ─────────────────────────────────────────────────────────────────────────
 * BAGIAN 3: MODAL GOAL-SEEK
 * ───────────────────────────────────────────────────────────────────────── */

function _gsBuildModal() {
  if (document.getElementById('gs-overlay')) return;

  var overlay = document.createElement('div'); overlay.id = 'gs-overlay';
  overlay.onclick = function (e) { if (e.target === overlay) _gsCloseModal(); };

  var modal = document.createElement('div'); modal.id = 'gs-modal';
  overlay.appendChild(modal);
  document.body.appendChild(overlay);

  modal.innerHTML = `
    <h3>🎯 Goal-Seek — Penyesuaian Laju Pertumbuhan</h3>

    <div class="gs-form-row">
      <label>Indikator / Kode Proyeksi</label>
      <select id="gs-sel-code"><option value="">— pilih kode —</option></select>
    </div>
    <div class="gs-form-row">
      <label>Tahun Target</label>
      <select id="gs-sel-year"><option value="">— pilih tahun —</option></select>
    </div>
    <div class="gs-form-row">
      <label>Nilai Target (Jumlah)</label>
      <input type="number" id="gs-inp-target" placeholder="Masukkan nilai target…" step="any">
    </div>
    <div class="gs-form-row" id="gs-row-base">
      <label id="gs-lbl-base">Nilai Dasar (tahun sebelum target — auto-terisi)</label>
      <input type="number" id="gs-inp-base" placeholder="Terisi otomatis, atau isi manual…" step="1">
    </div>

    <div id="gs-result-box"></div>

    <div class="gs-btn-row">
      <button class="gs-btn gs-btn-preview" onclick="_gsPreview()">🔍 Preview</button>
      <button class="gs-btn gs-btn-apply"   onclick="_gsApply()"  id="gs-apply-btn" disabled>✅ Terapkan</button>
      <button class="gs-btn gs-btn-clear"   onclick="_gsClearCode()">🗑 Hapus Override</button>
      <button class="gs-btn gs-btn-close"   onclick="_gsCloseModal()">Tutup</button>
    </div>

    <hr class="gs-divider">
    <div style="font-size:11px;color:#64748b;font-weight:600;margin-bottom:6px;">📋 Override Aktif Saat Ini</div>
    <div id="gs-override-list"></div>
  `;

  // Saat kode dipilih → isi opsi tahun dari targetImports
  document.getElementById('gs-sel-code').onchange = function () {
    var code = this.value;
    var yrSel = document.getElementById('gs-sel-year');
    yrSel.innerHTML = '<option value="">— pilih tahun —</option>';
    if (!code) return;
    _gsTargetYears(code).forEach(function (y) {
      var opt = document.createElement('option'); opt.value = y; opt.textContent = y;
      yrSel.appendChild(opt);
    });
    document.getElementById('gs-result-box').className = '';
    document.getElementById('gs-result-box').style.display = 'none';
    document.getElementById('gs-apply-btn').disabled = true;
  };

  // Saat tahun dipilih → isi target dari targetImports + baseVal dari computed
  document.getElementById('gs-sel-year').onchange = function () {
    var code = document.getElementById('gs-sel-code').value;
    var yr   = Number(this.value);
    if (!code || !yr) return;

    var targetVal = P.targetImports && P.targetImports[code] && P.targetImports[code][yr];
    if (targetVal !== undefined)
      // Simpan presisi penuh (5 desimal) — JANGAN Math.round, karena akan memotong target
      document.getElementById('gs-inp-target').value = Number(targetVal).toFixed(5);

    // Auto-detect base: coba yr-1 (proj), P.hZ, P.td via _gsAutoBase
    var baseInfo = _gsAutoBase(code, yr);
    var _bInp = document.getElementById('gs-inp-base');
    var _bLbl = document.getElementById('gs-lbl-base');
    if (baseInfo) {
      _bInp.value = Math.round(baseInfo.val);
      if (_bLbl) _bLbl.textContent = 'Nilai Dasar tahun ' + baseInfo.yr + ' (auto-terisi)';
    } else {
      _bInp.value = '';
      if (_bLbl) _bLbl.textContent = 'Nilai Dasar (isi manual)';
    }
    document.getElementById('gs-result-box').style.display = 'none';
    document.getElementById('gs-apply-btn').disabled = true;
  };
}


/* ─────────────────────────────────────────────────────────────────────────
 * BAGIAN 4: AKSI MODAL (Preview / Apply / Clear / Close)
 * ───────────────────────────────────────────────────────────────────────── */

// State sementara untuk hasil preview
var _gsLastPreview = null;

function _gsPreview() {
  var code      = document.getElementById('gs-sel-code').value;
  var yr        = Number(document.getElementById('gs-sel-year').value);
  var targetVal = Number(document.getElementById('gs-inp-target').value);
  var baseVal   = Number(document.getElementById('gs-inp-base').value);
  var box       = document.getElementById('gs-result-box');
  var applyBtn  = document.getElementById('gs-apply-btn');

  // Jika baseVal kosong, coba auto-detect
  if (!baseVal) {
    var autoB = _gsAutoBase(code, yr);
    if (autoB) {
      baseVal = autoB.val;
      document.getElementById('gs-inp-base').value = Math.round(baseVal);
    }
  }

  if (!code || !yr || !targetVal) {
    box.className = 'show error'; box.style.display = 'block';
    box.innerHTML = '⚠️ Pilih kode dan tahun, serta isi Nilai Target.';
    applyBtn.disabled = true; return;
  }
  if (!baseVal) {
    box.className = 'show error'; box.style.display = 'block';
    box.innerHTML = '⚠️ Nilai Dasar tidak terdeteksi otomatis. Isi manual di kolom Nilai Dasar.';
    applyBtn.disabled = true; return;
  }

  // Deteksi jenis otomatis dari kode
  var jenis = code.startsWith('2.C') ? 'puk'
            : code.startsWith('2.D') ? 'tpak'
            : code.startsWith('3.')  ? 'ak'
            : code.startsWith('4.')  ? 'pdrb'
            : code.startsWith('5.')  ? 'kk'
            : 'umum';

  // baseYear = tahun baseVal yang dipakai
  var autoInfo  = _gsAutoBase(code, yr);
  var baseYear  = autoInfo ? autoInfo.yr : (yr - 1);

  // Coba proporsi (distribusi ke semua baris)
  var baseRows = _gsGetBaseRows(code, yr);
  var hasil;

  if (baseRows.length > 1) {
    // Mode proporsional: distribusi ke semua baris
    hasil = GoalSeek.previewTotal(baseRows, targetVal, baseYear, yr, jenis);
    _gsLastPreview = { mode: 'total', code: code, baseRows: baseRows, targetVal: targetVal, yr: yr, baseYear: baseYear, jenis: jenis };
  } else {
    // Mode single row (Jumlah)
    hasil = GoalSeek.preview(baseVal, targetVal, baseYear, yr, jenis);
    _gsLastPreview = { mode: 'single', code: code, baseVal: baseVal, targetVal: targetVal, yr: yr, baseYear: baseYear, jenis: jenis };
  }

  // Tampilkan hasil
  var isWarn  = !hasil.valid || (hasil.peringatan && hasil.peringatan.length > 0);
  box.className = 'show' + (!hasil.valid ? ' error' : isWarn ? ' warn' : '');
  box.style.display = 'block';

  if (!hasil.valid) {
    box.innerHTML = '❌ <strong>Tidak dapat dihitung:</strong> ' + hasil.pesan;
    applyBtn.disabled = true;
    _gsLastPreview = null;
    return;
  }

  if (hasil.mode === 'total' || baseRows.length > 1) {
    // Tampilkan ringkasan proporsional
    var rows = hasil.baris || [];
    var rowsHtml = rows.slice(0, 6).map(function (r) {
      return '<tr><td style="padding:1px 8px 1px 0;color:#475569;">' + r.label + '</td>'
        + '<td style="font-weight:700;color:#1d4ed8;">' + (r.persen !== null ? r.persen + '%' : '-') + '</td></tr>';
    }).join('');
    if (rows.length > 6) rowsHtml += '<tr><td colspan="2" style="color:#94a3b8;font-size:10px;">…dan ' + (rows.length - 6) + ' baris lainnya</td></tr>';
    box.innerHTML = '📊 <strong>Mode Proporsional</strong> — distribusi ke ' + rows.length + ' baris<br>'
      + '<table style="margin-top:6px;font-size:11px;border-collapse:collapse;">' + rowsHtml + '</table>'
      + (hasil.peringatan ? '<br>⚠️ ' + hasil.peringatan : '');
  } else {
    box.innerHTML = 'Laju yang diperlukan: <span class="gs-laju-highlight">' + hasil.persen + '%</span> per tahun<br>'
      + 'Verifikasi: ' + (typeof fmtNum === 'function' ? fmtNum(hasil.verifikasi) : hasil.verifikasi)
      + ' (target: ' + (typeof fmtNum === 'function' ? fmtNum(targetVal) : targetVal) + ')<br>'
      + (hasil.peringatan ? '⚠️ ' + hasil.peringatan : '✅ Laju dalam batas wajar.');
  }

  applyBtn.disabled = false;
}


function _gsApply() {
  if (!_gsLastPreview) return;
  var p = _gsLastPreview;

  if (p.mode === 'total') {
    GoalSeek.applyTotal(p.code, p.baseRows, p.targetVal, p.yr, p.baseYear, p.jenis);
  } else {
    // single-mode: apply ke semua baris secara proporsional dengan baseRows dari computed
    var rows = _gsGetBaseRows(p.code, p.yr);
    if (rows.length > 1) {
      GoalSeek.applyTotal(p.code, rows, p.targetVal, p.yr, p.baseYear, p.jenis);
    } else {
      GoalSeek.apply(p.code, 'Jumlah', p.yr, p.targetVal, p.baseVal, p.baseYear, p.jenis);
    }
  }

  _gsLastPreview = null;
  document.getElementById('gs-apply-btn').disabled = true;
  document.getElementById('gs-result-box').innerHTML
    += '<br><strong style="color:#16a34a;">✅ Override diterapkan! Tabel diperbarui.</strong>';

  _gsRefreshOverrideList();
  _gsUpdateBadge();
}

function _gsClearCode() {
  var code = document.getElementById('gs-sel-code').value;
  if (!code) { GoalSeek.clearGlobal(); }
  else        { GoalSeek.clearAll(code); }
  _gsLastPreview = null;
  document.getElementById('gs-apply-btn').disabled = true;
  document.getElementById('gs-result-box').style.display = 'none';
  _gsRefreshOverrideList();
  _gsUpdateBadge();
}

function _gsCloseModal() {
  var ov = document.getElementById('gs-overlay');
  if (ov) ov.classList.remove('open');
  _gsLastPreview = null;
}

/* Refresh daftar override aktif di dalam modal */
function _gsRefreshOverrideList() {
  var list = document.getElementById('gs-override-list');
  if (!list) return;
  var report = GoalSeek.getReport();
  if (report.length === 0) {
    list.innerHTML = '<div style="font-size:11px;color:#94a3b8;">Tidak ada override aktif.</div>';
    return;
  }
  list.innerHTML = report.map(function (r) {
    return '<div class="gs-ov-row">'
      + '<span class="gs-ov-lbl">' + r.projCode + ' · ' + r.rowLabel + ' · ' + r.year + '</span>'
      + '<span class="gs-ov-val">' + r.persen + '%</span>'
      + '<span class="gs-ov-del" onclick="_gsDeleteOne(\'' + r.projCode + '\',\'' + r.rowLabel + '\',' + r.year + ')">✕</span>'
      + '</div>';
  }).join('');
}

function _gsDeleteOne(projCode, rowLabel, year) {
  GoalSeek.clear(projCode, rowLabel, year);
  _gsRefreshOverrideList();
  _gsUpdateBadge();
}


/* ─────────────────────────────────────────────────────────────────────────
 * BAGIAN 5: BUKA MODAL
 * ───────────────────────────────────────────────────────────────────────── */

/**
 * Buka modal goal-seek. Bisa dipanggil dengan data pre-fill (dari tombol sel)
 * atau tanpa argumen (dari floating button).
 *
 * @param {object} [pre] - { code, year, targetVal, baseVal }
 */
function openGoalSeekModal(pre) {
  _gsBuildModal();
  var overlay = document.getElementById('gs-overlay');
  overlay.classList.add('open');

  // Isi dropdown kode dari targetImports
  var codeSel = document.getElementById('gs-sel-code');
  codeSel.innerHTML = '<option value="">— pilih kode —</option>';
  _gsTargetCodes().forEach(function (c) {
    var opt = document.createElement('option');
    opt.value = c; opt.textContent = c;
    if (pre && pre.code === c) opt.selected = true;
    codeSel.appendChild(opt);
  });

  // Isi tahun
  var yrSel = document.getElementById('gs-sel-year');
  yrSel.innerHTML = '<option value="">— pilih tahun —</option>';
  if (pre && pre.code) {
    _gsTargetYears(pre.code).forEach(function (y) {
      var opt = document.createElement('option');
      opt.value = y; opt.textContent = y;
      if (pre.year === y) opt.selected = true;
      yrSel.appendChild(opt);
    });
  }

  // Pre-fill nilai
  if (pre) {
    // Simpan presisi penuh pada target — JANGAN Math.round
    if (pre.targetVal != null) document.getElementById('gs-inp-target').value = Number(pre.targetVal).toFixed(5);
    if (pre.baseVal   != null) document.getElementById('gs-inp-base').value   = Math.round(pre.baseVal);
  } else {
    document.getElementById('gs-inp-target').value = '';
    document.getElementById('gs-inp-base').value   = '';
  }

  document.getElementById('gs-result-box').style.display = 'none';
  document.getElementById('gs-apply-btn').disabled = true;
  _gsLastPreview = null;
  _gsRefreshOverrideList();
}


/* ─────────────────────────────────────────────────────────────────────────
 * BAGIAN 6: PATCH _addTargetRows — tambah tombol 🎯 di sel Selisih
 * ───────────────────────────────────────────────────────────────────────── */

(function _patchAddTargetRows() {
  // Tunggu sampai _addTargetRows terdefinisi (sudah pasti karena file ini
  // dimuat setelah step3-panels-standard.js & step3-main.js)
  if (typeof _addTargetRows !== 'function') return;

  var _orig = _addTargetRows;

  _addTargetRows = function (tbl, projCode, proj, projYears) {
    // Jalankan fungsi asli dulu
    _orig.apply(this, arguments);

    // Setelah baris Selisih ditambahkan, tambahkan tombol 🎯 di tiap sel
    var tbody = tbl.querySelector('tbody');
    if (!tbody) return;

    var rows = tbody.querySelectorAll('tr.row-selisih');
    if (!rows.length) return;

    var imports = P.targetImports && P.targetImports[projCode];

    rows.forEach(function (tr) {
      projYears.forEach(function (yr, idx) {
        // Sel selisih dimulai dari kolom ke-2 (idx+1 karena kolom label)
        var td = tr.cells[idx + 1];
        if (!td) return;

        var targetVal = imports && imports[yr] !== undefined ? imports[yr] : null;
        if (targetVal === null) return;

        // Cek apakah override sudah ada
        var ovExists = GoalSeek.getOverride(projCode, 'Jumlah', yr) !== undefined;

        // Tombol 🎯
        var btn = document.createElement('span');
        btn.className = 'gs-cell-btn';
        btn.textContent = '🎯';
        btn.title = 'Goal-Seek: cari laju agar proyeksi = ' + targetVal;
        btn.onclick = (function (code, y, tv) {
          return function (e) {
            e.stopPropagation();
            var bInfo = _gsAutoBase(code, y);
            openGoalSeekModal({ code: code, year: y, targetVal: tv, baseVal: bInfo ? bInfo.val : null });
          };
        })(projCode, yr, targetVal);
        td.appendChild(btn);

        // Badge jika override aktif
        if (ovExists) {
          var badge = document.createElement('span');
          badge.className = 'gs-override-badge';
          badge.textContent = 'adj';
          badge.title = 'Override laju aktif untuk tahun ' + yr;
          td.appendChild(badge);
        }
      });
    });
  };
})();


/* ─────────────────────────────────────────────────────────────────────────
 * BAGIAN 7: PATCH buildStep3 — update badge setelah render
 * (Floating button dihapus — tombol ⚡ Auto Adj sudah ada di header Tabel 2)
 * ───────────────────────────────────────────────────────────────────────── */

(function _patchBuildStep3() {
  if (typeof buildStep3 !== 'function') return;
  var _origBuild = buildStep3;

  buildStep3 = function () {
    _origBuild.apply(this, arguments);
    _gsUpdateBadge();
  };
})();

/**
 * _gsAttachFloatingBtn — dipertahankan sebagai stub agar kode lain yang
 * memanggil fungsi ini tidak error, tapi tidak membuat tombol apapun.
 */
function _gsAttachFloatingBtn() {
  // Hapus sisa tombol lama jika masih ada di DOM (misal dari session sebelumnya)
  var existing = document.getElementById('gs-float-btn');
  if (existing) { existing.remove(); }
}

/* ─────────────────────────────────────────────────────────────────────────
 * BAGIAN 8: INISIALISASI AWAL
 * ───────────────────────────────────────────────────────────────────────── */

/**
 * Jika Step 3 sudah aktif saat file ini dimuat (misal reload), pasang langsung.
 * Jika belum, biarkan — patch buildStep3 akan menanganinya saat dipanggil.
 */
(function _gsInit() {
  // Hapus floating button lama jika masih ada di DOM
  var existing = document.getElementById('gs-float-btn');
  if (existing) { existing.remove(); }

  var s3area = document.getElementById('s3area');
  if (s3area && s3area.children.length > 0) {
    // Step 3 sudah dirender — update badge override (tanpa floating button)
    _gsUpdateBadge();
  }
})();

