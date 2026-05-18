// Helper function to create years for columns
function makeYears(start, end) {
  const years = [];
  for (let i = start; i <= end; i++) {
    years.push(String(i));
  }
  return years;
}

// untuk bagian "TAHUNXXX" akan di replace sesuai dengan tahun pada tahun.js

// untuk pemanggilan berulang 
const KARAKTERISTIK = {
  LU17: [ // LU17 = Lapangan Usaha
    "1. Pertanian, Kehutanan dan  Perikanan",
    "2. Pertambangan dan Penggalian",
    "3. Industri Pengolahan",
    "4. Pengadaan Listrik dan Gas",
    "5. Pengadaan Air, Pengelolaan Sampah, Limbah dan Daur Ulang",
    "6. Konstruksi",
    "7. Perdagangan Besar dan Eceran, Reparasi Mobil dan Sepeda Motor",
    "8. Transportasi dan Pergudangan",
    "9. Penyediaan Akomodasi dan Makan Minum",
    "10. Informasi dan Komunikasi",
    "11. Jasa Keuangan dan Asuransi",
    "12. Real Estate",
    "13. Jasa Perusahaan",
    "14. Administrasi Pemerintahan, Pertahanan dan Jaminan Sosial Wajib",
    "15. Jasa Pendidikan",
    "16. Jasa Kesehatan dan Kegiatan Sosial",
    "17. Jasa Lainnya"],
  LU17A: [ // LU17A = Nomor Lapangan Usaha
    "1",
    "2",
    "3",
    "4",
    "5",
    "6",
    "7",
    "8",
    "9",
    "10",
    "11",
    "12",
    "13",
    "14",
    "15",
    "16",
    "17"],

  TS: ["SD", "SMTP", "SMTA Umum", "SMTA Kejuruan", "Diploma", "Universitas"], // TS = Tingkat Sekolah

  GU: ["15-19", "20-24", "25-29", "30-34", "35-39", "40-44", "45-49", "50-54", "55-59", "60-64", "65+"], //GU = Golongan Umur 

  JK: ["Laki-laki", "Perempuan"], // JK = Jenis Kelamin

  SPU: [ //SPU = Status Pekerjaan Utama
    "1. Brsh Sendiri tanpa bantuan",
    "2. Brsh dibantu buruh tidak tetap/buruh tidak dibayar",
    "3. Brsh dibantu Buruh tetap/buruh dibayar",
    "4. Pekerja/Buruh/Karyawan",
    "5. Pkj. Bebas di Pertanian",
    "6. Pkj. Bebas di Non Pertanian",
    "7. Pekerja keluarga/tak dibayar"
  ],

  JBT: [ // JBT = Jabatan
    "1 Manajer",
    "2 Profesional",
    "3 Teknisi dan Asisten Profesional",
    "4 Tenaga Tata Usaha",
    "5 Tenaga Usaha Jasa dan Tenaga Penjualan",
    "6 Pekerja Terampil Pertanian, Kehutanan dan Perikanan",
    "7 Pekerja Pengolahan, Kerajinan, dan ybdi",
    "8 Operator dan Perakit Mesin",
    "9 Pekerja Kasar"
  ],

  JAM: [ // JAM = Jam Kerja
    "0*",
    "1-14",
    "15-34",
    "35-40",
    "41-48",
    ">48"
  ],
  URA: [ // URA = Uraian
    "Jumlah kapasitas terpasang pemerintah",
    "Jumlah kapasitas terpasang swasta",
    "Jumlah instruktur pemerintah",
    "Jumlah instruktur swasta",
    "Jumlah lulusan pelatihan pemerintah",
    "Jumlah lulusan pelatihan swasta",
    "Jumlah lembaga pelatihan pemerintah yang terakreditasi",
    "Jumlah lembaga pelatihan swasta yang terakreditasi",
    "Jumlah alumni di lembaga pelatihan pemerintah yang memiliki sertifikat kompetensi",
    "Jumlah alumni di lembaga pelatihan swasta yang memiliki sertifikat kompetensi",
    "Jumlah alumni di lembaga pelatihan pemerintah yang ditempatkan (bekerja dan atau berwirausaha)",
    "Jumlah alumni di lembaga pelatihan swasta yang ditempatkan (bekerja dan atau berwirausaha)"
  ],

  PEN: [ // PEN = Penempatan Tenaga Kerja  
    "Lowongan kerja terdaftar",
    "Pencari kerja terdaftar",
    "Pencari kerja ditempatkan",
    "Bursa kerja pemerintah",
    "Bursa kerja swasta",
    "Jumlah pengantar kerja"
  ],

  HIJS: [ // HIJS = Hubungan Industrial dan Jaminan Sosial Tenaga Kerja  
    "Jumlah Mediator",
    "Jumlah Peraturan Perusahaan (PP)",
    "Jumlah Perjanjian Kerja Bersama (PKB)",
    "Jumlah SP / SB",
    "Jumlah Lembaga Bipartit",
    "Jumlah perusahaan dengan tenaga kerja minimal 50 orang",
    "Perusahaan peserta jamsostek aktif",
    "Tenaga kerja peserta jamsostek aktif",
    "Jumlah penyelesaian kasus hubungan industrial melalui Perjanjian Bersama",
    "Jumlah kasus hubungan industrial"
  ],

  PENG: [ // PENG = Pengawasan Ketenagakerjaan
    "Jumlah perusahaan yang melapor ketenagakerjaan",
    "Jumlah tenaga kerja",
    "Jumlah kecelakaan kerja",
    "Jumlah perush. yang diaudit SMK3",
    "Jumlah perusahaan yang sudah menerapkan SMK3",
    "Jumlah perusahaan yang wajib menerapkan SMK3*",
    "Jumlah pengawas ketenagakerjaan",
    "Jumlah perusahaan yang sudah menerapkan Norma K3",
    "Jumlah Perusahaan yang wajib menerapkan Norma K3"
  ],
   
  INDK: [ // INDK = Indikator
    "Penduduk Usia Kerja (PUK)",
    "Tingkat Partisipasi Angkatan Kerja (TPAK)",
    "Angkatan Kerja (AK)",
    "Kesempatan Kerja (KK)",
    "Penganggur Terbuka (PT)",
    "Tingkat Penganggur Terbuka (TPT)"	
	],
   
  KEG: [ // KEG = Kegiatan
    "ANGKATAN KERJA",
    "BEKERJA",
    "PENGANGGUR",
    "BUKAN ANGKATAN KERJA",
    "SEKOLAH",
    "MENGURUS RUMAH TANGGA",
    "LAINNYA",
    "PENDUDUK USIA KERJA",
    "TPAK (%)",
    "TPT (%)",
    "TKK (%)"
	]

};

