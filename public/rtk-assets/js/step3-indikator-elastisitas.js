/* ══════════════════════════════════════════
   step3-indikator-elastisitas.js — RTK Makro v8
   Render panel indikator Elastisitas (Perubahan Elastisitas)

   Perubahan elastisitas = Laju Pertumbuhan KK / Laju Pertumbuhan PDRB
   per lapangan usaha per tahun (sumber: [4.A.1] & [4.C.1]).
   ══════════════════════════════════════════ */

// ══════════════════════════════════════════════════════════════
// RENDER PANEL INDIKATOR ELASTISITAS
// ══════════════════════════════════════════════════════════════
function renderIndikatorElastisitas(charDef) {
  const wrap = document.createElement('div'); wrap.className = 'char-content';
  const pdrb = computed['4.A.1'];
  const kk   = computed['4.C.1'];
  const py   = pY();

  if (!hasData('4.A.1') || !hasData('4.C.1')) {
    wrap.innerHTML = '<div class="no-data-msg">⬜ Data PDRB atau Kesempatan Kerja (lapangan usaha) belum tersedia untuk menghitung elastisitas.</div>';
    return wrap;
  }

  // Hitung elastisitas = Δ%KK / Δ%PDRB per sektor per tahun
  const labels = [...new Set([
    ...(pdrb.rows.map(r => r.label)),
    ...(kk.rows.map(r => r.label)),
  ])].filter(l => l !== 'Jumlah' && l !== 'JUMLAH');
  labels.push('Jumlah');

  const eRows = labels.map(lbl => {
    const rPDRB = pdrb.rows.find(r => r.label === lbl);
    const rKK   = kk.rows.find(r => r.label === lbl);
    const vals = py.map((y, i) => {
      if (i === 0) return null;
      const iPrev = pdrb.years.indexOf(py[i - 1]);
      const iCur  = pdrb.years.indexOf(y);
      const jPrev = kk.years.indexOf(py[i - 1]);
      const jCur  = kk.years.indexOf(y);
      const pdrbPrev = rPDRB && iPrev >= 0 ? rPDRB.vals[iPrev] : null;
      const pdrbCur  = rPDRB && iCur  >= 0 ? rPDRB.vals[iCur]  : null;
      const kkPrev   = rKK   && jPrev >= 0 ? rKK.vals[jPrev]   : null;
      const kkCur    = rKK   && jCur  >= 0 ? rKK.vals[jCur]    : null;
      if (pdrbPrev === null || pdrbCur === null || kkPrev === null || kkCur === null) return null;
      if (pdrbPrev === 0 || pdrbCur === 0) return null;
      const lajuPDRB = (pdrbCur - pdrbPrev) / Math.abs(pdrbPrev);
      const lajuKK   = (kkCur   - kkPrev)   / Math.abs(kkPrev);
      if (lajuPDRB === 0) return null;
      return Math.round(lajuKK / lajuPDRB * 100) / 100;
    });
    return { label: lbl, vals };
  });
  const eData = { years: py, rows: eRows };
  const eBlock = makeBlock(
    'PERUBAHAN ELASTISITAS',
    'Elastisitas = Laju Pertumbuhan KK / Laju Pertumbuhan PDRB per Lapangan Usaha',
    makeTable(eData, 'Lapangan Usaha', 'normal'),
  );
  // Note sumber data komputasi (bukan badge tabel resmi)
  const srcNote = document.createElement('div');
  srcNote.style.cssText = 'margin: 6px 0 12px; font-size: 11px; color: var(--muted); font-style: italic;';
  srcNote.innerHTML = 'Sumber data: Proyeksi PDRB <span style="font-family:monospace;font-weight:700;color:var(--navy);">[4.A.1]</span> &amp; Proyeksi KK <span style="font-family:monospace;font-weight:700;color:var(--navy);">[4.C.1]</span>';
  eBlock.insertBefore(srcNote, eBlock.querySelector('.tbl-scroll') || eBlock.firstChild.nextSibling);
  wrap.appendChild(eBlock);
  return wrap;
}
