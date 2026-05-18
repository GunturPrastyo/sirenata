/**
 * goal-seek.js — Logika Penyesuaian Laju Pertumbuhan (Goal-Seek / Adjustment)
 * RTK Makro v8 | Rahmad Saleh | 2026
 *
 * Tujuan:
 *   Menghitung Laju Pertumbuhan (r) yang diperlukan agar proyeksi suatu baris
 *   (misal: PUK, TPAK, AK) mencapai nilai target yang diinginkan.
 *
 * Rumus Inti — Invers CAGR:
 *   Proyeksi maju:  V_t = V_0 × (1 + r)^t
 *   Invers (goal):  r   = (V_target / V_0)^(1/t) − 1
 *
 * Integrasi dengan App:
 *   Hasil r disimpan ke P.lajuOverrides[projCode][rowLabel][year]
 *   yang sudah dibaca oleh calcPUKTPAK() di step3-compute.js.
 *
 * Dependencies:
 *   - data-state.js   : P, saveStateToLS(), deepCopy()
 *   - step3-compute.js: buildComputed()
 *   - (opsional) Decimal.js via window.Decimal untuk presisi tinggi
 */

'use strict';

/* ─────────────────────────────────────────────────────────────────────────
 * BAGIAN 1: UTILITAS PRESISI
 * Menggunakan Decimal.js bila tersedia, fallback ke Number biasa.
 * ───────────────────────────────────────────────────────────────────────── */

/** Bungkus nilai menjadi Decimal (atau Number bila Decimal.js tidak ada). */
function _D(v) {
  if (window.Decimal) return new window.Decimal(v);
  return { val: Number(v), toNumber: function () { return this.val; } };
}

/**
 * Kembalikan nilai sebagai Number biasa (4 desimal) — untuk TAMPILAN angka umum.
 * JANGAN pakai untuk laju yang akan disimpan ke P.lajuOverrides.
 */
function _precise(v) {
  if (v && typeof v.toNumber === 'function') return Number(v.toNumber().toFixed(4));
  return Number(Number(v).toFixed(4));
}

/**
 * Simpan laju ke P.lajuOverrides dalam satuan PERSEN dengan presisi 10 desimal.
 * calcPUKTPAK menggunakan formula: lastProjVal * (1 + currentLaju / 100)
 * sehingga nilai yang disimpan HARUS dalam persen (misal 2.6776384... bukan 0.026776...).
 *
 * Contoh: lajuDesimal = 0.02677638 → disimpan sebagai 2.677638...
 */
function _precisePct(lajuDesimal) {
  // kalikan 100 → persen; simpan 10 desimal agar proyeksi ≈ target hingga 5 desimal
  return Number((lajuDesimal * 100).toFixed(10));
}

/**
 * Format persen laju untuk TAMPILAN UI — 6 desimal supaya angka panjang terlihat.
 * Contoh: 2.677638% (bukan 2.68%).
 * Input: laju dalam DESIMAL (0.02677...).
 */
function _precisePersen(lajuDesimal) {
  return Number((lajuDesimal * 100).toFixed(6));
}


/* ─────────────────────────────────────────────────────────────────────────
 * BAGIAN 2: FUNGSI MATEMATIKA INTI
 * ───────────────────────────────────────────────────────────────────────── */

/**
 * Hitung laju pertumbuhan (r) yang dibutuhkan agar nilai awal (baseVal)
 * berkembang menjadi targetVal dalam t tahun.
 *
 * Rumus: r = (targetVal / baseVal)^(1/t) − 1
 *
 * @param {number} baseVal   - Nilai awal (tahun dasar)
 * @param {number} targetVal - Nilai target yang ingin dicapai
 * @param {number} t         - Jumlah tahun (horizon proyeksi)
 * @returns {{ laju: number, persen: number, valid: boolean, pesan: string }}
 */
