/* ══════════════════════════════════════════
   step4-excel-sheets.js — RTK Makro
   Konstanta, helper, dan sheet builder untuk Excel:
     EXCEL_INPUT_SHEETS, style constants,
     buildDaftarIsi(), buildNormalSheet(),
     buildMultiHdrSheet(), buildYearMtxSheet(),
     buildCrossTabSheet()
   Dipecah dari step4-download-excel.js
   ══════════════════════════════════════════ */

const EXCEL_INPUT_SHEETS = [
    // ── BAB 2: Data Historis ──────────────────────────────────────────────────
    { code: '2.A.1', name: 'Laju Pertumbuhan PDRB ADHK 2010 Menurut Lapangan Usaha' },
    { code: '2.A.2', name: 'Struktur PDRB ADHK 2010 Menurut Lapangan Usaha' },
    { code: '2.B.1', name: 'Jumlah Sekolah Negeri dan Swasta Sederajat' },
    { code: '2.B.2', name: 'Realisasi Investasi Menurut Sektor Usaha, Nilai Investasi, Jumlah Proyek dan Kebutuhan Tenaga Kerja' },
    { code: '2.C.1', name: 'Penduduk Usia Kerja (PUK) Menurut Golongan Umur' },
    { code: '2.C.2', name: 'Penduduk Usia Kerja (PUK) Menurut Tingkat Pendidikan' },
    { code: '2.C.3', name: 'Penduduk Usia Kerja (PUK) Menurut Jenis Kelamin' },
    { code: '2.C.4', name: 'Penduduk Usia Kerja (PUK) Menurut Kabupaten/Kota ★' },
    { code: '2.D.1', name: 'Tingkat Partisipasi Angkatan Kerja (TPAK) Menurut Golongan Umur' },
    { code: '2.D.2', name: 'Tingkat Partisipasi Angkatan Kerja (TPAK) Menurut Tingkat Pendidikan' },
    { code: '2.D.3', name: 'Tingkat Partisipasi Angkatan Kerja (TPAK) Menurut Jenis Kelamin' },
    { code: '2.D.4', name: 'Tingkat Partisipasi Angkatan Kerja (TPAK) Menurut Kabupaten/Kota ★' },
    { code: '2.E.1', name: 'Angkatan Kerja (AK) Menurut Golongan Umur' },
    { code: '2.E.2', name: 'Angkatan Kerja (AK) Menurut Tingkat Pendidikan' },
    { code: '2.E.3', name: 'Angkatan Kerja (AK) Menurut Jenis Kelamin' },
    { code: '2.E.4', name: 'Angkatan Kerja (AK) Menurut Kabupaten/Kota ★' },
    { code: '2.F.1', name: 'Penduduk yang Bekerja (PYB) Menurut Lapangan Usaha' },
    { code: '2.F.2', name: 'Penduduk yang Bekerja (PYB) Menurut Golongan Umur' },
    { code: '2.F.3', name: 'Penduduk yang Bekerja (PYB) Menurut Tingkat Pendidikan' },
    { code: '2.F.4', name: 'PYB Menurut Jenis Kelamin' },
    { code: '2.F.5', name: 'PYB Menurut Status Pekerjaan Utama' },
    { code: '2.F.6', name: 'PYB Menurut Jabatan' },
    { code: '2.F.7', name: 'PYB Menurut Jam Kerja' },
    { code: '2.F.8', name: 'PYB Menurut Kabupaten/Kota ★' },
    { code: '2.G.1', name: 'Penganggur Terbuka Menurut Golongan Umur' },
    { code: '2.G.2', name: 'Tingkat Penganggur Terbuka Menurut Golongan Umur' },
    { code: '2.G.3', name: 'Penganggur Terbuka Menurut Tingkat Pendidikan' },
    { code: '2.G.4', name: 'Tingkat Penganggur Terbuka Menurut Tingkat Pendidikan' },
    { code: '2.G.5', name: 'Penganggur Terbuka Menurut Jenis Kelamin' },
    { code: '2.G.6', name: 'Tingkat Penganggur Terbuka Menurut Jenis Kelamin' },
    { code: '2.G.7', name: 'Penganggur Terbuka Menurut Kabupaten/Kota ★' },
    { code: '2.G.8', name: 'Tingkat Penganggur Terbuka Menurut Kabupaten/Kota ★' },
    { code: '2.H.1', name: 'Produktivitas Tenaga Kerja Menurut Lapangan Usaha' },
    { code: '2.I.1', name: 'Kapasitas Terpasang, Jumlah Instruktur dan Lulusan Pelatihan' },
    { code: '2.J.1', name: 'Jumlah Pencari Kerja, Bursa Kerja dan Pengantar Kerja' },
    { code: '2.K.1', name: 'Perangkat Hubungan Industrial dan Jaminan Sosial Tenaga Kerja' },
    { code: '2.K.2', name: 'Perkembangan Upah Minimum' },
    { code: '2.L.1', name: 'Jumlah Perusahaan dan Pegawai Pengawas Ketenagakerjaan' },
    // ── BAB 3: Perkiraan (Proyeksi) ───────────────────────────────────────────
//   { code: '3.A.1', name: 'Perkiraan Penduduk Usia Kerja (PUK) Menurut Golongan Umur' },
//   { code: '3.A.2', name: 'Perkiraan Penduduk Usia Kerja (PUK) Menurut Tingkat Pendidikan' },
//   { code: '3.A.3', name: 'Perkiraan Penduduk Usia Kerja (PUK) Menurut Jenis Kelamin' },
//   { code: '3.A.4', name: 'Perkiraan Penduduk Usia Kerja (PUK) Menurut Kabupaten/Kota ★' },
//   { code: '3.B.1', name: 'Perkiraan Tingkat Partisipasi Angkatan Kerja (TPAK) Menurut Golongan Umur' },
//   { code: '3.B.2', name: 'Perkiraan Tingkat Partisipasi Angkatan Kerja (TPAK) Menurut Tingkat Pendidikan' },
//   { code: '3.B.3', name: 'Perkiraan Tingkat Partisipasi Angkatan Kerja (TPAK) Menurut Jenis Kelamin' },
//   { code: '3.B.4', name: 'Perkiraan Tingkat Partisipasi Angkatan Kerja (TPAK) Menurut Kabupaten/Kota ★' },
//   { code: '3.C.1', name: 'Perkiraan Angkatan Kerja (AK) Menurut Golongan Umur' },
//   { code: '3.C.2', name: 'Perkiraan Angkatan Kerja (AK) Menurut Tingkat Pendidikan' },
//   { code: '3.C.3', name: 'Perkiraan Angkatan Kerja (AK) Menurut Jenis Kelamin' },
//   { code: '3.C.4', name: 'Perkiraan Angkatan Kerja (AK) Menurut Kabupaten/Kota ★' },
//   // ── BAB 4: Rencana ────────────────────────────────────────────────────────
// { code: '4.A.1', name: 'Perkiraan Laju Pertumbuhan PDRB ADHK 2010 Menurut Lapangan Usaha' },
// { code: '4.A.2', name: 'Perkiraan Struktur PDRB ADHK 2010 Menurut Lapangan Usaha' },
// { code: '4.B.1', name: 'Rencana Investasi Menurut Sektor Usaha, Nilai Investasi, Jumlah Proyek dan Kebutuhan Tenaga Kerja' },
// { code: '4.C.1', name: 'Kesempatan Kerja Menurut Lapangan Usaha' },
// { code: '4.C.2', name: 'Kesempatan Kerja Menurut Golongan Umur' },
// { code: '4.C.3', name: 'Kesempatan Kerja Menurut Tingkat Pendidikan' },
// { code: '4.C.4', name: 'Kesempatan Kerja Menurut Jenis Kelamin' },
// { code: '4.C.5', name: 'Kesempatan Kerja Menurut Status Pekerjaan' },
// { code: '4.C.6', name: 'Kesempatan Kerja Menurut Jabatan' },
// { code: '4.C.7', name: 'Kesempatan Kerja Menurut Jam Kerja' },
// { code: '4.C.8', name: 'Kesempatan Kerja Menurut Kabupaten/Kota ★' },
// { code: '4.D.1', name: 'Produktivitas Tenaga Kerja Bruto Menurut Lapangan Usaha' },
// { code: '4.E.1', name: 'Tambahan Kesempatan Kerja Menurut Status Pekerjaan dan Tingkat Pendidikan' },
// { code: '4.E.2', name: 'Target Kapasitas Terpasang, Jumlah Instruktur dan Lulusan Pelatihan' },
// { code: '4.F.1', name: 'Tambahan Kesempatan Kerja Menurut Status Pekerjaan dan Lapangan Usaha' },
// { code: '4.F.2', name: 'Target Lowongan, Pencari Kerja, Penempatan, Bursa Kerja & Pengantar Kerja' },
// { code: '4.G.1', name: 'Target Mediator, PP, PKB, SP/SB, LKS Bipartit, Perusahaan dan Tenaga Kerja' },
// { code: '4.G.2', name: 'Target Perkembangan Upah Minimum' },
// { code: '4.H.1', name: 'Jumlah Perusahaan dan Pegawai Pengawas Ketenagakerjaan' },
//   // ── BAB 5: Perkiraan Pengangguran ─────────────────────────────────────────
// { code: '5.A.1', name: 'Perkiraan Penganggur Terbuka Menurut Golongan Umur' },
// { code: '5.A.2', name: 'Perkiraan TPT Menurut Golongan Umur' },
// { code: '5.B.1', name: 'Perkiraan Penganggur Terbuka Menurut Tingkat Pendidikan' },
// { code: '5.B.2', name: 'Perkiraan TPT Menurut Tingkat Pendidikan' },
// { code: '5.C.1', name: 'Perkiraan Penganggur Terbuka Menurut Jenis Kelamin' },
// { code: '5.C.2', name: 'Perkiraan TPT Menurut Jenis Kelamin' },
// { code: '5.D.1', name: 'Perkiraan Penganggur Terbuka Menurut Kabupaten/Kota ★' },
// { code: '5.D.2', name: 'Perkiraan TPT Menurut Kabupaten/Kota ★' },
];