// ===============================
// MASTER SCHEMA UNTUK NAMA KOLOM DAN BARIS PADA MASING-MASING TABEL
// ===============================

const TABLE_SCHEMA = {

  // ===============================
  // TARGET — Target Proyeksi
  // ===============================
  "Target": {
    columns: ["Indikator", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.INDK]
  },

  // ===============================
  // BAB II – KONDISI KETENAGAKERJAAN
  // ===============================

  // 2.A — Kondisi Ekonomi
  "2.A.1": {
    columns: ["Lapangan Usaha", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.LU17, "Laju Pertumbuhan"]
  },
  "2.A.2": {
    columns: ["Lapangan Usaha", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.LU17, "Jumlah", "PDRB ( miliar )"]
  },

  // 2.B — Potensi Daerah
  "2.B.1": {
    columns: ["Tingkat Sekolah", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.TS]
  },
  "2.B.2": {
    multiHeader: [
      ["Tahun", "Sektor Usaha", "", "", "Investasi", "", "Kebutuhan Tenaga Kerja"],
      ["", "Primer", "Sekunder", "Tersier", "Nilai", "Jumlah Proyek", ""]
    ],
    rows: ["TAHUNXXX"]
  },

  // 2.C — Penduduk Usia Kerja
  "2.C.0": {
    columns: ["Kegiatan", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.KEG]
  },  
  "2.C.1": {
    columns: ["Golongan Umur", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.GU, "Jumlah"]
  },
  "2.C.2": {
    columns: ["Pendidikan", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.TS, "Jumlah"]
  },
  "2.C.3": {
    columns: ["Jenis Kelamin", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.JK, "Jumlah"]
  },
  "2.C.4": {
    columns: ["Kabupaten/Kota", "T1", "T2", "T3", "T4", "T5"],
    rows: ["KABKOT", "Jumlah"]
  },

  // 2.D — TPAK
  "2.D.1": {
    columns: ["Golongan Umur", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.GU, "TPAK"]
  },
  "2.D.2": {
    columns: ["Pendidikan", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.TS, "TPAK"]
  },
  "2.D.3": {
    columns: ["Jenis Kelamin", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.JK, "TPAK"]
  },
  "2.D.4": {
    columns: ["Kabupaten/Kota", "TAHUNXXX"],
    rows: ["KABKOT", "TPAK"]
  },

  // 2.E — Angkatan Kerja
  "2.E.1": {
    columns: ["Golongan Umur", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.GU, "Jumlah"]
  },
  "2.E.2": {
    columns: ["Pendidikan", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.TS, "Jumlah"]
  },
  "2.E.3": {
    columns: ["Jenis Kelamin", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.JK, "Jumlah"]
  },
  "2.E.4": {
    columns: ["Kabupaten/Kota", "TAHUNXXX"],
    rows: ["KABKOT", "Jumlah"]
  },

  // 2.F — Penduduk Bekerja
  "2.F.1": {
    columns: ["Lapangan Usaha", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.LU17, "Jumlah"]
  },
  "2.F.2": {
    columns: ["Golongan Umur", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.GU, "Jumlah"]
  },
  "2.F.3": {
    columns: ["Pendidikan", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.TS, "Jumlah"]
  },
  "2.F.4": {
    columns: ["Jenis Kelamin", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.JK, "Jumlah"]
  },
  "2.F.5": {
    columns: ["Status Pekerjaan Utama", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.SPU, "Jumlah"]
  },
  "2.F.6": {
    columns: ["Jabatan", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.JBT, "Jumlah"]
  },
  "2.F.7": {
    columns: ["Jam Kerja", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.JAM, "Jumlah"]
  },
  "2.F.8": {
    columns: ["Kabupaten/Kota", "TAHUNXXX"],
    rows: ["KABKOT", "Jumlah"]
  },

  // 2.G — Pengangguran
  "2.G.1": {
    columns: ["Golongan Umur", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.GU, "Jumlah"]
  },
  "2.G.2": {
    columns: ["Golongan Umur", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.GU, "TPT"]
  },
  "2.G.3": {
    columns: ["Pendidikan", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.TS, "Jumlah"]
  },
  "2.G.4": {
    columns: ["Pendidikan", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.TS, "TPT"]
  },
  "2.G.5": {
    columns: ["Jenis Kelamin", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.JK, "Jumlah"]
  },
  "2.G.6": {
    columns: ["Jenis Kelamin", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.JK, "TPT"]
  },
  "2.G.7": {
    columns: ["Kabupaten/Kota", "TAHUNXXX"],
    rows: ["KABKOT", "Jumlah"]
  },
  "2.G.8": {
    columns: ["Kabupaten/Kota", "TAHUNXXX"],
    rows: ["KABKOT", "TPT"]
  },

  // 2.H
  "2.H.1": {
    columns: ["Lapangan Usaha", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.LU17, "Produktivitas"]
  },

  // 2.I
  "2.I.1": {
    columns: ["Uraian", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.URA]
  },

  // 2.J
  "2.J.1": {
    columns: ["Kabupaten/Kota", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.PEN]
  },

  // 2.K
  "2.K.1": {
    columns: ["Uraian", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.HIJS]
  },

  "2.K.2": {
    yearMatrix: [
      ["Tahun", "UMP (Rp)", "KHL (Rp)", "Upah riil yang diterima pekerja (Rp)", "Rata-rata jam kerja pekerja", "Prosentase UMK/KHL (%)"]
    ],
    rows: ["TAHUNXXX"]
  },

  // 2.L
  "2.L.1": {
    columns: ["Uraian", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.PENG]
  }

};