function calcRequiredLaju(baseVal, targetVal, t) {
  var result = { laju: null, persen: null, lajuRaw: null, valid: false, pesan: '' };

  if (!isFinite(baseVal) || baseVal === 0) {
    result.pesan = 'Nilai dasar (base) tidak valid atau nol — pembagian tidak mungkin.';
    return result;
  }
  if (!isFinite(targetVal)) {
    result.pesan = 'Nilai target tidak valid.';
    return result;
  }
  if (!t || t <= 0) {
    result.pesan = 'Jumlah tahun (t) harus lebih dari 0.';
    return result;
  }

  var ratio = targetVal / baseVal;

  // Ratio negatif tidak dapat di-root genap → tidak terdefinisi secara real
  if (ratio <= 0) {
    result.pesan = 'Rasio target/base negatif atau nol — pertumbuhan tidak dapat dihitung.';
    return result;
  }

  var lajuRaw = Math.pow(ratio, 1 / t) - 1;   // laju desimal asli JS (15–17 digit)
  result.lajuRaw  = lajuRaw;                    // disimpan mentah untuk verifikasi internal
  result.laju     = _precisePct(lajuRaw);        // PERSEN 10 desimal → disimpan ke override
  result.persen   = _precisePersen(lajuRaw);    // PERSEN 6 desimal  → tampil di UI
  result.valid    = true;
  result.pesan    = 'OK';
  return result;
}


/**
 * Verifikasi maju: hitung proyeksi nilai dari baseVal + laju selama t tahun.
 * Berguna untuk konfirmasi bahwa laju yang dihitung menghasilkan target yang benar.
 *
 * Rumus: V_t = V_0 × (1 + r)^t
 *
 * @param {number} baseVal - Nilai awal
 * @param {number} laju    - Laju pertumbuhan (desimal, bukan persen)
 * @param {number} t       - Jumlah tahun
 * @returns {number} Nilai proyeksi
 */
function verifyProjection(baseVal, laju, t) {
  // laju di sini adalah lajuRaw (desimal), bukan persen
  return Math.round(baseVal * Math.pow(1 + laju, t) * 1e5) / 1e5;  // bulat 5 desimal
}

/**
 * Hitung proyeksi satu tahun ke depan (dipakai per-step di CAGR tahunan).
 *
 * @param {number} prevVal - Nilai tahun sebelumnya
 * @param {number} laju    - Laju pertumbuhan (desimal)
 * @returns {number}
 */
function projectOneYear(prevVal, laju) {
  return Math.round(prevVal * (1 + laju) * 1e5) / 1e5;  // bulat 5 desimal
}

/* ─────────────────────────────────────────────────────────────────────────
 * BAGIAN 3: GOAL-SEEK SATU BARIS (SINGLE ROW)
 * ───────────────────────────────────────────────────────────────────────── */

/**
 * Goal-seek untuk satu baris / satu tahun target.
 * Mengembalikan laju yang harus dipakai mulai dari baseYear hingga targetYear,
 * agar nilai di targetYear = targetVal.
 *
 * @param {number} baseVal    - Nilai aktual di baseYear
 * @param {number} targetVal  - Nilai yang ingin dicapai di targetYear
 * @param {number} baseYear   - Tahun dasar (angka, misal 2024)
 * @param {number} targetYear - Tahun target (angka, misal 2029)
 * @returns {object} Hasil + metadata
 */
function goalSeekSingleRow(baseVal, targetVal, baseYear, targetYear) {
  var t    = targetYear - baseYear;
  var calc = calcRequiredLaju(baseVal, targetVal, t);

  // Verifikasi maju: pakai lajuRaw (desimal penuh) agar selisih ≈ 0
  var verif = null;
  if (calc.valid) {
    // lajuRaw: desimal JS asli | fallback: konversi persen → desimal
    var rawLaju = (calc.lajuRaw !== undefined) ? calc.lajuRaw : (calc.laju / 100);
    verif = Math.round(baseVal * Math.pow(1 + rawLaju, t) * 1e5) / 1e5;
  }

  return {
    baseVal   : baseVal,
    targetVal : targetVal,
    baseYear  : baseYear,
    targetYear: targetYear,
    t         : t,
    laju      : calc.laju,       // 10 desimal — untuk disimpan ke override
    lajuRaw   : calc.lajuRaw,   // float penuh — untuk verifikasi internal
    persen    : calc.persen,    // 6 desimal  — untuk tampil di UI
    valid     : calc.valid,
    pesan     : calc.pesan,
    verifikasi: verif            // dibulatkan 5 desimal
  };
}


/* ─────────────────────────────────────────────────────────────────────────
 * BAGIAN 4: GOAL-SEEK PROPORSIONAL (TOTAL → DISTRIBUSI KE BARIS)
 * Dipakai ketika target adalah TOTAL (misal: total AK provinsi),
 * dan distribusi ke sektor/wilayah dilakukan secara proporsional
 * berdasarkan nilai historis (PYB atau nilai awal).
 * ───────────────────────────────────────────────────────────────────────── */

