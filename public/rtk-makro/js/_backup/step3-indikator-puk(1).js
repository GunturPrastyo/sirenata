/* ══════════════════════════════════════════
   step3-indikator-puk.js — RTK Makro v8
   Render panel indikator PUK (Penduduk Usia Kerja)
   Menggunakan gaya Excel Bimtek — 4 tabel berdempet + Tabel Bantu 2.

   File ini juga mendefinisikan helper `_renderBimtekPanel()` yang
   dipakai bersama oleh step3-indikator-tpak.js (keduanya memakai
   gaya bimtek yang sama dengan label sedikit berbeda).
   ══════════════════════════════════════════ */

// ══════════════════════════════════════════════════════════════
// HELPER — Render panel gaya Bimtek untuk PUK & TPAK
// Mode ditentukan dari histCode: '2.C.*' → PUK, '2.D.*' → TPAK
// ══════════════════════════════════════════════════════════════
function _renderBimtekPanel(charDef) {
  const wrap = document.createElement('div');
  wrap.className = 'char-content';

  const isPUK  = charDef.histCode && charDef.histCode.startsWith('2.C');
  const isTPAK = charDef.histCode && charDef.histCode.startsWith('2.D');

  const hist = computed[charDef.histCode];
  const proj = computed[charDef.projCode];

  if (!hasData(charDef.histCode) && !hasData(charDef.projCode)) {
    wrap.innerHTML = '<div class="no-data-msg">⬜ Belum ada data untuk karakteristik ini.</div>';
    return wrap;
  }

  // Info bar edit — di ATAS 4 tabel, bukan di dalam thead
  const infoBar = document.createElement('div');
  infoBar.style.cssText = 'font-size:11px; color:#16a34a; padding:4px 8px; margin-bottom:6px; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:6px; display:flex; align-items:center; gap:6px;';
  infoBar.innerHTML = `✏️ Klik angka di blok <strong>Perkiraan Laju Pertumbuhan</strong> untuk edit &nbsp;|&nbsp; <kbd style="background:#dcfce7;border:1px solid #86efac;border-radius:3px;padding:1px 5px;font-size:10px;">Enter</kbd> simpan &amp; hitung ulang &nbsp;|&nbsp; <kbd style="background:#dcfce7;border:1px solid #86efac;border-radius:3px;padding:1px 5px;font-size:10px;">Esc</kbd> batal`;
  wrap.appendChild(infoBar);

  const pcWrap = document.createElement('div'); pcWrap.className = 'char-content-wrap split-view';
  pcWrap.style.cssText = 'display:flex; overflow-x:auto; gap:10px; padding-bottom:20px;';

  const lbl = charDef.label || 'Sektor / Karakteristik';

  // Helper untuk membuat seragam tinggi header semua tabel
  function makeFlexHeader(text, badgeCode = null) {
    const hdr = document.createElement('div');
    hdr.className = 'tbl-block-h1';
    hdr.style.cssText = 'font-size:12px; margin-bottom:10px; display:flex; align-items:flex-end; gap:8px; height:42px; line-height:1.25; padding-bottom:2px;';
    hdr.innerHTML = `<span>${text}</span>`;
    if (badgeCode) {
      const b = makeCodeBadge(badgeCode);
      b.style.margin = '0'; // hapus margin bawaan
      hdr.appendChild(b);
    }
    return hdr;
  }

  // 1. Table Input Data
  const blk1 = document.createElement('div');
  blk1.appendChild(makeFlexHeader(`Input Data ${isPUK ? 'Penduduk Usia Kerja' : 'TPAK'}`, charDef.histCode));
  blk1.appendChild(makeBimtekTable1(hist, proj, lbl, charDef.histCode));

  // 2. Table Laju Pertumbuhan
  const blk2 = document.createElement('div');
  const hdr2 = makeFlexHeader('Perubahan Laju Pertumbuhan');
  hdr2.style.justifyContent = 'space-between';
  hdr2.style.alignItems = 'flex-end';
  const _editRowLabels2 = hist && hist.rows
    ? hist.rows.filter(r => !(r.label === 'Jumlah' || r.label === 'JUMLAH')).map(r => r.label)
    : [];
  const _pasteBtn2 = document.createElement('button');
  _pasteBtn2.className = 'btn-paste-excel';
  _pasteBtn2.innerHTML = '📋 Paste dari Excel';
  _pasteBtn2.title = `Paste ${_editRowLabels2.length} baris × ${(proj && proj.years ? proj.years.length : 0)} kolom nilai laju dari Excel\n(Otomatis dari clipboard)`;
  _pasteBtn2.onclick = () => _directLajuPaste(_editRowLabels2, proj.years, charDef.projCode);

  // ─── Tombol ℹ Rumus T2 ───
  const _t2pYears    = (proj && proj.years) ? proj.years : [];
  const _t2HistRange = (hist && hist.years && hist.years.length)
    ? `${hist.years[0]}–${hist.years[hist.years.length - 1]}` : '?';
  const _t2InfoBtn = document.createElement('button');
  _t2InfoBtn.textContent = 'ℹ Rumus';
  _t2InfoBtn.style.cssText = 'font-size:11px;padding:2px 8px;border:1px solid #a5b4fc;border-radius:4px;background:#eef2ff;color:#4338ca;cursor:pointer;white-space:nowrap;flex-shrink:0;';

  const _t2Popup = document.createElement('div');
  _t2Popup.className = 't4-formula-popup';
  _t2Popup.style.cssText = 'display:none;position:fixed;z-index:9999;background:#1e293b;color:#f1f5f9;border-radius:10px;padding:14px 16px;font-size:12px;line-height:1.7;width:320px;max-height:80vh;overflow-y:auto;box-shadow:0 8px 24px rgba(0,0,0,0.45);pointer-events:auto;';
  const _t2ColRows = _t2pYears.map(y => `
      <tr>
        <td style="padding:2px 8px 2px 0;color:#fde68a;font-weight:600;white-space:nowrap;">Kolom ${y}</td>
        <td style="padding:2px 0;font-size:11px;">Default = rata laju historis.<br><span style="color:#86efac;">Bisa diedit manual (klik angka)</span></td>
      </tr>`).join('');
  _t2Popup.innerHTML = `
      <div style="font-weight:700;font-size:12.5px;margin-bottom:10px;color:#a5f3fc;">📐 Rumus Perubahan Laju Pertumbuhan</div>
      <table style="border-collapse:collapse;width:100%;font-size:11.5px;margin-bottom:10px;">
        <tr>
          <td style="padding:2px 8px 6px 0;color:#fde68a;font-weight:600;white-space:nowrap;">Laju<br>Pertumbuhan (r)</td>
          <td style="padding:2px 0;font-size:11px;">Rata laju historis, dihitung otomatis dari data tahun <code style="background:#334155;padding:1px 5px;border-radius:3px;">${_t2HistRange}</code></td>
        </tr>
        <tr style="border-top:1px solid #334155;">
          <td colspan="2" style="padding:6px 0 4px;color:#94a3b8;font-size:10.5px;">Kolom Perkiraan Laju Pertumbuhan (dapat diedit):</td>
        </tr>
        ${_t2ColRows}
      </table>
      <div style="padding-top:8px;border-top:1px solid #334155;color:#64748b;font-size:10.5px;">
        Nilai laju ini digunakan sebagai <code style="background:#334155;padding:1px 4px;border-radius:3px;">r</code> dalam rumus proyeksi (Tabel 3).<br>
        Klik di luar untuk menutup.
      </div>`;
  document.body.appendChild(_t2Popup);

  let _t2PopupOpen = false;
  const _positionT2Popup = () => {
    _t2Popup.style.top = '-9999px';
    _t2Popup.style.display = 'block';
    const _r2 = _t2InfoBtn.getBoundingClientRect();
    const ph2 = _t2Popup.offsetHeight, pw2 = _t2Popup.offsetWidth;
    const vh2 = window.innerHeight, vw2 = window.innerWidth;
    const topBelow2 = _r2.bottom + 6, topAbove2 = _r2.top - ph2 - 6;
    _t2Popup.style.top  = (topBelow2 + ph2 > vh2 && topAbove2 >= 0 ? topAbove2 : topBelow2) + 'px';
    _t2Popup.style.left = Math.max(8, Math.min(_r2.right - pw2, vw2 - pw2 - 8)) + 'px';
  };
  _t2InfoBtn.onclick = (e) => {
    e.stopPropagation();
    _t2PopupOpen = !_t2PopupOpen;
    if (_t2PopupOpen) _positionT2Popup(); else _t2Popup.style.display = 'none';
  };
  document.addEventListener('click', (e) => {
    if (_t2PopupOpen && !_t2Popup.contains(e.target) && e.target !== _t2InfoBtn) {
      _t2PopupOpen = false; _t2Popup.style.display = 'none';
    }
  });

  // ─── Tombol Reset Laju T2 ───
  const _hasOverride2 = () => !!(P.lajuOverrides && P.lajuOverrides[charDef.projCode] && Object.keys(P.lajuOverrides[charDef.projCode]).length > 0);
  const _resetBtn2 = document.createElement('button');
  const _updateResetBtn2Style = () => {
    const active = _hasOverride2();
    _resetBtn2.disabled = !active;
    _resetBtn2.style.cssText = [
      'font-size:11px', 'padding:2px 8px',
      `border:1px solid ${active ? '#fb923c' : '#d1d5db'}`,
      'border-radius:4px',
      `background:${active ? '#fff7ed' : '#f9fafb'}`,
      `color:${active ? '#c2410c' : '#9ca3af'}`,
      `cursor:${active ? 'pointer' : 'not-allowed'}`,
      'white-space:nowrap', 'flex-shrink:0',
      'transition:all 0.15s'
    ].join(';');
  };
  _resetBtn2.textContent = '↺ Reset Laju';
  _resetBtn2.title = 'Kembalikan semua laju ke nilai awal historis (hapus semua override)';
  _updateResetBtn2Style();
  _resetBtn2.onclick = () => {
    if (!_hasOverride2()) return;
    if (!confirm('Reset semua laju ke nilai awal historis?\n(Semua perubahan manual di tabel ini akan dihapus)')) return;
    const tabState = _saveTabState();
    delete P.lajuOverrides[charDef.projCode];
    buildComputed();
    buildStep3();
    _restoreTabState(tabState);
    saveStateToLS();
    _updateUndoRedoUI();
    _showToast('↺ Laju dikembalikan ke nilai awal historis');
  };

  // ─── Tombol ⚡ Auto Adj ───
  // Cek apakah ada target untuk projCode ini (import atau manual)
  const _hasTargetForCode = () => {
    const pc = charDef.projCode;
    const hasImp = !!(P.targetImports && P.targetImports[pc] && Object.keys(P.targetImports[pc]).length > 0);
    const hasMn  = !!(P.targets       && P.targets[pc]       && Object.keys(P.targets[pc]).length > 0);
    return hasImp || hasMn;
  };
  // Ambil nilai target (manual override prioritas di atas import)
  const _getTargetVal = (pc, y) => {
    if (P.targets      && P.targets[pc]      && P.targets[pc][y]      !== undefined) return P.targets[pc][y];
    if (P.targetImports && P.targetImports[pc] && P.targetImports[pc][y] !== undefined) return P.targetImports[pc][y];
    return null;
  };

  const _autoAdjBtn = document.createElement('button');
  const _updateAutoAdjStyle = () => {
    const active = _hasTargetForCode();
    _autoAdjBtn.disabled = !active;
    _autoAdjBtn.title = active
      ? '⚡ Auto Adjustment: sesuaikan laju semua tahun agar Jumlah Proyeksi ≈ Target'
      : 'Auto Adjustment tidak tersedia — belum ada Target yang ditetapkan di Tabel 3';
    _autoAdjBtn.style.cssText = [
      'font-size:11px', 'padding:2px 8px',
      `border:1px solid ${active ? '#f97316' : '#d1d5db'}`,
      'border-radius:4px',
      `background:${active ? '#fff7ed' : '#f9fafb'}`,
      `color:${active ? '#ea580c' : '#9ca3af'}`,
      `cursor:${active ? 'pointer' : 'not-allowed'}`,
      'white-space:nowrap', 'flex-shrink:0',
      'transition:all 0.15s'
    ].join(';');
  };
  _autoAdjBtn.textContent = '⚡ Auto Adj';
  _updateAutoAdjStyle();

  _autoAdjBtn.onclick = () => {
    const pCode  = charDef.projCode;
    const pYears = (proj && proj.years) ? proj.years : [];

    // Kumpulkan tahun yang memiliki target
    const yearsWithTarget = pYears.filter(y => _getTargetVal(pCode, y) !== null);
    if (!yearsWithTarget.length) {
      _showToast('⚠️ Tidak ada Target untuk kode ini. Set target di Tabel 3 terlebih dahulu.');
      return;
    }

    const labelKode = charDef.label || pCode;
    if (!confirm(
      `⚡ Auto Adjustment — ${labelKode}\n\n` +
      `Laju pertumbuhan semua sektor akan disesuaikan\n` +
      `agar Jumlah Proyeksi = Target untuk ${yearsWithTarget.length} tahun:\n` +
      `→ ${yearsWithTarget.join(', ')}\n\n` +
      `Lanjutkan?`
    )) return;

    const tabState = _saveTabState();
    let rowsAdj = 0;
    const failYears = [];

    // ── Rumus step3-compute.js: P_y = baseVal × (1 + r/100)^(y - baseYear) ──
    // baseVal dan baseYear adalah nilai/tahun historis TERAKHIR — konstan untuk
    // semua tahun proyeksi.  Setiap tahun dihitung INDEPENDEN dari base yang sama.
    // → t harus = y - baseYear, BUKAN 1.

    // Ambil baseYear = tahun historis terakhir
    const baseYear = (hist && hist.years && hist.years.length)
      ? hist.years[hist.years.length - 1]
      : null;
    if (!baseYear) {
      _showToast('⚠️ Tidak dapat menentukan tahun dasar historis.');
      return;
    }

    // Bangun baseRows template: nilai historis terakhir per baris (konstan)
    // + histLaju agar GoalSeek.byProporsi memakai mode distribusi yang lebih akurat
    const projData  = (typeof computed !== 'undefined' && computed[pCode]) ? computed[pCode] : null;
    const lajuHistMap = {};
    if (projData && projData.rataTambahanRows && projData.rows) {
      projData.rows
        .filter(r => r.label !== 'Jumlah' && r.label !== 'JUMLAH' && !r.isTarget && !r.isSelisih)
        .forEach((r, i) => {
          if (projData.rataTambahanRows[i] !== undefined)
            lajuHistMap[r.label] = projData.rataTambahanRows[i].laju;
        });
    }

    const baseRowsTemplate = [];
    if (hist && hist.rows) {
      hist.rows
        .filter(r => r.label !== 'Jumlah' && r.label !== 'JUMLAH')
        .forEach(r => {
          const lastVal = r.vals[r.vals.length - 1];
          if (lastVal !== null && lastVal !== undefined && isFinite(lastVal) && lastVal > 0) {
            const row = { label: r.label, baseVal: lastVal };
            if (lajuHistMap[r.label] !== undefined) row.histLaju = lajuHistMap[r.label];
            baseRowsTemplate.push(row);
          }
        });
    }

    if (!baseRowsTemplate.length) {
      _showToast('⚠️ Tidak ada data historis per baris yang valid.');
      return;
    }

    // ── Setiap tahun proyeksi dihitung INDEPENDEN ──
    // t = y - baseYear  →  sesuai rumus compute: (1 + r/100)^(y - baseYear)
    yearsWithTarget.forEach(yr => {
      const targetVal = _getTargetVal(pCode, yr);
      if (targetVal === null) return;

      const t = yr - baseYear;
      if (t <= 0) { failYears.push(yr + ' (t≤0)'); return; }

      const hasil = GoalSeek.byProporsi(baseRowsTemplate, targetVal, t);

      hasil.forEach(row => {
        if (row.valid) {
          if (!P.lajuOverrides)                     P.lajuOverrides = {};
          if (!P.lajuOverrides[pCode])              P.lajuOverrides[pCode] = {};
          if (!P.lajuOverrides[pCode][row.label])   P.lajuOverrides[pCode][row.label] = {};
          P.lajuOverrides[pCode][row.label][yr] = row.laju;
          rowsAdj++;
        } else {
          failYears.push(yr + '/' + row.label);
        }
      });
    });

    // ── Satu kali rebuild setelah semua override ditulis ──
    saveStateToLS();
    buildComputed();
    buildStep3();
    _restoreTabState(tabState);
    _updateUndoRedoUI();
    if (typeof _gsUpdateBadge === 'function') _gsUpdateBadge();

    const failMsg = failYears.length ? ` ⚠️ ${failYears.length} item gagal.` : '';
    _showToast(`⚡ Auto Adjustment selesai: ${yearsWithTarget.length} tahun, ${rowsAdj} baris disesuaikan.${failMsg}`);
  };

  // Grup tombol kanan hdr2: [ℹ Rumus] [⚡ Auto Adj] [↺ Reset] [📋 Paste]
  const _hdr2BtnGroup = document.createElement('div');
  _hdr2BtnGroup.style.cssText = 'display:flex;gap:4px;align-items:center;flex-shrink:0;';
  _hdr2BtnGroup.appendChild(_t2InfoBtn);
  _hdr2BtnGroup.appendChild(_autoAdjBtn);
  _hdr2BtnGroup.appendChild(_resetBtn2);
  _hdr2BtnGroup.appendChild(_pasteBtn2);
  hdr2.appendChild(_hdr2BtnGroup);
  blk2.appendChild(hdr2);
  blk2.appendChild(makeBimtekTable2(hist, proj, charDef.projCode));

  // 3. Table Proyeksi & Target
  const blk3 = document.createElement('div');
  const hdr3 = makeFlexHeader(`Proyeksi ${isPUK ? 'Penduduk Usia Kerja' : 'TPAK'} (${P.nama})`, charDef.projCode);

  // ─── Tombol ℹ Rumus T3 ───
  const _t3pYears   = (proj && proj.years) ? proj.years : [];
  const _t3HistLast = (hist && hist.years && hist.years.length) ? hist.years[hist.years.length - 1] : '?';
  const _t3InfoBtn = document.createElement('button');
  _t3InfoBtn.textContent = 'ℹ Rumus';
  _t3InfoBtn.style.cssText = 'font-size:11px;padding:2px 8px;border:1px solid #a5b4fc;border-radius:4px;background:#eef2ff;color:#4338ca;cursor:pointer;white-space:nowrap;flex-shrink:0;';

  const _t3Popup = document.createElement('div');
  _t3Popup.className = 't4-formula-popup';
  _t3Popup.style.cssText = 'display:none;position:fixed;z-index:9999;background:#1e293b;color:#f1f5f9;border-radius:10px;padding:14px 16px;font-size:12px;line-height:1.7;width:320px;max-height:80vh;overflow-y:auto;box-shadow:0 8px 24px rgba(0,0,0,0.45);pointer-events:auto;';
  const _t3ProjRows = _t3pYears.map((y, i) => {
    const prev = i === 0 ? _t3HistLast : _t3pYears[i - 1];
    return `<tr>
        <td style="padding:2px 8px 2px 0;color:#fde68a;font-weight:600;white-space:nowrap;">Kolom ${y}</td>
        <td style="padding:2px 0;font-size:11px;">
          <code style="background:#334155;padding:1px 5px;border-radius:3px;">P<sub>${prev}</sub> × (1 + r<sub>${y}</sub> / 100)</code>
          ${i === 0 ? '<br><span style="color:#64748b;font-size:10px;">← P hist terakhir × (1 + r / 100)</span>' : ''}
        </td>
      </tr>`;
  }).join('');
  _t3Popup.innerHTML = `
      <div style="font-weight:700;font-size:12.5px;margin-bottom:10px;color:#a5f3fc;">📐 Rumus Tabel Proyeksi</div>
      <div style="margin-bottom:6px;font-size:11.5px;">
        Rumus umum: <code style="background:#334155;padding:2px 7px;border-radius:4px;">Pₜ = Pₜ₋₁ × (1 + r / 100)</code>
      </div>
      <div style="margin-bottom:8px;color:#94a3b8;font-size:10.5px;">
        Pₜ = proyeksi tahun t &nbsp;|&nbsp; Pₜ₋₁ = nilai tahun sebelumnya &nbsp;|&nbsp; r = laju dari Tabel 2
      </div>
      <table style="border-collapse:collapse;width:100%;font-size:11.5px;margin-bottom:10px;">
        ${_t3ProjRows}
      </table>
      <div style="padding-top:8px;border-top:1px solid #334155;margin-bottom:6px;font-size:11.5px;">
        <span style="color:#fde68a;font-weight:600;">Baris Target</span>:
        Override manual <span style="background:#3b82f6;color:#fff;padding:0 4px;border-radius:3px;font-size:10px;">biru</span>
        atau import sheet Excel <span style="background:#22c55e;color:#fff;padding:0 4px;border-radius:3px;font-size:10px;">hijau</span>.
        Dapat diedit.
      </div>
      <div style="font-size:11.5px;">
        <span style="color:#fde68a;font-weight:600;">Selisih</span>:
        <code style="background:#334155;padding:1px 5px;border-radius:3px;">Target − Jumlah Proyeksi</code>
      </div>
      <div style="padding-top:8px;border-top:1px solid #334155;margin-top:6px;color:#64748b;font-size:10.5px;">
        Klik di luar untuk menutup.
      </div>`;
  document.body.appendChild(_t3Popup);

  let _t3PopupOpen = false;
  const _positionT3Popup = () => {
    _t3Popup.style.top = '-9999px';
    _t3Popup.style.display = 'block';
    const _r3 = _t3InfoBtn.getBoundingClientRect();
    const ph3 = _t3Popup.offsetHeight, pw3 = _t3Popup.offsetWidth;
    const vh3 = window.innerHeight, vw3 = window.innerWidth;
    const topBelow3 = _r3.bottom + 6, topAbove3 = _r3.top - ph3 - 6;
    _t3Popup.style.top  = (topBelow3 + ph3 > vh3 && topAbove3 >= 0 ? topAbove3 : topBelow3) + 'px';
    _t3Popup.style.left = Math.max(8, Math.min(_r3.right - pw3, vw3 - pw3 - 8)) + 'px';
  };
  _t3InfoBtn.onclick = (e) => {
    e.stopPropagation();
    _t3PopupOpen = !_t3PopupOpen;
    if (_t3PopupOpen) _positionT3Popup(); else _t3Popup.style.display = 'none';
  };
  document.addEventListener('click', (e) => {
    if (_t3PopupOpen && !_t3Popup.contains(e.target) && e.target !== _t3InfoBtn) {
      _t3PopupOpen = false; _t3Popup.style.display = 'none';
    }
  });
  hdr3.appendChild(_t3InfoBtn);

  // Tombol toggle 5 desimal
  const _t3DecBtn = document.createElement('button');
  _t3DecBtn.textContent = '.00000';
  _t3DecBtn.title = 'Tampilkan/sembunyikan 5 digit desimal';
  _t3DecBtn.style.cssText = 'font-size:11px;padding:2px 8px;border:1px solid #86efac;border-radius:4px;background:#f0fdf4;color:#166534;cursor:pointer;white-space:nowrap;flex-shrink:0;margin-left:6px;font-family:"DM Mono",monospace;';
  const _t3Tbl = makeBimtekTable3(hist, proj, lbl, charDef);
  _t3DecBtn.onclick = function () {
    if (_t3Tbl._toggleDecimal) _t3Tbl._toggleDecimal();
    const active = _t3Tbl._show5dec;
    _t3DecBtn.style.background = active ? '#166534' : '#f0fdf4';
    _t3DecBtn.style.color = active ? '#fff' : '#166534';
    _t3DecBtn.style.borderColor = active ? '#166534' : '#86efac';
  };
  hdr3.appendChild(_t3DecBtn);

  blk3.appendChild(hdr3);
  blk3.appendChild(_t3Tbl);

  // Hitung data delta T4 terlebih dahulu — dipakai oleh T4 (render) dan T5 (sumber data)
  const _t4DeltaData = _computeT4Deltas(hist, proj, isTPAK);

  // 4. Table Analisa Proyeksi + tombol toggle T5 di headernya
  const blk4 = document.createElement('div');
  const hdr4 = makeFlexHeader('Tabel Bantu Analisa Proyeksi 1');
  hdr4.style.justifyContent = 'space-between';
  hdr4.style.alignItems = 'flex-end';

  // 5. Tabel Bantu ke-2 (awalnya tersembunyi penuh)
  // Rumus sama dengan T4, sumber data: T3 (proj) + T4 delta (_t4DeltaData)
  const blk5 = document.createElement('div');
  blk5.style.display = 'none';
  const hdr5 = makeFlexHeader('Tabel Bantu Analisa Proyeksi 2');
  hdr5.style.justifyContent = 'space-between';
  hdr5.style.alignItems = 'flex-end';

  // ─── Tombol ℹ Rumus T5 ───
  const _t5InfoBtn = document.createElement('button');
  _t5InfoBtn.textContent = 'ℹ Rumus';
  _t5InfoBtn.style.cssText = 'font-size:11px;padding:2px 8px;border:1px solid #a5b4fc;border-radius:4px;background:#eef2ff;color:#4338ca;cursor:pointer;white-space:nowrap;flex-shrink:0;';
  const _t5Popup = document.createElement('div');
  _t5Popup.className = 't4-formula-popup';
  _t5Popup.style.cssText = 'display:none;position:fixed;z-index:9999;background:#1e293b;color:#f1f5f9;border-radius:10px;padding:14px 16px;font-size:12px;line-height:1.7;width:340px;max-height:80vh;overflow-y:auto;box-shadow:0 8px 24px rgba(0,0,0,0.45);pointer-events:auto;';
  const _t5pYears = (proj && proj.years) ? proj.years : [];
  const _t5ColRows = _t5pYears.map((y, i) => {
    if (i === 0) return `<tr>
        <td style="padding:2px 8px 2px 0;color:#fde68a;font-weight:600;white-space:nowrap;">Kolom ${y}</td>
        <td style="padding:2px 0;font-size:11px;">
          <code style="background:#334155;padding:1px 5px;border-radius:3px;">T4[${y}] − T3[${y}]</code>
          <span style="color:#64748b;font-size:10px;margin-left:4px;">← delta T4 dikurangi nilai proyeksi T3 tahun ${y}</span>
        </td>
      </tr>`;
    return `<tr>
        <td style="padding:2px 8px 2px 0;color:#fde68a;font-weight:600;white-space:nowrap;">Kolom ${y}</td>
        <td style="padding:2px 0;font-size:11px;">
          <code style="background:#334155;padding:1px 5px;border-radius:3px;">T4[${y}] − T4[${_t5pYears[i-1]}]</code>
        </td>
      </tr>`;
  }).join('');
  const _t5AllPairs = _t5pYears.map((y, i) =>
    i === 0 ? `(T4[${y}]−T3[${y}])` : `(T4[${y}]−T4[${_t5pYears[i-1]}])`
  ).join(' + ');
  _t5Popup.innerHTML = `
    <div style="font-weight:700;font-size:12.5px;margin-bottom:10px;color:#a5f3fc;">📐 Rumus Tabel Bantu Analisa Proyeksi 2</div>
    <div style="margin-bottom:8px;font-size:11px;color:#94a3b8;">
      Pola rumus sama dengan Tabel 4 (selisih absolut), sumber data:<br>
      <span style="color:#86efac;">Tabel 3 (Proyeksi)</span> sebagai acuan awal &amp;
      <span style="color:#7dd3fc;">Tabel 4 (Delta)</span> sebagai nilai setiap kolom.
    </div>
    <table style="border-collapse:collapse;width:100%;margin-bottom:10px;font-size:11.5px;">
      ${_t5ColRows}
      <tr style="border-top:1px solid #334155;">
        <td style="padding:6px 8px 2px 0;color:#fde68a;font-weight:600;white-space:nowrap;">Rata / Tahun</td>
        <td style="padding:6px 0 2px 0;">
          <code style="background:#334155;padding:1px 6px;border-radius:4px;font-size:11px;">AVERAGE(${_t5AllPairs})</code>
        </td>
      </tr>
    </table>
    <div style="padding-top:8px;border-top:1px solid #334155;color:#64748b;font-size:10.5px;">
      Klik di luar untuk menutup
    </div>`;
  document.body.appendChild(_t5Popup);
  let _t5PopupOpen = false;
  const _positionT5Popup = () => {
    _t5Popup.style.top = '-9999px';
    _t5Popup.style.display = 'block';
    const _r5 = _t5InfoBtn.getBoundingClientRect();
    const ph5 = _t5Popup.offsetHeight, pw5 = _t5Popup.offsetWidth;
    const vh5 = window.innerHeight, vw5 = window.innerWidth;
    const topBelow5 = _r5.bottom + 6, topAbove5 = _r5.top - ph5 - 6;
    _t5Popup.style.top  = (topBelow5 + ph5 > vh5 && topAbove5 >= 0 ? topAbove5 : topBelow5) + 'px';
    _t5Popup.style.left = Math.max(8, Math.min(_r5.right - pw5, vw5 - pw5 - 8)) + 'px';
  };
  _t5InfoBtn.onclick = (e) => {
    e.stopPropagation();
    _t5PopupOpen = !_t5PopupOpen;
    if (_t5PopupOpen) _positionT5Popup(); else _t5Popup.style.display = 'none';
  };
  document.addEventListener('click', (e) => {
    if (_t5PopupOpen && !_t5Popup.contains(e.target) && e.target !== _t5InfoBtn) {
      _t5PopupOpen = false; _t5Popup.style.display = 'none';
    }
  });
  hdr5.appendChild(_t5InfoBtn);
  blk5.appendChild(hdr5);
  blk5.appendChild(makeBimtekTable5(proj, _t4DeltaData, isTPAK));

  // Tombol ℹ Rumus T4
  const _t4InfoBtn = document.createElement('button');
  _t4InfoBtn.textContent = 'ℹ Rumus';
  _t4InfoBtn.style.cssText = 'font-size:11px;padding:2px 8px;border:1px solid #a5b4fc;border-radius:4px;background:#eef2ff;color:#4338ca;cursor:pointer;white-space:nowrap;flex-shrink:0;';

  // Popup penjelasan rumus
  const _t4Popup = document.createElement('div');
  _t4Popup.className = 't4-formula-popup';
  _t4Popup.style.cssText = [
    'display:none', 'position:fixed', 'z-index:9999',
    'background:#1e293b', 'color:#f1f5f9',
    'border-radius:10px', 'padding:14px 16px',
    'font-size:12px', 'line-height:1.7', 'width:320px',
    'max-height:80vh', 'overflow-y:auto',
    'box-shadow:0 8px 24px rgba(0,0,0,0.45)',
    'pointer-events:auto'
  ].join(';');
  const _pYears   = (proj && proj.years) ? proj.years : [];
  const _histLast = (hist && hist.years && hist.years.length) ? hist.years[hist.years.length - 1] : '?';
  // Buat baris rumus tiap kolom: kolom-1 = projYear[0]−histLast, kolom-n = projYear[n]−projYear[n-1]
  const _colRows = _pYears.map((y, i) => {
    const prev = i === 0 ? _histLast : _pYears[i - 1];
    return `<tr>
        <td style="padding:2px 8px 2px 0;color:#fde68a;font-weight:600;white-space:nowrap;">Kolom ${y}</td>
        <td style="padding:2px 0;">
          <code style="background:#334155;padding:1px 6px;border-radius:4px;font-size:11px;">${y} − ${prev}</code>
          ${i === 0 ? '<span style="color:#64748b;font-size:10px;margin-left:4px;">← proj pertama − hist terakhir</span>' : ''}
        </td>
      </tr>`;
  }).join('');
  const _allPairs = _pYears.map((y, i) => `(${y}−${i === 0 ? _histLast : _pYears[i-1]})`).join(' + ');
  _t4Popup.innerHTML = `
    <div style="font-weight:700;font-size:12.5px;margin-bottom:10px;color:#a5f3fc;">📐 Rumus Tabel Bantu Analisa Proyeksi</div>
    <table style="border-collapse:collapse;width:100%;margin-bottom:10px;font-size:11.5px;">
      ${_colRows}
      <tr style="border-top:1px solid #334155;">
        <td style="padding:6px 8px 2px 0;color:#fde68a;font-weight:600;white-space:nowrap;">Rata / Tahun</td>
        <td style="padding:6px 0 2px 0;">
          <code style="background:#334155;padding:1px 6px;border-radius:4px;font-size:11px;">
            AVERAGE(${_allPairs})
          </code>
        </td>
      </tr>
    </table>
    <div style="padding-top:8px;border-top:1px solid #334155;color:#64748b;font-size:10.5px;">
      Klik di luar untuk menutup
    </div>`;
  // Posisi popup: fixed (viewport), otomatis flip ke atas jika tidak muat di bawah
  const _positionPopup = () => {
    _t4Popup.style.top = '-9999px'; // render dulu agar bisa ukur tinggi
    _t4Popup.style.display = 'block';
    const r   = _t4InfoBtn.getBoundingClientRect();
    const ph  = _t4Popup.offsetHeight;
    const pw  = _t4Popup.offsetWidth;
    const vh  = window.innerHeight;
    const vw  = window.innerWidth;
    // Vertikal: muncul di bawah tombol, atau di atas jika tidak muat
    const topBelow = r.bottom + 6;
    const topAbove = r.top - ph - 6;
    _t4Popup.style.top  = (topBelow + ph > vh && topAbove >= 0 ? topAbove : topBelow) + 'px';
    // Horizontal: rata kanan tombol, clamp agar tidak keluar layar
    const leftIdeal = r.right - pw;
    _t4Popup.style.left = Math.max(8, Math.min(leftIdeal, vw - pw - 8)) + 'px';
  };
  document.body.appendChild(_t4Popup);

  let _t4PopupOpen = false;
  _t4InfoBtn.onclick = (e) => {
    e.stopPropagation();
    _t4PopupOpen = !_t4PopupOpen;
    if (_t4PopupOpen) {
      _positionPopup(); // sudah set display:block di dalamnya
    } else {
      _t4Popup.style.display = 'none';
    }
  };
  const _closeT4Popup = (e) => {
    if (_t4PopupOpen && !_t4Popup.contains(e.target) && e.target !== _t4InfoBtn) {
      _t4PopupOpen = false;
      _t4Popup.style.display = 'none';
    }
  };
  document.addEventListener('click', _closeT4Popup);

  // Tombol toggle T5 — duduk di header T4, mengontrol seluruh blk5
  const _t5Btn = document.createElement('button');
  _t5Btn.textContent = '+ Tabel Bantu 2';
  _t5Btn.style.cssText = 'font-size:11px;padding:2px 10px;border:1px solid #93c5fd;border-radius:4px;background:#eff6ff;color:#1d4ed8;cursor:pointer;white-space:nowrap;flex-shrink:0;';
  _t5Btn.onclick = () => {
    const hidden = blk5.style.display === 'none';
    blk5.style.display = hidden ? '' : 'none';
    _t5Btn.textContent = hidden ? '− Tabel Bantu 2' : '+ Tabel Bantu 2';
    if (hidden) {
      requestAnimationFrame(() => {
        syncSplitViewHeights(pcWrap);
        blk5.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'end' });
      });
    } else {
      requestAnimationFrame(() => {
        blk4.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'start' });
      });
    }
  };
  hdr4.appendChild(_t4InfoBtn);
  hdr4.appendChild(_t5Btn);
  blk4.appendChild(hdr4);
  blk4.appendChild(makeBimtekTable4(hist, proj, isTPAK));

  pcWrap.appendChild(blk1);
  pcWrap.appendChild(blk2);
  pcWrap.appendChild(blk3);
  pcWrap.appendChild(blk4);
  pcWrap.appendChild(blk5);

  // Sinkronisasi dijalankan dari switchChar() saat panel menjadi visible,
  // bukan di sini — karena panel lain masih display:none saat ini.

  wrap.appendChild(pcWrap);
  return wrap;
}

// ══════════════════════════════════════════════════════════════
// RENDER PANEL INDIKATOR PUK
// ══════════════════════════════════════════════════════════════
function renderIndikatorPuk(charDef) {
  return _renderBimtekPanel(charDef);
}
