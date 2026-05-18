function D(v) { return new Decimal(v === null || v === undefined || isNaN(v) ? 0 : v); }
function _n(d) { return parseFloat(d.toPrecision(15)); }
function precise(num) {
  if (num === null || num === undefined || isNaN(num)) return null;
  return parseFloat(new Decimal(num).toPrecision(15));
}
function pY() { const a = []; for (let y = P.pA; y <= P.pZ; y++)a.push(y); return a; }

function getSheet(code) {
  const d = rawSheets[code]; if (!d) return null;
  const bab = parseInt(code.split('.')[0]);
  const yMin = bab === 2 ? P.hA : P.pA, yMax = bab === 2 ? P.hZ : P.pZ;
  if (d.tableType !== 'normal') {
    if (d.tableType === 'crossTab')
      return { years: [], rows: d.rows, colHeaders: d.colHeaders, data: [], tableType: 'crossTab', status: 'input' };
    return {
      years: d.years, rows: d.rows, headers: d.headers,
      data: (d.data || []).filter(r => r.year >= yMin && r.year <= yMax), tableType: d.tableType, status: 'input'
    };
  }
  const useY = d.years.filter(y => y >= yMin && y <= yMax); if (!useY.length) return null;
  const rows = d.rows.map(r => ({ label: r.label, vals: useY.map(y => { const i = d.years.indexOf(y); return i >= 0 ? r.vals[i] : null; }) }));
  return { years: useY, rows, tableType: 'normal', status: 'input' };
}
function calcAK(cP, cT, years) {
  const dP = rawSheets[cP], dT = rawSheets[cT]; if (!dP || !dT) return null;
  const rows = dP.rows.filter(r => !['Jumlah', 'TPAK', 'TPT'].includes(r.label)).map(rP => {
    const rT = dT.rows.find(r => normL(r.label) === normL(rP.label));
    const vals = years.map(y => { const iP = dP.years.indexOf(y), iT = rT ? dT.years.indexOf(y) : -1; if (iP < 0) return null; const p = rP.vals[iP], t = (rT && iT >= 0) ? rT.vals[iT] : null; if (p === null || t === null) return null; return D(p).mul(t).div(100).toNumber(); });
    return { label: rP.label, vals };
  });
  rows.push({ label: 'Jumlah', vals: years.map((_, ci) => rows.reduce((s, r) => s + (r.vals[ci] || 0), 0)) });
  return { years, rows, tableType: 'normal', status: 'calc' };
}
function calcG(cAK, cPYB, years) {
  const tAK = computed[cAK], dPYB = rawSheets[cPYB]; if (!tAK || !dPYB) return null;
  const rows = tAK.rows.filter(r => r.label !== 'Jumlah').map(rAK => {
    const rPYB = dPYB.rows.find(r => normL(r.label) === normL(rAK.label));
    const vals = years.map((y, ci) => { const ak = rAK.vals[ci]; const iP = rPYB ? dPYB.years.indexOf(y) : -1; const pyb = (rPYB && iP >= 0) ? rPYB.vals[iP] : null; if (ak === null) return null; return Math.max(0, D(ak||0).minus(pyb||0).toNumber()); });
    return { label: rAK.label, vals };
  });
  rows.push({ label: 'Jumlah', vals: years.map((_, ci) => rows.reduce((s, r) => s + (r.vals[ci] || 0), 0)) });
  return { years, rows, tableType: 'normal', status: 'calc' };
}
function calcTPT(cG, cAK, years) {
  const tG = computed[cG], tAK = computed[cAK]; if (!tG || !tAK) return null;
  const rows = tG.rows.filter(r => r.label !== 'Jumlah').map(rG => {
    const rAK = tAK.rows.find(r => normL(r.label) === normL(rG.label));
    return { label: rG.label, vals: years.map((_, ci) => { const g = rG.vals[ci], ak = rAK ? rAK.vals[ci] : null; if (!ak || ak === 0) return null; return D(g).div(ak).mul(100).toNumber(); }) };
  });
  const tG2 = tG.rows.find(r => r.label === 'Jumlah'), tAK2 = tAK.rows.find(r => r.label === 'Jumlah');
  rows.push({ label: 'TPT', vals: years.map((_, ci) => { if (!tG2 || !tAK2 || !tAK2.vals[ci] || tAK2.vals[ci] === 0) return null; return D(tG2.vals[ci]||0).div(tAK2.vals[ci]).mul(100).toNumber(); }) });
  return { years, rows, tableType: 'normal', status: 'calc' };
}
function linProj(srcCode, years) {
  const d = rawSheets[srcCode]; if (!d) return null;
  const hYs = d.years.filter(y => y >= P.hA && y <= P.hZ); if (hYs.length < 2) return null;
  const isTPAK = d.rows.map(r => r.vals.filter(v => v !== null && v > 0).reduce((a, b) => Math.max(a, b), 0)).reduce((a, b) => Math.max(a, b), 0) <= 100;
  const rows = d.rows.filter(r => !['Jumlah', 'TPAK', 'TPT'].includes(r.label)).map(row => {
    const pts = hYs.map(y => ({ y, v: row.vals[d.years.indexOf(y)] })).filter(p => p.v !== null);
    if (pts.length < 2) return { label: row.label, vals: years.map(() => null) };
    const n = pts.length, sx = pts.reduce((s, p) => s + p.y, 0), sy = pts.reduce((s, p) => s + p.v, 0);
    const sxy = pts.reduce((s, p) => s + p.y * p.v, 0), sxx = pts.reduce((s, p) => s + p.y * p.y, 0);
    const b = (n * sxy - sx * sy) / (n * sxx - sx * sx), a = (sy - b * sx) / n;
    return { label: row.label, vals: years.map(y => { const v = a + b * y; return isTPAK ? precise(Math.max(0, Math.min(100, v))) : precise(Math.max(0, v)); }) };
  });
  rows.push({ label: 'Jumlah', vals: years.map((_, ci) => rows.reduce((s, r) => s + (r.vals[ci] || 0), 0)) });
  return { years, rows, tableType: 'normal', status: 'proj' };
}