// ══════════════════════════════════════════════════════════════
// STYLE HELPERS — ExcelJS API
// ══════════════════════════════════════════════════════════════

// Border tipis abu-abu muda di semua sisi
const THIN_BORDER = {
    top:    { style: 'thin', color: { argb: 'FFCCCCCC' } },
    bottom: { style: 'thin', color: { argb: 'FFCCCCCC' } },
    left:   { style: 'thin', color: { argb: 'FFCCCCCC' } },
    right:  { style: 'thin', color: { argb: 'FFCCCCCC' } },
};

// Fill — warna latar
const FILL_HDR   = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF1A3A5C' } }; // biru tua — header kolom
const FILL_TITLE = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFDCE6F1' } }; // biru muda — baris judul
const FILL_DI    = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFD9E1F2' } }; // biru muda — header Daftar Isi

// Font default data
const FONT_NORMAL = { name: 'Arial', size: 10 };
// Font header tabel: putih tebal (di atas FILL_HDR biru tua)
const FONT_HDR = { name: 'Arial', size: 10, bold: true, color: { argb: 'FFFFFFFF' } };
// Font header Daftar Isi: hitam tebal (di atas FILL_DI)
const FONT_HDR_DI = { name: 'Arial', size: 10, bold: true };
// Font judul baris 1 (tebal)
const FONT_TITLE = { name: 'Arial', size: 10, bold: true };
// Font hyperlink: biru modern + underline
const FONT_LINK = { name: 'Arial', size: 10, color: { argb: 'FF0563C1' }, underline: true };