/**
 * Distribusikan totalTarget ke setiap baris secara proporsional,
 * lalu hitung laju yang dibutuhkan untuk masing-masing baris.
 *
 * Distribusi mempertimbangkan laju historis (r) masing-masing baris:
 *   1. Proyeksikan tiap baris dengan laju historisnya: proj_i = base_i × (1 + r_i/100)^t
 *   2. Hitung adjustment factor = totalTarget / Σ proj_i
 *   3. Target per baris = proj_i × factor
 *   4. Hitung laju baru per baris dari base ke target
 *
 * Jika laju historis tidak tersedia, fallback ke proporsi baseVal (perilaku lama).
 *
 * @param {Array<{label: string, baseVal: number, histLaju?: number}>} baseRows
 *   - Array baris dengan label, nilai dasar, dan opsional laju historis (%)
 * @param {number} totalTarget - Total nilai target yang ingin dicapai
 * @param {number} t           - Jumlah tahun
 * @returns {Array<object>} Hasil per baris
 */
function goalSeekByProporsi(baseRows, totalTarget, t) {
  var totalBase = baseRows.reduce(function (sum, row) {
    return sum + (isFinite(row.baseVal) ? row.baseVal : 0);
  }, 0);

  if (totalBase === 0) {
    return baseRows.map(function (row) {
      return { label: row.label, valid: false, pesan: 'Total base = 0, proporsi tidak dapat dihitung.' };
    });
  }

  // Cek apakah ada laju historis di minimal satu baris
  var hasHistLaju = baseRows.some(function (row) {
    return row.histLaju !== undefined && row.histLaju !== null && isFinite(row.histLaju);
  });

  if (hasHistLaju && t > 0) {
    // ── Mode baru: distribusi berdasarkan proyeksi laju historis ──
    // 1. Proyeksikan tiap baris dengan laju historisnya masing-masing
    var projections = baseRows.map(function (row) {
      var r = (row.histLaju !== undefined && row.histLaju !== null && isFinite(row.histLaju))
            ? row.histLaju : 0;
      var base = isFinite(row.baseVal) ? row.baseVal : 0;
      // proj_i = base_i × (1 + r_hist/100)^t
      var projVal = base * Math.pow(1 + r / 100, t);
      return { label: row.label, baseVal: base, histLaju: r, projVal: projVal };
    });

    // 2. Total proyeksi dengan laju historis
    var totalProjected = projections.reduce(function (s, p) { return s + p.projVal; }, 0);

    // 3. Hitung adjustment factor
    //    Jika totalProjected = 0, fallback ke proporsi baseVal
    if (totalProjected === 0 || !isFinite(totalProjected)) {
      // Fallback
      return _goalSeekByBaseProporsi(baseRows, totalTarget, t);
    }
    var factor = totalTarget / totalProjected;

    // 4. Target per baris = proyeksi historis × factor
    return projections.map(function (p) {
      var targetRow = p.projVal * factor;
      var calc = calcRequiredLaju(p.baseVal, targetRow, t);

      return {
        label     : p.label,
        baseVal   : p.baseVal,
        histLaju  : p.histLaju,
        proporsi  : _precise(totalProjected > 0 ? p.projVal / totalProjected : 0),
        targetVal : _precise(targetRow),
        laju      : calc.laju,
        persen    : calc.persen,
        valid     : calc.valid,
        pesan     : calc.pesan,
        verifikasi: calc.valid ? verifyProjection(p.baseVal, calc.laju, t) : null
      };
    });
  }

  // ── Fallback: proporsi berdasarkan baseVal (perilaku lama) ──
  return _goalSeekByBaseProporsi(baseRows, totalTarget, t);
}

/**
 * Fallback: distribusi proporsional berdasarkan baseVal saja (perilaku lama).
 */
