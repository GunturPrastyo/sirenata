/* ══════════════════════════════════════════
   step3-ui-components.js — RTK Makro v8
   Komponen render tabel:
     makeCodeBadge(), makeFlexHeader(),
     makeTable(), makeBimtekTable1–4(), makeBlock()
   Edit/undo/redo dipindah ke step3-edit-manager.js
   ══════════════════════════════════════════ */

// ══════════════════════════════════════════════════════════════
// HELPER GLOBAL — Toast notifikasi singkat
// ══════════════════════════════════════════════════════════════
function _showToast(msg, isError = false) {
  document.querySelectorAll('.rtk-toast').forEach(el => el.remove());
  const toast = document.createElement('div');
  toast.className = 'rtk-toast';
  toast.style.cssText = [
    'position:fixed', 'bottom:28px', 'left:50%', 'transform:translateX(-50%)',
    `background:${isError ? '#dc2626' : '#166534'}`, 'color:#fff',
    'padding:10px 22px', 'border-radius:10px', 'font-size:13px',
    'z-index:10000', 'box-shadow:0 6px 24px rgba(0,0,0,.35)',
    'animation:popup-in .15s ease', 'white-space:nowrap', 'font-weight:500'
  ].join(';');
  toast.textContent = msg;
  document.body.appendChild(toast);
  setTimeout(() => toast.remove(), 3500);
}

// ══════════════════════════════════════════════════════════════
// DIALOG PASTE LAJU DARI EXCEL
// Dipanggil dari tombol di makeBimtekTable2()
// rowLabels : array label baris yang bisa diedit (tanpa Jumlah)
// years     : array tahun proyeksi
// projCode  : kode tabel proyeksi (mis. '3.A.1')
// ══════════════════════════════════════════════════════════════
async function _directLajuPaste(rowLabels, years, projCode) {
  try {
    const text = await navigator.clipboard.readText();
    if (!text || !text.trim()) {
      typeof _showToast === 'function' ? _showToast('⚠️ Clipboard kosong atau bukan teks.') : alert('⚠️ Clipboard kosong atau bukan teks.');
      return;
    }
    
    // Parse teks → grid; pisah baris dengan \n, sel dengan \t (Excel default)
    const rawRows = text.trim().split(/\r?\n/).filter(r => r.trim());
    const grid = rawRows.map(row => row.split('\t'));

    const edits = [];
    let filled = 0, skipped = 0;

    grid.forEach((pasteRow, ri) => {
      if (ri >= rowLabels.length) return;
      const rowLabel = rowLabels[ri];

      // Ambil hanya kolom sejumlah tahun proyeksi dari posisi paling kanan.
      // Hal ini mencegah kesalahan bergeser jika user ikut mem-blok kolom historis
      const startCol = Math.max(0, pasteRow.length - years.length);
      const targetCols = pasteRow.slice(startCol);

      targetCols.forEach((cell, ci) => {
        if (ci >= years.length) return;
        const year = years[ci];

        // Bersihkan spasi & persen:
        let cleaned = cell.trim().replace(/%/g, '').replace(/\s/g, '');
        
        // Deteksi posisi koma & titik terakhir untuk menentukan pemisah desimal
        const lastComma = cleaned.lastIndexOf(',');
        const lastDot = cleaned.lastIndexOf('.');
        if (lastComma > lastDot) {
          // Koma digunakan sebagai pemisah desimal (misal "1.234,56" atau "1,56")
          cleaned = cleaned.replace(/\./g, '').replace(/,/g, '.');
        } else if (lastDot > lastComma) {
          // Titik digunakan sebagai pemisah desimal (misal "1,234.56" atau "1.56")
          cleaned = cleaned.replace(/,/g, '');
        }
        
        // Pastikan hanya angka, titik desimal tunggal, dan minus yang tersisa
        cleaned = cleaned.replace(/[^0-9.\-]/g, '');
        const newVal  = parseFloat(cleaned);
        if (isNaN(newVal)) { skipped++; return; }

        const oldVal = (P.lajuOverrides && P.lajuOverrides[projCode] &&
                        P.lajuOverrides[projCode][rowLabel] &&
                        P.lajuOverrides[projCode][rowLabel][year] !== undefined)
          ? P.lajuOverrides[projCode][rowLabel][year] : null;

        edits.push({ code: projCode, rowLabel, year, oldVal, newVal });
        filled++;
      });
    });

    if (!edits.length) {
      if (typeof _showToast === 'function') _showToast('⚠️ Tidak ada nilai valid yang bisa di-paste. Cek format.');
      return;
    }

    applyBatchLajuEdits(edits);
    if (typeof _showToast === 'function') _showToast(`✅ ${filled} nilai laju berhasil di-paste${skipped ? ` (${skipped} sel diabaikan)` : ''}`);

  } catch (err) {
    console.warn('Clipboard read failed, falling back to manual paste dialog:', err);
    _showLajuPasteDialog(rowLabels, years, projCode);
  }
}

