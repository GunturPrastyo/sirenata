/* ══════════════════════════════════════════
   step3-indikator-pelengkap.js — RTK Makro v8
   Render panel indikator Pelengkap / Data Dukung (Input Langsung)

   Tabel yang dicover: 2.B, 2.I, 2.J, 2.K, 2.L, 4.B, 4.E, 4.F, 4.G, 4.H
   Menampilkan data dari rawSheets jika ada, atau placeholder.
   ══════════════════════════════════════════ */

// ══════════════════════════════════════════════════════════════
// RENDER PANEL INDIKATOR PELENGKAP (Data Dukung)
// ══════════════════════════════════════════════════════════════
function renderIndikatorPelengkap(charDef) {
  const wrap = document.createElement('div'); wrap.className = 'char-content';
  const hasH = charDef.histCode && hasData(charDef.histCode);
  const hasP = charDef.projCode && hasData(charDef.projCode);

  if (!hasH && !hasP) {
    const nm = document.createElement('div'); nm.className = 'no-data-msg';
    nm.innerHTML = `⬜ Data untuk tabel ini belum diunggah dari Excel.<br><small>Unggah file Excel di Langkah 1 yang memuat sheet ${[charDef.histCode, charDef.projCode].filter(Boolean).join(' / ')}.</small>`;
    wrap.appendChild(nm);
    return wrap;
  }

  if (hasH) {
    const blk = document.createElement('div'); blk.className = 'tbl-block tbl-adj';
    const h = document.createElement('div'); h.className = 'tbl-block-h1';
    h.textContent = 'DATA HISTORIS / INPUT'; blk.appendChild(h);
    blk.appendChild(makeCodeBadge(charDef.histCode, charDef.label));
    const sc = document.createElement('div'); sc.className = 'tbl-scroll';
    sc.appendChild(makeTable(computed[charDef.histCode] || rawSheets[charDef.histCode], 'Uraian', 'normal'));
    blk.appendChild(sc); wrap.appendChild(blk);
  }
  if (hasP) {
    const blk = document.createElement('div'); blk.className = 'tbl-block tbl-proj';
    const h = document.createElement('div'); h.className = 'tbl-block-h1';
    h.textContent = 'DATA PROYEKSI / TARGET'; blk.appendChild(h);
    blk.appendChild(makeCodeBadge(charDef.projCode, charDef.label));
    const sc = document.createElement('div'); sc.className = 'tbl-scroll';
    sc.appendChild(makeTable(computed[charDef.projCode] || rawSheets[charDef.projCode], 'Uraian', 'normal'));
    blk.appendChild(sc); wrap.appendChild(blk);
  }
  return wrap;
}