function _goalSeekByBaseProporsi(baseRows, totalTarget, t) {
  var totalBase = baseRows.reduce(function (sum, row) {
    return sum + (isFinite(row.baseVal) ? row.baseVal : 0);
  }, 0);

  return baseRows.map(function (row) {
    var proporsi   = isFinite(row.baseVal) ? row.baseVal / totalBase : 0;
    var targetRow  = proporsi * totalTarget;
    var calc       = calcRequiredLaju(row.baseVal, targetRow, t);

    return {
      label     : row.label,
      baseVal   : row.baseVal,
      proporsi  : _precise(proporsi),
      targetVal : _precise(targetRow),
      laju      : calc.laju,
      persen    : calc.persen,
      valid     : calc.valid,
      pesan     : calc.pesan,
      verifikasi: calc.valid ? verifyProjection(row.baseVal, calc.laju, t) : null
    };
  });
}


/* ─────────────────────────────────────────────────────────────────────────
 * BAGIAN 5: GOAL-SEEK MULTI-TAHUN
 * Ketika setiap tahun proyeksi punya target yang berbeda-beda,
 * hitung laju tahunan yang berbeda per tahun.
 * ───────────────────────────────────────────────────────────────────────── */

/**
 * Goal-seek dengan target berbeda per tahun.
 * Laju dihitung dari nilai tahun sebelumnya ke target tahun berikutnya (t=1).
 *
 * @param {number} baseVal
 *   - Nilai awal (tahun sebelum tahun pertama di yearTargets)
 * @param {Object} yearTargets
 *   - { [year]: targetValue } — target untuk setiap tahun proyeksi
 * @returns {Object} { [year]: { laju, targetVal, verifikasi, valid, pesan } }
 */
function goalSeekMultiYear(baseVal, yearTargets) {
  var years  = Object.keys(yearTargets).map(Number).sort(function (a, b) { return a - b; });
  var result = {};
  var prev   = baseVal;

  years.forEach(function (yr) {
    var target  = yearTargets[yr];
    var calc    = calcRequiredLaju(prev, target, 1); // satu langkah per tahun
    var rawLaju = (calc.lajuRaw !== undefined) ? calc.lajuRaw : (calc.laju / 100);

    result[yr] = {
      targetVal : target,
      baseVal   : prev,
      laju      : calc.laju,       // 10 desimal — disimpan ke override
      lajuRaw   : calc.lajuRaw,
      persen    : calc.persen,    // 6 desimal  — tampil UI
      valid     : calc.valid,
      pesan     : calc.pesan,
      verifikasi: calc.valid ? Math.round(prev * (1 + rawLaju) * 1e5) / 1e5 : null
    };

    // Nilai tahun ini menjadi basis tahun berikutnya
    prev = calc.valid ? target : prev;
  });

  return result;
}

/* ─────────────────────────────────────────────────────────────────────────
 * BAGIAN 6: VALIDASI & BATAS WAJAR
 * ───────────────────────────────────────────────────────────────────────── */

/** Batas wajar laju pertumbuhan per jenis indikator (dalam PERSEN, sesuai P.lajuOverrides). */
var BATAS_LAJU = {
  puk   : { min: -5,   max: 10,  label: 'PUK'   },
  tpak  : { min: -10,  max: 10,  label: 'TPAK'  },
  ak    : { min: -5,   max: 10,  label: 'AK'    },
  kk    : { min: -10,  max: 15,  label: 'KK'    },
  pdrb  : { min: -5,   max: 15,  label: 'PDRB'  },
  umum  : { min: -20,  max: 30,  label: 'Umum'  }
};


/**
 * Validasi apakah laju hasil goal-seek masih dalam batas wajar.
 *
 * @param {number} lajuPersen  - Laju dalam PERSEN (misal 2.677638, bukan 0.02677)
 * @param {string} jenisKey    - Kunci ke BATAS_LAJU ('puk', 'tpak', ..., 'umum')
 * @returns {{ wajar: boolean, peringatan: string }}
 */
function validasiLaju(lajuPersen, jenisKey) {
  var batas  = BATAS_LAJU[jenisKey] || BATAS_LAJU.umum;
  var wajar  = lajuPersen >= batas.min && lajuPersen <= batas.max;
  var peringatan = '';

  if (!wajar) {
    peringatan = 'PERINGATAN: Laju ' + Number(lajuPersen).toFixed(6) + '% di luar batas wajar '
      + batas.label + ' [' + batas.min + '%, ' + batas.max + '%].';
  }
  return { wajar: wajar, peringatan: peringatan };
}

/**
 * Validasi nilai TPAK: harus berada di rentang 0–100.
 * Bila proyeksi melampaui batas, kembalikan peringatan.
 *
 * @param {number} tpakProyeksi - Nilai TPAK hasil proyeksi (%)
 * @returns {{ valid: boolean, pesan: string }}
 */