// Menghitung proyeksi PUK / TPAK berdasarkan Rata-Rata Tambahan historis (seperti Excel Bimtek)
function calcPUKTPAK(srcCode, years, projCode) {
  const d = rawSheets[srcCode]; if (!d) return null;
  const hYs = d.years.filter(y => y >= P.hA && y <= P.hZ); if (hYs.length < 2) return null;
  const isTPAK = srcCode.startsWith('2.D');
  
  // Array untuk menyimpan object tambahan tahunan: {rata, laju}
  const rataTambahanRows = [];

  const rows = d.rows.filter(r => !['Jumlah', 'TPAK', 'TPT'].includes(r.label)).map(row => {
    const ptsInfo = hYs.map(y => ({ y, v: row.vals[d.years.indexOf(y)] })).filter(p => p.v !== null);
    if (ptsInfo.length < 2) {
      rataTambahanRows.push({rata: 0, laju: 0});
      return { label: row.label, vals: years.map(() => null) };
    }
    
    const pAwal = ptsInfo[0];
    const pAkhir = ptsInfo[ptsInfo.length - 1];
    const deltaTahun = pAkhir.y - pAwal.y;
    
    // Hitung rata-rata tambahan dari historis (Metode Linear / Absolut)
    const rataTambahan = deltaTahun > 0 ? D(pAkhir.v - pAwal.v).div(deltaTahun).toNumber() : 0;
    
    // Hitung CAGR (Compound Annual Growth Rate) sebagai Laju (r)
    let laju = 0;
    if (pAwal.v > 0 && pAkhir.v > 0 && deltaTahun > 0) {
       laju = D(Math.pow(pAkhir.v / pAwal.v, 1 / deltaTahun)).minus(1).mul(100).toNumber(); // dalam persentase
    }
    
    rataTambahanRows.push({rata: rataTambahan, laju: laju});
    
    const baseVal = pAkhir.v;
    const baseYear = pAkhir.y;
    
    // Proyeksikan dengan Laju Pertumbuhan (CAGR) dan override jika ada
    const projVals = years.map((y, yi) => {
        let currentLaju = laju;
        if (P.lajuOverrides && P.lajuOverrides[projCode] && P.lajuOverrides[projCode][row.label] && P.lajuOverrides[projCode][row.label][y] !== undefined) {
             currentLaju = P.lajuOverrides[projCode][row.label][y];
        }
        
        // Rumus Proyeksi Makro Bimtek: Metode base-year konstan.
        // Pop_terkini = Pop_Base * ((1 + Laju_Kolom_Ini / 100) ^ N_Tahun)
        const n = y - baseYear;
        const multiplier = Math.pow(1 + currentLaju / 100, n);
        let v = D(baseVal).mul(D(multiplier)).toNumber();
        
        let finalV = isTPAK ? precise(Math.max(0, Math.min(100, v))) : precise(Math.max(0, v));
        return finalV;
    });
    
    return { label: row.label, vals: projVals };
  });

  // -------------------------------------------------------------------------
  // KHUSUS PUK PENDIDIKAN, TEMPAT TINGGAL, JENIS KELAMIN (3.A.2, 3.A.3, 3.A.4)
  // -------------------------------------------------------------------------
  // Hasil calc_PUK di atas (Base * (1+r)^n) hanya sebagai TABEL BANTU (Pembilang).
  // Nilai akhirnya harus didistribusi proporsional agar Jumlah Totalnya 
  // SAMA PERSIS dengan Total PUK Golongan Umur (3.A.1).
  if (projCode === '3.A.2' || projCode === '3.A.3' || projCode === '3.A.4') {
     const tMaster = computed['3.A.1'];
     if (tMaster && tMaster.rows) {
         const masterJmlRow = tMaster.rows.find(r => r.label === 'Jumlah' || r.label === 'JUMLAH');
         if (masterJmlRow) {
             const helperSums = years.map((_, ci) => rows.reduce((s, r) => s + (r.vals[ci] || 0), 0));
             rows.forEach(r => {
                 r.vals = r.vals.map((helperV, ci) => {
                     const sumH = helperSums[ci];
                     const masterSum = masterJmlRow.vals[ci];
                     if (!sumH || sumH === 0) return 0;
                     return precise((helperV / sumH) * masterSum);
                 });
             });
         }
     }
  }
  
  // Baris Jumlah Proyeksi (jika sudah diproporsi di atas, nilainya otomatis sama dengan Jumlah 3.A.1)
  rows.push({ label: 'Jumlah', vals: years.map((_, ci) => rows.reduce((s, r) => s + (r.vals[ci] || 0), 0)) });

  // Tambahkan rataTambahan untuk baris Jumlah = SUM dari semua baris non-Jumlah
  const totalRata = rataTambahanRows.reduce((s, r) => s + (r.rata || 0), 0);
  // Hitung laju gabungan dari data historis total (menggunakan baris Jumlah di rawSheets)
  const jmlRow = isTPAK
    ? d.rows.find(r => r.label === 'TPAK')
    : d.rows.find(r => r.label === 'Jumlah' || r.label === 'JUMLAH');
  let lajuJml = 0;
  if (jmlRow) {
    const jmlPtsInfo = hYs.map(y => ({ y, v: jmlRow.vals[d.years.indexOf(y)] })).filter(p => p.v !== null);
    if (jmlPtsInfo.length >= 2) {
      const pA = jmlPtsInfo[0], pZ = jmlPtsInfo[jmlPtsInfo.length - 1];
      const deltaT = pZ.y - pA.y;
      if (pA.v > 0 && pZ.v > 0 && deltaT > 0) lajuJml = D(Math.pow(pZ.v / pA.v, 1 / deltaT)).minus(1).mul(100).toNumber();
    }
  }
  rataTambahanRows.push({ rata: totalRata, laju: lajuJml });

  // ── KHUSUS TPAK: tambahkan baris 'TPAK Agregat' ─────────────────────────
  // Sumber: baris 'TPAK' dari rawSheets (rata-rata tertimbang historis).
  // Proyeksi: menggunakan laju TPAK agregat + override khusus label 'TPAK'.
  // Baris ini dipakai sebagai acuan Target & Selisih di Tabel 3 (pengganti
  // 'Jumlah' sum-of-rates yang tidak bermakna untuk data persentase).
  if (isTPAK) {
    const tpakHistRow = d.rows.find(r => r.label === 'TPAK');
    if (tpakHistRow) {
      const tpakPts = hYs
        .map(y => ({ y, v: tpakHistRow.vals[d.years.indexOf(y)] }))
        .filter(p => p.v !== null);
      if (tpakPts.length >= 2) {
        const tpakPAwal  = tpakPts[0];
        const tpakPAkhir = tpakPts[tpakPts.length - 1];
        const tpakDelta  = tpakPAkhir.y - tpakPAwal.y;
        let   tpakHistLaju = 0;
        if (tpakPAwal.v > 0 && tpakPAkhir.v > 0 && tpakDelta > 0) {
          tpakHistLaju = D(Math.pow(tpakPAkhir.v / tpakPAwal.v, 1 / tpakDelta)).minus(1).mul(100).toNumber();
        }
        const tpakBase     = tpakPAkhir.v;
        const tpakBaseYear = tpakPAkhir.y;
        const tpakVals = years.map(y => {
          let laju = tpakHistLaju;
          if (P.lajuOverrides?.[projCode]?.['TPAK']?.[y] !== undefined) {
            laju = P.lajuOverrides[projCode]['TPAK'][y];
          }
          const n = y - tpakBaseYear;
          const v = D(tpakBase).mul(Math.pow(1 + laju / 100, n)).toNumber();
          return precise(Math.max(0, Math.min(100, v)));
        });
        rows.push({ label: 'TPAK', vals: tpakVals, isTPAKAggregate: true });
        const tpakRata = tpakDelta > 0 ? D(tpakPAkhir.v - tpakPAwal.v).div(tpakDelta).toNumber() : 0;
        rataTambahanRows.push({ rata: tpakRata, laju: tpakHistLaju });
      }
    }
  }

  // Status khusus untuk diidentifikasi bahwa data ini adalah 'puk_tpak_proj'
  return { years, rows, rataTambahanRows, srcCode, tableType: 'normal', status: 'puk_tpak_proj' };
}

