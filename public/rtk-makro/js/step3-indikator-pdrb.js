/* ══════════════════════════════════════════
   step3-indikator-pdrb.js — RTK Makro v8
   Render panel indikator PDRB (Produk Domestik Regional Bruto)

   Mengikuti PDF referensi Kepmen 309/2013 — 5 blok:
     1. PROYEKSI SEMENTARA PDRB (nilai PDRB, kolom = tahun hist)
     2. LAJU PERTUMBUHAN PDRB (%, hist)
     3. ADJUSMENT LAJU PERTUMBUHAN INPUT DATA PDRB
     4. HASIL PERHITUNGAN PROYEKSI PDRB (proj)
     5. LAJU PERTUMBUHAN PDRB PER TAHUN (%, proj)
   ══════════════════════════════════════════ */

// ══════════════════════════════════════════════════════════════
// RENDER PANEL INDIKATOR PDRB
// ══════════════════════════════════════════════════════════════
function renderIndikatorPdrb(charDef) {
  const wrap = document.createElement('div'); wrap.className = 'char-content';
  const histVal  = computed['2.A.1']; // PDRB historis nilai
  const histLaju = computed['2.A.2']; // PDRB historis laju %
  const projVal  = computed['4.A.1']; // PDRB proyeksi nilai
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