function _showLajuPasteDialog(rowLabels, years, projCode) {
  document.querySelectorAll('.laju-paste-overlay').forEach(el => el.remove());

  const overlay = document.createElement('div');
  overlay.className = 'laju-paste-overlay';
  overlay.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:9000;display:flex;align-items:center;justify-content:center;';

  const box = document.createElement('div');
  box.style.cssText = 'background:#fff;border-radius:14px;padding:22px 24px;width:500px;max-width:92vw;box-shadow:0 24px 64px rgba(0,0,0,.3);';

  const shortRows = rowLabels.slice(0, 3).join(', ') + (rowLabels.length > 3 ? `, …(${rowLabels.length})` : '');

  box.innerHTML = `
    <div style="font-weight:700;font-size:15px;margin-bottom:6px;">📋 Paste Laju dari Excel</div>
    <div style="font-size:12px;color:#4b5563;margin-bottom:10px;line-height:1.5;">
      Salin <strong>${rowLabels.length} baris × ${years.length} kolom</strong> nilai laju dari Excel
      <em>(tanpa header, tanpa tanda %)</em>, lalu paste di kotak di bawah.<br>
      <span style="color:#9ca3af;">Baris: ${shortRows} &nbsp;|&nbsp; Kolom: ${years.join(', ')}</span>
    </div>
    <textarea id="_laju-paste-ta" rows="8"
      style="width:100%;box-sizing:border-box;font-family:'DM Mono',monospace;font-size:12px;
             border:2px solid #d1d5db;border-radius:8px;padding:8px;resize:vertical;outline:none;"
      placeholder="Paste data dari Excel di sini (Ctrl+V)…"></textarea>
    <div id="_laju-paste-info" style="min-height:18px;font-size:11px;color:#6b7280;margin-top:6px;"></div>
    <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:14px;">
      <button id="_laju-paste-cancel"
        style="padding:7px 18px;border:1px solid #d1d5db;border-radius:8px;background:#f9fafb;cursor:pointer;font-size:13px;">
        Batal
      </button>
      <button id="_laju-paste-apply"
        style="padding:7px 18px;background:#16a34a;color:#fff;border:none;border-radius:8px;cursor:pointer;font-size:13px;font-weight:700;">
        ✅ Terapkan
      </button>
    </div>`;

  overlay.appendChild(box);
  document.body.appendChild(overlay);

  const ta   = box.querySelector('#_laju-paste-ta');
  const info = box.querySelector('#_laju-paste-info');

  setTimeout(() => ta.focus(), 60);

  // Tutup saat klik overlay
  overlay.addEventListener('click', e => { if (e.target === overlay) overlay.remove(); });

  box.querySelector('#_laju-paste-cancel').onclick = () => overlay.remove();

  // Highlight textarea saat focus agar jelas area paste-nya
  ta.addEventListener('focus', () => { ta.style.borderColor = '#3b82f6'; });
  ta.addEventListener('blur',  () => { ta.style.borderColor = '#d1d5db'; });

  // Preview: hitung berapa sel terdeteksi saat user mengetik/paste
  ta.addEventListener('input', () => {
    const rows = ta.value.trim().split(/\r?\n/).filter(r => r.trim());
    const cols = rows.length ? rows[0].split('\t').length : 0;
    if (!ta.value.trim()) { info.textContent = ''; return; }
    info.textContent = `🔍 Terdeteksi: ${rows.length} baris × ${cols} kolom`;
    info.style.color = '#6b7280';
  });

  // Ctrl+Enter untuk terapkan
  ta.addEventListener('keydown', e => {
    if (e.key === 'Escape') { overlay.remove(); }
    if (e.key === 'Enter' && e.ctrlKey) { box.querySelector('#_laju-paste-apply').click(); }
  });

  // ── Tombol Terapkan ──
  box.querySelector('#_laju-paste-apply').onclick = () => {
    const text = ta.value.trim();
    if (!text) { info.style.color = '#dc2626'; info.textContent = '⚠️ Kotak paste masih kosong.'; return; }

    // Parse teks → grid; pisah baris dengan \n, sel dengan \t (Excel default)
    const rawRows = text.split(/\r?\n/).filter(r => r.trim());
    const grid = rawRows.map(row => row.split('\t'));

    const edits = [];
    let filled = 0, skipped = 0;

    grid.forEach((pasteRow, ri) => {
      if (ri >= rowLabels.length) return;
      const rowLabel = rowLabels[ri];

      // Ambil hanya kolom sejumlah tahun proyeksi dari posisi paling kanan.
      // Hal ini mencegah kesalahan bergeser jika user ikut mem-blok kolom historis
      const startCol = Math.max(0, pasteRow.length - years.length);
      const targetCols = pasteRow.slice(startCol);

      targetCols.forEach((cell, ci) => {
        if (ci >= years.length) return;
        const year = years[ci];

        // Bersihkan spasi & persen:
        let cleaned = cell.trim().replace(/%/g, '').replace(/\s/g, '');
        
        // Deteksi posisi koma & titik terakhir untuk menentukan pemisah desimal
        const lastComma = cleaned.lastIndexOf(',');
        const lastDot = cleaned.lastIndexOf('.');
        if (lastComma > lastDot) {
          // Koma digunakan sebagai pemisah desimal (misal "1.234,56" atau "1,56")
          cleaned = cleaned.replace(/\./g, '').replace(/,/g, '.');
        } else if (lastDot > lastComma) {
          // Titik digunakan sebagai pemisah desimal (misal "1,234.56" atau "1.56")
          cleaned = cleaned.replace(/,/g, '');
        }
        
        // Pastikan hanya angka, titik desimal tunggal, dan minus yang tersisa
        cleaned = cleaned.replace(/[^0-9.\-]/g, '');
        const newVal  = parseFloat(cleaned);
        if (isNaN(newVal)) { skipped++; return; }

        const oldVal = (P.lajuOverrides && P.lajuOverrides[projCode] &&
                        P.lajuOverrides[projCode][rowLabel] &&
                        P.lajuOverrides[projCode][rowLabel][year] !== undefined)
          ? P.lajuOverrides[projCode][rowLabel][year] : null;

        edits.push({ code: projCode, rowLabel, year, oldVal, newVal });
        filled++;
      });
    });

    if (!edits.length) {
      info.style.color = '#dc2626';
      info.textContent = '⚠️ Tidak ada nilai valid yang bisa di-paste. Cek format (gunakan titik/koma desimal, tanpa pemisah ribuan).';
      return;
    }

    applyBatchLajuEdits(edits);
    overlay.remove();
    _showToast(`✅ ${filled} nilai laju berhasil di-paste${skipped ? ` (${skipped} sel diabaikan)` : ''}`);
  };
}

function makeCodeBadge(code, desc) {
  const el = document.createElement('div'); el.className = 'tbl-code-badge';
  const bd = document.createElement('span'); bd.className = 'badge-code'; bd.textContent = '[' + code + ']';
  el.appendChild(bd);
  if (desc) { const ds = document.createElement('span'); ds.className = 'badge-desc'; ds.textContent = ' ' + desc; el.appendChild(ds); }
  return el;
}

function makeRumusNote(formula, code) {
  const el = document.createElement('div'); el.className = 'rumus-note';
  el.innerHTML = '📐 <strong>Rumus:</strong> ' + formula;
  return el;
}

// ══════════════════════════════════════════════════════════════
// RENDER PRIMITIF: buat <table> dari data sheet
// ══════════════════════════════════════════════════════════════