function validasiTPAK(tpakProyeksi) {
  if (tpakProyeksi < 0 || tpakProyeksi > 100) {
    return {
      valid: false,
      pesan: 'TPAK proyeksi ' + tpakProyeksi + '% di luar rentang valid (0–100%).'
    };
  }
  return { valid: true, pesan: 'OK' };
}


/* ─────────────────────────────────────────────────────────────────────────
 * BAGIAN 7: INTEGRASI DENGAN APP (P.lajuOverrides + saveStateToLS)
 *
 * Fungsi-fungsi ini langsung mengubah state global P dan menyimpan ke
 * localStorage agar laju hasil goal-seek dipakai oleh calcPUKTPAK().
 * ───────────────────────────────────────────────────────────────────────── */

/**
 * Pastikan struktur nested P.lajuOverrides[projCode][rowLabel] ada.
 * @private
 */
function _ensureOverridePath(projCode, rowLabel) {
  if (!P.lajuOverrides)                        P.lajuOverrides = {};
  if (!P.lajuOverrides[projCode])              P.lajuOverrides[projCode] = {};
  if (!P.lajuOverrides[projCode][rowLabel])    P.lajuOverrides[projCode][rowLabel] = {};
}

/**
 * Terapkan laju hasil goal-seek untuk satu baris + satu tahun.
 * Setelah diterapkan, panggil buildComputed() + buildStep3() bila tersedia.
 *
 * @param {string} projCode  - Kode proyeksi (misal: 'prov_jatim')
 * @param {string} rowLabel  - Label baris (misal: 'PUK')
 * @param {number} year      - Tahun target (angka)
 * @param {number} targetVal - Nilai target yang ingin dicapai
 * @param {number} baseVal   - Nilai awal (tahun dasar)
 * @param {number} baseYear  - Tahun dasar
 * @param {string} [jenisKey='umum'] - Jenis untuk validasi batas wajar
 * @returns {object} Hasil goal-seek + status penerapan
 */
function applyGoalSeek(projCode, rowLabel, year, targetVal, baseVal, baseYear, jenisKey) {
  jenisKey = jenisKey || 'umum';
  var t    = year - (baseYear || (year - 1));
  var calc = goalSeekSingleRow(baseVal, targetVal, baseYear || (year - 1), year);

  if (!calc.valid) {
    return { applied: false, pesan: calc.pesan, detail: calc };
  }

  var validasi = validasiLaju(calc.laju, jenisKey);

  _ensureOverridePath(projCode, rowLabel);
  P.lajuOverrides[projCode][rowLabel][year] = calc.laju;
  saveStateToLS();

  // Recompute bila fungsi tersedia
  if (typeof buildComputed === 'function') buildComputed();
  if (typeof buildStep3    === 'function') buildStep3();

  return {
    applied    : true,
    projCode   : projCode,
    rowLabel   : rowLabel,
    year       : year,
    laju       : calc.laju,
    persen     : calc.persen,
    targetVal  : targetVal,
    verifikasi : calc.verifikasi,
    wajar      : validasi.wajar,
    peringatan : validasi.peringatan,
    pesan      : 'Goal-seek diterapkan.' + (validasi.peringatan ? ' ' + validasi.peringatan : '')
  };
}


/**
 * Terapkan goal-seek proporsional: dari total target, distribusikan ke semua baris
 * secara proporsional, lalu simpan semua laju ke P.lajuOverrides.
 *
 * @param {string} projCode    - Kode proyeksi
 * @param {Array<{label, baseVal}>} baseRows - Baris-baris dengan nilai dasar
 * @param {number} totalTarget - Total target yang ingin dicapai
 * @param {number} year        - Tahun target
 * @param {number} baseYear    - Tahun dasar
 * @param {string} [jenisKey]  - Jenis untuk validasi
 * @returns {Array<object>} Hasil per baris
 */
function applyGoalSeekTotal(projCode, baseRows, totalTarget, year, baseYear, jenisKey) {
  jenisKey  = jenisKey || 'umum';
  var t     = year - baseYear;
  var hasil = goalSeekByProporsi(baseRows, totalTarget, t);

  hasil.forEach(function (row) {
    if (row.valid) {
      _ensureOverridePath(projCode, row.label);
      P.lajuOverrides[projCode][row.label][year] = row.laju;
    }
  });

  saveStateToLS();
  if (typeof buildComputed === 'function') buildComputed();
  if (typeof buildStep3    === 'function') buildStep3();

  return hasil;
}