function calcAKProj(cP, cT, years) {
  const tP = computed[cP], tT = computed[cT]; if (!tP || !tT) return null;
  const rows = tP.rows.filter(r => r.label !== 'Jumlah').map(rP => {
    const rT = tT.rows.find(r => normL(r.label) === normL(rP.label));
    return { label: rP.label, vals: years.map((_, ci) => { const p = rP.vals[ci], t = rT ? rT.vals[ci] : null; if (p === null || t === null) return null; return D(p).mul(t).div(100).toNumber(); }) };
  });
  rows.push({ label: 'Jumlah', vals: years.map((_, ci) => rows.reduce((s, r) => s + (r.vals[ci] || 0), 0)) });
  return { years, rows, tableType: 'normal', status: 'proj' };
}
function calcGProj(cAK, cKK, years) {
  const tAK = computed[cAK], tKK = computed[cKK]; if (!tAK || !tKK) return null;
  const rows = tAK.rows.filter(r => r.label !== 'Jumlah').map(rAK => {
    const rKK = tKK.rows.find(r => normL(r.label) === normL(rAK.label));
    return { label: rAK.label, vals: years.map((_, ci) => { const ak = rAK.vals[ci], kk = rKK ? rKK.vals[ci] : null; if (ak === null) return null; return Math.max(0, D(ak||0).minus(kk||0).toNumber()); }) };
  });
  rows.push({ label: 'Jumlah', vals: years.map((_, ci) => rows.reduce((s, r) => s + (r.vals[ci] || 0), 0)) });
  return { years, rows, tableType: 'normal', status: 'proj' };
}
function calcTPTProj(cG, cAK, years) {
  const tG = computed[cG], tAK = computed[cAK]; if (!tG || !tAK) return null;
  const rows = tG.rows.filter(r => r.label !== 'Jumlah').map(rG => {
    const rAK = tAK.rows.find(r => normL(r.label) === normL(rG.label));
    return { label: rG.label, vals: years.map((_, ci) => { const g = rG.vals[ci], ak = rAK ? rAK.vals[ci] : null; if (!ak || ak === 0) return null; return D(g||0).div(ak).mul(100).toNumber(); }) };
  });
  const tG2 = tG.rows.find(r => r.label === 'Jumlah'), tAK2 = tAK.rows.find(r => r.label === 'Jumlah');
  rows.push({ label: 'TPT', vals: years.map((_, ci) => { if (!tG2 || !tAK2 || !tAK2.vals[ci] || tAK2.vals[ci] === 0) return null; return D(tG2.vals[ci]||0).div(tAK2.vals[ci]).mul(100).toNumber(); }) });
  return { years, rows, tableType: 'normal', status: 'proj' };
}