/**
 * Buat tabel HTML dari data computed.
 * @param {object} data         — hasil computed (years + rows)
 * @param {string} labelHeader  — header kolom pertama
 * @param {string} variant      — 'normal'|'change'|'laju'
 * @param {string[]} [overrideYears] — ganti daftar tahun tampil
 * @param {object}  [opts]      — {editable:bool, code:string}
 */
function makeTable(data, labelHeader, variant = 'normal', overrideYears, opts = {}) {
  // --- Dispatch ke renderer spesifik ---
  if (data && data.tableType === 'multiHeader') return makeMultiHeaderTable(data, labelHeader);
  if (data && data.tableType === 'yearMatrix') return makeYearMatrixTable(data, labelHeader);
  if (data && data.tableType === 'crossTab') return makeCrossTabTable(data, labelHeader);

  const tbl = document.createElement('table');
  tbl.className = 'rtk-tbl';
  if (!data || !data.rows || !data.rows.length) {
    const tr = tbl.insertRow(); const td = tr.insertCell();
    td.colSpan = 99; td.style.cssText = 'text-align:center;color:#bbb;padding:14px;font-style:italic;';
    td.textContent = '— Belum ada data —'; return tbl;
  }

  // Sel bisa diedit hanya jika data asli ada di rawSheets (bukan computed)
  const srcSheet = opts.code ? rawSheets[opts.code] : null;
  const editable = opts.editable && srcSheet && srcSheet.tableType === 'normal' && variant === 'normal';

  // Deteksi otomatis apakah nilai persen:
  //   variant='laju'  → selalu persen (laju pertumbuhan %)
  //   variant lain    → cek dari kode tabel atau flag eksplisit
  const isPercent = (variant === 'laju') ||
    (opts.isPercent !== undefined ? opts.isPercent : isPercentCode(opts.code));

  const years = overrideYears || data.years || [];
  const thead = tbl.createTHead();
  const hrow = thead.insertRow();
  const th0 = document.createElement('th'); th0.className = 'col-label';
  th0.textContent = labelHeader || 'Sektor'; hrow.appendChild(th0);
  years.forEach(y => { const th = document.createElement('th'); th.textContent = y; hrow.appendChild(th); });

  // Petunjuk edit — gunakan <caption> agar muncul di atas tabel, bukan di dalam header
  if (editable) {
    const caption = document.createElement('caption');
    caption.className = 'edit-hint';
    caption.innerHTML = '✏️ Klik angka untuk edit &nbsp;|&nbsp; <kbd>Enter</kbd> simpan &amp; hitung ulang &nbsp;|&nbsp; <kbd>Esc</kbd> batal';
    tbl.insertBefore(caption, tbl.firstChild);
  }

  const tbody = tbl.createTBody();
  data.rows.forEach(row => {
    const tr = tbody.insertRow();
    const isTotal = row.label === 'Jumlah' || row.label === 'JUMLAH' || row.label === 'Total' || row.label === 'TOTAL' || row.label === 'TPT' || row.label === 'TPAK';
    if (isTotal) tr.className = 'row-total';

    const td0 = tr.insertCell(); td0.className = 'col-label'; td0.textContent = row.label;

    years.forEach((y, yi) => {
      const td = tr.insertCell();
      const idx = data.years.indexOf(y);   // index di data (filtered)
      // Index di rawSheets.years (unfiltered) — dipakai untuk update saat edit
      const rawSrc = opts.code ? rawSheets[opts.code] : null;
      const rawIdx = rawSrc ? rawSrc.years.indexOf(y) : idx;
      let v = idx >= 0 ? row.vals[idx] : null;

      if (variant === 'change') {
        if (yi > 0) {
          const iPrev = data.years.indexOf(years[yi - 1]);
          const prev = iPrev >= 0 ? row.vals[iPrev] : null;
          v = (v !== null && prev !== null) ? v - prev : null;
        } else if (opts.histData && opts.histData.years && opts.histData.years.length) {
          const lastHistY = opts.histData.years[opts.histData.years.length - 1];
          const rHist = opts.histData.rows.find(r => r.label === row.label);
          const iPrev = opts.histData.years.indexOf(lastHistY);
          const prev = (rHist && iPrev >= 0) ? rHist.vals[iPrev] : null;
          v = (v !== null && prev !== null) ? v - prev : null;
        } else {
          v = null;
        }
      }
      
      if (variant === 'laju') {
        if (yi > 0) {
          const iPrev = data.years.indexOf(years[yi - 1]);
          const prev = iPrev >= 0 ? row.vals[iPrev] : null;
          v = (v !== null && prev !== null && prev !== 0) ? Math.round((v - prev) / Math.abs(prev) * 10000) / 100 : null;
        } else if (opts.histData && opts.histData.years && opts.histData.years.length) {
          const lastHistY = opts.histData.years[opts.histData.years.length - 1];
          const rHist = opts.histData.rows.find(r => r.label === row.label);
          const iPrev = opts.histData.years.indexOf(lastHistY);
          const prev = (rHist && iPrev >= 0) ? rHist.vals[iPrev] : null;
          v = (v !== null && prev !== null && prev !== 0) ? Math.round((v - prev) / Math.abs(prev) * 10000) / 100 : null;
        } else {
          v = null;
        }
      }

      if (v === null) { td.textContent = '-'; td.style.color = '#bbb'; return; }
      const neg = v < 0;

      // ── Check if cell was edited ──
      const editKey = opts.code ? (opts.code + '|' + row.label + '|' + rawIdx) : '';
      const wasEdited = editKey && editedCells[editKey];

      // ── Editable cell ──
      if (editable && !isTotal) {
        td.className = (neg ? 'num-neg' : 'num') + ' cell-editable' + (wasEdited ? ' cell-edited' : '');
        td.contentEditable = 'true'; td.spellcheck = false;
        td.textContent = fmtNum(v, isPercent);
        const capRow = row; const capCode = opts.code;
        td.onfocus = function () {
          // Tampilkan angka mentah saat fokus (menggunakan nilai yang sudah di-edit)
          this.textContent = (v === null || v === undefined) ? '' : String(precise(v)).replace('.', ',');
          const rng = document.createRange(); rng.selectNodeContents(this);
          const sel = window.getSelection(); sel.removeAllRanges(); sel.addRange(rng);
        };
        td.onblur = function () {
          const str = this.textContent.trim().replace(/,/g, '.');
          const newVal = parseFloat(str.replace(/[^0-9.\-]/g, ''));
          const curVal = rawIdx >= 0 ? capRow.vals[idx] : null;
          if (!isNaN(newVal) && newVal !== curVal) {
            onCellEdit(capCode, capRow.label, rawIdx, newVal);
          } else {
            this.textContent = curVal !== null ? fmtNum(curVal, isPercent) : '-';
          }
        };
        td.onkeydown = function (e) {
          if (e.key === 'Enter') { e.preventDefault(); this.blur(); }
          if (e.key === 'Escape') {
            const cur = rawIdx >= 0 ? capRow.vals[idx] : null;
            this.textContent = cur !== null ? fmtNum(cur, isPercent) : '-';
            this.blur();
          }
        };
      } else {
        // Sel baca-saja (read-only): klik → popup nilai penuh (Aturan 2)
        td.className = (neg ? 'num-neg' : 'num') + (wasEdited ? ' cell-edited' : '');
        td.textContent = fmtNum(v, isPercent);
        td.style.cursor = 'pointer';
        const capV = v;
        td.onclick = function () { _showFullValue(this, capV); };
      }
    });
  });
  return tbl;
}

