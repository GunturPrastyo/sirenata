/* ══════════════════════════════════════════
   data-state.js — RTK Makro v8
   State global, konstanta tabel, definisi
   MERGED_GROUPS (tabel terpadu), dan
   TOC_WORD (struktur dokumen Word)
   ══════════════════════════════════════════ */

// ── Deteksi lingkungan (file:// vs http://) ──
const IS_SERVER = location.protocol === 'http:' || location.protocol === 'https:';

// ── State global ──
let rawSheetsOriginal = {}; // Salinan ASLI dari Excel — TIDAK PERNAH diubah
let rawSheets = {};    // kode → {title,tableType,years,rows,headers,data} — salinan kerja (bisa diedit)
let computed = {};    // kode → {years,rows,tableType,status} — dipakai Step 4 (download)
let computedStep3 = {};// kode → {years,rows,tableType,status} — khusus tampilan Step 3
let histYrs = [];
let projYrs = [];
let P = { nama: '', hA: 0, hZ: 0, pA: 0, pZ: 0, td: 0, targets: {}, targetImports: {}, lajuComments: {} };

// ── Data target dari sheet Excel "Target" ──
// rawTargetSheet  : data mentah hasil parse sheet "Target" dari file Excel yang diupload
//                   format: { years: [2025,2026,...], rows: [{ code, label, vals:[...] }, ...] }
//                   null jika belum ada sheet "Target" di file yang diupload
// P.targets       : { [projCode]: { [year]: value } } — override manual oleh user (prioritas tertinggi)
// P.targetImports : { [projCode]: { [year]: value } } — diisi dari sheet "Target" Excel (fallback)
//
// Merge logic di makeBimtekTable3:
//   nilai target = P.targets[code][year] ?? P.targetImports[code][year] ?? null
let rawTargetSheet = null;

// ── Undo / Redo state ──
let undoStack = [];   // [{code, rowLabel, yearIdx, oldVal, newVal}, ...]
let redoStack = [];   // idem — dikosongkan setiap edit baru
let editedCells = {};   // "code|rowLabel|yearIdx" → true — untuk highlight

// ── Deep copy utility ──
function deepCopy(obj) {
  return JSON.parse(JSON.stringify(obj));
}

// ── Konstanta jenis tabel khusus ──
const PROV_ONLY = new Set(['2.C.4', '2.D.4', '2.E.4', '2.F.8', '2.G.7', '2.G.8', '3.A.4', '3.B.4', '3.C.4', '4.C.8', '5.D.1', '5.D.2']);
const MULTI_HDR = new Set(['2.B.2', '4.B.1']);
const CROSS_TAB = new Set(['4.E.1', '4.F.1']);
const YEAR_MTX = new Set(['2.K.2', '4.G.2']);