// Hitung Laju Pertumbuhan PDRB dari tabel nilai
// Laju(t) = (PDRB(t) - PDRB(t-1)) / |PDRB(t-1)| * 100
function calcLajuPDRB(srcCode, years) {
  const d = computed[srcCode]; if (!d || !d.rows) return null;
  const rows = d.rows.map(row => {
    const vals = years.map((y, yi) => {
      const iCur = d.years.indexOf(y);
      if (iCur <= 0) return null; // tahun pertama tidak ada selisih
      const iPrev = d.years.indexOf(years[yi - 1]);
      const vCur = iCur >= 0 ? row.vals[iCur] : null;
      const vPrev = iPrev >= 0 ? row.vals[iPrev] : null;
      if (vCur === null || vPrev === null || vPrev === 0) return null;
      return D(vCur).minus(vPrev).div(Math.abs(vPrev)).mul(100).toNumber();
    });
    return { label: row.label, vals };
  });
  return { years, rows, tableType: 'normal', status: 'calc' };
}

// Hitung Produktivitas Tenaga Kerja = PDRB / KK (juta rupiah per orang)
function calcPTK(cPDRB, cKK, years) {
  const tPDRB = computed[cPDRB], tKK = computed[cKK]; if (!tPDRB || !tKK) return null;
  const rows = tPDRB.rows.filter(r => r.label !== 'Jumlah').map(rPDRB => {
    const rKK = tKK.rows.find(r => normL(r.label) === normL(rPDRB.label));
    const vals = years.map((y, ci) => {
      const iP = tPDRB.years.indexOf(y), iK = rKK ? tKK.years.indexOf(y) : -1;
      const pdrb = iP >= 0 ? rPDRB.vals[iP] : null;
      const kk = rKK && iK >= 0 ? rKK.vals[iK] : null;
      if (pdrb === null || !kk || kk === 0) return null;
      return D(pdrb).div(kk).toNumber(); // miliar / orang → dalam miliar per orang
    });
    return { label: rPDRB.label, vals };
  });
  // Baris Jumlah = rata-rata tertimbang (total PDRB / total KK)
  const tPDRBJml = tPDRB.rows.find(r => r.label === 'Jumlah');
  const tKKJml = tKK.rows.find(r => r.label === 'Jumlah');
  if (tPDRBJml && tKKJml) {
    rows.push({ label: 'Rata-rata', vals: years.map((y) => {
      const iP = tPDRB.years.indexOf(y), iK = tKK.years.indexOf(y);
      const p = iP >= 0 ? tPDRBJml.vals[iP] : null;
      const k = iK >= 0 ? tKKJml.vals[iK] : null;
      if (!p || !k || k === 0) return null;
      return D(p).div(k).toNumber();
    })});
  }
  return { years, rows, tableType: 'normal', status: 'calc' };
}