// ── Renderer: multiHeader ──
function makeMultiHeaderTable(data, labelHeader) {
  const tbl = document.createElement('table'); tbl.className = 'rtk-tbl';
  if (!data || !data.data || !data.data.length || !data.headers || !data.headers.length) {
    const tr = tbl.insertRow(); const td = tr.insertCell();
    td.colSpan = 99; td.style.cssText = 'text-align:center;color:#bbb;padding:14px;font-style:italic;';
    td.textContent = '— Belum ada data —'; return tbl;
  }
  
  const thead = tbl.createTHead();
  const tr1 = thead.insertRow();
  const tr2 = thead.insertRow();
  
  const th0 = document.createElement('th');
  th0.rowSpan = 2; th0.className = 'col-label'; th0.style.verticalAlign = 'middle';
  th0.textContent = 'Tahun';
  tr1.appendChild(th0);
  
  let lastParent = null;
  let thParent = null;
  
  data.headers.forEach(h => {
    if (h.parent !== lastParent) {
      thParent = document.createElement('th');
      thParent.textContent = h.parent || '';
      thParent.colSpan = 1;
      tr1.appendChild(thParent);
      lastParent = h.parent;
    } else {
      if (thParent) thParent.colSpan++;
    }
    const thChild = document.createElement('th');
    thChild.textContent = h.child || h.parent || '';
    tr2.appendChild(thChild);
  });
  
  const tbody = tbl.createTBody();
  data.data.forEach(row => {
    const tr = tbody.insertRow();
    const td0 = tr.insertCell(); td0.className = 'col-label'; td0.textContent = row.year;
    data.headers.forEach(h => {
      const td = tr.insertCell(); td.className = 'num';
      const v = row.values[h.key];
      td.textContent = fmtNum(v);           // multiHeader: tidak persen (nilai rupiah/unit)
      td.style.cursor = 'pointer';
      td.onclick = function () { _showFullValue(this, v); };
    });
  });
  return tbl;
}

// ── Renderer: yearMatrix ──
function makeYearMatrixTable(data, labelHeader) {
  const tbl = document.createElement('table'); tbl.className = 'rtk-tbl';
  if (!data || !data.data || !data.data.length || !data.headers || !data.headers.length) {
    const tr = tbl.insertRow(); const td = tr.insertCell();
    td.colSpan = 99; td.style.cssText = 'text-align:center;color:#bbb;padding:14px;font-style:italic;';
    td.textContent = '— Belum ada data —'; return tbl;
  }
  const thead = tbl.createTHead(); const hrow = thead.insertRow();
  const th0 = document.createElement('th'); th0.className = 'col-label'; th0.textContent = 'Tahun'; hrow.appendChild(th0);
  data.headers.forEach(h => { const th = document.createElement('th'); th.textContent = h; hrow.appendChild(th); });
  
  const tbody = tbl.createTBody();
  data.data.forEach(row => {
    const tr = tbody.insertRow();
    const td0 = tr.insertCell(); td0.className = 'col-label'; td0.textContent = row.year;
    data.headers.forEach(h => {
      const td = tr.insertCell(); td.className = 'num';
      const v = row.values[h];
      td.textContent = fmtNum(v);           // yearMatrix: upah minimum dsb. (tidak persen)
      td.style.cursor = 'pointer';
      td.onclick = function () { _showFullValue(this, v); };
    });
  });
  return tbl;
}

// ── Renderer: crossTab ──
function makeCrossTabTable(data, labelHeader) {
  const tbl = document.createElement('table'); tbl.className = 'rtk-tbl';
  if (!data || !data.rows || !data.rows.length || !data.colHeaders || !data.colHeaders.length) {
    const tr = tbl.insertRow(); const td = tr.insertCell();
    td.colSpan = 99; td.style.cssText = 'text-align:center;color:#bbb;padding:14px;font-style:italic;';
    td.textContent = '— Belum ada data —'; return tbl;
  }
  const thead = tbl.createTHead();
  const tr1 = thead.insertRow(); const tr2 = thead.insertRow();
  const th0 = document.createElement('th'); th0.rowSpan = 2; th0.className = 'col-label'; th0.style.verticalAlign = 'middle';
  th0.textContent = labelHeader || 'Uraian'; tr1.appendChild(th0);
  
  let lastGroup = null; let thGroup = null;
  data.colHeaders.forEach(h => {
    if (h.standalone) {
      const th = document.createElement('th'); th.rowSpan = 2; th.textContent = h.group; tr1.appendChild(th);
      lastGroup = null; thGroup = null;
    } else {
      if (h.group !== lastGroup) {
        thGroup = document.createElement('th'); thGroup.textContent = h.group; thGroup.colSpan = 1; tr1.appendChild(thGroup);
        lastGroup = h.group;
      } else {
        if (thGroup) thGroup.colSpan++;
      }
      const thSub = document.createElement('th'); thSub.textContent = h.sub; tr2.appendChild(thSub);
    }
  });
  
  const tbody = tbl.createTBody();
  data.rows.forEach(row => {
    const tr = tbody.insertRow();
    const isTotal = row.label === 'Jumlah' || row.label === 'JUMLAH';
    if (isTotal) tr.className = 'row-total';
    const td0 = tr.insertCell(); td0.className = 'col-label'; td0.textContent = row.label;
    row.vals.forEach(v => {
      const td = tr.insertCell(); td.className = 'num';
      td.textContent = fmtNum(v);           // crossTab: tidak persen
      td.style.cursor = 'pointer';
      td.onclick = function () { _showFullValue(this, v); };
    });
  });
  return tbl;
}

