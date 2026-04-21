/* ══════════════════════════════════════════
   step1-upload-excel.js — RTK Makro v8
   Langkah 1: upload file Excel, parsing
   semua sheet, dan deteksi tahun historis
   serta proyeksi
   ══════════════════════════════════════════ */

// ── Navigasi antar langkah ──
function goTo(n) {
  [1, 2, 3, 4].forEach(i => {
    document.getElementById('s' + i).classList.toggle('active', i === n);
    const p = document.getElementById('pg' + i);
    if (p) {
      p.classList.toggle('active', i === n);
      p.classList.toggle('done', i < n);
    }
  });
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

// ── File dipilih ──
function onFile(inp) {
  const f = (inp.files || inp)[0] || (Array.isArray(inp) ? inp[0] : null);
  if (!f) return;
  document.getElementById('fnm').textContent = f.name;
  document.getElementById('fileBadge').style.display = 'flex';
  document.getElementById('uploadZone').style.display = 'none';
  document.getElementById('btnProses').disabled = false;
  document.getElementById('stEx').innerHTML = '';
}

// ── Proses Excel ──
function prosesExcel() {
  const f = document.getElementById('fileIn').files[0]; if (!f) return;
  document.getElementById('stEx').innerHTML = '<div class="msg msg-info">⏳ Membaca file Excel...</div>';
  const rd = new FileReader();
  rd.onload = e => {
    try {
      const wb = XLSX.read(new Uint8Array(e.target.result), { type: 'array', raw: true });
      rawSheets = {}; rawTargetSheet = null; P.targetImports = {}; let hSet = new Set(), pSet = new Set();
      wb.SheetNames.forEach(nm => {
        if (/daftar.?isi/i.test(nm)) return;

        // Sheet "Target" diproses terpisah — bukan bagian rawSheets biasa
        if (/^target$/i.test(nm.trim())) {
          const ws = wb.Sheets[nm];
          const raw = XLSX.utils.sheet_to_json(ws, { header: 1, defval: null, raw: true });
          rawTargetSheet = parseTargetSheet(raw);
          P.targetImports = buildTargetImports(rawTargetSheet);
          return;
        }

        const ws = wb.Sheets[nm];
        const raw = XLSX.utils.sheet_to_json(ws, { header: 1, defval: null, raw: true });
        if (!raw || raw.length < 2) return;
        const title = String((raw[0] || [])[1] || nm).trim();
        let parsed;
        if (CROSS_TAB.has(nm)) { parsed = parseCrossTab(raw); parsed.tableType = 'crossTab'; }
        else if (MULTI_HDR.has(nm)) { parsed = parseMultiHdr(raw); parsed.tableType = 'multiHeader'; }
        else if (YEAR_MTX.has(nm)) { parsed = parseYearMtx(raw); parsed.tableType = 'yearMatrix'; }
        else { parsed = parseNormal(raw); parsed.tableType = 'normal'; }
        rawSheets[nm] = { title, ...parsed };
        const bab = parseInt(nm.split('.')[0]);
        parsed.years.forEach(y => { if (bab === 2) hSet.add(y); if (bab >= 3 && bab <= 5) pSet.add(y); });
        if (parsed.data && parsed.data[0] && parsed.data[0].year !== undefined) {
          parsed.data.forEach(r => { if (bab === 2) hSet.add(r.year); if (bab >= 3 && bab <= 5) pSet.add(r.year); });
        }
      });
      histYrs = [...hSet].sort((a, b) => a - b);
      projYrs = [...pSet].sort((a, b) => a - b);
      if (!histYrs.length && !projYrs.length) {
        document.getElementById('stEx').innerHTML = '<div class="msg msg-warn">⚠ Tidak ditemukan data tahun. Periksa format file.</div>';
        return;
      }
      // Simpan salinan ASLI sebelum ada edit apapun
      rawSheetsOriginal = deepCopy(rawSheets);
      fillDrops();
      saveStateToLS();
      document.getElementById('stEx').innerHTML =
        `<div class="msg msg-ok">✅ Berhasil: ${Object.keys(rawSheets).length} sheet. Historis: ${histYrs[0]}–${histYrs[histYrs.length - 1]} | Proyeksi: ${projYrs[0] || '?'}–${projYrs[projYrs.length - 1] || '?'}</div>`;
      setTimeout(() => goTo(2), 500);
    } catch (err) {
      document.getElementById('stEx').innerHTML = `<div class="msg msg-err">❌ Error: ${err.message}</div>`;
      console.error(err);
    }
  };
  rd.readAsArrayBuffer(f);
}

// ── Parser: tabel normal (baris = uraian, kolom = tahun) ──
function parseNormal(raw) {
  const hdr = raw[1] || [], years = [], yIdx = [];
  for (let c = 1; c < hdr.length; c++) { const y = parseInt(String(hdr[c] || '')); if (!isNaN(y) && y >= 1990 && y <= 2060) { years.push(y); yIdx.push(c); } }
  const rows = [];
  for (let r = 2; r < raw.length; r++) {
    const row = raw[r]; if (!row) continue;
    const lbl = String(row[0] || '').trim(); if (!lbl || lbl.startsWith('<<')) continue;
    const vals = yIdx.map(c => { const v = row[c]; if (v === null || v === undefined || v === '') return null; const n = parseFloat(String(v).replace(/,/g, '')); return isNaN(n) ? null : n; });
    rows.push({ label: lbl, vals });
  }
  return { years, rows, headers: [String(hdr[0] || 'Uraian')], data: [] };
}

// ── Parser: sheet "Target" ──
// Format yang diharapkan:
//   Baris 0 (opsional): judul sheet, diabaikan
//   Baris header     : col 0 = "Kode" / "Indikator", col 1 = "Label" (opsional), col 2+ = tahun (angka)
//   Baris data       : col 0 = projCode (misal "3.A.1"), col 1 = label, col 2+ = nilai target per tahun
//
// Menghasilkan: { years: [...], rows: [{ code, label, vals: [...] }, ...] }
// null/string di kolom nilai ditoleransi → disimpan sebagai null
function parseTargetSheet(raw) {
  if (!raw || raw.length < 2) return { years: [], rows: [] };

  // Cari baris header: baris pertama yang col 2-nya berupa angka tahun (1990–2060)
  let hdrIdx = -1, years = [], yColStart = 2;
  for (let r = 0; r < Math.min(raw.length, 5); r++) {
    const row = raw[r] || [];
    const yearCols = [];
    // Coba mulai dari kolom 1 atau 2 (toleransi ada/tidaknya kolom label)
    for (let c = 1; c < row.length; c++) {
      const y = parseInt(String(row[c] || ''));
      if (!isNaN(y) && y >= 1990 && y <= 2060) yearCols.push({ y, c });
    }
    if (yearCols.length >= 1) {
      hdrIdx = r;
      years = yearCols.map(o => o.y);
      yColStart = yearCols[0].c;
      // Apakah ada kolom label? Jika yColStart === 1 berarti tidak ada kolom label
      break;
    }
  }
  if (hdrIdx === -1) return { years: [], rows: [] };

  const hasLabelCol = yColStart >= 2; // col 0 = code, col 1 = label, col 2+ = tahun
  const rows = [];
  for (let r = hdrIdx + 1; r < raw.length; r++) {
    const row = raw[r] || [];
    const code = String(row[0] || '').trim();
    if (!code) continue; // baris kosong
    const label = hasLabelCol ? String(row[1] || '').trim() : '';
    const vals = years.map((y, i) => {
      const c = yColStart + i;
      const v = row[c];
      if (v === null || v === undefined || v === '') return null;
      const n = parseFloat(String(v).replace(/,/g, '.'));
      return isNaN(n) ? null : n;
    });
    rows.push({ code, label, vals });
  }
  return { years, rows };
}

// ── Bangun P.targetImports dari rawTargetSheet ──
// Output: { [projCode]: { [year]: value } }
//
// Sheet "Target" memakai label indikator (bukan kode), misal "Penduduk Usia Kerja (PUK)".
// KEYWORD_MAP menghubungkan label ke semua projCode yang relevan.
// Satu label bisa memetakan ke banyak projCode (PUK → 3.A.1, 3.A.2, 3.A.3, 3.A.4).
// Urutan array PENTING: keyword lebih spesifik harus lebih dulu
// (misal "tpt" sebelum "penganggur", "tpak" sebelum "angkatan kerja").
function buildTargetImports(targetSheet) {
  const out = {};
  if (!targetSheet || !targetSheet.rows || !targetSheet.years) return out;

  const KEYWORD_MAP = [
    // TPT sebelum Penganggur (label TPT juga mengandung kata "penganggur")
    { kw: ['tpt', 'tingkat penganggur'],
      codes: ['5.A.2', '5.B.2', '5.C.2', '5.D.2'] },
    // TPAK sebelum AK (label TPAK juga mengandung "angkatan kerja")
    { kw: ['tpak', 'partisipasi angkatan kerja'],
      codes: ['3.B.1', '3.B.2', '3.B.3', '3.B.4'] },
    // Penganggur Terbuka (PT) — setelah TPT
    { kw: ['penganggur'],
      codes: ['5.A.1', '5.B.1', '5.C.1', '5.D.1'] },
    // PUK
    { kw: ['puk', 'penduduk usia kerja'],
      codes: ['3.A.1', '3.A.2', '3.A.3', '3.A.4'] },
    // AK — setelah TPAK
    { kw: ['angkatan kerja'],
      codes: ['3.C.1', '3.C.2', '3.C.3', '3.C.4'] },
    // KK (Kesempatan Kerja)
    { kw: ['kesempatan kerja', ' kk'],
      codes: ['4.C.1', '4.C.2', '4.C.3', '4.C.4', '4.C.5', '4.C.6', '4.C.7', '4.C.8'] },
  ];

  targetSheet.rows.forEach(r => {
    // r.code berisi label indikator (sheet Target tidak punya kolom kode terpisah)
    if (!r.code) return;
    const labelLow = r.code.toLowerCase();

    // Pakai entry pertama yang cocok
    let matchedCodes = null;
    for (const entry of KEYWORD_MAP) {
      if (entry.kw.some(kw => labelLow.includes(kw))) {
        matchedCodes = entry.codes;
        break;
      }
    }
    if (!matchedCodes) return; // label tidak dikenali → skip

    // Terapkan nilai ke semua projCode yang matching
    matchedCodes.forEach(code => {
      if (!out[code]) out[code] = {};
      targetSheet.years.forEach((y, i) => {
        if (r.vals[i] !== null && r.vals[i] !== undefined) {
          out[code][y] = r.vals[i];
        }
      });
    });
  });

  return out;
}

// ── Parser: tabel multi-header (baris = tahun, kolom = kategori bertingkat) ──
function parseMultiHdr(raw) {
  if (raw.length < 4) return { years: [], rows: [], headers: [], data: [] };
  const r1 = raw[1] || [], r2 = raw[2] || [], hdrs = [];
  let lastP = '';
  for (let i = 1; i < Math.max(r1.length, r2.length); i++) {
    let p = String(r1[i] || '').trim();
    if (p) lastP = p; else p = lastP;
    const c = String(r2[i] || '').trim();
    const key = (p && c) ? p + '_' + c : (c || p); 
    if (key) hdrs.push({ parent: p, child: c, col: i, key });
  }
  const years = [], data = [];
  for (let i = 3; i < raw.length; i++) {
    const row = raw[i]; if (!row || !row[0]) continue;
    const y = parseInt(String(row[0]).trim()); if (isNaN(y) || y < 1990 || y > 2060) continue;
    years.push(y);
    const vals = {}; hdrs.forEach(h => { const n = parseFloat(String(row[h.col] || '').replace(/,/g, '')); vals[h.key] = isNaN(n) ? null : n; });
    data.push({ year: y, values: vals });
  }
  return { years, rows: [], headers: hdrs, data };
}

// ── Parser: tabel year-matrix (baris = tahun, kolom = label tetap) ──
function parseYearMtx(raw) {
  if (raw.length < 3) return { years: [], rows: [], headers: [], data: [] };
  const hr = raw[1] || [], hdrs = [];
  for (let i = 1; i < hr.length; i++) { if (hr[i]) hdrs.push(String(hr[i]).trim()); }
  const years = [], data = [];
  for (let i = 2; i < raw.length; i++) {
    const row = raw[i]; if (!row || !row[0]) continue;
    const y = parseInt(String(row[0]).trim()); if (isNaN(y) || y < 1990 || y > 2060) continue;
    years.push(y);
    const vals = {}; hdrs.forEach((h, j) => { const n = parseFloat(String(row[j + 1] || '').replace(/,/g, '')); vals[h] = isNaN(n) ? null : n; });
    data.push({ year: y, values: vals });
  }
  return { years, rows: [], headers: hdrs, data };
}

// ── Parser: tabel cross-tab (baris = uraian, kolom = kategori silang) ──
function parseCrossTab(raw) {
  if (raw.length < 4) return { years: [], rows: [], colHeaders: [], data: [], tableType: 'crossTab' };
  const hdr1 = raw[1] || [];
  const hdr2 = raw[2] || [];
  const colHeaders = [];
  let lastGroup = '';
  for (let c = 1; c < hdr1.length; c++) {
    const g = String(hdr1[c] || '').trim();
    const s = String(hdr2[c] || '').trim();
    const isSubCol = !g && s !== '' && lastGroup !== '';
    const group = isSubCol ? lastGroup : g;
    if (g) lastGroup = g;
    const label = s || group;
    if (label) colHeaders.push({ col: c, group, sub: s, standalone: !isSubCol && !s });
  }
  const rows = [];
  for (let r = 3; r < raw.length; r++) {
    const row = raw[r]; if (!row) continue;
    const lbl = String(row[0] || '').trim();
    if (!lbl || lbl.startsWith('<<')) continue;
    const vals = colHeaders.map(h => {
      const v = row[h.col];
      if (v === null || v === undefined || v === '') return null;
      const n = parseFloat(String(v).replace(/,/g, ''));
      return isNaN(n) ? null : n;
    });
    rows.push({ label: lbl, vals });
  }
  return { years: [], rows, colHeaders, data: [], tableType: 'crossTab' };
}