// Alignment helpers
const AL_LEFT   = { vertical: 'middle', wrapText: true };
const AL_CENTER = { horizontal: 'center', vertical: 'middle', wrapText: true };
const AL_RIGHT  = { horizontal: 'right', vertical: 'middle' };

// ── Terapkan style pada satu cell ExcelJS ──
function _styleCell(cell, font, alignment, border) {
    cell.font      = font      || FONT_NORMAL;
    cell.alignment = alignment || AL_LEFT;
    cell.border    = border    || THIN_BORDER;
}

// ── Terapkan style header tabel (biru tua + teks putih tebal) ──
function _styleHdr(cell) {
    cell.font      = FONT_HDR;
    cell.fill      = FILL_HDR;
    cell.alignment = AL_CENTER;
    cell.border    = THIN_BORDER;
}

// ── Terapkan style hyperlink ("◀ Daftar Isi" / "Lihat ▶") ──
function _styleLink(cell) {
    _styleCell(cell, FONT_LINK, { vertical: 'middle', horizontal: 'center' }, THIN_BORDER);
}

// ── Set nilai numerik atau string biasa ──
function _val(v) {
    if (v === null || v === undefined || v === '') return '';
    return typeof v === 'number' ? v : v;
}

// ══════════════════════════════════════════════════════════════
// BUILDER: DAFTAR ISI
// Format identik referensi:
//   Row 1: [No, Kode Sheet, Judul Tabel, Aksi]
//   Row 2+: [no, code, name, "View" ← hyperlink ke sheet]
// ColWidths: A=10, B=12, C=101, D=10
// ══════════════════════════════════════════════════════════════
// Baris "Target" selalu ada di posisi pertama Daftar Isi
function buildDaftarIsi(wb, includedSheets) {
    const ws = wb.addWorksheet('Daftar Isi');

    ws.columns = [
        { width: 8  },   // A: No
        { width: 12 },   // B: Kode Sheet
        { width: 101 },  // C: Judul Tabel
        { width: 12 },   // D: Aksi
    ];

    // ── Baris judul workbook ──
    const titleRow = ws.addRow(['', `Data Input RTK Makro — ${P.nama || ''}`, '', '']);
    titleRow.height = 26;
    const tCell = titleRow.getCell(2);
    tCell.font      = { name: 'Arial', size: 13, bold: true, color: { argb: 'FFFFFFFF' } };
    tCell.fill      = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF1A3A5C' } };
    tCell.alignment = { vertical: 'middle', horizontal: 'left' };
    tCell.border    = THIN_BORDER;
    [1, 3, 4].forEach(c => {
        const cell = titleRow.getCell(c);
        cell.fill   = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF1A3A5C' } };
        cell.border = THIN_BORDER;
    });

    // ── Baris header kolom ──
    const hdrRow = ws.addRow(['No', 'Kode Sheet', 'Judul Tabel', 'Aksi']);
    hdrRow.height = 20;
    hdrRow.eachCell(cell => {
        cell.font      = FONT_HDR_DI;
        cell.fill      = FILL_DI;
        cell.alignment = AL_CENTER;
        cell.border    = THIN_BORDER;
    });

    let no = 1;

    // ── Baris Target — selalu tampil di posisi 1 ──
    const rowTarget = ws.addRow([no, 'Target', 'Target Indikator Proyeksi', '']);
    rowTarget.height = 17;
    _styleCell(rowTarget.getCell(1), FONT_NORMAL, AL_CENTER, THIN_BORDER);
    _styleCell(rowTarget.getCell(2), FONT_NORMAL, AL_CENTER, THIN_BORDER);
    _styleCell(rowTarget.getCell(3), FONT_NORMAL, AL_LEFT,   THIN_BORDER);
    const cellViewTarget = rowTarget.getCell(4);
    cellViewTarget.value = { text: 'Lihat ▶', hyperlink: `#'Target'!A1` };
    _styleLink(cellViewTarget);
    no++;

    // ── Baris sheet input data ──
    includedSheets.forEach(item => {
        const row = ws.addRow([no, item.code, item.name, '']);
        row.height = 17;
        _styleCell(row.getCell(1), FONT_NORMAL, AL_CENTER, THIN_BORDER);
        _styleCell(row.getCell(2), FONT_NORMAL, AL_CENTER, THIN_BORDER);
        _styleCell(row.getCell(3), FONT_NORMAL, AL_LEFT,   THIN_BORDER);
        const cellView = row.getCell(4);
        cellView.value = { text: 'Lihat ▶', hyperlink: `#'${item.code}'!A1` };
        _styleLink(cellView);
        no++;
    });

    return ws;
}

