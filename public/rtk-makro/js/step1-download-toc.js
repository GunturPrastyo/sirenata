const TOC = [];

// =======================
// SAMBUTAN
// =======================
TOC.push({
  bab: "Sambutan",
  title: "",
  hasTable: false,
  sections: []
});


// =======================
// KATA PENGANTAR
// =======================
TOC.push({
  bab: "Kata Pengantar",
  title: "",
  hasTable: false,
  sections: []
});


// =======================
// EXECUTIVE SUMMARY
// =======================
TOC.push({
  bab: "Executive Summary",
  title: "",
  hasTable: false,
  sections: []
});


// =======================
// DAFTAR ISI
// =======================
TOC.push({
  bab: "Daftar Isi",
  title: "",
  hasTable: false,
  sections: []
});


// =======================
// TARGET
// =======================
TOC.push({
  bab: "Target",
  title: "Target",
  hasTable: true,
  sections: [
    {
      code: "Target",
      title: "Target",
      tables: [
        { no: "Target", name: "Target Indikator Proyeksi", sheet: "Target" }
      ]
    }
  ]
});


// =======================
// BAB I
// =======================
TOC.push({
  bab: "BAB I",
  title: "Pendahuluan",
  hasTable: false,
  sections: [
    { code: "1.A", title: "Latar Belakang", tables: [] },
    { code: "1.B", title: "Dasar Hukum", tables: [] },
    { code: "1.C", title: "Tujuan", tables: [] },
    { code: "1.D", title: "Metodologi", tables: [] },
    { code: "1.E", title: "Sumber Data", tables: [] },
    { code: "1.F", title: "Pengertian", tables: [] },
    { code: "1.G", title: "Sistematika", tables: [] }
  ]
});