function makeBimtekTable1(hist, proj, labelTitle, histCode) {
    const tbl = document.createElement('table'); tbl.className = 'rtk-tbl';
    if (!hist || !hist.rows) return tbl;
    // Auto-detect: TPAK (2.D.*) → persen; PUK (2.C.*) → ribuan (tidak persen)
    const isPercent = isPercentCode(histCode);
    const hY = hist.years; const y0 = hY[0], y1 = hY[hY.length - 1];
    
    const thead = tbl.createTHead();
    const trH1 = thead.insertRow();
    trH1.innerHTML = `<th rowspan="2" class="col-label" style="vertical-align:middle;">${labelTitle}</th><th colspan="2">Tahun Historis</th><th rowspan="2" style="background:#eef2ff;vertical-align:middle;">Rata Tambahan / Tahun</th>`;
    const trH2 = thead.insertRow();
    trH2.innerHTML = `<th>${y0}</th><th>${y1}</th>`;
    
    // Buat peta label → rataTambahan dari proj.rataTambahanRows
    // (proj.rows sudah sejajar dengan rataTambahanRows setelah fix calcPUKTPAK)
    const rataMap = {};
    if (proj && proj.rows && proj.rataTambahanRows) {
        proj.rows.forEach((pr, pri) => {
            if (proj.rataTambahanRows[pri] !== undefined) {
                rataMap[pr.label] = proj.rataTambahanRows[pri];
            }
        });
    }
    
    const tbody = tbl.createTBody();
    hist.rows.forEach((r, ri) => {
        const isTot = r.label === 'Jumlah' || r.label === 'JUMLAH';
        const tr = tbody.insertRow();
        if (isTot) tr.className = 'row-total';
        const td0 = tr.insertCell(); td0.className = 'col-label'; td0.textContent = r.label;
        
        const v0 = r.vals[0]; const v1 = r.vals[r.vals.length - 1];
        // Lookup rata berdasarkan label (aman dari misalignment indeks)
        const rataD = rataMap[r.label] || null;
        
        // Kolom v0 (y0) dan v1 (y1) → HANYA READONLY (sesuai request)
        [{ v: v0, year: y0 }, { v: v1, year: y1 }].forEach(({ v, year }) => {
            const td = tr.insertCell(); td.className = 'num';
            td.textContent = fmtNum(v, isPercent);
            td.style.cursor = 'pointer';
            td.onclick = function () { _showFullValue(this, v); };
        });
        // Kolom Rata Tambahan → AUTO (kunci)
        const rataVal = rataD ? rataD.rata : 0;
        const tdR = tr.insertCell(); tdR.className = 'num cell-auto';
        tdR.textContent = fmtNum(rataVal, isPercent);
        tdR.style.backgroundColor = isTot ? '#bbf7d0' : '#dcfce7';
        tdR.title = '🔒 Rata tambahan dihitung otomatis dari data historis';
        tdR.style.cursor = 'pointer';
        tdR.onclick = function () { _showFullValue(this, rataVal); };
    });
    
    return tbl;
}

function makeBimtekTable2(hist, proj, projCode) {
    const tbl = document.createElement('table'); tbl.className = 'rtk-tbl';
    if (!hist || !hist.rows) return tbl;

    const thead = tbl.createTHead();
    thead.insertRow().innerHTML = `<th rowspan="2" style="background:#eef2ff;vertical-align:middle;">Laju<br>Pertumbuhan (r)</th><th colspan="${proj.years.length}">Perkiraan Laju Pertumbuhan</th>`;
    thead.insertRow().innerHTML = proj.years.map(y => `<th>${y}</th>`).join('');
    
    // Buat peta label → rataTambahan dari proj
    const rataMap2 = {};
    if (proj.rows && proj.rataTambahanRows) {
        proj.rows.forEach((pr, pri) => {
            if (proj.rataTambahanRows[pri] !== undefined) rataMap2[pr.label] = proj.rataTambahanRows[pri];
        });
    }

    const tbody = tbl.createTBody();
    // Baris Jumlah tidak ditampilkan di tabel Laju (laju tidak bisa dijumlahkan)
    hist.rows.filter(r => !(r.label === 'Jumlah' || r.label === 'JUMLAH')).forEach((r, ri) => {
        const tr = tbody.insertRow();
        const rataD = rataMap2[r.label] || null;
        const laju = rataD ? rataD.laju : 0;

        const lajuRaw = laju; // Biarkan full presisi, UI membulatkannya via fmtPct
        const tdL = tr.insertCell(); tdL.className = 'num';
        tdL.textContent = fmtPct(lajuRaw);
        tdL.style.backgroundColor = '#dcfce7';
        tdL.style.cursor = 'pointer';
        tdL.onclick = function () { _showFullValue(this, lajuRaw); };

        proj.years.forEach((y, yi) => {
            const td = tr.insertCell(); td.className = 'num';
            td.style.backgroundColor = '#fce7f3';

            // Sub-baris: tampilkan laju override atau laju historis (bisa diedit)
            let currentLaju = laju;
            let isEdited = false;
            if (P.lajuOverrides && P.lajuOverrides[projCode] && P.lajuOverrides[projCode][r.label] && P.lajuOverrides[projCode][r.label][y] !== undefined) {
                 currentLaju = P.lajuOverrides[projCode][r.label][y];
                 isEdited = true;
            }
            // Tampilkan visual 2 desimal, namun simpan di background sebagai full presisi
            td.textContent = fmtPct(currentLaju);

            if (projCode) {
                td.className += ' cell-editable' + (isEdited ? ' cell-edited' : '');
                td.contentEditable = 'true'; td.spellcheck = false;
                td.onfocus = function() {
                    const val = (P.lajuOverrides && P.lajuOverrides[projCode] && P.lajuOverrides[projCode][r.label] && P.lajuOverrides[projCode][r.label][y] !== undefined) ? P.lajuOverrides[projCode][r.label][y] : laju;
                    this.textContent = (val === null || val === undefined) ? '' : String(precise(val)).replace('.', ',');
                    const rng = document.createRange(); rng.selectNodeContents(this);
                    const sel = window.getSelection(); sel.removeAllRanges(); sel.addRange(rng);
                };
                td.onblur = function() {
                    let valStr = this.textContent.replace('%','').trim().replace(/,/g, '.');
                    const newVal = parseFloat(valStr.replace(/[^0-9.\-]/g, ''));
                    const val = (P.lajuOverrides && P.lajuOverrides[projCode] && P.lajuOverrides[projCode][r.label] && P.lajuOverrides[projCode][r.label][y] !== undefined) ? P.lajuOverrides[projCode][r.label][y] : laju;
                    if (!isNaN(newVal) && newVal !== precise(val)) {
                        const originalValToStore = (P.lajuOverrides && P.lajuOverrides[projCode] && P.lajuOverrides[projCode][r.label] && P.lajuOverrides[projCode][r.label][y] !== undefined) ? P.lajuOverrides[projCode][r.label][y] : null;
                        onLajuEdit(projCode, r.label, y, originalValToStore, newVal);
                    } else {
                        this.textContent = fmtPct(precise(val));
                    }
                };
                td.onkeydown = function(e) {
                    if (e.key === 'Enter') { e.preventDefault(); this.blur(); }
                    if (e.key === 'Escape') {
                        const val = (P.lajuOverrides && P.lajuOverrides[projCode] && P.lajuOverrides[projCode][r.label] && P.lajuOverrides[projCode][r.label][y] !== undefined) ? P.lajuOverrides[projCode][r.label][y] : laju;
                        this.textContent = fmtPct(precise(val));
                        this.blur();
                    }
                };
            }
        });
    });
    return tbl;
}