// Patch baris 'TPAK' aggregate di proj TPAK (3.B.*) menggunakan rumus AK/PUK×100
// Dipanggil setelah 3.C.* (AK) selesai dihitung
function _patchTPAKAggregate(tpakCode, pukCode, akCode, years) {
  const tB = computed[tpakCode];
  const tA = computed[pukCode];
  const tC = computed[akCode];
  if (!tB || !tA || !tC) return;

  // Jika ada lajuOverrides untuk 'TPAK' pada projCode ini, calcPUKTPAK sudah
  // menghitung vals yang tepat (CAGR override → = target). Jangan ditimpa.
  const hasTPAKOverride = !!(P.lajuOverrides && P.lajuOverrides[tpakCode] &&
    P.lajuOverrides[tpakCode]['TPAK'] &&
    Object.keys(P.lajuOverrides[tpakCode]['TPAK']).length > 0);
  if (hasTPAKOverride) return;

  const akJml  = tC.rows.find(r => r.label === 'Jumlah' || r.label === 'JUMLAH');
  const pukJml = tA.rows.find(r => r.label === 'Jumlah' || r.label === 'JUMLAH');
  if (!akJml || !pukJml) return;

  // Patch baris individual: TPAK_i = AK_i / PUK_i × 100
  tB.rows
    .filter(r => !r.isTPAKAggregate && r.label !== 'Jumlah' && r.label !== 'JUMLAH')
    .forEach(row => {
      const akRow  = tC.rows.find(r => normL(r.label) === normL(row.label));
      const pukRow = tA.rows.find(r => normL(r.label) === normL(row.label));
      if (!akRow || !pukRow) return;
      row.vals = years.map((_, ci) => {
        const ak  = akRow.vals[ci];
        const puk = pukRow.vals[ci];
        if (ak === null || puk === null || puk === 0) return null;
        return precise(D(ak).div(puk).mul(100).toNumber());
      });
    });

  // Patch baris Jumlah (sum individual yang baru)
  const jmlRow = tB.rows.find(r => r.label === 'Jumlah' || r.label === 'JUMLAH');
  if (jmlRow) {
    const dataRows = tB.rows.filter(r => !r.isTPAKAggregate && r.label !== 'Jumlah' && r.label !== 'JUMLAH');
    jmlRow.vals = years.map((_, ci) => {
      const ak  = akJml.vals[ci];
      const puk = pukJml.vals[ci];
      if (ak === null || puk === null || puk === 0) return null;
      return precise(D(ak).div(puk).mul(100).toNumber());
    });
  }

  // Patch baris aggregate (TPAK % total = AK_jumlah / PUK_jumlah × 100)
  const tpakVals = years.map((_, ci) => {
    const ak  = akJml.vals[ci];
    const puk = pukJml.vals[ci];
    if (ak === null || puk === null || puk === 0) return null;
    return precise(D(ak).div(puk).mul(100).toNumber());
  });

  const aggRow = tB.rows.find(r => r.isTPAKAggregate);
  if (aggRow) {
    aggRow.vals = tpakVals;
  } else {
    tB.rows.push({ label: 'TPAK', vals: tpakVals, isTPAKAggregate: true });
  }
}