// =======================
// BAB II
// =======================
TOC.push({
  bab: "BAB II",
  title: "Kondisi Ketenagakerjaan",
  hasTable: true,
  sections: [
    {
      code: "2.A",
      title: "Kondisi Ekonomi",
      tables: [
        { no: "2.A.1", name: "Laju Pertumbuhan PDRB ADHK 2010 Menurut Lapangan Usaha", sheet: "2.A.1" },
        { no: "2.A.2", name: "Struktur PDRB ADHK 2010 Menurut Lapangan Usaha", sheet: "2.A.2" }
      ]
    },
    {
      code: "2.B",
      title: "Potensi Daerah",
      tables: [
        { no: "2.B.1", name: "Jumlah Sekolah Negeri dan Swasta Sederajat", sheet: "2.B.1" },
        { no: "2.B.2", name: "Realisasi Investasi Menurut Sektor Usaha, Nilai Investasi, Jumlah Proyek dan Kebutuhan Tenaga Kerja", sheet: "2.B.2" }
      ]
    },
    {
      code: "2.C",
      title: "Penduduk Usia Kerja",
      tables: [
        { no: "2.C.1", name: "Penduduk Usia Kerja Menurut Golongan Umur", sheet: "2.C.1" },
        { no: "2.C.2", name: "Penduduk Usia Kerja Menurut Tingkat Pendidikan", sheet: "2.C.2" },
        { no: "2.C.3", name: "Penduduk Usia Kerja Menurut Jenis Kelamin", sheet: "2.C.3" },
        { no: "2.C.4", name: "Penduduk Usia Kerja Menurut Kabupaten/Kota", sheet: "2.C.4", note: "Khusus Provinsi" }
      ]
    },
    {
      code: "2.D",
      title: "TPAK",
      tables: [
        { no: "2.D.1", name: "TPAK Menurut Golongan Umur", sheet: "2.D.1" },
        { no: "2.D.2", name: "TPAK Menurut Tingkat Pendidikan", sheet: "2.D.2" },
        { no: "2.D.3", name: "TPAK Menurut Jenis Kelamin", sheet: "2.D.3" },
        { no: "2.D.4", name: "TPAK Menurut Kabupaten/Kota", sheet: "2.D.4", note: "Khusus Provinsi" }
      ]
    },
    {
      code: "2.E",
      title: "Angkatan Kerja",
      tables: [
        { no: "2.E.1", name: "Angkatan Kerja Menurut Golongan Umur", sheet: "2.E.1" },
        { no: "2.E.2", name: "Angkatan Kerja Menurut Tingkat Pendidikan", sheet: "2.E.2" },
        { no: "2.E.3", name: "Angkatan Kerja Menurut Jenis Kelamin", sheet: "2.E.3" },
        { no: "2.E.4", name: "Angkatan Kerja Menurut Kabupaten/Kota", sheet: "2.E.4", note: "Khusus Provinsi" }
      ]
    },
    {
      code: "2.F",
      title: "Penduduk yang Bekerja",
      tables: [
        { no: "2.F.1", name: "PYB Menurut Lapangan Usaha", sheet: "2.F.1" },
        { no: "2.F.2", name: "PYB Menurut Golongan Umur", sheet: "2.F.2" },
        { no: "2.F.3", name: "PYB Menurut Tingkat Pendidikan", sheet: "2.F.3" },
        { no: "2.F.4", name: "PYB Menurut Jenis Kelamin", sheet: "2.F.4" },
        { no: "2.F.5", name: "PYB Menurut Status Pekerjaan Utama", sheet: "2.F.5" },
        { no: "2.F.6", name: "PYB Menurut Jabatan", sheet: "2.F.6" },
        { no: "2.F.7", name: "PYB Menurut Jam Kerja", sheet: "2.F.7" },
        { no: "2.F.8", name: "PYB Menurut Kabupaten/Kota", sheet: "2.F.8", note: "Khusus Provinsi" }
      ]
    },

    {
      code: "2.G",
      title: "Penganggur dan Tingkat Penggangur",
      tables: [
        { no: "2.G.1", name: "Penganggur Terbuka Menurut Golongan Umur", sheet: "2.G.1" },
        { no: "2.G.2", name: "Tingkat Penganggur Terbuka Menurut Golongan Umur", sheet: "2.G.2" },
        { no: "2.G.3", name: "Penganggur Terbuka Menurut Tingkat Pendidikan", sheet: "2.G.3" },
        { no: "2.G.4", name: "Tingkat Penganggur TerbukaMenurut Tingkat Pendidikan", sheet: "2.G.4" },
        { no: "2.G.5", name: "Penganggur Terbuka Menurut Jenis Kelamin", sheet: "2.G.5" },
        { no: "2.G.6", name: "Tingkat Penganggur Terbuka Menurut Jenis Kelamin", sheet: "2.G.6" },
        { no: "2.G.7", name: "Penganggur Terbuka Menurut Kabupaten/Kota", sheet: "2.G.7" },
        { no: "2.G.8", name: "Tingkat Penganggur Terbuka Menurut Kabupaten/Kota", sheet: "2.G.8" }
      ]
    },
    {
      code: "2.H",
      title: "Produktivitas Tenaga Kerja",
      tables: [
        { no: "2.H.1", name: "Produktivitas Tenaga Kerja Menurut Lapangan Usaha", sheet: "2.H.1" }
      ]
    },
    {
      code: "2.I",
      title: "Pelatihan Tenaga Kerja",
      tables: [
        { no: "2.I.1", name: "Kapasitas Terpasang, Jumlah Instruktur dan Lulusan Pelatihan", sheet: "2.I.1" }
      ]
    },
    {
      code: "2.J",
      title: "Penempatan Tenaga Kerja",
      tables: [
        { no: "2.J.1", name: "Jumlah Pencari Kerja, Bursa Kerja dan Pengantar Kerja", sheet: "2.J.1" }
      ]
    },
    {
      code: "2.K",
      title: "Hubungan Industrial dan Jaminan Sosial Tenaga Kerja",
      tables: [
        { no: "2.K.1", name: "Perangkat Hubungan Industrial dan Jaminan Sosial Tenaga Kerja", sheet: "2.K.1" },
        { no: "2.K.2", name: "Perkembangan Upah Minimum", sheet: "2.K.2" }
      ]
    },
    {
      code: "2.L",
      title: "Pengawasan Ketenagakerjaan",
      tables: [
        { no: "2.L.1", name: "Jumlah Perusahaan dan Pegawai Pengawas Ketenagakerjaan", sheet: "2.L.1" }
      ]
    }
  ]
});


