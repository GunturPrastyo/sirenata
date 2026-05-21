const INDIKATOR_DEFS = [
  {
    id: 'puk', label: 'PUK',
    title: 'Proyeksi Penduduk Usia Kerja (PUK)',
    chars: [
      { id: 'umur', label: 'GOLONGAN UMUR', histCode: '2.C.1', projCode: '3.A.1' },
      { id: 'didik', label: 'PENDIDIKAN', histCode: '2.C.2', projCode: '3.A.2' },
      { id: 'jk', label: 'JENIS KELAMIN', histCode: '2.C.3', projCode: '3.A.3' },
      { id: 'kab', label: 'KABUPATEN/KOTA', histCode: '2.C.4', projCode: '3.A.4', provOnly: true },
    ]
  },
  {
    id: 'tpak', label: 'TPAK',
    title: 'Proyeksi Tingkat Partisipasi Angkatan Kerja (TPAK)',
    chars: [
      { id: 'umur', label: 'GOLONGAN UMUR', histCode: '2.D.1', projCode: '3.B.1' },
      { id: 'didik', label: 'PENDIDIKAN', histCode: '2.D.2', projCode: '3.B.2' },
      { id: 'jk', label: 'JENIS KELAMIN', histCode: '2.D.3', projCode: '3.B.3' },
      { id: 'kab', label: 'KABUPATEN/KOTA', histCode: '2.D.4', projCode: '3.B.4', provOnly: true },
    ]
  },
  {
    id: 'ak', label: 'AK',
    title: 'Proyeksi Angkatan Kerja (AK)',
    chars: [
      {
        id: 'umur', label: 'GOLONGAN UMUR', histCode: '2.E.1', projCode: '3.C.1',
        srcPukHist: '2.C.1', srcTpakHist: '2.D.1', srcPukProj: '3.A.1', srcTpakProj: '3.B.1'
      },
      {
        id: 'didik', label: 'PENDIDIKAN', histCode: '2.E.2', projCode: '3.C.2',
        srcPukHist: '2.C.2', srcTpakHist: '2.D.2', srcPukProj: '3.A.2', srcTpakProj: '3.B.2'
      },
      {
        id: 'jk', label: 'JENIS KELAMIN', histCode: '2.E.3', projCode: '3.C.3',
        srcPukHist: '2.C.3', srcTpakHist: '2.D.3', srcPukProj: '3.A.3', srcTpakProj: '3.B.3'
      },
      {
        id: 'kab', label: 'KABUPATEN/KOTA', histCode: '2.E.4', projCode: '3.C.4', provOnly: true,
        srcPukHist: '2.C.4', srcTpakHist: '2.D.4', srcPukProj: '3.A.4', srcTpakProj: '3.B.4'
      },
    ]
  },
  {
    id: 'pdrb', label: 'PDRB',
    title: 'Proyeksi PDRB',
    // PDRB tidak pakai sub-tab karakteristik, langsung tampil blok-blok
    chars: [
      {
        id: 'lu', label: 'LAPANGAN USAHA', histCode: '2.A.1', projCode: '4.A.1',
        extraHistCode: '2.A.2', extraProjCode: '4.A.2'
      },
    ],
    pdrbMode: true  // render khusus seperti PDF referensi
  },
  {
    id: 'elastisitas', label: 'Elastisitas',
    title: 'Perubahan Elastisitas',
    chars: [
      { id: 'lu', label: 'LAPANGAN USAHA', histCode: '2.A.1', projCode: '4.A.1' },
    ],
    elastisitasMode: true
  },
  {
    id: 'kk', label: 'KK',
    title: 'Kesempatan Kerja (KK)',
    chars: [
      { id: 'lu',   label: 'LAPANGAN USAHA',         histCode: '2.F.1', projCode: '4.C.1', srcPybCode: '2.F.1' },
      { id: 'didik',label: 'PENDIDIKAN',              histCode: '2.F.3', projCode: '4.C.3', srcPybCode: '2.F.3' },
      { id: 'jk',   label: 'JENIS KELAMIN',           histCode: '2.F.4', projCode: '4.C.4', srcPybCode: '2.F.4' },
      { id: 'umur', label: 'GOLONGAN UMUR',           histCode: '2.F.2', projCode: '4.C.2', srcPybCode: '2.F.2' },
      { id: 'kab',  label: 'KABUPATEN/KOTA',          histCode: '2.F.8', projCode: '4.C.8', provOnly: true, srcPybCode: '2.F.8' },
      { id: 'stat', label: 'STATUS PEKERJAAN',        histCode: '2.F.5', projCode: '4.C.5', srcPybCode: '2.F.5' },
      { id: 'jab',  label: 'JENIS PEKERJAAN/JABATAN', histCode: '2.F.6', projCode: '4.C.6', srcPybCode: '2.F.6' },
      { id: 'jam',  label: 'JAM KERJA',               histCode: '2.F.7', projCode: '4.C.7', srcPybCode: '2.F.7' },
    ]
  },
  {
    id: 'pt', label: 'PT',
    title: 'Produktivitas Tenaga Kerja (PT)',
    chars: [
      { id: 'lu', label: 'LAPANGAN USAHA', histCode: '2.H.1', projCode: '4.D.1' },
    ]
  },
  {
    id: 'tpt', label: 'TPT',
    title: 'Penganggur Terbuka & Tingkat Pengangguran Terbuka (TPT)',
    chars: [
      { id: 'umur', label: 'GOLONGAN UMUR', histCode: '2.G.1', projCode: '5.A.1', tptHistCode: '2.G.2', tptProjCode: '5.A.2' },
      { id: 'didik', label: 'PENDIDIKAN', histCode: '2.G.3', projCode: '5.B.1', tptHistCode: '2.G.4', tptProjCode: '5.B.2' },
      { id: 'jk', label: 'JENIS KELAMIN', histCode: '2.G.5', projCode: '5.C.1', tptHistCode: '2.G.6', tptProjCode: '5.C.2' },
      { id: 'kab', label: 'KABUPATEN/KOTA', histCode: '2.G.7', projCode: '5.D.1', tptHistCode: '2.G.8', tptProjCode: '5.D.2', provOnly: true },
    ]
  },
  {
    id: 'pelengkap', label: 'Data Dukung',
    title: 'Data Dukung (Input Langsung)',
    pelengkapMode: true,
    chars: [
      { id: 'b1',  label: 'Jumlah Sekolah',              histCode: '2.B.1', projCode: null },
      { id: 'b2',  label: 'Investasi',          histCode: '2.B.2', projCode: '4.B.1' },
      { id: 'i1',  label: 'Kapasitas Terpasang Pelatihan',histCode: '2.I.1', projCode: '4.E.2' },
      { id: 'j1',  label: 'Pencari Kerja & Bursa Kerja',  histCode: '2.J.1', projCode: '4.F.2' },
      { id: 'k1',  label: 'Perangkat HI & Jamsostek',     histCode: '2.K.1', projCode: '4.G.1' },
      { id: 'k2',  label: 'Perkembangan Upah Minimum',    histCode: '2.K.2', projCode: '4.G.2' },
      { id: 'l1',  label: 'Perusahaan & Pengawas',        histCode: '2.L.1', projCode: '4.H.1' },
      { id: 'e1',  label: 'Tambahan KK Status & Pendidikan', histCode: null, projCode: '4.E.1' },
      { id: 'f1p', label: 'Tambahan KK Lapangan Usaha',   histCode: null,    projCode: '4.F.1' },
    ]
  },
];

