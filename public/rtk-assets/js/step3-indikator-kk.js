/* ══════════════════════════════════════════
   step3-indikator-kk.js — RTK Makro v8
   Render panel indikator KK (Kesempatan Kerja)

   Struktur panel:
     BLOK 1 — Data Historis: PYB (Dapat Diedit)
     BLOK 2 — Hasil Proyeksi: KK (Regresi Linear dari PYB)
              + Benchmark historis terakhir & selisih
              + Target (dari sheet Excel, jika ada)
     BLOK 3 — Perubahan: ∆ KK (vs data historis PYB)
   ══════════════════════════════════════════ */

// Catatan: helper `_addTargetRows(tbl, projCode, proj, projYears)` didefinisikan
// secara global di step3-main.js, dan bisa di-override oleh step3-goal-seek-ui.js
// (untuk menambah tombol 🎯 Goal-Seek di sel Selisih).

// ══════════════════════════════════════════════════════════════
// RENDER PANEL INDIKATOR KK
// ══════════════════════════════════════════════════════════════
function renderIndikatorKk(charDef) {
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

  // ══ BLOK 1: DATA HISTORIS (PYB — Dapat Diedit) ══
  if (!noHist) {
    const adjWrap = document.createElement('div'); adjWrap.className = 'tbl-block tbl-adj';
    const adjH = document.createElement('div'); adjH.className = 'tbl-block-h1';
    adjH.textContent = 'DATA HISTORIS — PYB (Dapat Diedit)';
    adjWrap.appendChild(adjH);

    adjWrap.appendChild(makeCodeBadge(charDef.histCode, 'PYB / Orang Bekerja — Input Excel'));
    const sc = document.createElement('div'); sc.className = 'tbl-scroll';
    sc.appendChild(makeTable(hist, charDef.label || 'Uraian', 'normal', null, { editable: true, code: charDef.histCode }));
    adjWrap.appendChild(sc);

    wrap.appendChild(adjWrap);
  }

  // ══ BLOK 2+3: HASIL PROYEKSI | PERUBAHAN ══
  const pcWrap = document.createElement('div'); pcWrap.className = 'proj-change-wrap';

  const projBlock = document.createElement('div'); projBlock.className = 'tbl-block tbl-proj';
  const projH = document.createElement('div'); projH.className = 'tbl-block-h1';
  projH.textContent = 'HASIL PROYEKSI'; projBlock.appendChild(projH);

  if (!noProj) {
    projBlock.appendChild(makeCodeBadge(charDef.projCode, 'Kesempatan Kerja (Regresi Linear dari PYB)'));
    projBlock.appendChild(makeRumusNote('KK = linProj(PYB historis)', charDef.projCode));
    const projTbl = makeTable(proj, charDef.label || 'Uraian', 'normal');
    if (hist && hist.rows && projYears.length) {
      const totH = hist.rows.find(r => r.label === 'Jumlah');
      const totP = proj.rows.find(r => r.label === 'Jumlah');
      if (totH && totP) {
        const lhIdx = hist.years.indexOf(histYears[histYears.length - 1]);
        const tv = lhIdx >= 0 ? totH.vals[lhIdx] : null;
        if (tv !== null) {
          const tbody = projTbl.querySelector('tbody');
          const trT = tbody.insertRow(); trT.className = 'row-target';
          const t0 = trT.insertCell(); t0.className = 'col-label'; t0.textContent = 'Benchmark Historis Terakhir';
          projYears.forEach(() => {
            const td = trT.insertCell(); td.className = 'num';
            td.textContent = fmtNum(tv); td.style.cursor = 'pointer';
            td.onclick = function () { _showFullValue(this, tv); };
          });
          const trS = tbody.insertRow(); trS.className = 'row-selisih';
          const s0 = trS.insertCell(); s0.className = 'col-label'; s0.textContent = 'Selisih vs Benchmark';
          projYears.forEach(y => {
            const td = trS.insertCell();
            const i = proj.years.indexOf(y);
            const v = i >= 0 ? totP.vals[i] : null;
            if (v !== null && tv !== null) {
              const d = v - tv;
              td.className = d < 0 ? 'num-neg' : 'num';
              td.textContent = fmtNum(d);
              td.style.cursor = 'pointer';
              td.onclick = function () { _showFullValue(this, d); };
            } else {
              td.textContent = '-';
              td.style.color = '#bbb';
            }
          });
        }
      }
    }
    // Target dari sheet Excel (melengkapi benchmark historis di atas)
    _addTargetRows(projTbl, charDef.projCode, proj, projYears);
    const sc = document.createElement('div'); sc.className = 'tbl-scroll'; sc.appendChild(projTbl); projBlock.appendChild(sc);
  } else {
    projBlock.innerHTML += '<div class="no-data-msg">⬜ Data proyeksi belum tersedia.</div>';
  }
  pcWrap.appendChild(projBlock);

  // ══ BLOK PERUBAHAN: ∆ KK ══
  if (!noProj && projYears.length > 1) {
    const chBlock = document.createElement('div'); chBlock.className = 'tbl-block tbl-change';
    const chH = document.createElement('div'); chH.className = 'tbl-block-h1'; chH.textContent = 'PERUBAHAN'; chBlock.appendChild(chH);

    function addDeltaHeader(title) {
      const d = document.createElement('div');
      d.style.cssText = 'margin:12px 0 6px; font-size:11.5px; font-weight:700; color:#4c1d95; letter-spacing:0.3px;';
      d.textContent = '∆ ' + title;
      chBlock.appendChild(d);
    }

    if (charDef.projCode) addDeltaHeader('Selisih ' + (charDef.label || 'Proyeksi'));
    const sc2 = document.createElement('div'); sc2.className = 'tbl-scroll';
    sc2.appendChild(makeTable(proj, charDef.label || 'Uraian', 'change', null, { histData: hist }));
    chBlock.appendChild(sc2);

    pcWrap.appendChild(chBlock);
  }
  wrap.appendChild(pcWrap);
  return wrap;
}