// =======================
// BAB III 
// =======================

Object.assign(TABLE_SCHEMA, {

  // =======================
  // 3.A — Perkiraan Penduduk Usia Kerja
  // =======================
  "3.A.1": {
    columns: ["Golongan Umur", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.GU, "Jumlah"]
  },

  "3.A.2": {
    columns: ["Pendidikan", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.TS, "Jumlah"]
  },

  "3.A.3": {
    columns: ["Jenis Kelamin", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.JK, "Jumlah"]
  },

  "3.A.4": {
    columns: ["Kabupaten/Kota", "TAHUNXXX"],
    rows: ["KABKOT", "Jumlah"]
  },

  // =======================
  // 3.B — Perkiraan TPAK
  // =======================
  "3.B.1": {
    columns: ["Golongan Umur", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.GU, "TPAK"]
  },

  "3.B.2": {
    columns: ["Pendidikan", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.TS, "TPAK"]
  },

  "3.B.3": {
    columns: ["Jenis Kelamin", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.JK, "TPAK"]
  },

  "3.B.4": {
    columns: ["Kabupaten/Kota", "TAHUNXXX"],
    rows: ["KABKOT", "TPAK"]
  },

  // =======================
  // 3.C — Perkiraan Angkatan Kerja
  // =======================
  "3.C.1": {
    columns: ["Golongan Umur", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.GU, "Jumlah"]
  },

  "3.C.2": {
    columns: ["Pendidikan", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.TS, "Jumlah"]
  },

  "3.C.3": {
    columns: ["Jenis Kelamin", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.JK, "Jumlah"]
  },

  "3.C.4": {
    columns: ["Kabupaten/Kota", "TAHUNXXX"],
    rows: ["KABKOT", "Jumlah"]
  }

});

// =======================
// BAB IV — 
// =======================