function makeBimtekTable3(hist, proj, labelTitle, charDef) {
    const tbl = document.createElement('table'); tbl.className = 'rtk-tbl';
    if (!proj || !proj.rows) return tbl;
    // Auto-detect: TPAK projCode (3.B.*) → persen; PUK projCode (3.A.*) → ribuan
    const isPercent = isPercentCode(charDef.histCode) || isPercentCode(charDef.projCode);

    // Format khusus 5 desimal (hanya tampil saat toggle aktif)
    const fmt5 = (v) => {
        if (v === null || v === undefined || v === '') return '-';
        if (typeof v === 'number') {
            return v.toLocaleString('id-ID', { minimumFractionDigits: 5, maximumFractionDigits: 5 });
        }
        return String(v);
    };

    // State toggle: default tampil format normal
    tbl._show5dec = false;

    // Helper: format sel sesuai mode aktif
    const _fmtCell = (v) => tbl._show5dec ? fmt5(v) : fmtNum(v, isPercent);

    const thead = tbl.createTHead();
    const trH1 = thead.insertRow();
    trH1.innerHTML = `<th rowspan="2" class="col-label" style="vertical-align:middle;">${labelTitle}</th><th colspan="${proj.years.length}">Tahun Proyeksi</th>`;
    const trH2 = thead.insertRow();
    trH2.innerHTML = proj.years.map(y => `<th>${y}</th>`).join('');

    // Baris Jumlah proyeksi — dipakai untuk hitung Selisih
    const _projJmlRow = proj.rows.find(r => r.label === 'Jumlah' || r.label === 'JUMLAH');

    // Kumpulkan semua sel numerik agar bisa di-toggle format
    const _numCells = [];  // [{td, rawVal}]

    const tbody = tbl.createTBody();

    proj.rows.filter(r => !r.isTarget && !r.isSelisih).forEach((r, ri) => {
        const isTot = r.label === 'Jumlah' || r.label === 'JUMLAH';
        const tr = tbody.insertRow();
        if (isTot) tr.className = 'row-total';
        const td0 = tr.insertCell(); td0.className = 'col-label'; td0.textContent = r.label;
        r.vals.forEach(v => {
            const td = tr.insertCell(); td.className = 'num cell-auto';
            td.textContent = _fmtCell(v);
            td.style.backgroundColor = '#bbf7d0';
            td.title = '🔒 Hasil proyeksi dihitung otomatis dari data historis';
            td.style.cursor = 'pointer';
            td.onclick = function () { _showFullValue(this, v); };
            _numCells.push({ td, rawVal: v });
        });
    });

    // ── Helper: ambil nilai target untuk satu projCode + tahun ──
    // Prioritas: (1) override manual P.targets, (2) import dari Excel P.targetImports, (3) null
    const _getTarget = (code, y) => {
        if (P.targets && P.targets[code] && P.targets[code][y] !== undefined) return P.targets[code][y];
        if (P.targetImports && P.targetImports[code] && P.targetImports[code][y] !== undefined) return P.targetImports[code][y];
        return null;
    };
    // Apakah nilai target berasal dari override manual (bukan import)?
    const _isManualTarget = (code, y) => P.targets && P.targets[code] && P.targets[code][y] !== undefined;

    // ── Baris Target (selalu tampil, dapat diedit) ──
    const trTarget = tbody.insertRow(); trTarget.className = 'row-target';
    const tdTL = trTarget.insertCell(); tdTL.className = 'col-label';
    tdTL.textContent = 'Target';
    proj.years.forEach((y, yi) => {
        const storedTarget = _getTarget(charDef.projCode, y);
        const isManual = _isManualTarget(charDef.projCode, y);
        const isImported = !isManual && storedTarget !== null;
        const isSet = storedTarget !== null;
        const td = trTarget.insertCell();
        // cell-edited (biru tua) → override manual; cell-imported (hijau muda) → dari Excel
        td.className = 'num cell-editable target-input' + (isManual ? ' cell-edited' : isImported ? ' cell-imported' : '');
        td.contentEditable = 'true'; td.spellcheck = false;
        td.textContent = isSet ? _fmtCell(storedTarget) : '-';
        td.title = isImported ? '📥 Nilai dari sheet Target Excel (bisa di-override)' : '';
        _numCells.push({ td, rawVal: storedTarget, isTarget: true });
        td.onfocus = function () {
            const cur = _getTarget(charDef.projCode, y);
            this.textContent = cur !== null ? String(precise(cur)).replace('.', ',') : '';
            const rng = document.createRange(); rng.selectNodeContents(this);
            window.getSelection().removeAllRanges(); window.getSelection().addRange(rng);
        };
        td.onblur = function () {
            const str = this.textContent.trim().replace(/,/g, '.');
            const newVal = parseFloat(str.replace(/[^0-9.\-]/g, ''));
            const cur = _getTarget(charDef.projCode, y);
            if (!isNaN(newVal)) {
                if (newVal !== cur) onTargetEdit(charDef.projCode, y, cur, newVal);
            } else {
                // Input kosong / tidak valid → hapus override manual (fallback ke import atau null)
                if (isManual) onTargetEdit(charDef.projCode, y, cur, null);
                else this.textContent = isSet ? _fmtCell(storedTarget) : '-';
            }
        };
        td.onkeydown = function (e) {
            if (e.key === 'Enter') { e.preventDefault(); this.blur(); }
            if (e.key === 'Escape') { this.textContent = isSet ? _fmtCell(storedTarget) : '-'; this.blur(); }
        };
    });

    // ── Baris Selisih = Target − Jumlah Proyeksi (selalu tampil) ──
    const trSelisih = tbody.insertRow(); trSelisih.className = 'row-selisih';
    const tdSL = trSelisih.insertCell(); tdSL.className = 'col-label';
    tdSL.textContent = 'Selisih (Target − Jumlah)';
    proj.years.forEach((y, yi) => {
        const storedTarget = _getTarget(charDef.projCode, y);
        const jumlahVal = _projJmlRow ? _projJmlRow.vals[yi] : null;
        const selisih = (storedTarget !== null && jumlahVal !== null) ? storedTarget - jumlahVal : null;
        const tdS = trSelisih.insertCell(); tdS.className = 'num';
        tdS.style.cssText = `background:${selisih === null ? '#f8fafc' : selisih >= 0 ? '#dcfce7' : '#fee2e2'};font-weight:600;cursor:pointer;`;
        tdS.textContent = selisih !== null ? _fmtCell(selisih) : '-';
        tdS.onclick = function () { _showFullValue(this, selisih); };
        _numCells.push({ td: tdS, rawVal: selisih });
    });

    // ── Toggle function: dipanggil dari tombol di luar tabel ──
    tbl._toggleDecimal = function () {
        tbl._show5dec = !tbl._show5dec;
        _numCells.forEach(c => {
            if (c.isTarget && c.td.contentEditable === 'true') {
                // Untuk sel target, re-read nilai terkini dari P
                // (tidak di-update di sini karena bisa berubah — cukup format ulang rawVal)
            }
            const v = c.rawVal;
            if (v === null || v === undefined) return;
            c.td.textContent = tbl._show5dec ? fmt5(v) : fmtNum(v, isPercent);
        });
    };

    return tbl;
}