/**
 * Terapkan goal-seek multi-tahun untuk satu baris.
 * Setiap tahun target bisa berbeda.
 *
 * @param {string} projCode    - Kode proyeksi
 * @param {string} rowLabel    - Label baris
 * @param {number} baseVal     - Nilai awal (sebelum tahun pertama)
 * @param {Object} yearTargets - { [year]: targetValue }
 * @param {string} [jenisKey]  - Jenis untuk validasi
 * @returns {Object} Hasil per tahun
 */
function applyGoalSeekMultiYear(projCode, rowLabel, baseVal, yearTargets, jenisKey) {
  jenisKey  = jenisKey || 'umum';
  var hasil = goalSeekMultiYear(baseVal, yearTargets);

  Object.keys(hasil).forEach(function (yr) {
    var row = hasil[yr];
    if (row.valid) {
      _ensureOverridePath(projCode, rowLabel);
      P.lajuOverrides[projCode][rowLabel][Number(yr)] = row.laju;
    }
  });

  saveStateToLS();
  if (typeof buildComputed === 'function') buildComputed();
  if (typeof buildStep3    === 'function') buildStep3();

  return hasil;
}


/* ─────────────────────────────────────────────────────────────────────────
 * BAGIAN 8: HAPUS OVERRIDE
 * ───────────────────────────────────────────────────────────────────────── */

/**
 * Hapus override laju untuk satu baris + satu tahun.
 * Setelah dihapus, kalkulasi kembali ke laju default dari data historis.
 */
function clearGoalSeek(projCode, rowLabel, year) {
  if (P.lajuOverrides
      && P.lajuOverrides[projCode]
      && P.lajuOverrides[projCode][rowLabel]) {
    delete P.lajuOverrides[projCode][rowLabel][year];

    // Bersihkan objek kosong agar rapi
    if (Object.keys(P.lajuOverrides[projCode][rowLabel]).length === 0)
      delete P.lajuOverrides[projCode][rowLabel];
    if (Object.keys(P.lajuOverrides[projCode]).length === 0)
      delete P.lajuOverrides[projCode];
  }
  saveStateToLS();
  if (typeof buildComputed === 'function') buildComputed();
  if (typeof buildStep3    === 'function') buildStep3();
}

/**
 * Hapus semua override laju untuk satu projCode.
 */
function clearAllGoalSeek(projCode) {
  if (P.lajuOverrides && P.lajuOverrides[projCode]) {
    delete P.lajuOverrides[projCode];
  }
  saveStateToLS();
  if (typeof buildComputed === 'function') buildComputed();
  if (typeof buildStep3    === 'function') buildStep3();
}

/**
 * Hapus SEMUA override laju dari seluruh projCode.
 */
function clearAllGoalSeekGlobal() {
  P.lajuOverrides = {};
  saveStateToLS();
  if (typeof buildComputed === 'function') buildComputed();
  if (typeof buildStep3    === 'function') buildStep3();
}


/* ─────────────────────────────────────────────────────────────────────────
 * BAGIAN 9: PREVIEW TANPA APPLY
 * Hitung dan tampilkan hasil goal-seek tanpa mengubah P.lajuOverrides.
 * ───────────────────────────────────────────────────────────────────────── */

/**
 * Preview goal-seek satu baris tanpa menyimpan ke state.
 * Berguna untuk menampilkan di UI sebelum user menekan "Terapkan".
 *
 * @param {number} baseVal   - Nilai awal
 * @param {number} targetVal - Nilai target
 * @param {number} baseYear  - Tahun dasar
 * @param {number} targetYear- Tahun target
 * @param {string} [jenisKey='umum']
 * @returns {object} Ringkasan hasil preview
 */
function previewGoalSeek(baseVal, targetVal, baseYear, targetYear, jenisKey) {
  jenisKey    = jenisKey || 'umum';
  var calc     = goalSeekSingleRow(baseVal, targetVal, baseYear, targetYear);
  var validasi = calc.valid ? validasiLaju(calc.laju, jenisKey) : { wajar: false, peringatan: '' };

  return {
    baseVal    : baseVal,
    targetVal  : targetVal,
    baseYear   : baseYear,
    targetYear : targetYear,
    t          : targetYear - baseYear,
    laju       : calc.laju,
    persen     : calc.persen,
    verifikasi : calc.verifikasi,
    valid      : calc.valid,
    wajar      : validasi.wajar,
    peringatan : validasi.peringatan,
    pesan      : calc.pesan,
    applied    : false   // preview only
  };
}

