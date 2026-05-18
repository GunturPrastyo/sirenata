/* ══════════════════════════════════════════
   step3-indikator-tpt.js — RTK Makro v8
   Render panel indikator TPT (Penganggur Terbuka & Tingkat Pengangguran Terbuka)

   Struktur panel:
     BLOK 1 — Data Historis: Penganggur + TPT (%)
     BLOK 2 — Hasil Proyeksi: Perkiraan Penganggur Terbuka + Perkiraan TPT
              (+ target & selisih per tabel)
     BLOK 3 — Perubahan: ∆ Penganggur Terbuka, ∆ TPT
   ══════════════════════════════════════════ */

// Catatan: helper `_addTargetRows(tbl, projCode, proj, projYears)` didefinisikan
// secara global di step3-main.js, dan bisa di-override oleh step3-goal-seek-ui.js
// (untuk menambah tombol 🎯 Goal-Seek di sel Selisih).

// ══════════════════════════════════════════════════════════════
// RENDER PANEL INDIKATOR TPT
// ══════════════════════════════════════════════════════════════
function renderIndikatorTpt(charDef) {
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

  // ══ BLOK 1: DATA HISTORIS — Penganggur & TPT ══
  if (!noHist) {
    const adjWrap = document.createElement('div'); adjWrap.className = 'tbl-block tbl-adj';
    const adjH = document.createElement('div'); adjH.className = 'tbl-block-h1';
    adjH.textContent = 'DATA HISTORIS — Penganggur & TPT';
    adjWrap.appendChild(adjH);

    adjWrap.appendChild(makeCodeBadge(charDef.histCode, 'Penganggur Terbuka'));
    const sc1 = document.createElement('div'); sc1.className = 'tbl-scroll';
    sc1.appendChild(makeTable(hist, charDef.label || 'Uraian', 'normal'));
    adjWrap.appendChild(sc1);

    if (charDef.tptHistCode && hasData(charDef.tptHistCode)) {
      adjWrap.appendChild(makeCodeBadge(charDef.tptHistCode, 'Tingkat Pengangguran Terbuka (TPT)'));
      adjWrap.appendChild(makeRumusNote('TPT = Penganggur / Angkatan Kerja × 100'));
      const sc2 = document.createElement('div'); sc2.className = 'tbl-scroll';
      sc2.appendChild(makeTable(computed[charDef.tptHistCode], charDef.label || 'Uraian', 'normal'));
      adjWrap.appendChild(sc2);
    }

    wrap.appendChild(adjWrap);
  }

  // ══ BLOK 2+3: HASIL PROYEKSI | PERUBAHAN ══
  const pcWrap = document.createElement('div'); pcWrap.className = 'proj-change-wrap';

  const projBlock = document.createElement('div'); projBlock.className = 'tbl-block tbl-proj';
  const projH = document.createElement('div'); projH.className = 'tbl-block-h1';
  projH.textContent = 'HASIL PROYEKSI'; projBlock.appendChild(projH);

  if (!noProj) {
    projBlock.appendChild(makeCodeBadge(charDef.projCode, 'Perkiraan Penganggur Terbuka'));
    const sc1 = document.createElement('div'); sc1.className = 'tbl-scroll';
    const _ptProjTbl = makeTable(proj, charDef.label || 'Uraian', 'normal');
    _addTargetRows(_ptProjTbl, charDef.projCode, proj, projYears);
    sc1.appendChild(_ptProjTbl);
    projBlock.appendChild(sc1);

    if (charDef.tptProjCode && hasData(charDef.tptProjCode)) {
      projBlock.appendChild(makeCodeBadge(charDef.tptProjCode, 'Perkiraan TPT'));
      projBlock.appendChild(makeRumusNote('TPT Proyeksi = Penganggur proj / AK proj × 100'));
      const sc2 = document.createElement('div'); sc2.className = 'tbl-scroll';
      const _tptProjData = computed[charDef.tptProjCode];
      const _tptProjTbl = makeTable(_tptProjData, charDef.label || 'Uraian', 'normal');
      _addTargetRows(_tptProjTbl, charDef.tptProjCode, _tptProjData, projYears);
      sc2.appendChild(_tptProjTbl);
      projBlock.appendChild(sc2);
    }
  } else {
    projBlock.innerHTML += '<div class="no-data-msg">⬜ Data proyeksi belum tersedia.</div>';
  }
  pcWrap.appendChild(projBlock);

  // ══ BLOK PERUBAHAN: ∆ Penganggur, ∆ TPT ══
  if (!noProj && projYears.length > 1) {
    const chBlock = document.createElement('div'); chBlock.className = 'tbl-block tbl-change';
    const chH = document.createElement('div'); chH.className = 'tbl-block-h1'; chH.textContent = 'PERUBAHAN'; chBlock.appendChild(chH);

    function addDeltaHeader(title) {
      const d = document.createElement('div');
      d.style.cssText = 'margin:12px 0 6px; font-size:11.5px; font-weight:700; color:#4c1d95; letter-spacing:0.3px;';
      d.textContent = '∆ ' + title;
      chBlock.appendChild(d);
    }

    if (charDef.tptProjCode) {
      if (charDef.projCode) addDeltaHeader('Selisih Penganggur Terbuka');
      const sc1 = document.createElement('div'); sc1.className = 'tbl-scroll';
      sc1.appendChild(makeTable(proj, charDef.label || 'Uraian', 'change', null, { histData: hist }));
      chBlock.appendChild(sc1);

      addDeltaHeader('Selisih TPT');
      const sc2 = document.createElement('div'); sc2.className = 'tbl-scroll';
      const histTpt = computed[charDef.tptHistCode];
      const projTpt = computed[charDef.tptProjCode];
      sc2.appendChild(makeTable(projTpt, charDef.label || 'Uraian', 'change', null, { histData: histTpt }));
      chBlock.appendChild(sc2);
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