// ══════════════════════════════════════════════════════════════
// _computeT4Deltas — Hitung data selisih antar-tahun proyeksi
// (pola yang sama dengan rendering T4, tapi hasilnya berupa objek
//  data {years, rows} bukan DOM — agar bisa dipakai T5 sebagai sumber)
//
// hist      : {years, rows} data historis (T1)
// proj      : {years, rows} data proyeksi (T3)
// isPercent : true jika satuan sudah %  (misal TPAK)
// Return    : {years: proj.years, rows: [{label, vals:[d0,d1,…]}]}
// ══════════════════════════════════════════════════════════════
function _computeT4Deltas(hist, proj, isPercent = false) {
    if (!proj || !proj.rows) return { years: [], rows: [] };
    const histRowMap = {};
    if (hist && hist.rows) hist.rows.forEach(hr => { histRowMap[hr.label] = hr; });
    const rows = proj.rows
        .filter(r => !r.isTarget && !r.isSelisih && !(r.label === 'Jumlah' || r.label === 'JUMLAH'))
        .map(r => {
            const histRow = histRowMap[r.label];
            let prevProj = histRow ? histRow.vals[histRow.vals.length - 1] : 0;
            const vals = proj.years.map((y, yi) => {
                const cur = r.vals[yi];
                const diff = isPercent
                    ? Math.round((cur - prevProj) * 100) / 100
                    : Math.round(cur - prevProj);
                prevProj = cur;
                return diff;
            });
            return { label: r.label, vals };
        });
    return { years: [...proj.years], rows };
}

function makeBimtekTable4(hist, proj, isPercent = false) {
    const tbl = document.createElement('table'); tbl.className = 'rtk-tbl';
    if (!proj || !proj.rows) return tbl;
    const thead = tbl.createTHead();
    const trH1 = thead.insertRow();
    trH1.innerHTML = `<th colspan="${proj.years.length}">Tahun Proyeksi</th><th rowspan="2" style="background:#eef2ff;vertical-align:middle;">Rata Tambahan / Tahun</th>`;
    const trH2 = thead.insertRow();
    trH2.innerHTML = proj.years.map(y => `<th>${y}</th>`).join('');
    
    // Peta label → histRow untuk akses nilai hist terakhir (dipakai tiap baris)
    const histRowMap = {};
    if (hist && hist.rows) {
        hist.rows.forEach(hr => { histRowMap[hr.label] = hr; });
    }

    const tbody = tbl.createTBody();
    // Baris Jumlah tidak ditampilkan (selisih antar proyeksi tidak bisa dijumlahkan)
    proj.rows.filter(r => !r.isTarget && !r.isSelisih && !(r.label === 'Jumlah' || r.label === 'JUMLAH')).forEach((r, ri) => {
        const tr = tbody.insertRow();

        // Nilai historis terakhir → prevProj untuk kolom pertama (sesuai Excel: U35-G35)
        const histRow = histRowMap[r.label];
        let prevProj = histRow ? histRow.vals[histRow.vals.length - 1] : 0;

        // Kumpulkan semua selisih — rata kolom terakhir = AVERAGE(selisih) sesuai Excel AVERAGE(AC:AH)
        const diffs = [];
        proj.years.forEach((y, yi) => {
            const currentProj = r.vals[yi];
            // Kolom 1: proj[firstYear] − hist[lastYear]
            // Kolom 2+: proj[year] − proj[prevYear]
            const diff = isPercent
                ? Math.round((currentProj - prevProj) * 100) / 100
                : Math.round(currentProj - prevProj);
            prevProj = currentProj;
            diffs.push(diff);
            const td = tr.insertCell(); td.className = 'num';
            td.textContent = fmtNum(diff, isPercent);
            td.style.backgroundColor = '#cffafe';
            td.style.cursor = 'pointer';
            td.onclick = function () { _showFullValue(this, diff); };
        });

        // Rata Tambahan / Tahun = AVERAGE semua selisih di atas (bukan rata historis)
        const rata = diffs.length
            ? (isPercent
                ? Math.round(diffs.reduce((a, b) => a + b, 0) / diffs.length * 100) / 100
                : Math.round(diffs.reduce((a, b) => a + b, 0) / diffs.length))
            : 0;
        const tdR = tr.insertCell(); tdR.className = 'num';
        tdR.textContent = fmtNum(rata, isPercent);
        tdR.style.backgroundColor = '#bfdbfe';
        tdR.style.cursor = 'pointer';
        tdR.onclick = function () { _showFullValue(this, rata); };
    });
    return tbl;
}