// ══════════════════════════════════════════════════════════════
// BUILDER: SHEET TARGET
// Format sesuai referensi schema.js:
//   Row 1: [<<Back, Target Indikator Proyeksi]
//   Row 2: [Indikator, thn1, thn2, ...]    ← header bold
//   Row 3-8: [label_indikator, val, val, ...]
//
// Nilai diambil dari P.targets (override manual, prioritas)
// atau P.targetImports (hasil parse sheet Target Excel)
// Satu baris per indikator — menggunakan code representatif pertama yg ada nilainya
// ══════════════════════════════════════════════════════════════
const _TARGET_INDK = [
    {
        label: 'Penduduk Usia Kerja (PUK)',
        codes: ['3.A.1', '3.A.2', '3.A.3', '3.A.4']
    },
    {
        label: 'Tingkat Partisipasi Angkatan Kerja (TPAK)',
        codes: ['3.B.1', '3.B.2', '3.B.3', '3.B.4']
    },
    {
        label: 'Angkatan Kerja (AK)',
        codes: ['3.C.1', '3.C.2', '3.C.3', '3.C.4']
    },
    {
        label: 'Kesempatan Kerja (KK)',
        codes: ['4.C.1', '4.C.2', '4.C.3', '4.C.4', '4.C.5', '4.C.6', '4.C.7', '4.C.8']
    },
    {
        label: 'Penganggur Terbuka (PT)',
        codes: ['5.A.1', '5.B.1', '5.C.1', '5.D.1']
    },
    {
        label: 'Tingkat Penganggur Terbuka (TPT)',
        codes: ['5.A.2', '5.B.2', '5.C.2', '5.D.2']
    },
];