Object.assign(TABLE_SCHEMA, {

  // =======================
  // 4.A — Perekonomian
  // =======================
  "4.A.1": {
    columns: ["Lapangan Usaha", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.LU17, "Perkiraan Laju Pertumbuhan"]
  },
  "4.A.2": {
    columns: ["Lapangan Usaha", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.LU17, "Jumlah", "PDRB ( miliar )"]
  },

  // =======================
  // 4.B — Investasi
  // =======================
  "4.B.1": {
    multiHeader: [
      ["Tahun", "Sektor Usaha", "", "", "Investasi", "", "Kebutuhan Tenaga Kerja"],
      ["", "Primer", "Sekunder", "Tersier", "Nilai", "Jumlah Proyek", ""]
    ],
    rows: ["TAHUNXXX"]
  },

  // =======================
  // 4.C — Kesempatan Kerja
  // =======================
  "4.C.1": {
    columns: ["Lapangan Usaha", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.LU17, "Jumlah"]
  },
  "4.C.2": {
    columns: ["Golongan Umur", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.GU, "Jumlah"]
  },
  "4.C.3": {
    columns: ["Pendidikan", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.TS, "Jumlah"]
  },
  "4.C.4": {
    columns: ["Jenis Kelamin", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.JK, "Jumlah"]
  },
  "4.C.5": {
    columns: ["Status Pekerjaan", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.SPU, "Jumlah"]
  },
  "4.C.6": {
    columns: ["Jabatan", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.JBT, "Jumlah"]
  },
  "4.C.7": {
    columns: ["Jam Kerja", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.JAM, "Jumlah"]
  },
  "4.C.8": {
    columns: ["Kabupaten/Kota", "TAHUNXXX"],
    rows: ["KABKOT", "Jumlah"]
  },

  // =======================
  // 4.D — Produktivitas
  // =======================
  "4.D.1": {
    columns: ["Lapangan Usaha", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.LU17, "Produktivitas"]
  },

  // =======================
  // 4.E — Pelatihan
  // =======================
  "4.E.1": {
    multiHeader: [
      ["STATUS PEKERJAAN", "TINGKAT PENDIDIKAN", "", "", "", "", "", "Jumlah"],
      ["", ...KARAKTERISTIK.TS, ""]

    ],
    rows: [...KARAKTERISTIK.SPU, "Jumlah"]

  },
  "4.E.2": {
    columns: ["Uraian", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.URA]
  },

  // =======================
  // 4.F — Penempatan
  // =======================
  "4.F.1": {
    multiHeader: [
      ["Status Pekerjaan", "Lapangan Usaha", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "", "Jumlah"],
      ["", ...KARAKTERISTIK.LU17A, ""]
    ],
    rows: [...KARAKTERISTIK.SPU, "Jumlah"]
  },
  "4.F.2": {
    columns: ["Uraian", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.PEN]
  },

  // =======================
  // 4.G — HI & Jamsos
  // =======================
  "4.G.1": {
    columns: ["Uraian", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.HIJS]
  },
  "4.G.2": {
    yearMatrix: [
      ["Tahun", "UMP (Rp)", "KHL (Rp)", "Upah riil yang diterima pekerja (Rp)", "Rata-rata jam kerja pekerja", "Prosentase UMK/KHL (%)"]
    ],
    rows: ["TAHUNXXX"]
  },




  // =======================
  // 4.H — Pengawasan
  // =======================
  "4.H.1": {
    columns: ["Indikator", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.PENG]
  },

});

// =======================
// BAB V —
// =======================

Object.assign(TABLE_SCHEMA, {

  // =======================
  // 5.A — Golongan Umur
  // =======================
  "5.A.1": {
    columns: ["Golongan Umur", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.GU, "Jumlah"]
  },

  "5.A.2": {
    columns: ["Golongan Umur", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.GU, "TPT"]
  },

  // =======================
  // 5.B — Tingkat Pendidikan
  // =======================
  "5.B.1": {
    columns: ["Pendidikan", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.TS, "Jumlah"]
  },

  "5.B.2": {
    columns: ["Pendidikan", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.TS, "TPT"]
  },

  // =======================
  // 5.C — Jenis Kelamin
  // =======================
  "5.C.1": {
    columns: ["Jenis Kelamin", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.JK, "Jumlah"]
  },

  "5.C.2": {
    columns: ["Jenis Kelamin", "TAHUNXXX"],
    rows: [...KARAKTERISTIK.JK, "TPT"]
  },

  // =======================
  // 5.D — Kabupaten/Kota
  // =======================
  "5.D.1": {
    columns: ["Kabupaten/Kota", "TAHUNXXX"],
    rows: ["KABKOT", "Jumlah"]
  },

  "5.D.2": {
    columns: ["Kabupaten/Kota", "TAHUNXXX"],
    rows: ["KABKOT", "TPT"]
  }

});


// Make sure this is available when referenced
window.TABLE_SCHEMA = TABLE_SCHEMA;  // Expose TABLE_SCHEMA to global window object if needed