// ══════════════════════════════════════════════════════════════
// makeBimtekTable5 — Tabel Bantu Analisa Proyeksi ke-2
// Pola rumus SAMA dengan T4 (selisih absolut antar-tahun),
// tapi sumber data berbeda:
//   t3Proj     = data proyeksi (Tabel 3) → sebagai acuan awal (prevProj)
//   t4DeltaData = hasil _computeT4Deltas() → sebagai nilai "current"
//
// Kolom: [tahun proyeksi…] + [Rata Tambahan / Tahun]
// Rumus kolom ke-i:
//   i = 0 → selisih = t4Delta[0] − t3Proj[0]
//   i > 0 → selisih = t4Delta[i] − t4Delta[i−1]   (rantai melalui T4)
// ══════════════════════════════════════════════════════════════
function makeBimtekTable5(t3Proj, t4DeltaData, isPercent = false) {
    const tbl = document.createElement('table'); tbl.className = 'rtk-tbl';
    if (!t4DeltaData || !t4DeltaData.rows || !t4DeltaData.rows.length) return tbl;

    // Kolom pertama (tahun proyeksi pertama) dihapus — mulai dari tahun ke-2
    const allYears = t4DeltaData.years;
    const years = allYears.slice(1);  // skip tahun pertama
    if (!years.length) return tbl;

    const thead = tbl.createTHead();
    const trH1 = thead.insertRow();
    trH1.innerHTML = `<th colspan="${years.length}">Tahun Proyeksi</th><th rowspan="2" style="background:#eef2ff;vertical-align:middle;">Rata Tambahan / Tahun</th>`;
    const trH2 = thead.insertRow();
    trH2.innerHTML = years.map(y => `<th>${y}</th>`).join('');

    // Peta label → t3Proj row (untuk nilai awal prevProj di kolom pertama)
    const t3RowMap = {};
    if (t3Proj && t3Proj.rows) t3Proj.rows.forEach(r => { t3RowMap[r.label] = r; });

    const tbody = tbl.createTBody();
    t4DeltaData.rows.forEach(r => {
        const tr = tbody.insertRow();
        const t3Row = t3RowMap[r.label];
        // prevProj: nilai T3 (proyeksi) untuk tahun pertama → acuan awal kolom 0
        let prevProj = (t3Row && t3Row.vals && t3Row.vals.length) ? t3Row.vals[0] : 0;

        const diffs = [];
        r.vals.forEach((deltaVal, yi) => {
            // Kolom 0 : T4_delta[0] − T3_proj[tahun pertama]
            // Kolom i>0: T4_delta[i] − T4_delta[i−1]  (prev beralih ke T4)
            const diff = isPercent
                ? Math.round((deltaVal - prevProj) * 100) / 100
                : Math.round(deltaVal - prevProj);
            prevProj = deltaVal;   // prev berikutnya = nilai T4 saat ini
            diffs.push(diff);

            // Skip kolom pertama (yi === 0), hanya render dari kolom ke-2
            if (yi === 0) return;

            const td = tr.insertCell(); td.className = 'num';
            td.textContent = fmtNum(diff, isPercent);
            td.style.backgroundColor = '#cffafe';
            td.style.cursor = 'pointer';
            td.onclick = function () { _showFullValue(this, diff); };
        });

        // Rata Tambahan / Tahun = AVERAGE selisih TANPA kolom pertama
        const diffsForAvg = diffs.slice(1);
        const rata = diffsForAvg.length
            ? (isPercent
                ? Math.round(diffsForAvg.reduce((a, b) => a + b, 0) / diffsForAvg.length * 100) / 100
                : Math.round(diffsForAvg.reduce((a, b) => a + b, 0) / diffsForAvg.length))
            : 0;
        const tdR = tr.insertCell(); tdR.className = 'num';
        tdR.textContent = fmtNum(rata, isPercent);
        tdR.style.backgroundColor = '#bfdbfe';
        tdR.style.cursor = 'pointer';
        tdR.onclick = function () { _showFullValue(this, rata); };
    });
    return tbl;
}

/**
 * Blok tabel: judul biru (seperti PDF referensi) + sub-judul kecil + tabel
 */
function makeBlock(title, subtitle, tblEl, className = '') {
  const block = document.createElement('div');
  block.className = 'tbl-block ' + className;

  const h = document.createElement('div'); h.className = 'tbl-block-h1';
  h.textContent = title; block.appendChild(h);

  if (subtitle) {
    const s = document.createElement('div'); s.className = 'tbl-block-sub';
    s.textContent = subtitle; block.appendChild(s);
  }

  const scroll = document.createElement('div'); scroll.className = 'tbl-scroll';
  scroll.appendChild(tblEl); block.appendChild(scroll);
  return block;
}

// ══════════════════════════════════════════════════════════════
// RENDER PANEL STANDAR
// (PUK, TPAK, AK, KK, PT, TPT)
// Panel AK: PUK+TPAK (editable) → AK computed
// Panel KK: PYB (editable) → KK linProj
// Panel TPT: Penganggur + TPT historis & proyeksi
// ══════════════════════════════════════════════════════════════