// ══════════════════════════════════════════════════════════════
// DEFINISI GRUP TERPADU
// Setiap grup = satu tampilan tabel merged historis+proyeksi.
// Berisi satu atau lebih "lapisan" (PUK, TPAK, AK, PYB, dsb)
// yang berbagi baris/label yang sama.
// ══════════════════════════════════════════════════════════════
const MERGED_GROUPS = [
  // ─── EKONOMI ───
  {
    id: 'eko-pdrb', section: 'A. Kondisi Ekonomi',
    title: 'Pertumbuhan & Struktur PDRB Menurut Lapangan Usaha',
    prov: false,
    layers: [
      { code: '2.A.1', label: 'Laju Pertumbuhan PDRB ADHK 2010 (%)', kind: 'hist', projCode: '4.A.1' },
      { code: '2.A.2', label: 'Struktur PDRB ADHK 2010 (%)', kind: 'hist', projCode: '4.A.2' },
    ]
  },
  // ─── PUK + TPAK + AK per karakteristik ───
  {
    id: 'puk-umur', section: 'B. Persediaan Tenaga Kerja — Golongan Umur',
    title: 'PUK, TPAK dan Angkatan Kerja Menurut Golongan Umur',
    prov: false,
    layers: [
      { code: '2.C.1', label: 'Penduduk Usia Kerja (ribu)', kind: 'hist', projCode: '3.A.1' },
      { code: '2.D.1', label: 'TPAK (%)', kind: 'hist', projCode: '3.B.1' },
      { code: '2.E.1', label: 'Angkatan Kerja (ribu)', kind: 'hist', projCode: '3.C.1' },
    ]
  },
  {
    id: 'puk-didik', section: 'B. Persediaan Tenaga Kerja — Tingkat Pendidikan',
    title: 'PUK, TPAK dan Angkatan Kerja Menurut Tingkat Pendidikan',
    prov: false,
    layers: [
      { code: '2.C.2', label: 'Penduduk Usia Kerja (ribu)', kind: 'hist', projCode: '3.A.2' },
      { code: '2.D.2', label: 'TPAK (%)', kind: 'hist', projCode: '3.B.2' },
      { code: '2.E.2', label: 'Angkatan Kerja (ribu)', kind: 'hist', projCode: '3.C.2' },
    ]
  },
  {
    id: 'puk-jk', section: 'B. Persediaan Tenaga Kerja — Jenis Kelamin',
    title: 'PUK, TPAK dan Angkatan Kerja Menurut Jenis Kelamin',
    prov: false,
    layers: [
      { code: '2.C.3', label: 'Penduduk Usia Kerja (ribu)', kind: 'hist', projCode: '3.A.3' },
      { code: '2.D.3', label: 'TPAK (%)', kind: 'hist', projCode: '3.B.3' },
      { code: '2.E.3', label: 'Angkatan Kerja (ribu)', kind: 'hist', projCode: '3.C.3' },
    ]
  },
  {
    id: 'puk-kab', section: 'B. Persediaan Tenaga Kerja — Kabupaten/Kota',
    title: 'PUK, TPAK dan Angkatan Kerja Menurut Kabupaten/Kota',
    prov: true,
    layers: [
      { code: '2.C.4', label: 'Penduduk Usia Kerja (ribu)', kind: 'hist', projCode: '3.A.4' },
      { code: '2.D.4', label: 'TPAK (%)', kind: 'hist', projCode: '3.B.4' },
      { code: '2.E.4', label: 'Angkatan Kerja (ribu)', kind: 'hist', projCode: '3.C.4' },
    ]
  },
  // ─── PYB + Kesempatan Kerja per karakteristik ───
  {
    id: 'pyb-lu', section: 'C. Penduduk Bekerja & Kesempatan Kerja — Lapangan Usaha',
    title: 'PYB dan Kesempatan Kerja Menurut Lapangan Usaha',
    prov: false,
    layers: [
      { code: '2.F.1', label: 'PYB / Orang Bekerja (ribu)', kind: 'hist', projCode: '4.C.1' },
    ]
  },
  {
    id: 'pyb-umur', section: 'C. Penduduk Bekerja & Kesempatan Kerja — Golongan Umur',
    title: 'PYB dan Kesempatan Kerja Menurut Golongan Umur',
    prov: false,
    layers: [
      { code: '2.F.2', label: 'PYB / Orang Bekerja (ribu)', kind: 'hist', projCode: '4.C.2' },
    ]
  },
  {
    id: 'pyb-didik', section: 'C. Penduduk Bekerja & Kesempatan Kerja — Tingkat Pendidikan',
    title: 'PYB dan Kesempatan Kerja Menurut Tingkat Pendidikan',
    prov: false,
    layers: [
      { code: '2.F.3', label: 'PYB / Orang Bekerja (ribu)', kind: 'hist', projCode: '4.C.3' },
    ]
  },
  {
    id: 'pyb-jk', section: 'C. Penduduk Bekerja & Kesempatan Kerja — Jenis Kelamin',
    title: 'PYB dan Kesempatan Kerja Menurut Jenis Kelamin',
    prov: false,
    layers: [
      { code: '2.F.4', label: 'PYB / Orang Bekerja (ribu)', kind: 'hist', projCode: '4.C.4' },
    ]
  },
  {
    id: 'pyb-stat', section: 'C. Penduduk Bekerja & Kesempatan Kerja — Status Pekerjaan',
    title: 'PYB dan Kesempatan Kerja Menurut Status Pekerjaan',
    prov: false,
    layers: [
      { code: '2.F.5', label: 'PYB / Orang Bekerja (ribu)', kind: 'hist', projCode: '4.C.5' },
    ]
  },
  {
    id: 'pyb-jab', section: 'C. Penduduk Bekerja & Kesempatan Kerja — Jabatan',
    title: 'PYB dan Kesempatan Kerja Menurut Jabatan',
    prov: false,
    layers: [
      { code: '2.F.6', label: 'PYB / Orang Bekerja (ribu)', kind: 'hist', projCode: '4.C.6' },
    ]
  },
  {
    id: 'pyb-jam', section: 'C. Penduduk Bekerja & Kesempatan Kerja — Jam Kerja',
    title: 'PYB dan Kesempatan Kerja Menurut Jam Kerja',
    prov: false,
    layers: [
      { code: '2.F.7', label: 'PYB / Orang Bekerja (ribu)', kind: 'hist', projCode: '4.C.7' },
    ]
  },
  {
    id: 'pyb-kab', section: 'C. Penduduk Bekerja & Kesempatan Kerja — Kabupaten/Kota',
    title: 'PYB dan Kesempatan Kerja Menurut Kabupaten/Kota',
    prov: true,
    layers: [
      { code: '2.F.8', label: 'PYB / Orang Bekerja (ribu)', kind: 'hist', projCode: '4.C.8' },
    ]
  },
  // ─── Penganggur + TPT per karakteristik ───
  {
    id: 'pelatihan-tk', section: 'D. Tambahan Kesempatan Kerja',
    title: 'Tambahan Kesempatan Kerja Menurut Status & Pendidikan',
    prov: false,
    layers: [{ code: '4.E.1', label: '', kind: 'proj', projCode: null }]
  },
  {
    id: 'penempatan-tk', section: 'D. Tambahan Kesempatan Kerja',
    title: 'Tambahan Kesempatan Kerja Menurut Status & Lapangan Usaha',
    prov: false,
    layers: [{ code: '4.F.1', label: '', kind: 'proj', projCode: null }]
  },
  {
    id: 'tpt-umur', section: 'E. Pengangguran & TPT — Golongan Umur',
    title: 'Penganggur Terbuka dan TPT Menurut Golongan Umur',
    prov: false,
    layers: [
      { code: '2.G.1', label: 'Penganggur Terbuka (ribu)', kind: 'hist', projCode: '5.A.1' },
      { code: '2.G.2', label: 'TPT (%)', kind: 'hist', projCode: '5.A.2' },
    ]
  },
  {
    id: 'tpt-didik', section: 'E. Pengangguran & TPT — Tingkat Pendidikan',
    title: 'Penganggur Terbuka dan TPT Menurut Tingkat Pendidikan',
    prov: false,
    layers: [
      { code: '2.G.3', label: 'Penganggur Terbuka (ribu)', kind: 'hist', projCode: '5.B.1' },
      { code: '2.G.4', label: 'TPT (%)', kind: 'hist', projCode: '5.B.2' },
    ]
  },
  {
    id: 'tpt-jk', section: 'E. Pengangguran & TPT — Jenis Kelamin',
    title: 'Penganggur Terbuka dan TPT Menurut Jenis Kelamin',
    prov: false,
    layers: [
      { code: '2.G.5', label: 'Penganggur Terbuka (ribu)', kind: 'hist', projCode: '5.C.1' },
      { code: '2.G.6', label: 'TPT (%)', kind: 'hist', projCode: '5.C.2' },
    ]
  },
  {
    id: 'tpt-kab', section: 'E. Pengangguran & TPT — Kabupaten/Kota',
    title: 'Penganggur Terbuka dan TPT Menurut Kabupaten/Kota',
    prov: true,
    layers: [
      { code: '2.G.7', label: 'Penganggur Terbuka (ribu)', kind: 'hist', projCode: '5.D.1' },
      { code: '2.G.8', label: 'TPT (%)', kind: 'hist', projCode: '5.D.2' },
    ]
  },
  // ─── Produktivitas ───
  {
    id: 'prod-lu', section: 'F. Produktivitas Tenaga Kerja',
    title: 'Produktivitas Tenaga Kerja Menurut Lapangan Usaha',
    prov: false,
    layers: [
      { code: '2.H.1', label: 'Produktivitas Historis', kind: 'hist', projCode: '4.D.1' },
    ]
  },
  // ─── Tabel mandiri (tidak ada pasangan historis/proyeksi yang digabung) ───
  {
    id: 'potensi-sek', section: 'G. Potensi Daerah',
    title: 'Jumlah Sekolah Negeri dan Swasta',
    prov: false,
    layers: [{ code: '2.B.1', label: '', kind: 'hist', projCode: null }]
  },
  {
    id: 'invest-real', section: 'G. Potensi Daerah',
    title: 'Realisasi Investasi Menurut Sektor Usaha',
    prov: false,
    layers: [{ code: '2.B.2', label: '', kind: 'hist', projCode: null }]
  },
  {
    id: 'invest-ren', section: 'G. Potensi Daerah',
    title: 'Rencana Investasi Menurut Sektor Usaha',
    prov: false,
    layers: [{ code: '4.B.1', label: '', kind: 'proj', projCode: null }]
  },
  {
    id: 'pelatihan', section: 'H. Pelatihan Tenaga Kerja',
    title: 'Kapasitas, Instruktur dan Lulusan Pelatihan',
    prov: false,
    layers: [
      { code: '2.I.1', label: 'Data Historis', kind: 'hist', projCode: '4.E.2' },
    ]
  },
  {
    id: 'penempatan', section: 'I. Penempatan Tenaga Kerja',
    title: 'Pencari Kerja, Bursa Kerja dan Pengantar Kerja',
    prov: false,
    layers: [
      { code: '2.J.1', label: 'Data Historis', kind: 'hist', projCode: '4.F.2' },
    ]
  },
  {
    id: 'hubind', section: 'J. Hubungan Industrial & Jaminan Sosial',
    title: 'Perangkat Hubungan Industrial',
    prov: false,
    layers: [
      { code: '2.K.1', label: 'Data Historis', kind: 'hist', projCode: '4.G.1' },
    ]
  },
  {
    id: 'upah', section: 'J. Hubungan Industrial & Jaminan Sosial',
    title: 'Perkembangan Upah Minimum',
    prov: false,
    layers: [
      { code: '2.K.2', label: 'Data Historis', kind: 'hist', projCode: '4.G.2' },
    ]
  },
  {
    id: 'pengawasan', section: 'K. Pengawasan Ketenagakerjaan',
    title: 'Jumlah Perusahaan dan Pegawai Pengawas',
    prov: false,
    layers: [
      { code: '2.L.1', label: 'Data Historis', kind: 'hist', projCode: '4.H.1' },
    ]
  },
];