function buildTargetSheet(wb) {
    const ws = wb.addWorksheet('Target');
    // Kolom target = histEnd+1 s/d projEnd agar mencakup tahun gap & proyeksi
    const years = projYrs || [];

    // Ambil nilai target: prioritas P.targets → P.targetImports
    const _getVal = (codes, year) => {
        for (const code of codes) {
            if (P.targets && P.targets[code] && P.targets[code][year] !== undefined)
                return P.targets[code][year];
            if (P.targetImports && P.targetImports[code] && P.targetImports[code][year] !== undefined)
                return P.targetImports[code][year];
        }
        return null;
    };

    ws.columns = [
        { width: 48 },                          // A: Indikator
        ...years.map(() => ({ width: 14 })),    // tahun proyeksi
    ];

    // Row 1: <<Back + judul
    _addBackRow(ws, 'Target Indikator Proyeksi', years.length);

    // Row 2: header
    const hRow = ws.addRow(['Indikator', ...years.map(y => String(y))]);
    hRow.height = 18;
    hRow.eachCell(cell => _styleHdr(cell));

    // Row 3+: satu baris per indikator
    _TARGET_INDK.forEach(ind => {
        const vals = years.map(y => {
            const v = _getVal(ind.codes, y);
            return v !== null ? v : '';
        });
        const dr = ws.addRow([ind.label, ...vals]);
        dr.height = 16;
        _styleCell(dr.getCell(1), FONT_NORMAL, AL_LEFT, THIN_BORDER);
        for (let c = 2; c <= years.length + 1; c++) {
            _styleCell(dr.getCell(c), FONT_NORMAL, AL_RIGHT, THIN_BORDER);
        }
    });

    return ws;
}

