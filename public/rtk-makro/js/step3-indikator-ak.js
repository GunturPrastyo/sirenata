/* ══════════════════════════════════════════
   step3-indikator-ak.js — RTK Makro v8
   Render panel indikator AK (Angkatan Kerja)

   Struktur panel:
     BLOK 1 — Data Historis: PUK hist + TPAK hist + AK hist
     BLOK 2 — Hasil Proyeksi: PUK proj + TPAK proj + AK proj (+ target & selisih)
     BLOK 3 — Perubahan: ∆ PUK, ∆ TPAK, ∆ AK
   ══════════════════════════════════════════ */

// Catatan: helper `_addTargetRows(tbl, projCode, proj, projYears)` didefinisikan
// secara global di step3-main.js, dan bisa di-override oleh step3-goal-seek-ui.js
// (untuk menambah tombol 🎯 Goal-Seek di sel Selisih).

// ══════════════════════════════════════════════════════════════
// RENDER PANEL INDIKATOR AK
// ══════════════════════════════════════════════════════════════
function renderIndikatorAk(charDef) {
  const wrap = document.createElement('div');
  wrap.className = 'char-content';

  const hist = computed[charDef.histCode];
  const proj = computed[charDef.projCode];
  const histYears = hist ? hist.years : [];
  const projYears = proj ? proj.years : pY();

  const noHist = !hasData(charDef.histCode);
  const noProj = !hasData(charDef.projCode);

  if (noHist && noProj) {
    wrap.innerHTML = '<div class="no-data-msg">⬜ Belum ada data untuk karakteristik ini.</div>';
    return wrap;
  }

  // ══ BLOK 1: DATA HISTORIS ══
  if (!noHist) {
    const adjWrap = document.createElement('div'); adjWrap.className = 'tbl-block tbl-adj';
    const adjH = document.createElement('div'); adjH.className = 'tbl-block-h1';
    adjH.textContent = 'DATA HISTORIS — Sumber Komputasi AK';
    adjWrap.appendChild(adjH);

    const pukHist = computed[charDef.srcPukHist];
    if (pukHist && hasData(charDef.srcPukHist)) {
      adjWrap.appendChild(makeCodeBadge(charDef.srcPukHist, 'PUK — Penduduk Usia Kerja'));
      const sc = document.createElement('div'); sc.className = 'tbl-scroll';
      sc.appendChild(makeTable(pukHist, charDef.label || 'Uraian', 'normal', null, { editable: true, code: charDef.srcPukHist }));
      adjWrap.appendChild(sc);
    }
    const tpakHist = computed[charDef.srcTpakHist];
    if (tpakHist && hasData(charDef.srcTpakHist)) {
      adjWrap.appendChild(makeCodeBadge(charDef.srcTpakHist, 'TPAK — Tingkat Partisipasi AK (%)'));
      const sc = document.createElement('div'); sc.className = 'tbl-scroll';
      sc.appendChild(makeTable(tpakHist, charDef.label || 'Uraian', 'normal', null, { editable: true, code: charDef.srcTpakHist }));
      adjWrap.appendChild(sc);
    }
    adjWrap.appendChild(makeCodeBadge(charDef.histCode, 'Angkatan Kerja'));
    adjWrap.appendChild(makeRumusNote('AK = PUK × TPAK / 100'));
    const sc2 = document.createElement('div'); sc2.className = 'tbl-scroll';
    sc2.appendChild(makeTable(hist, charDef.label || 'Uraian', 'normal'));
    adjWrap.appendChild(sc2);

    wrap.appendChild(adjWrap);
  }

  // ══ BLOK 2+3: HASIL PROYEKSI | PERUBAHAN ══
  const pcWrap = document.createElement('div'); pcWrap.className = 'proj-change-wrap';

  const projBlock = document.createElement('div'); projBlock.className = 'tbl-block tbl-proj';
  const projH = document.createElement('div'); projH.className = 'tbl-block-h1';
  projH.textContent = 'HASIL PROYEKSI'; projBlock.appendChild(projH);

  if (!noProj) {
    if (charDef.srcPukProj && hasData(charDef.srcPukProj)) {
      projBlock.appendChild(makeCodeBadge(charDef.srcPukProj, 'Proyeksi PUK (Regresi Linear)'));
      const sc = document.createElement('div'); sc.className = 'tbl-scroll';
      sc.appendChild(makeTable(computed[charDef.srcPukProj], charDef.label || 'Uraian', 'normal'));
      projBlock.appendChild(sc);
    }
    if (charDef.srcTpakProj && hasData(charDef.srcTpakProj)) {
      projBlock.appendChild(makeCodeBadge(charDef.srcTpakProj, 'Proyeksi TPAK (Regresi Linear)'));
      const sc = document.createElement('div'); sc.className = 'tbl-scroll';
      sc.appendChild(makeTable(computed[charDef.srcTpakProj], charDef.label || 'Uraian', 'normal'));
      projBlock.appendChild(sc);
    }
    projBlock.appendChild(makeCodeBadge(charDef.projCode, 'Angkatan Kerja Proyeksi'));
    projBlock.appendChild(makeRumusNote('AK Proyeksi = PUK proj × TPAK proj / 100'));
    const sc2 = document.createElement('div'); sc2.className = 'tbl-scroll';
    const _akProjTbl = makeTable(proj, charDef.label || 'Uraian', 'normal');
    _addTargetRows(_akProjTbl, charDef.projCode, proj, projYears);
    sc2.appendChild(_akProjTbl);
    projBlock.appendChild(sc2);
  } else {
    projBlock.innerHTML += '<div class="no-data-msg">⬜ Data proyeksi belum tersedia.</div>';
  }
  pcWrap.appendChild(projBlock);

  // ══ BLOK PERUBAHAN: ∆ PUK, ∆ TPAK, ∆ AK ══
  if (!noProj && projYears.length > 1) {
    const chBlock = document.createElement('div'); chBlock.className = 'tbl-block tbl-change';
    const chH = document.createElement('div'); chH.className = 'tbl-block-h1'; chH.textContent = 'PERUBAHAN'; chBlock.appendChild(chH);

    function addDeltaHeader(title) {
      const d = document.createElement('div');
      d.style.cssText = 'margin:12px 0 6px; font-size:11.5px; font-weight:700; color:#4c1d95; letter-spacing:0.3px;';
      d.textContent = '∆ ' + title;
      chBlock.appendChild(d);
    }

    if (charDef.srcPukProj && charDef.srcTpakProj) {
      addDeltaHeader('Selisih PUK');
      const sc1 = document.createElement('div'); sc1.className = 'tbl-scroll';
      const histPuk = computed[charDef.srcPukHist || charDef.histCode];
      sc1.appendChild(makeTable(computed[charDef.srcPukProj], charDef.label || 'Uraian', 'change', null, { histData: histPuk }));
      chBlock.appendChild(sc1);

      addDeltaHeader('Selisih TPAK');
      const sc2 = document.createElement('div'); sc2.className = 'tbl-scroll';
      const histTpak = computed[charDef.srcTpakHist];
      sc2.appendChild(makeTable(computed[charDef.srcTpakProj], charDef.label || 'Uraian', 'change', null, { histData: histTpak }));
      chBlock.appendChild(sc2);

      addDeltaHeader('Selisih Angkatan Kerja');
      const sc3 = document.createElement('div'); sc3.className = 'tbl-scroll';
      const akSurfix = charDef.histCode.split('.')[2];
      const histAk = computed['2.E.' + akSurfix] || hist;
      sc3.appendChild(makeTable(proj, charDef.label || 'Uraian', 'change', null, { histData: histAk }));
      chBlock.appendChild(sc3);
    } else {
      if (charDef.projCode) addDeltaHeader('Selisih ' + (charDef.label || 'Proyeksi'));
      const sc2 = document.createElement('div'); sc2.className = 'tbl-scroll';
      sc2.appendChild(makeTable(proj, charDef.label || 'Uraian', 'change', null, { histData: hist }));
      chBlock.appendChild(sc2);
    }
    pcWrap.appendChild(chBlock);
  }
  wrap.appendChild(pcWrap);
  return wrap;
}