/**
 * Preview proporsional: hitung distribusi + laju per baris tanpa apply.
 *
 * @param {Array<{label, baseVal}>} baseRows
 * @param {number} totalTarget
 * @param {number} baseYear
 * @param {number} targetYear
 * @param {string} [jenisKey]
 * @returns {object} { baris: [...], total: ..., valid: bool }
 */
function previewGoalSeekTotal(baseRows, totalTarget, baseYear, targetYear, jenisKey) {
  jenisKey  = jenisKey || 'umum';
  var t     = targetYear - baseYear;
  var baris = goalSeekByProporsi(baseRows, totalTarget, t);

  var totalVerif = baris.reduce(function (s, r) {
    return s + (r.verifikasi || 0);
  }, 0);

  return {
    baseYear    : baseYear,
    targetYear  : targetYear,
    t           : t,
    totalTarget : totalTarget,
    totalVerif  : _precise(totalVerif),
    selisih     : _precise(totalTarget - totalVerif),
    baris       : baris,
    valid       : baris.every(function (r) { return r.valid; }),
    applied     : false
  };
}


/* ─────────────────────────────────────────────────────────────────────────
 * BAGIAN 10: LAPORAN OVERRIDE AKTIF
 * ───────────────────────────────────────────────────────────────────────── */

/**
 * Kembalikan ringkasan semua laju override yang sedang aktif di P.lajuOverrides.
 * Berguna untuk ditampilkan di panel "Adjustment Aktif" di Step 3.
 *
 * @returns {Array<{ projCode, rowLabel, year, laju, persen }>}
 */
function getGoalSeekReport() {
  var report = [];
  if (!P.lajuOverrides) return report;

  Object.keys(P.lajuOverrides).forEach(function (projCode) {
    Object.keys(P.lajuOverrides[projCode]).forEach(function (rowLabel) {
      Object.keys(P.lajuOverrides[projCode][rowLabel]).forEach(function (yr) {
        var laju = P.lajuOverrides[projCode][rowLabel][yr];
        // laju sudah dalam PERSEN (misal 2.677638) — tampilkan 6 desimal
        report.push({
          projCode : projCode,
          rowLabel : rowLabel,
          year     : Number(yr),
          laju     : laju,
          persen   : Number(Number(laju).toFixed(6))
        });
      });
    });
  });

  // Urutkan: projCode → rowLabel → year
  report.sort(function (a, b) {
    if (a.projCode  !== b.projCode)  return a.projCode.localeCompare(b.projCode);
    if (a.rowLabel  !== b.rowLabel)  return a.rowLabel.localeCompare(b.rowLabel);
    return a.year - b.year;
  });

  return report;
}

/**
 * Cek apakah ada override aktif untuk projCode + rowLabel + year tertentu.
 *
 * @returns {number|undefined} Laju override, atau undefined bila tidak ada.
 */
function getOverride(projCode, rowLabel, year) {
  if (P.lajuOverrides
      && P.lajuOverrides[projCode]
      && P.lajuOverrides[projCode][rowLabel]) {
    return P.lajuOverrides[projCode][rowLabel][year];
  }
  return undefined;
}

/**
 * Migrasi otomatis: konversi override lama (desimal, misal 0.026)
 * ke format baru (persen, misal 2.6).
 * Panggil sekali saat app load bila ada override tersimpan.
 * Override lama terdeteksi bila nilai absolut < 1 (desimal, bukan persen).
 */
function migrateGoalSeekOverrides() {
  if (!P.lajuOverrides) return 0;
  var count = 0;
  Object.keys(P.lajuOverrides).forEach(function (projCode) {
    Object.keys(P.lajuOverrides[projCode]).forEach(function (rowLabel) {
      Object.keys(P.lajuOverrides[projCode][rowLabel]).forEach(function (yr) {
        var v = P.lajuOverrides[projCode][rowLabel][yr];
        // Heuristik: laju persen wajar -20% s/d 30%, desimal < 1 artinya perlu dikali 100
        if (typeof v === 'number' && Math.abs(v) < 1) {
          P.lajuOverrides[projCode][rowLabel][yr] = Number((v * 100).toFixed(10));
          count++;
        }
      });
    });
  });
  if (count > 0) {
    saveStateToLS();
    console.info('[GoalSeek] Migrasi ' + count + ' override dari desimal → persen selesai.');
  }
  return count;
}