// ══════════════════════════════════════════════════════════════
// HELPER: tambahkan baris judul + tombol kembali di baris 1
// ══════════════════════════════════════════════════════════════
function _addBackRow(ws, name, extraCols) {
    const emptyCount = Math.max(0, extraCols - 1);
    const emptyExtra = Array(emptyCount).fill('');
    const row = ws.addRow(['◀ Daftar Isi', name, ...emptyExtra]);
    row.height = 22;

    // Cell A1: tombol kembali — hyperlink ke Daftar Isi
    const cellBack = row.getCell(1);
    cellBack.value = { text: '◀ Daftar Isi', hyperlink: `#'Daftar Isi'!A1` };
    cellBack.font      = FONT_LINK;
    cellBack.fill      = FILL_TITLE;
    cellBack.alignment = { vertical: 'middle', horizontal: 'center' };
    cellBack.border    = THIN_BORDER;

    // Cell B1: judul tabel — latar biru muda
    const cellTitle = row.getCell(2);
    cellTitle.font      = FONT_TITLE;
    cellTitle.fill      = FILL_TITLE;
    cellTitle.alignment = { vertical: 'middle', wrapText: false };
    cellTitle.border    = THIN_BORDER;

    // Sisa kolom C s.d. (1+extraCols): latar biru muda, border
    for (let c = 3; c <= 1 + extraCols; c++) {
        const cell = row.getCell(c);
        cell.fill   = FILL_TITLE;
        cell.border = THIN_BORDER;
    }

    return row;
}

// ══════════════════════════════════════════════════════════════
// BUILDER: TABEL NORMAL
// Format referensi:
//   Row 1: [<<Back, judul]
//   Row 2: [Uraian, thn1, thn2, ...]   ← header bold
//   Row 3+: [label, v1, v2, ...]
// ColWidths: A=68, year cols=10 each
// ══════════════════════════════════════════════════════════════
function buildNormalSheet(wb, code, name, sheet) {
    const { years, rows } = sheet;
    const ws = wb.addWorksheet(code);

    ws.columns = [
        { width: 68 },
        ...years.map(() => ({ width: 10 })),
    ];

    // Row 1: <<Back + judul
    _addBackRow(ws, name, years.length);

    // Row 2: header
    const hRow = ws.addRow(['Uraian', ...years.map(y => String(y))]);
    hRow.height = 18;
    hRow.eachCell(cell => _styleHdr(cell));

    // Fill untuk baris total/ringkasan
    const FILL_TOTAL = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFE8F0FE' } };

    // Row 3+: data
    rows.forEach(row => {
        const isTotal = row.label === 'Jumlah' || row.label === 'JUMLAH' ||
            row.label === 'TPT' || row.label === 'TPAK' ||
            row.label === 'Laju Pertumbuhan';
        const fnt  = isTotal ? { name: 'Arial', size: 10, bold: true } : FONT_NORMAL;
        const fill = isTotal ? FILL_TOTAL : undefined;
        const dr = ws.addRow([row.label, ...row.vals.map(v => _val(v))]);
        dr.height = 16;
        // Label (col A)
        const cA = dr.getCell(1);
        cA.font = fnt; cA.alignment = AL_LEFT; cA.border = THIN_BORDER;
        if (fill) cA.fill = fill;
        // Nilai (col B+)
        for (let c = 2; c <= years.length + 1; c++) {
            const cN = dr.getCell(c);
            cN.font = fnt; cN.alignment = AL_RIGHT; cN.border = THIN_BORDER;
            if (fill) cN.fill = fill;
        }
    });

    return ws;
}