// ══════════════════════════════════════════════════════════════
// KOMPUTASI — tidak berubah dari versi sebelumnya
// ══════════════════════════════════════════════════════════════
function normL(s) { return String(s || '').toLowerCase().replace(/[^a-z0-9]/g, ' ').replace(/\s+/g, ' ').trim(); }
function hY() { return histYrs.filter(y => y >= P.hA && y <= P.hZ); }
const BAB_TREE = [
  {
    bab: 'BAB II — Data Historis',
    items: [
      { code: '2.A.1', name: 'Laju Pertumbuhan PDRB ADHK 2010 Menurut Lapangan Usaha', indId: 'pdrb', charId: 'lu' },
      { code: '2.A.2', name: 'Struktur PDRB ADHK 2010 Menurut Lapangan Usaha', indId: 'pdrb', charId: 'lu' },
      { code: '2.B.1', name: 'Jumlah Sekolah Negeri dan Swasta Sederajat', indId: 'pelengkap', charId: 'b1' },
      { code: '2.B.2', name: 'Realisasi Investasi Menurut Sektor Usaha, Nilai Investasi, Jumlah Proyek dan Kebutuhan Tenaga Kerja', indId: 'pelengkap', charId: 'b2' },
      { code: '2.C.1', name: 'Penduduk Usia Kerja (PUK) Menurut Golongan Umur', indId: 'puk', charId: 'umur' },
      { code: '2.C.2', name: 'Penduduk Usia Kerja (PUK) Menurut Tingkat Pendidikan', indId: 'puk', charId: 'didik' },
      { code: '2.C.3', name: 'Penduduk Usia Kerja (PUK) Menurut Jenis Kelamin', indId: 'puk', charId: 'jk' },
      { code: '2.C.4', name: 'Penduduk Usia Kerja (PUK) Menurut Kabupaten/Kota ★', indId: 'puk', charId: 'kab' },
      { code: '2.D.1', name: 'Tingkat Partisipasi Angkatan Kerja (TPAK) Menurut Golongan Umur', indId: 'tpak', charId: 'umur' },
      { code: '2.D.2', name: 'Tingkat Partisipasi Angkatan Kerja (TPAK) Menurut Tingkat Pendidikan', indId: 'tpak', charId: 'didik' },
      { code: '2.D.3', name: 'Tingkat Partisipasi Angkatan Kerja (TPAK) Menurut Jenis Kelamin', indId: 'tpak', charId: 'jk' },
      { code: '2.D.4', name: 'Tingkat Partisipasi Angkatan Kerja (TPAK) Menurut Kabupaten/Kota ★', indId: 'tpak', charId: 'kab' },
      { code: '2.E.1', name: 'Angkatan Kerja (AK) Menurut Golongan Umur', indId: 'ak', charId: 'umur' },
      { code: '2.E.2', name: 'Angkatan Kerja (AK) Menurut Tingkat Pendidikan', indId: 'ak', charId: 'didik' },
      { code: '2.E.3', name: 'Angkatan Kerja (AK) Menurut Jenis Kelamin', indId: 'ak', charId: 'jk' },
      { code: '2.E.4', name: 'Angkatan Kerja (AK) Menurut Kabupaten/Kota ★', indId: 'ak', charId: 'kab' },
      { code: '2.F.1', name: 'Penduduk yang Bekerja (PYB) Menurut Lapangan Usaha', indId: 'kk', charId: 'lu' },
      { code: '2.F.2', name: 'Penduduk yang Bekerja (PYB) Menurut Golongan Umur', indId: 'kk', charId: 'umur' },
      { code: '2.F.3', name: 'Penduduk yang Bekerja (PYB) Menurut Tingkat Pendidikan', indId: 'kk', charId: 'didik' },
      { code: '2.F.4', name: 'PYB Menurut Jenis Kelamin', indId: 'kk', charId: 'jk' },
      { code: '2.F.5', name: 'PYB Menurut Status Pekerjaan Utama', indId: 'kk', charId: 'stat' },
      { code: '2.F.6', name: 'PYB Menurut Jabatan', indId: 'kk', charId: 'jab' },
      { code: '2.F.7', name: 'PYB Menurut Jam Kerja', indId: 'kk', charId: 'jam' },
      { code: '2.F.8', name: 'PYB Menurut Kabupaten/Kota ★', indId: 'kk', charId: 'kab' },
      { code: '2.G.1', name: 'Penganggur Terbuka Menurut Golongan Umur', indId: 'tpt', charId: 'umur' },
      { code: '2.G.2', name: 'Tingkat Penganggur Terbuka Menurut Golongan Umur', indId: 'tpt', charId: 'umur' },
      { code: '2.G.3', name: 'Penganggur Terbuka Menurut Tingkat Pendidikan', indId: 'tpt', charId: 'didik' },
      { code: '2.G.4', name: 'Tingkat Penganggur Terbuka Menurut Tingkat Pendidikan', indId: 'tpt', charId: 'didik' },
      { code: '2.G.5', name: 'Penganggur Terbuka Menurut Jenis Kelamin', indId: 'tpt', charId: 'jk' },
      { code: '2.G.6', name: 'Tingkat Penganggur Terbuka Menurut Jenis Kelamin', indId: 'tpt', charId: 'jk' },
      { code: '2.G.7', name: 'Penganggur Terbuka Menurut Kabupaten/Kota ★', indId: 'tpt', charId: 'kab' },
      { code: '2.G.8', name: 'Tingkat Penganggur Terbuka Menurut Kabupaten/Kota ★', indId: 'tpt', charId: 'kab' },
      { code: '2.H.1', name: 'Produktivitas Tenaga Kerja Menurut Lapangan Usaha', indId: 'pt', charId: 'lu' },
      { code: '2.I.1', name: 'Kapasitas Terpasang, Jumlah Instruktur dan Lulusan Pelatihan', indId: 'pelengkap', charId: 'i1' },
      { code: '2.J.1', name: 'Jumlah Pencari Kerja, Bursa Kerja dan Pengantar Kerja', indId: 'pelengkap', charId: 'j1' },
      { code: '2.K.1', name: 'Perangkat Hubungan Industrial dan Jaminan Sosial Tenaga Kerja', indId: 'pelengkap', charId: 'k1' },
      { code: '2.K.2', name: 'Perkembangan Upah Minimum', indId: 'pelengkap', charId: 'k2' },
      { code: '2.L.1', name: 'Jumlah Perusahaan dan Pegawai Pengawas Ketenagakerjaan', indId: 'pelengkap', charId: 'l1' },
    ]
  },
  {
    bab: 'BAB III — Proyeksi Persediaan TK',
    items: [
      { code: '3.A.1', name: 'Perkiraan Penduduk Usia Kerja (PUK) Menurut Golongan Umur', indId: 'puk', charId: 'umur' },
      { code: '3.A.2', name: 'Perkiraan Penduduk Usia Kerja (PUK) Menurut Tingkat Pendidikan', indId: 'puk', charId: 'didik' },
      { code: '3.A.3', name: 'Perkiraan Penduduk Usia Kerja (PUK) Menurut Jenis Kelamin', indId: 'puk', charId: 'jk' },
      { code: '3.A.4', name: 'Perkiraan Penduduk Usia Kerja (PUK) Menurut Kabupaten/Kota ★', indId: 'puk', charId: 'kab' },
      { code: '3.B.1', name: 'Perkiraan Tingkat Partisipasi Angkatan Kerja (TPAK) Menurut Golongan Umur', indId: 'tpak', charId: 'umur' },
      { code: '3.B.2', name: 'Perkiraan Tingkat Partisipasi Angkatan Kerja (TPAK) Menurut Tingkat Pendidikan', indId: 'tpak', charId: 'didik' },
      { code: '3.B.3', name: 'Perkiraan Tingkat Partisipasi Angkatan Kerja (TPAK) Menurut Jenis Kelamin', indId: 'tpak', charId: 'jk' },
      { code: '3.B.4', name: 'Perkiraan Tingkat Partisipasi Angkatan Kerja (TPAK) Menurut Kabupaten/Kota ★', indId: 'tpak', charId: 'kab' },
      { code: '3.C.1', name: 'Perkiraan Angkatan Kerja (AK) Menurut Golongan Umur', indId: 'ak', charId: 'umur' },
      { code: '3.C.2', name: 'Perkiraan Angkatan Kerja (AK) Menurut Tingkat Pendidikan', indId: 'ak', charId: 'didik' },
      { code: '3.C.3', name: 'Perkiraan Angkatan Kerja (AK) Menurut Jenis Kelamin', indId: 'ak', charId: 'jk' },
      { code: '3.C.4', name: 'Perkiraan Angkatan Kerja (AK) Menurut Kabupaten/Kota ★', indId: 'ak', charId: 'kab' },
    ]
  },
  {
    bab: 'BAB IV — Proyeksi Kebutuhan TK',
    items: [
      { code: '4.A.1', name: 'Perkiraan Laju Pertumbuhan PDRB ADHK 2010 Menurut Lapangan Usaha', indId: 'pdrb', charId: 'lu' },
      { code: '4.A.2', name: 'Perkiraan Struktur PDRB ADHK 2010 Menurut Lapangan Usaha', indId: 'pdrb', charId: 'lu' },
      { code: '4.B.1', name: 'Rencana Investasi Menurut Sektor Usaha, Nilai Investasi, Jumlah Proyek dan Kebutuhan Tenaga Kerja', indId: 'pelengkap', charId: 'b2' },
      { code: '4.C.1', name: 'Kesempatan Kerja Menurut Lapangan Usaha', indId: 'kk', charId: 'lu' },
      { code: '4.C.2', name: 'Kesempatan Kerja Menurut Golongan Umur', indId: 'kk', charId: 'umur' },
      { code: '4.C.3', name: 'Kesempatan Kerja Menurut Tingkat Pendidikan', indId: 'kk', charId: 'didik' },
      { code: '4.C.4', name: 'Kesempatan Kerja Menurut Jenis Kelamin', indId: 'kk', charId: 'jk' },
      { code: '4.C.5', name: 'Kesempatan Kerja Menurut Status Pekerjaan', indId: 'kk', charId: 'stat' },
      { code: '4.C.6', name: 'Kesempatan Kerja Menurut Jabatan', indId: 'kk', charId: 'jab' },
      { code: '4.C.7', name: 'Kesempatan Kerja Menurut Jam Kerja', indId: 'kk', charId: 'jam' },
      { code: '4.C.8', name: 'Kesempatan Kerja Menurut Kabupaten/Kota ★', indId: 'kk', charId: 'kab' },
      { code: '4.D.1', name: 'Produktivitas Tenaga Kerja Bruto Menurut Lapangan Usaha', indId: 'pt', charId: 'lu' },
      { code: '4.E.1', name: 'Tambahan Kesempatan Kerja Menurut Status Pekerjaan dan Tingkat Pendidikan', indId: 'pelengkap', charId: 'e1' },
      { code: '4.E.2', name: 'Target Kapasitas Terpasang, Jumlah Instruktur dan Lulusan Pelatihan', indId: 'pelengkap', charId: 'i1' },
      { code: '4.F.1', name: 'Tambahan Kesempatan Kerja Menurut Status Pekerjaan dan Lapangan Usaha', indId: 'pelengkap', charId: 'f1p' },
      { code: '4.F.2', name: 'Target Lowongan, Pencari Kerja, Penempatan, Bursa Kerja & Pengantar Kerja', indId: 'pelengkap', charId: 'j1' },
      { code: '4.G.1', name: 'Target Mediator, PP, PKB, SP/SB, LKS Bipartit, Perusahaan dan Tenaga Kerja', indId: 'pelengkap', charId: 'k1' },
      { code: '4.G.2', name: 'Target Perkembangan Upah Minimum', indId: 'pelengkap', charId: 'k2' },
      { code: '4.H.1', name: 'Jumlah Perusahaan dan Pegawai Pengawas Ketenagakerjaan', indId: 'pelengkap', charId: 'l1' },
    ]
  },
  {
    bab: 'BAB V — Proyeksi Penganggur & TPT',
    items: [
      { code: '5.A.1', name: 'Perkiraan Penganggur Terbuka Menurut Golongan Umur', indId: 'tpt', charId: 'umur' },
      { code: '5.A.2', name: 'Perkiraan TPT Menurut Golongan Umur', indId: 'tpt', charId: 'umur' },
      { code: '5.B.1', name: 'Perkiraan Penganggur Terbuka Menurut Tingkat Pendidikan', indId: 'tpt', charId: 'didik' },
      { code: '5.B.2', name: 'Perkiraan TPT Menurut Tingkat Pendidikan', indId: 'tpt', charId: 'didik' },
      { code: '5.C.1', name: 'Perkiraan Penganggur Terbuka Menurut Jenis Kelamin', indId: 'tpt', charId: 'jk' },
      { code: '5.C.2', name: 'Perkiraan TPT Menurut Jenis Kelamin', indId: 'tpt', charId: 'jk' },
      { code: '5.D.1', name: 'Perkiraan Penganggur Terbuka Menurut Kabupaten/Kota ★', indId: 'tpt', charId: 'kab' },
      { code: '5.D.2', name: 'Perkiraan TPT Menurut Kabupaten/Kota ★', indId: 'tpt', charId: 'kab' },
    ]
  },
];

// Warna per indikator (dot sidebar tab 1)
const IND_COLORS = {
  puk: '#3b82f6', tpak: '#8b5cf6', ak: '#06b6d4',
  pdrb: '#f59e0b', elastisitas: '#f97316',
  kk: '#10b981', pt: '#6366f1', tpt: '#ef4444'
};