/* ─────────────────────────────────────────────────────────────────────────
 * BAGIAN 11: EKSPOR NAMESPACE
 * Semua fungsi publik dikumpulkan di objek GoalSeek agar tidak mencemari
 * global scope dan mudah dipanggil dari file lain.
 * ───────────────────────────────────────────────────────────────────────── */

var GoalSeek = {
  /* — Matematika inti — */
  calcRequiredLaju    : calcRequiredLaju,
  verifyProjection    : verifyProjection,
  projectOneYear      : projectOneYear,

  /* — Goal-seek per kasus — */
  singleRow           : goalSeekSingleRow,
  byProporsi          : goalSeekByProporsi,
  multiYear           : goalSeekMultiYear,

  /* — Apply ke state (mengubah P.lajuOverrides) — */
  apply               : applyGoalSeek,
  applyTotal          : applyGoalSeekTotal,
  applyMultiYear      : applyGoalSeekMultiYear,

  /* — Preview (tidak mengubah state) — */
  preview             : previewGoalSeek,
  previewTotal        : previewGoalSeekTotal,

  /* — Hapus override — */
  clear               : clearGoalSeek,
  clearAll            : clearAllGoalSeek,
  clearGlobal         : clearAllGoalSeekGlobal,

  /* — Laporan & cek — */
  getReport           : getGoalSeekReport,
  getOverride         : getOverride,
  validasiLaju        : validasiLaju,
  validasiTPAK        : validasiTPAK,

  /* — Migrasi data lama — */
  migrateOverrides    : migrateGoalSeekOverrides,

  /* — Konstanta — */
  BATAS_LAJU          : BATAS_LAJU
};

/* Auto-migrasi: konversi override lama (desimal) ke persen saat script pertama dimuat.
 * Aman dipanggil berkali-kali karena hanya mengubah nilai < 1. */
(function () {
  if (typeof P !== 'undefined' && P.lajuOverrides &&
      Object.keys(P.lajuOverrides).length > 0) {
    migrateGoalSeekOverrides();
  }
})();

/* ─────────────────────────────────────────────────────────────────────────
 * BAGIAN 12: CATATAN INTEGRASI UNTUK DEVELOPER
 * ─────────────────────────────────────────────────────────────────────────
 *
 * 1. LOAD ORDER di index.html:
 *    <script src="js/data-state.js"></script>
 *    <script src="js/step3-compute.js"></script>
 *    <script src="js/goal-seek.js"></script>   ← setelah kedua file di atas
 *    <script src="js/step3-ui.js"></script>
 *
 * 2. CONTOH PEMAKAIAN — Adjust laju PUK agar PUK 2029 = 5.200.000:
 *
 *    var hasil = GoalSeek.apply('prov_jatim', 'PUK', 2029, 5200000, 4800000, 2024, 'puk');
 *    console.log(hasil);
 *    // { applied: true, laju: 0.0161, persen: 1.61, verifikasi: 5200000, wajar: true, ... }
 *
 * 3. CONTOH PEMAKAIAN — Preview dulu sebelum apply:
 *
 *    var prev = GoalSeek.preview(4800000, 5200000, 2024, 2029, 'puk');
 *    if (prev.valid && prev.wajar) GoalSeek.apply(...);
 *
 * 4. CONTOH — Target total AK provinsi, distribusi proporsional ke kabupaten:
 *
 *    var baris = [
 *      { label: 'Kab. A', baseVal: 120000 },
 *      { label: 'Kab. B', baseVal: 80000  }
 *    ];
 *    GoalSeek.applyTotal('prov_jatim', baris, 220000, 2029, 2024, 'ak');
 *
 * 5. CONTOH — Lihat semua override aktif:
 *
 *    console.table(GoalSeek.getReport());
 *
 * 6. CONTOH — Bersihkan semua override lalu recompute:
 *
 *    GoalSeek.clearGlobal();
 *
 * ───────────────────────────────────────────────────────────────────────── */