// ══════════════════════════════════════════════════════════════
// TOC DOKUMEN (untuk Word export — per kode tabel sesuai
// Kepmen 309/2013)
// ══════════════════════════════════════════════════════════════
const TOC_WORD = [
  {
    bab: 'BAB II', title: 'Kondisi Ketenagakerjaan', sections: [
      { code: 'A', title: 'Kondisi Ekonomi', tables: [{ no: '2.A.1', name: 'Laju Pertumbuhan PDRB ADHK 2010 Menurut Lapangan Usaha' }, { no: '2.A.2', name: 'Struktur PDRB ADHK 2010 Menurut Lapangan Usaha' }] },
      { code: 'B', title: 'Potensi Daerah', tables: [{ no: '2.B.1', name: 'Jumlah Sekolah Negeri dan Swasta Sederajat' }, { no: '2.B.2', name: 'Realisasi Investasi Menurut Sektor Usaha, Nilai Investasi, Jumlah Proyek dan Kebutuhan Tenaga Kerja' }] },
      { code: 'C', title: 'Penduduk Usia Kerja', tables: [{ no: '2.C.1', name: 'Penduduk Usia Kerja Menurut Golongan Umur' }, { no: '2.C.2', name: 'PUK Menurut Tingkat Pendidikan' }, { no: '2.C.3', name: 'PUK Menurut Jenis Kelamin' }, { no: '2.C.4', name: 'PUK Menurut Kabupaten/Kota', prov: true }] },
      { code: 'D', title: 'TPAK', tables: [{ no: '2.D.1', name: 'TPAK Menurut Golongan Umur' }, { no: '2.D.2', name: 'TPAK Menurut Tingkat Pendidikan' }, { no: '2.D.3', name: 'TPAK Menurut Jenis Kelamin' }, { no: '2.D.4', name: 'TPAK Menurut Kabupaten/Kota', prov: true }] },
      { code: 'E', title: 'Angkatan Kerja', tables: [{ no: '2.E.1', name: 'AK Menurut Golongan Umur' }, { no: '2.E.2', name: 'AK Menurut Tingkat Pendidikan' }, { no: '2.E.3', name: 'AK Menurut Jenis Kelamin' }, { no: '2.E.4', name: 'AK Menurut Kabupaten/Kota', prov: true }] },
      { code: 'F', title: 'Penduduk yang Bekerja', tables: [{ no: '2.F.1', name: 'PYB Menurut Lapangan Usaha' }, { no: '2.F.2', name: 'PYB Menurut Golongan Umur' }, { no: '2.F.3', name: 'PYB Menurut Tingkat Pendidikan' }, { no: '2.F.4', name: 'PYB Menurut Jenis Kelamin' }, { no: '2.F.5', name: 'PYB Menurut Status Pekerjaan Utama' }, { no: '2.F.6', name: 'PYB Menurut Jabatan' }, { no: '2.F.7', name: 'PYB Menurut Jam Kerja' }, { no: '2.F.8', name: 'PYB Menurut Kabupaten/Kota', prov: true }] },
      { code: 'G', title: 'Penganggur dan TPT', tables: [{ no: '2.G.1', name: 'Penganggur Terbuka Menurut Golongan Umur' }, { no: '2.G.2', name: 'TPT Menurut Golongan Umur' }, { no: '2.G.3', name: 'Penganggur Menurut Tingkat Pendidikan' }, { no: '2.G.4', name: 'TPT Menurut Tingkat Pendidikan' }, { no: '2.G.5', name: 'Penganggur Menurut Jenis Kelamin' }, { no: '2.G.6', name: 'TPT Menurut Jenis Kelamin' }, { no: '2.G.7', name: 'Penganggur Menurut Kabupaten/Kota', prov: true }, { no: '2.G.8', name: 'TPT Menurut Kabupaten/Kota', prov: true }] },
      { code: 'H', title: 'Produktivitas', tables: [{ no: '2.H.1', name: 'Produktivitas Tenaga Kerja Menurut Lapangan Usaha' }] },
      { code: 'I', title: 'Pelatihan', tables: [{ no: '2.I.1', name: 'Kapasitas Terpasang, Instruktur dan Lulusan Pelatihan' }] },
      { code: 'J', title: 'Penempatan', tables: [{ no: '2.J.1', name: 'Pencari Kerja, Bursa Kerja dan Pengantar Kerja' }] },
      { code: 'K', title: 'Hubungan Industrial & Jamsos', tables: [{ no: '2.K.1', name: 'Perangkat Hubungan Industrial dan Jaminan Sosial' }, { no: '2.K.2', name: 'Perkembangan Upah Minimum' }] },
      { code: 'L', title: 'Pengawasan', tables: [{ no: '2.L.1', name: 'Jumlah Perusahaan dan Pegawai Pengawas Ketenagakerjaan' }] },
    ]
  },
  {
    bab: 'BAB III', title: 'Perkiraan Persediaan Tenaga Kerja', sections: [
      { code: 'A', title: 'Perkiraan PUK', tables: [{ no: '3.A.1', name: 'Perkiraan PUK Menurut Golongan Umur' }, { no: '3.A.2', name: 'Perkiraan PUK Menurut Tingkat Pendidikan' }, { no: '3.A.3', name: 'Perkiraan PUK Menurut Jenis Kelamin' }, { no: '3.A.4', name: 'Perkiraan PUK Menurut Kabupaten/Kota', prov: true }] },
      { code: 'B', title: 'Perkiraan TPAK', tables: [{ no: '3.B.1', name: 'Perkiraan TPAK Menurut Golongan Umur' }, { no: '3.B.2', name: 'Perkiraan TPAK Menurut Tingkat Pendidikan' }, { no: '3.B.3', name: 'Perkiraan TPAK Menurut Jenis Kelamin' }, { no: '3.B.4', name: 'Perkiraan TPAK Menurut Kabupaten/Kota', prov: true }] },
      { code: 'C', title: 'Perkiraan Angkatan Kerja', tables: [{ no: '3.C.1', name: 'Perkiraan AK Menurut Golongan Umur' }, { no: '3.C.2', name: 'Perkiraan AK Menurut Tingkat Pendidikan' }, { no: '3.C.3', name: 'Perkiraan AK Menurut Jenis Kelamin' }, { no: '3.C.4', name: 'Perkiraan AK Menurut Kabupaten/Kota', prov: true }] },
    ]
  },
  {
    bab: 'BAB IV', title: 'Perkiraan Kebutuhan Akan Tenaga Kerja', sections: [
      { code: 'A', title: 'Perkiraan Perekonomian', tables: [{ no: '4.A.1', name: 'Perkiraan Laju Pertumbuhan PDRB' }, { no: '4.A.2', name: 'Perkiraan Struktur PDRB' }] },
      { code: 'B', title: 'Rencana Pengembangan Potensi Daerah', tables: [{ no: '4.B.1', name: 'Rencana Investasi Menurut Sektor Usaha' }] },
      { code: 'C', title: 'Perkiraan Kesempatan Kerja', tables: [{ no: '4.C.1', name: 'KK Menurut Lapangan Usaha' }, { no: '4.C.2', name: 'KK Menurut Golongan Umur' }, { no: '4.C.3', name: 'KK Menurut Tingkat Pendidikan' }, { no: '4.C.4', name: 'KK Menurut Jenis Kelamin' }, { no: '4.C.5', name: 'KK Menurut Status Pekerjaan' }, { no: '4.C.6', name: 'KK Menurut Jabatan' }, { no: '4.C.7', name: 'KK Menurut Jam Kerja' }, { no: '4.C.8', name: 'KK Menurut Kabupaten/Kota', prov: true }] },
      { code: 'D', title: 'Perkiraan Produktivitas', tables: [{ no: '4.D.1', name: 'Produktivitas TK Bruto Menurut Lapangan Usaha' }] },
      { code: 'E', title: 'Perkiraan Pelatihan', tables: [{ no: '4.E.1', name: 'Tambahan KK Menurut Status & Pendidikan' }, { no: '4.E.2', name: 'Target Kapasitas, Instruktur dan Lulusan Pelatihan' }] },
      { code: 'F', title: 'Perkiraan Penempatan', tables: [{ no: '4.F.1', name: 'Tambahan KK Menurut Status & Lapangan Usaha' }, { no: '4.F.2', name: 'Target Lowongan, Pencari Kerja, Penempatan' }] },
      { code: 'G', title: 'Perkiraan Hubungan Industrial & Jamsos', tables: [{ no: '4.G.1', name: 'Target Mediator, PP, PKB, SP/SB, LKS Bipartit' }, { no: '4.G.2', name: 'Target Perkembangan Upah Minimum' }] },
      { code: 'H', title: 'Perkiraan Pengawasan', tables: [{ no: '4.H.1', name: 'Jumlah Perusahaan dan Pegawai Pengawas' }] },
    ]
  },
  {
    bab: 'BAB V', title: 'Perkiraan Keseimbangan Persediaan dan Kebutuhan Tenaga Kerja', sections: [
      { code: 'A', title: 'Golongan Umur', tables: [{ no: '5.A.1', name: 'Perkiraan Penganggur Terbuka Menurut Golongan Umur' }, { no: '5.A.2', name: 'Perkiraan TPT Menurut Golongan Umur' }] },
      { code: 'B', title: 'Tingkat Pendidikan', tables: [{ no: '5.B.1', name: 'Perkiraan Penganggur Menurut Tingkat Pendidikan' }, { no: '5.B.2', name: 'Perkiraan TPT Menurut Tingkat Pendidikan' }] },
      { code: 'C', title: 'Jenis Kelamin', tables: [{ no: '5.C.1', name: 'Perkiraan Penganggur Menurut Jenis Kelamin' }, { no: '5.C.2', name: 'Perkiraan TPT Menurut Jenis Kelamin' }] },
      { code: 'D', title: 'Kabupaten/Kota', tables: [{ no: '5.D.1', name: 'Perkiraan Penganggur Menurut Kabupaten/Kota', prov: true }, { no: '5.D.2', name: 'Perkiraan TPT Menurut Kabupaten/Kota', prov: true }] },
    ]
  },
];

