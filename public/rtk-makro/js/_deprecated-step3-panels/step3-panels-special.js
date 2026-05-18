/* ══════════════════════════════════════════
   step3-panels-special.js — RTK Makro v8
   Panel renderer untuk indikator khusus:
     renderPdrbPanel()        — PDRB (historis + proyeksi)
     renderElastisitasPanel() — Elastisitas KK/PDRB
     renderPelengkapPanel()   — Tabel pelengkap dari rawSheets
   Dipecah dari step3-panels.js
   ══════════════════════════════════════════ */

// ══════════════════════════════════════════════════════════════
// RENDER PANEL PDRB
// Mengikuti PDF referensi:
//   1. PROYEKSI SEMENTARA PDRB (nilai PDRB, kolom = tahun hist)
//   2. LAJU PERTUMBUHAN PDRB   (%, hist)
//   3. ADJUSMENT LAJU PERTUMBUHAN INPUT DATA PDRB
//   4. HASIL PERHITUNGAN PROYEKSI PDRB (proj)
//   5. LAJU PERTUMBUHAN PDRB PER TAHUN (%, proj)
// ══════════════════════════════════════════════════════════════
function renderPdrbPanel() {
  const wrap = document.createElement('div'); wrap.className = 'char-content';
  const histVal = computed['2.A.1']; // PDRB historis nilai
  const histLaju = computed['2.A.2']; // PDRB historis laju %
  const projVal = computed['4.A.1']; // PDRB proyeksi nilai
  const projLaju = computed['4.A.2']; // PDRB proyeksi laju %

  // 1. PROYEKSI SEMENTARA PDRB
  if (hasData('2.A.1')) {
    const b1 = makeBlock(
      'PROYEKSI SEMENTARA PDRB',
      'ATAS DASAR HARGA KONSTAN MENURUT LAPANGAN USAHA (MILIAR RUPIAH)',
      makeTable(histVal, 'Sektor', 'normal'),
    );
    b1.insertBefore(makeCodeBadge('2.A.1', 'Data Historis PDRB Nilai'), b1.querySelector('.tbl-scroll') || b1.firstChild.nextSibling);
    wrap.appendChild(b1);
  }

  // 2. LAJU PERTUMBUHAN PDRB (historis)
  if (hasData('2.A.2')) {
    const b2 = makeBlock(
      'LAJU PERTUMBUHAN PDRB',
      null,
      makeTable(histLaju, 'Sektor', 'normal'),
    );
    b2.insertBefore(makeCodeBadge('2.A.2', 'Data Historis PDRB Laju %'), b2.querySelector('.tbl-scroll') || b2.firstChild.nextSibling);
    wrap.appendChild(b2);
  }

  // 3. ADJUSMENT LAJU PERTUMBUHAN
  // Tabel sederhana: baris = laju diharapkan & proyeksi total
  if (hasData('2.A.1')) {
    const py = pY();
    const adjData = {
      years: py, rows: [
        {
          label: 'LAJU PERTUMBUHAN PDRB YANG DIHARAPKAN DI TAHUN MENDATANG',
          vals: py.map(() => null)
        },
        {
          label: 'PROYEKSI TOTAL PDRB YANG DIHARAPKAN DI TAHUN MENDATANG',
          vals: py.map(() => null)
        },
      ]
    };
    wrap.appendChild(makeBlock(
      'ADJUSMENT LAJU PERTUMBUHAN INPUT DATA PDRB',
      null,
      makeTable(adjData, 'Tahun', 'normal'),
      'tbl-adj'
    ));
  }

  // 4. HASIL PERHITUNGAN PROYEKSI PDRB
  if (hasData('4.A.1')) {
    const b4 = makeBlock(
      'HASIL PERHITUNGAN PROYEKSI PDRB',
      'ATAS DASAR HARGA KONSTAN MENURUT LAPANGAN USAHA (MILIAR RUPIAH)',
      makeTable(projVal, 'Sektor', 'normal'),
      'tbl-proj'
    );
    b4.insertBefore(makeCodeBadge('4.A.1', 'Proyeksi PDRB Nilai'), b4.querySelector('.tbl-scroll') || b4.firstChild.nextSibling);
    wrap.appendChild(b4);
  }

  // 5. LAJU PERTUMBUHAN PDRB PER TAHUN (proyeksi)
  if (hasData('4.A.2')) {
    const b5 = makeBlock(
      'LAJU PERTUMBUHAN PDRB PER TAHUN',
      null,
      makeTable(projLaju, 'Sektor', 'normal'),
      'tbl-proj'
    );
    b5.insertBefore(makeCodeBadge('4.A.2', 'Proyeksi PDRB Laju %'), b5.querySelector('.tbl-scroll') || b5.firstChild.nextSibling);
    wrap.appendChild(b5);
  }

  if (!hasData('2.A.1') && !hasData('4.A.1')) {
    wrap.innerHTML = '<div class="no-data-msg">⬜ Belum ada data PDRB.</div>';
  }
  return wrap;
}

// ══════════════════════════════════════════════════════════════
// RENDER PANEL ELASTISITAS
// Perubahan elastisitas = laju KK / laju PDRB per lapangan usaha
// ══════════════════════════════════════════════════════════════
function renderElastisitasPanel() {
  const wrap = document.createElement('div'); wrap.className = 'char-content';
  const pdrb = computed['4.A.1'];
  const kk = computed['4.C.1'];
  const py = pY();

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
    const rKK = kk.rows.find(r => r.label === lbl);
    const vals = py.map((y, i) => {
      if (i === 0) return null;
      const iPrev = pdrb.years.indexOf(py[i - 1]);
      const iCur = pdrb.years.indexOf(y);
      const jPrev = kk.years.indexOf(py[i - 1]);
      const jCur = kk.years.indexOf(y);
      const pdrbPrev = rPDRB && iPrev >= 0 ? rPDRB.vals[iPrev] : null;
      const pdrbCur = rPDRB && iCur >= 0 ? rPDRB.vals[iCur] : null;
      const kkPrev = rKK && jPrev >= 0 ? rKK.vals[jPrev] : null;
      const kkCur = rKK && jCur >= 0 ? rKK.vals[jCur] : null;
      if (pdrbPrev === null || pdrbCur === null || kkPrev === null || kkCur === null) return null;
      if (pdrbPrev === 0 || pdrbCur === 0) return null;
      const lajuPDRB = (pdrbCur - pdrbPrev) / Math.abs(pdrbPrev);
      const lajuKK = (kkCur - kkPrev) / Math.abs(kkPrev);
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

// ══════════════════════════════════════════════════════════════
// RENDER PANEL PELENGKAP
// Tabel 2.B, 2.I, 2.J, 2.K, 2.L, 4.B, 4.E, 4.F, 4.G, 4.H
// Menampilkan data dari rawSheets jika ada, atau placeholder
// ══════════════════════════════════════════════════════════════
function renderPelengkapPanel(charDef) {
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