function buildComputed() {
  computed = {};
  const hy = hY(), py = pY();
  const codes = [
    '2.A.1', '2.A.2', '2.B.1', '2.B.2',
    '2.C.1', '2.C.2', '2.C.3', '2.C.4',
    '2.D.1', '2.D.2', '2.D.3', '2.D.4',
    '2.E.1', '2.E.2', '2.E.3', '2.E.4',
    '2.F.1', '2.F.2', '2.F.3', '2.F.4', '2.F.5', '2.F.6', '2.F.7', '2.F.8',
    '2.G.1', '2.G.2', '2.G.3', '2.G.4', '2.G.5', '2.G.6', '2.G.7', '2.G.8',
    '2.H.1', '2.I.1', '2.J.1', '2.K.1', '2.K.2', '2.L.1',
    '3.A.1', '3.A.2', '3.A.3', '3.A.4',
    '3.B.1', '3.B.2', '3.B.3', '3.B.4',
    '3.C.1', '3.C.2', '3.C.3', '3.C.4',
    '4.A.1', '4.A.2', '4.B.1',
    '4.C.1', '4.C.2', '4.C.3', '4.C.4', '4.C.5', '4.C.6', '4.C.7', '4.C.8',
    '4.D.1', '4.E.1', '4.E.2', '4.F.1', '4.F.2', '4.G.1', '4.G.2', '4.H.1',
    '5.A.1', '5.A.2', '5.B.1', '5.B.2', '5.C.1', '5.C.2', '5.D.1', '5.D.2',
  ];

  // Kode ini WAJIB selalu dihitung ulang dari rumus,
  // meskipun ada sheet-nya di Excel (rawSheets).
  // Ini agar setiap edit di tabel input (PUK, TPAK, PYB)
  // langsung menyesuaikan semua tabel turunan.
  const ALWAYS_COMPUTE = new Set([
    '2.A.2',                                           // Laju PDRB Historis
    '2.E.1', '2.E.2', '2.E.3', '2.E.4',              // AK = PUK × TPAK / 100
    '2.G.1', '2.G.2', '2.G.3', '2.G.4',              // Penganggur & TPT
    '2.G.5', '2.G.6', '2.G.7', '2.G.8',
    '2.H.1',                                           // PTK Historis
    '3.A.1', '3.A.2', '3.A.3', '3.A.4',              // Proyeksi PUK
    '3.B.1', '3.B.2', '3.B.3', '3.B.4',              // Proyeksi TPAK
    '3.C.1', '3.C.2', '3.C.3', '3.C.4',              // Proyeksi AK
    '4.A.2',                                           // Laju PDRB Proyeksi
    '4.C.1', '4.C.2', '4.C.3', '4.C.4',              // Proyeksi KK (linProj)
    '4.C.5', '4.C.6', '4.C.7', '4.C.8',
    '4.D.1',                                           // PTK Proyeksi
    '5.A.1', '5.A.2', '5.B.1', '5.B.2',              // Keseimbangan
    '5.C.1', '5.C.2', '5.D.1', '5.D.2',
  ]);

  codes.forEach(c => {
    // Ambil dari rawSheets HANYA untuk tabel input murni (bukan tabel turunan)
    if (rawSheets[c] && !ALWAYS_COMPUTE.has(c)) { computed[c] = getSheet(c); return; }

    let t = null;
    if (c === '2.E.1') t = calcAK('2.C.1', '2.D.1', hy);
    if (c === '2.E.2') t = calcAK('2.C.2', '2.D.2', hy);
    if (c === '2.E.3') t = calcAK('2.C.3', '2.D.3', hy);
    if (c === '2.E.4') t = calcAK('2.C.4', '2.D.4', hy);
    if (c === '2.G.1') t = calcG('2.E.1', '2.F.2', hy);
    if (c === '2.G.2') t = calcTPT('2.G.1', '2.E.1', hy);
    if (c === '2.G.3') t = calcG('2.E.2', '2.F.3', hy);
    if (c === '2.G.4') t = calcTPT('2.G.3', '2.E.2', hy);
    if (c === '2.G.5') t = calcG('2.E.3', '2.F.4', hy);
    if (c === '2.G.6') t = calcTPT('2.G.5', '2.E.3', hy);
    if (c === '2.G.7') t = calcG('2.E.4', '2.F.8', hy);
    if (c === '2.G.8') t = calcTPT('2.G.7', '2.E.4', hy);
    // Laju PDRB — dihitung dari nilai PDRB yang sudah ada
    if (c === '2.A.2') t = calcLajuPDRB('2.A.1', hy);
    if (c === '4.A.2') t = calcLajuPDRB('4.A.1', py);
    // PTK = PDRB / KK
    if (c === '2.H.1') t = calcPTK('2.A.1', '2.F.1', hy);
    if (c === '4.D.1') t = calcPTK('4.A.1', '4.C.1', py);
    const srcMap = {
      '4.C.1': '2.F.1', '4.C.2': '2.F.2', '4.C.3': '2.F.3', '4.C.4': '2.F.4',
      '4.C.5': '2.F.5', '4.C.6': '2.F.6', '4.C.7': '2.F.7', '4.C.8': '2.F.8'
    };
    if (srcMap[c]) t = linProj(srcMap[c], py);
    
    // PUK dan TPAK pakai rumus calcPUKTPAK
    const pukTpakMap = {
      '3.A.1': '2.C.1', '3.A.2': '2.C.2', '3.A.3': '2.C.3', '3.A.4': '2.C.4',
      '3.B.1': '2.D.1', '3.B.2': '2.D.2', '3.B.3': '2.D.3', '3.B.4': '2.D.4',
    };
    if (pukTpakMap[c]) t = calcPUKTPAK(pukTpakMap[c], py, c); // pass c as projCode
    // Setelah AK dihitung, patch TPAK aggregate menggunakan AK/PUK dari Golongan Umur (3.C.1/3.A.1)
    // karena total TPAK = total AK / total PUK — nilainya sama untuk semua karakteristik
    if (c === '3.C.1') { t = calcAKProj('3.A.1', '3.B.1', py); computed['3.C.1'] = t; _patchTPAKAggregate('3.B.1', '3.A.1', '3.C.1', py); }
    if (c === '3.C.2') { t = calcAKProj('3.A.2', '3.B.2', py); computed['3.C.2'] = t; _patchTPAKAggregate('3.B.2', '3.A.2', '3.C.2', py); }
    if (c === '3.C.3') { t = calcAKProj('3.A.3', '3.B.3', py); computed['3.C.3'] = t; _patchTPAKAggregate('3.B.3', '3.A.3', '3.C.3', py); }
    if (c === '3.C.4') { t = calcAKProj('3.A.4', '3.B.4', py); computed['3.C.4'] = t; _patchTPAKAggregate('3.B.4', '3.A.4', '3.C.4', py); }
    if (c === '5.A.1') t = calcGProj('3.C.1', '4.C.2', py);
    if (c === '5.A.2') t = calcTPTProj('5.A.1', '3.C.1', py);
    if (c === '5.B.1') t = calcGProj('3.C.2', '4.C.3', py);
    if (c === '5.B.2') t = calcTPTProj('5.B.1', '3.C.2', py);
    if (c === '5.C.1') t = calcGProj('3.C.3', '4.C.4', py);
    if (c === '5.C.2') t = calcTPTProj('5.C.1', '3.C.3', py);
    if (c === '5.D.1') t = calcGProj('3.C.4', '4.C.8', py);
    if (c === '5.D.2') t = calcTPTProj('5.D.1', '3.C.4', py);
    computed[c] = t || { years: parseInt(c) < 3 ? hy : py, rows: [], tableType: 'normal', status: 'empty' };
  });
}

// ══════════════════════════════════════════════════════════════
// HELPERS
// ══════════════════════════════════════════════════════════════