// ══════════════════════════════════════════════════════════════
// LOCAL STORAGE — simpan & muat state agar tidak hilang saat refresh
// ══════════════════════════════════════════════════════════════
const LS_KEY = 'rtk_state';

function saveStateToLS() {
  try {
    // editedCells disimpan agar highlight tetap muncul setelah refresh
    const state = JSON.stringify({ rawSheets, rawSheetsOriginal, P, histYrs, projYrs, editedCells, rawTargetSheet });
    localStorage.setItem(LS_KEY, state);
  } catch (e) { console.warn('Gagal menyimpan ke localStorage:', e); }
}

function loadStateFromLS() {
  try {
    const json = localStorage.getItem(LS_KEY);
    if (!json) return false;
    const s = JSON.parse(json);
    if (s.rawSheets) rawSheets = s.rawSheets;
    if (s.rawSheetsOriginal) rawSheetsOriginal = s.rawSheetsOriginal;
    else if (s.rawSheets) rawSheetsOriginal = deepCopy(s.rawSheets); // fallback migrasi
    if (s.P) {
      P = s.P;
      if (!P.targets)       P.targets = {};       // migrasi state lama
      if (!P.targetImports) P.targetImports = {}; // migrasi: ditambah saat fitur import Target
      if (!P.lajuOverrides) P.lajuOverrides = {}; // migrasi: jaga-jaga state lama tanpa field ini
      if (!P.lajuComments)  P.lajuComments  = {}; // migrasi: ditambah saat fitur kolom Catatan
    }
    if (s.histYrs) histYrs = s.histYrs;
    if (s.projYrs) projYrs = s.projYrs;
    // Pulihkan marker highlight sel yang sudah diedit
    if (s.editedCells && typeof s.editedCells === 'object') editedCells = s.editedCells;
    // Pulihkan data sheet Target (jika ada)
    if (s.rawTargetSheet) rawTargetSheet = s.rawTargetSheet;
    return Object.keys(rawSheets).length > 0;
  } catch (e) { console.warn('Gagal memuat dari localStorage:', e); return false; }
}

function clearStateLS() {
  localStorage.removeItem(LS_KEY);
}