// ══════════════════════════════════════════════════════════════
// BUILDER: TABEL MULTI-HEADER
// Format referensi (2.B.2):
//   Row 1: [<<Back, judul]
//   Row 2: [Tahun, parent1, parent2, ...]
//   Row 3: ['', child1, child2, ...]
//   Row 4+: [year, v1, v2, ...]
// ══════════════════════════════════════════════════════════════
function buildMultiHdrSheet(wb, code, name, sheet) {
    const { headers, data } = sheet;
    const hdrs = headers || [];
    const ws = wb.addWorksheet(code);

    ws.columns = [
        { width: 12 },
        ...hdrs.map(() => ({ width: 18 })),
    ];

    // Row 1: <<Back
    _addBackRow(ws, name, hdrs.length);

    // Row 2: parent headers
    const pr = ws.addRow(['Tahun', ...hdrs.map(h => h.parent || '')]);
    pr.height = 18;
    pr.eachCell(cell => _styleHdr(cell));

    // Row 3: child headers
    const cr = ws.addRow(['', ...hdrs.map(h => h.child || h.key || '')]);
    cr.height = 18;
    cr.eachCell(cell => _styleHdr(cell));

    // Row 4+: data
    (data || []).forEach(row => {
        const dr = ws.addRow([
            row.year,
            ...hdrs.map(h => _val(row.values[h.key]))
        ]);
        dr.height = 16;
        _styleCell(dr.getCell(1), FONT_NORMAL, AL_CENTER, THIN_BORDER);
        for (let c = 2; c <= hdrs.length + 1; c++) {
            _styleCell(dr.getCell(c), FONT_NORMAL, AL_RIGHT, THIN_BORDER);
        }
    });

    return ws;
}

// ══════════════════════════════════════════════════════════════
// BUILDER: TABEL YEAR-MATRIX
// Format referensi (2.K.2):
//   Row 1: [<<Back, judul]
//   Row 2: [Tahun, cat1, cat2, ...]
//   Row 3+: [year, v1, v2, ...]
// ══════════════════════════════════════════════════════════════
function buildYearMtxSheet(wb, code, name, sheet) {
    const { headers, data } = sheet;
    const hdrs = headers || [];
    const ws = wb.addWorksheet(code);

    ws.columns = [
        { width: 12 },
        ...hdrs.map(() => ({ width: 20 })),
    ];

    _addBackRow(ws, name, hdrs.length);

    const hRow = ws.addRow(['Tahun', ...hdrs]);
    hRow.height = 18;
    hRow.eachCell(cell => _styleHdr(cell));

    (data || []).forEach(row => {
        const dr = ws.addRow([
            row.year,
            ...hdrs.map(h => _val(row.values[h]))
        ]);
        dr.height = 16;
        _styleCell(dr.getCell(1), FONT_NORMAL, AL_CENTER, THIN_BORDER);
        for (let c = 2; c <= hdrs.length + 1; c++) {
            _styleCell(dr.getCell(c), FONT_NORMAL, AL_RIGHT, THIN_BORDER);
        }
    });

    return ws;
}

// ══════════════════════════════════════════════════════════════
// BUILDER: TABEL CROSS-TAB
// Format referensi (4.E.1):
//   Row 1: [<<Back, judul]
//   Row 2: [Uraian, group1, group2, ...]   ← header grup
//   Row 3: ['', sub1, sub2, ...]           ← header sub
//   Row 4+: [label, v1, v2, ...]
// ══════════════════════════════════════════════════════════════
function buildCrossTabSheet(wb, code, name, sheet) {
    const { colHeaders, rows } = sheet;
    const cols = colHeaders || [];
    const ws = wb.addWorksheet(code);

    ws.columns = [
        { width: 40 },
        ...cols.map(() => ({ width: 14 })),
    ];

    _addBackRow(ws, name, cols.length);

    // Row 2: group headers
    const gr = ws.addRow(['Uraian', ...cols.map(h => h.group || '')]);
    gr.height = 18;
    gr.eachCell(cell => _styleHdr(cell));

    // Row 3: sub headers
    const sr = ws.addRow(['', ...cols.map(h => h.sub || '')]);
    sr.height = 18;
    sr.eachCell(cell => _styleHdr(cell));

    // Row 4+: data
    (rows || []).forEach(row => {
        const dr = ws.addRow([
            row.label,
            ...(row.vals || []).map(v => _val(v))
        ]);
        dr.height = 16;
        _styleCell(dr.getCell(1), FONT_NORMAL, AL_LEFT, THIN_BORDER);
        for (let c = 2; c <= cols.length + 1; c++) {
            _styleCell(dr.getCell(c), FONT_NORMAL, AL_RIGHT, THIN_BORDER);
        }
    });

    return ws;
}