// =======================
// BAB III
// =======================
TOC.push({
  bab: "BAB III",
  title: "Perkiraan Persediaan Tenaga Kerja",
  hasTable: true,
  sections: [
    {
      code: "3.A",
      title: "Perkiraan Penduduk Usia Kerja",
      tables: [
        { no: "3.A.1", name: "Perkiraan Penduduk Usia Kerja Menurut Golongan Umur", sheet: "3.A.1" },
        { no: "3.A.2", name: "Perkiraan Penduduk Usia Kerja Menurut Tingkat Pendidikan", sheet: "3.A.2" },
        { no: "3.A.3", name: "Perkiraan Penduduk Usia Kerja Menurut Jenis Kelamin", sheet: "3.A.3" },
        { no: "3.A.4", name: "Perkiraan Penduduk Usia Kerja Menurut Kabupaten/Kota", sheet: "3.A.4", note: "Khusus Provinsi" }
      ]
    },
    {
      code: "3.B",
      title: "Perkiraan Tingkat Partisipasi Angkatan Kerja",
      tables: [
        { no: "3.B.1", name: "TPAK Menurut Golongan Umur", sheet: "3.B.1" },
        { no: "3.B.2", name: "TPAK Menurut Tingkat Pendidikan", sheet: "3.B.2" },
        { no: "3.B.3", name: "TPAK Menurut Jenis Kelamin", sheet: "3.B.3" },
        { no: "3.B.4", name: "TPAK Menurut Kabupaten/Kota", sheet: "3.B.4", note: "Khusus Provinsi" }
      ]
    },
    {
      code: "3.C",
      title: "Perkiraan Angkatan Kerja",
      tables: [
        { no: "3.C.1", name: "Angkatan Kerja Menurut Golongan Umur", sheet: "3.C.1" },
        { no: "3.C.2", name: "Angkatan Kerja Menurut Tingkat Pendidikan", sheet: "3.C.2" },
        { no: "3.C.3", name: "Angkatan Kerja Menurut Jenis Kelamin", sheet: "3.C.3" },
        { no: "3.C.4", name: "Angkatan Kerja Menurut Kabupaten/Kota", sheet: "3.C.4", note: "Khusus Provinsi" }
      ]
    }
  ]
});

// =======================
// BAB IV
// =======================
TOC.push({
  bab: "BAB IV",
  title: "Perkiraan Kebutuhan Akan Tenaga Kerja",
  hasTable: true,
  sections: [
    {
      code: "4.A",
      title: "Perkiraan Perekonomian",
      tables: [
        { no: "4.A.1", name: "Perkiraan Laju Pertumbuhan PDRB ADHK 2010 Menurut Lapangan Usaha", sheet: "4.A.1" },
        { no: "4.A.2", name: "Perkiraan Struktur PDRB ADHK 2010 Menurut Lapangan Usaha", sheet: "4.A.2" }
      ]
    },
    {
      code: "4.B",
      title: "Rencana Pengembangan Potensi Daerah",
      tables: [
        { no: "4.B.1", name: "Rencana Investasi Menurut Sektor Usaha, Nilai Investasi, Jumlah Proyek dan Kebutuhan Tenaga Kerja", sheet: "4.B.1" }
      ]
    },
    {
      code: "4.C",
      title: "Perkiraan Kesempatan Kerja",
      tables: [
        { no: "4.C.1", name: "Kesempatan Kerja Menurut Lapangan Usaha", sheet: "4.C.1" },
        { no: "4.C.2", name: "Kesempatan Kerja Menurut Golongan Umur", sheet: "4.C.2" },
        { no: "4.C.3", name: "Kesempatan Kerja Menurut Tingkat Pendidikan", sheet: "4.C.3" },
        { no: "4.C.4", name: "Kesempatan Kerja Menurut Jenis Kelamin", sheet: "4.C.4" },
        { no: "4.C.5", name: "Kesempatan Kerja Menurut Status Pekerjaan", sheet: "4.C.5" },
        { no: "4.C.6", name: "Kesempatan Kerja Menurut Jabatan", sheet: "4.C.6" },
        { no: "4.C.7", name: "Kesempatan Kerja Menurut Jam Kerja", sheet: "4.C.7" },
        { no: "4.C.8", name: "Kesempatan Kerja Menurut Kabupaten/Kota", sheet: "4.C.8", note: "Khusus Provinsi" }
      ]
    },
    {
      code: "4.D",
      title: "Perkiraan Produktivitas Tenaga Kerja",
      tables: [
        { no: "4.D.1", name: "Produktivitas Tenaga Kerja Bruto Menurut Lapangan Usaha", sheet: "4.D.1" }
      ]
    },
    {
      code: "4.E",
      title: "Perkiraan Pelatihan Tenaga Kerja",
      tables: [
        { no: "4.E.1", name: "Tambahan Kesempatan Kerja Menurut Status Pekerjaan dan Tingkat Pendidikan", sheet: "4.E.1" },
        { no: "4.E.2", name: "Target Kapasitas Terpasang, Jumlah Instruktur dan Lulusan Pelatihan", sheet: "4.E.2" }
      ]
    },
    {
      code: "4.F",
      title: "Perkiraan Penempatan Tenaga Kerja",
      tables: [
        { no: "4.F.1", name: "Tambahan Kesempatan Kerja Menurut Status Pekerjaan dan Lapangan Usaha", sheet: "4.F.1" },
        { no: "4.F.2", name: "Target Lowongan, Pencari Kerja, Penempatan, Bursa Kerja & Pengantar Kerja", sheet: "4.F.2" }
      ]
    },
    {
      code: "4.G",
      title: "Perkiraan Hubungan Industrial & Jamsos",
      tables: [
        { no: "4.G.1", name: "Target Mediator, PP, PKB, SP/SB, LKS Bipartit, Perusahaan dan Tenaga Kerja", sheet: "4.G.1" },
        { no: "4.G.2", name: "Target Perkembangan Upah Minimum", sheet: "4.G.2" }
      ]
    },
    {
      code: "4.H",
      title: "Perkiraan Pengawasan Ketenagakerjaan",
      tables: [
        { no: "4.H.1", name: "Jumlah Perusahaan dan Pegawai Pengawas Ketenagakerjaan", sheet: "4.H.1" }
      ]
    }
  ]
});

// =======================
// BAB V
// =======================
TOC.push({
  bab: "BAB V",
  title: "Perkiraan Keseimbangan Persediaan dan Kebutuhan Tenaga Kerja",
  hasTable: true,
  sections: [
    {
      code: "5.A",
      title: "Penganggur Terbuka Menurut Golongan Umur",
      tables: [
        { no: "5.A.1", name: "Perkiraan Penganggur Terbuka Menurut Golongan Umur", sheet: "5.A.1" },
        { no: "5.A.2", name: "Perkiraan TPT Menurut Golongan Umur", sheet: "5.A.2" }
      ]
    },
    {
      code: "5.B",
      title: "Penganggur Terbuka Menurut Tingkat Pendidikan",
      tables: [
        { no: "5.B.1", name: "Perkiraan Penganggur Terbuka Menurut Tingkat Pendidikan", sheet: "5.B.1" },
        { no: "5.B.2", name: "Perkiraan TPT Menurut Tingkat Pendidikan", sheet: "5.B.2" }
      ]
    },
    {
      code: "5.C",
      title: "Penganggur Terbuka Menurut Jenis Kelamin",
      tables: [
        { no: "5.C.1", name: "Perkiraan Penganggur Terbuka Menurut Jenis Kelamin", sheet: "5.C.1" },
        { no: "5.C.2", name: "Perkiraan TPT Menurut Jenis Kelamin", sheet: "5.C.2" }
      ]
    },
    {
      code: "5.D",
      title: "Penganggur Terbuka Menurut Kabupaten/Kota",
      tables: [
        { no: "5.D.1", name: "Perkiraan Penganggur Terbuka Menurut Kabupaten/Kota", sheet: "5.D.1", note: "Khusus Provinsi" },
        { no: "5.D.2", name: "Perkiraan TPT Menurut Kabupaten/Kota", sheet: "5.D.2", note: "Khusus Provinsi" }
      ]
    }
  ]
});


// =======================
// BAB VI
// =======================
TOC.push({
  bab: "BAB VI",
  title: "Kebijakan, Strategi dan Program Pembangunan Ketenagakerjaan",
  hasTable: false,
  sections: [
    { code: "6.A", title: "Kebijakan Umum", tables: [] },
    { code: "6.B", title: "Kebijakan Pengendalian Tambahan Angkatan Kerja", tables: [] },
    { code: "6.C", title: "Kebijakan Pendidikan dan Pelatihan", tables: [] },
    { code: "6.D", title: "Kebijakan Sektoral", tables: [] },
    { code: "6.E", title: "Kebijakan Penempatan Tenaga Kerja", tables: [] },
    { code: "6.F", title: "Kebijakan Hubungan Industrial dan Jaminan Sosial Tenaga Kerja", tables: [] },
    { code: "6.G", title: "Kebijakan Pengawasan", tables: [] },
    { code: "6.H", title: "Kebijakan Lainnya", tables: [] }
  ]
});

// =======================
// BAB VII
// =======================
TOC.push({
  bab: "BAB VII",
  title: "Penutup",
  hasTable: false,
  sections: []
});

// =======================
// DAFTAR PUSTAKA
// =======================
TOC.push({
  bab: "DAFTAR PUSTAKA",
  title: "",
  hasTable: false,
  sections: []
});
