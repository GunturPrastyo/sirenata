/* ══════════════════════════════════════════
   step3-indikator-puk.js — RTK Makro v8
   Render panel indikator PUK (Penduduk Usia Kerja)
   Menggunakan gaya Excel Bimtek — 4 tabel berdempet + Tabel Bantu 2.

   File ini juga mendefinisikan helper `_renderBimtekPanel()` yang
   dipakai bersama oleh step3-indikator-tpak.js (keduanya memakai
   gaya bimtek yang sama dengan label sedikit berbeda).
   ══════════════════════════════════════════ */

// ══════════════════════════════════════════════════════════════
// HELPER — Render panel gaya Bimtek untuk PUK & TPAK
// Mode ditentukan dari histCode: '2.C.*' → PUK, '2.D.*' → TPAK
// ══════════════════════════════════════════════════════════════
function _renderBimtekPanel(charDef) {
  const wrap = document.createElement('div');
  wrap.className = 'char-content';

  const isPUK     = charDef.histCode && charDef.histCode.startsWith('2.C');
  const isTPAK    = charDef.histCode && charDef.histCode.startsWith('2.D');

  const hist = computed[charDef.histCode];
  const proj = computed[charDef.projCode];

  if (!hasData(charDef.histCode) && !hasData(charDef.projCode)) {
    wrap.innerHTML = '<div class="no-data-msg">⬜ Belum ada data untuk karakteristik ini.</div>';
    return wrap;
  }

  // Info bar edit — di ATAS 4 tabel, bukan di dalam thead
  const infoBar = document.createElement('div');
  infoBar.style.cssText = 'font-size:11px; color:#16a34a; padding:4px 8px; margin-bottom:6px; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:6px; display:flex; align-items:center; gap:6px;';
  infoBar.innerHTML = `✏️ Klik angka di blok <strong>Perkiraan Laju Pertumbuhan</strong> untuk edit &nbsp;|&nbsp; <kbd style="background:#dcfce7;border:1px solid #86efac;border-radius:3px;padding:1px 5px;font-size:10px;">Enter</kbd> simpan &amp; hitung ulang &nbsp;|&nbsp; <kbd style="background:#dcfce7;border:1px solid #86efac;border-radius:3px;padding:1px 5px;font-size:10px;">Esc</kbd> batal`;
  wrap.appendChild(infoBar);

  const pcWrap = document.createElement('div'); pcWrap.className = 'char-content-wrap split-view';
  pcWrap.style.cssText = 'display:flex; overflow-x:auto; gap:10px; padding-bottom:20px;';

  const lbl = charDef.label || 'Sektor / Karakteristik';

  // Helper untuk membuat seragam tinggi header semua tabel
  function makeFlexHeader(text, badgeCode = null) {
    const hdr = document.createElement('div');
    hdr.className = 'tbl-block-h1';
    hdr.style.cssText = 'font-size:12px; margin-bottom:10px; display:flex; align-items:flex-end; gap:8px; height:42px; line-height:1.25; padding-bottom:2px;';
    hdr.innerHTML = `<span>${text}</span>`;
    if (badgeCode) {
      const b = makeCodeBadge(badgeCode);
      b.style.margin = '0'; // hapus margin bawaan
      hdr.appendChild(b);
    }
    return hdr;
  }

  // 1. Table Input Data
  const blk1 = document.createElement('div');
  blk1.appendChild(makeFlexHeader(`Input Data ${isPUK ? 'Penduduk Usia Kerja' : 'TPAK'}`, charDef.histCode));
  blk1.appendChild(makeBimtekTable1(hist, proj, lbl, charDef.histCode));

  // 2. Table Laju Pertumbuhan
  const blk2 = document.createElement('div');
  const hdr2 = makeFlexHeader('Perubahan Laju Pertumbuhan');
  hdr2.style.justifyContent = 'space-between';
  hdr2.style.alignItems = 'flex-end';
  const _BIMTEK2_SKIP = ['Jumlah', 'JUMLAH', 'TPAK', 'TPT'];
  const _editRowLabels2 = hist && hist.rows
    ? hist.rows.filter(r => !_BIMTEK2_SKIP.includes(r.label)).map(r => r.label)
    : [];
  // ─── Tombol Copy T2 ───
  const _copyBtn2 = document.createElement('button');
  _copyBtn2.innerHTML = '⎘ Copy';
  _copyBtn2.title = `Copy isi Perubahan Laju Pertumbuhan (${_editRowLabels2.length} baris × ${(proj && proj.years ? proj.years.length : 0)} kolom) ke clipboard`;
  _copyBtn2.style.cssText = 'font-size:11px;padding:2px 8px;border:1px solid #a5b4fc;border-radius:4px;background:#eef2ff;color:#4338ca;cursor:pointer;white-space:nowrap;flex-shrink:0;';
  _copyBtn2.onclick = () => {
    const pCode = charDef.projCode;
    const years = proj && proj.years ? proj.years : [];
    // Buat peta label → laju historis (rata tambahan) sebagai fallback
    const rataMap = {};
    if (proj && proj.rows && proj.rataTambahanRows) {
      proj.rows.forEach((pr, pri) => {
        if (proj.rataTambahanRows[pri] !== undefined) rataMap[pr.label] = proj.rataTambahanRows[pri].laju;
      });
    }
    const rows = _editRowLabels2.map(label => {
      const baseLaju = rataMap[label] ?? 0;
      return years.map(yr => {
        const laju = (P.lajuOverrides && P.lajuOverrides[pCode] &&
                      P.lajuOverrides[pCode][label] &&
                      P.lajuOverrides[pCode][label][yr] !== undefined)
          ? P.lajuOverrides[pCode][label][yr]
          : baseLaju;
        return String(laju).replace('.', ',');
      }).join('\t');
    });
    const tsv = rows.join('\r\n');
    navigator.clipboard.writeText(tsv).then(() => {
      if (typeof _showToast === 'function') _showToast(`✅ ${_editRowLabels2.length} baris laju berhasil di-copy ke clipboard`);
    }).catch(() => {
      if (typeof _showToast === 'function') _showToast('⚠️ Gagal copy. Izinkan akses clipboard di browser.');
    });
  };

  const _pasteBtn2 = document.createElement('button');
  _pasteBtn2.className = 'btn-paste-excel';
  _pasteBtn2.innerHTML = '📋 Paste';
  _pasteBtn2.title = `Paste ${_editRowLabels2.length} baris × ${(proj && proj.years ? proj.years.length : 0)} kolom nilai laju dari Excel\n(Otomatis dari clipboard)`;
  _pasteBtn2.onclick = () => _directLajuPaste(_editRowLabels2, proj.years, charDef.projCode);

  // ─── Tombol Reset Laju T2 ───
  const _hasOverride2 = () => !!(P.lajuOverrides && P.lajuOverrides[charDef.projCode] && Object.keys(P.lajuOverrides[charDef.projCode]).length > 0);
  const _resetBtn2 = document.createElement('button');
  const _updateResetBtn2Style = () => {
    const active = _hasOverride2();
    _resetBtn2.disabled = !active;
    _resetBtn2.style.cssText = [
      'font-size:11px', 'padding:2px 8px',
      `border:1px solid ${active ? '#fb923c' : '#d1d5db'}`,
      'border-radius:4px',
      `background:${active ? '#fff7ed' : '#f9fafb'}`,
      `color:${active ? '#c2410c' : '#9ca3af'}`,
      `cursor:${active ? 'pointer' : 'not-allowed'}`,
      'white-space:nowrap', 'flex-shrink:0',
      'transition:all 0.15s'
    ].join(';');
  };
  _resetBtn2.textContent = '↺ Reset Laju';
  _resetBtn2.title = 'Kembalikan semua laju ke nilai awal historis (hapus semua override)';
  _updateResetBtn2Style();
  _resetBtn2.onclick = () => {
    if (!_hasOverride2()) return;
    if (!confirm('Reset semua laju ke nilai awal historis?\n(Semua perubahan manual di tabel ini akan dihapus)')) return;
    const tabState = _saveTabState();
    delete P.lajuOverrides[charDef.projCode];
    buildComputed();
    buildStep3();
    _restoreTabState(tabState);
    saveStateToLS();
    _updateUndoRedoUI();
    _showToast('↺ Laju dikembalikan ke nilai awal historis');
  };

  // ─── Tombol ⚡ Auto Adj ───
  // Cek apakah ada target untuk projCode ini (import atau manual)
  const _hasTargetForCode = () => {
    const pc = charDef.projCode;
    const hasImp = !!(P.targetImports && P.targetImports[pc] && Object.keys(P.targetImports[pc]).length > 0);
    const hasMn  = !!(P.targets       && P.targets[pc]       && Object.keys(P.targets[pc]).length > 0);
    return hasImp || hasMn;
  };
  // Ambil nilai target (manual override prioritas di atas import)
  const _getTargetVal = (pc, y) => {
    if (P.targets      && P.targets[pc]      && P.targets[pc][y]      !== undefined) return P.targets[pc][y];
    if (P.targetImports && P.targetImports[pc] && P.targetImports[pc][y] !== undefined) return P.targetImports[pc][y];
    return null;
  };

  const _autoAdjBtn = document.createElement('button');
  const _updateAutoAdjStyle = () => {
    const active = _hasTargetForCode();
    _autoAdjBtn.disabled = !active;
    _autoAdjBtn.title = active
      ? (isTPAK
          ? '⚡ Auto Adjustment TPAK: distribusi proporsional per golongan umur (laju berbeda) agar TPAK Agregat = Target (%)'
          : '⚡ Auto Adjustment PUK: distribusi proporsional agar Jumlah Proyeksi = Target')
      : 'Auto Adjustment tidak tersedia — belum ada Target yang ditetapkan di Tabel 3';
    _autoAdjBtn.style.cssText = [
      'font-size:11px', 'padding:2px 8px',
      `border:1px solid ${active ? '#f97316' : '#d1d5db'}`,
      'border-radius:4px',
      `background:${active ? '#fff7ed' : '#f9fafb'}`,
      `color:${active ? '#ea580c' : '#9ca3af'}`,
      `cursor:${active ? 'pointer' : 'not-allowed'}`,
      'white-space:nowrap', 'flex-shrink:0',
      'transition:all 0.15s'
    ].join(';');
  };
  _autoAdjBtn.textContent = '⚡ Auto Adj';
  _updateAutoAdjStyle();

  _autoAdjBtn.onclick = () => {
    const pCode  = charDef.projCode;
    const pYears = (proj && proj.years) ? proj.years : [];

    // Kumpulkan tahun yang memiliki target
    const yearsWithTarget = pYears.filter(y => _getTargetVal(pCode, y) !== null);
    if (!yearsWithTarget.length) {
      _showToast('⚠️ Tidak ada Target untuk kode ini. Set target di Tabel 3 terlebih dahulu.');
      return;
    }

    if (!confirm('Lanjutkan?')) return;

    const tabState = _saveTabState();
    let rowsAdj = 0;
    const failYears = [];

    // ── Rumus step3-compute.js: P_y = baseVal × (1 + r/100)^(y - baseYear) ──
    // baseVal dan baseYear adalah nilai/tahun historis TERAKHIR — konstan untuk
    // semua tahun proyeksi.  Setiap tahun dihitung INDEPENDEN dari base yang sama.
    // → t harus = y - baseYear, BUKAN 1.

    // Ambil baseYear = tahun historis terakhir
    const baseYear = (hist && hist.years && hist.years.length)
      ? hist.years[hist.years.length - 1]
      : null;
    if (!baseYear) {
      _showToast('⚠️ Tidak dapat menentukan tahun dasar historis.');
      return;
    }

    // Bangun baseRows template: nilai historis terakhir per baris (konstan)
    // + histLaju agar GoalSeek.byProporsi memakai mode distribusi yang lebih akurat.
    //
    // WAJIB pakai filter yang SAMA dengan step3-compute.js (calcPUKTPAK):
    // → kecualikan 'Jumlah', 'JUMLAH', 'TPAK', 'TPT'
    // Baris-baris itu tidak diproyeksikan oleh compute, sehingga jika ikut
    // masuk ke baseRowsTemplate, alokasi target akan meleset dan selisih ≠ 0.
    const _COMPUTE_SKIP = ['Jumlah', 'JUMLAH', 'TPAK', 'TPT'];

    const projData  = (typeof computed !== 'undefined' && computed[pCode]) ? computed[pCode] : null;
    const lajuHistMap = {};
    if (projData && projData.rataTambahanRows && projData.rows) {
      projData.rows
        .filter(r => !_COMPUTE_SKIP.includes(r.label) && !r.isTarget && !r.isSelisih)
        .forEach((r, i) => {
          if (projData.rataTambahanRows[i] !== undefined)
            lajuHistMap[r.label] = projData.rataTambahanRows[i].laju;
        });
    }

    const baseRowsTemplate = [];
    if (hist && hist.rows) {
      hist.rows
        .filter(r => !_COMPUTE_SKIP.includes(r.label))
        .forEach(r => {
          const lastVal = r.vals[r.vals.length - 1];
          if (lastVal !== null && lastVal !== undefined && isFinite(lastVal) && lastVal > 0) {
            const row = { label: r.label, baseVal: lastVal };
            if (lajuHistMap[r.label] !== undefined) row.histLaju = lajuHistMap[r.label];
            baseRowsTemplate.push(row);
          }
        });
    }

    if (!baseRowsTemplate.length) {
      _showToast('⚠️ Tidak ada data historis per baris yang valid.');
      return;
    }

    // ══════════════════════════════════════════════════════════════
    //  LOGIKA AUTO ADJ — BERBEDA UNTUK TPAK vs PUK
    // ══════════════════════════════════════════════════════════════
    if (isTPAK) {
      // ── TPAK: Data persentase (0–100%) ──────────────────────────
      // Target = TPAK agregat (%). Distribusi per golongan umur menggunakan pola
      // proporsional yang sama dengan PUK, namun "total" di-scale dari rasio
      // TPAK agregat agar tiap baris mendapat laju yang BERBEDA (seperti PUK).
      //
      // Rumus:
      //   scale        = target_TPAK_agg / base_TPAK_agg
      //   scaledTarget = sum(base_TPAK_i) × scale
      //   → GoalSeek.byProporsi(baseRowsTemplate, scaledTarget, t)
      //     menghasilkan laju berbeda per baris (distribusi proporsional hist laju)
      //
      // Baris 'TPAK' agregat tetap di-override langsung via CAGR agar
      // Selisih di Tabel 3 = 0.
      const rawSrc = (typeof rawSheets !== 'undefined') ? rawSheets[charDef.histCode] : null;
      const tpakAggRow  = rawSrc?.rows?.find(r => r.label === 'TPAK');
      const baseTPAKIdx = rawSrc ? rawSrc.years.indexOf(baseYear) : -1;
      const baseTPAK    = (tpakAggRow && baseTPAKIdx >= 0) ? tpakAggRow.vals[baseTPAKIdx] : null;

      if (!baseTPAK || baseTPAK <= 0) {
        _showToast('⚠️ Tidak dapat menemukan nilai TPAK agregat historis sebagai basis.');
        _restoreTabState(tabState);
        return;
      }

      // Sum nilai historis terakhir semua golongan umur (basis GoalSeek)
      const baseSumIndividual = baseRowsTemplate.reduce((s, r) => s + (r.baseVal || 0), 0);
      if (!baseSumIndividual || baseSumIndividual <= 0) {
        _showToast('⚠️ Tidak ada data historis per golongan umur yang valid.');
        _restoreTabState(tabState);
        return;
      }

      yearsWithTarget.forEach(yr => {
        const targetTPAK = _getTargetVal(pCode, yr);
        if (targetTPAK === null) return;
        const t = yr - baseYear;
        if (t <= 0) { failYears.push(yr + ' (t≤0)'); return; }

        const scale = targetTPAK / baseTPAK;
        if (scale <= 0) { failYears.push(yr + ' (scale≤0)'); return; }

        // Target "sum" yang di-scale — membuat tiap baris tumbuh proporsional
        const scaledTarget = baseSumIndividual * scale;

        // Distribusi proporsional (sama persis dengan PUK)
        const hasil = GoalSeek.byProporsi(baseRowsTemplate, scaledTarget, t);

        hasil.forEach(row => {
          if (row.valid) {
            if (!P.lajuOverrides)                     P.lajuOverrides = {};
            if (!P.lajuOverrides[pCode])              P.lajuOverrides[pCode] = {};
            if (!P.lajuOverrides[pCode][row.label])   P.lajuOverrides[pCode][row.label] = {};
            P.lajuOverrides[pCode][row.label][yr] = row.laju;
            rowsAdj++;
          } else {
            failYears.push(yr + '/' + row.label);
          }
        });

        // Override baris TPAK agregat via CAGR langsung → Selisih di T3 = 0
        const lajuAgg = Number(((Math.pow(scale, 1 / t) - 1) * 100).toFixed(10));
        if (!P.lajuOverrides)                   P.lajuOverrides = {};
        if (!P.lajuOverrides[pCode])            P.lajuOverrides[pCode] = {};
        if (!P.lajuOverrides[pCode]['TPAK'])    P.lajuOverrides[pCode]['TPAK'] = {};
        P.lajuOverrides[pCode]['TPAK'][yr] = lajuAgg;
      });

    } else {
      // ── PUK: Data absolut (jumlah orang) ────────────────────────
      yearsWithTarget.forEach(yr => {
        const targetVal = _getTargetVal(pCode, yr);
        if (targetVal === null) return;

        const t = yr - baseYear;
        if (t <= 0) { failYears.push(yr + ' (t≤0)'); return; }

        const hasil = GoalSeek.byProporsi(baseRowsTemplate, targetVal, t);
        hasil.forEach(row => {
          if (row.valid) {
            if (!P.lajuOverrides)                     P.lajuOverrides = {};
            if (!P.lajuOverrides[pCode])              P.lajuOverrides[pCode] = {};
            if (!P.lajuOverrides[pCode][row.label])   P.lajuOverrides[pCode][row.label] = {};
            P.lajuOverrides[pCode][row.label][yr] = row.laju;
            rowsAdj++;
          } else {
            failYears.push(yr + '/' + row.label);
          }
        });
      });
    }

    // ── Satu kali rebuild setelah semua override ditulis ──
    saveStateToLS();
    buildComputed();
    buildStep3();
    _restoreTabState(tabState);
    _updateUndoRedoUI();
    if (typeof _gsUpdateBadge === 'function') _gsUpdateBadge();

    const failMsg = failYears.length ? ` ⚠️ ${failYears.length} item gagal.` : '';
    _showToast(`⚡ Auto Adjustment selesai: ${yearsWithTarget.length} tahun, ${rowsAdj} baris disesuaikan.${failMsg}`);
  };

  // Grup tombol kanan hdr2: [⎘ Copy] [📋 Paste] [⚡ Auto Adj] [↺ Reset]
  const _hdr2BtnGroup = document.createElement('div');
  _hdr2BtnGroup.style.cssText = 'display:flex;gap:4px;align-items:center;flex-shrink:0;';
  _hdr2BtnGroup.appendChild(_copyBtn2);
  _hdr2BtnGroup.appendChild(_pasteBtn2);
  _hdr2BtnGroup.appendChild(_autoAdjBtn);
  _hdr2BtnGroup.appendChild(_resetBtn2);
  hdr2.appendChild(_hdr2BtnGroup);
  blk2.appendChild(hdr2);
  blk2.appendChild(makeBimtekTable2(hist, proj, charDef.projCode));

  // 3. Table Proyeksi & Target
  const blk3 = document.createElement('div');
  const hdr3 = makeFlexHeader(`Proyeksi ${isPUK ? 'Penduduk Usia Kerja' : 'TPAK'} (${P.nama})`, charDef.projCode);

  // Tombol toggle 5 desimal
  const _t3DecBtn = document.createElement('button');
  _t3DecBtn.textContent = '.00000';
  _t3DecBtn.title = 'Tampilkan/sembunyikan 5 digit desimal';
  _t3DecBtn.style.cssText = 'font-size:11px;padding:2px 8px;border:1px solid #86efac;border-radius:4px;background:#f0fdf4;color:#166534;cursor:pointer;white-space:nowrap;flex-shrink:0;margin-left:6px;font-family:"DM Mono",monospace;';
  const _t3Tbl = makeBimtekTable3(hist, proj, lbl, charDef);
  // Simpan fungsi update visual tombol agar bisa dipanggil dari luar (onfocus/onblur laju)
  const _updateDecBtn = () => {
    const active = _t3Tbl._show5dec;
    _t3DecBtn.style.background   = active ? '#166534' : '#f0fdf4';
    _t3DecBtn.style.color        = active ? '#fff'    : '#166534';
    _t3DecBtn.style.borderColor  = active ? '#166534' : '#86efac';
  };
  _t3Tbl._updateDecBtn = _updateDecBtn;
  _t3DecBtn.onclick = function () {
    if (_t3Tbl._toggleDecimal) _t3Tbl._toggleDecimal();
    // Tombol ditekan user → pin/unpin state 5dec
    _t3Tbl._pinned5dec = _t3Tbl._show5dec;
    _updateDecBtn();
  };
  // ─── Tombol Copy T3 ───
  const _copyBtn3 = document.createElement('button');
  _copyBtn3.innerHTML = '⎘ Copy';
  const _t3DataRows = proj && proj.rows
    ? proj.rows.filter(r => !r.isTarget && !r.isSelisih)
    : [];
  _copyBtn3.title = `Copy data proyeksi (${_t3DataRows.length} baris × ${proj && proj.years ? proj.years.length : 0} kolom) ke clipboard`;
  _copyBtn3.style.cssText = 'font-size:11px;padding:2px 8px;border:1px solid #a5b4fc;border-radius:4px;background:#eef2ff;color:#4338ca;cursor:pointer;white-space:nowrap;flex-shrink:0;';
  _copyBtn3.onclick = () => {
    const years = proj && proj.years ? proj.years : [];
    const rows = _t3DataRows.map(r => {
      const cols = r.vals.map(v => (v === null || v === undefined) ? '' : String(v).replace('.', ','));
      return cols.join('\t');
    });
    const tsv = rows.join('\r\n');
    navigator.clipboard.writeText(tsv).then(() => {
      if (typeof _showToast === 'function') _showToast(`✅ ${_t3DataRows.length} baris proyeksi berhasil di-copy ke clipboard`);
    }).catch(() => {
      if (typeof _showToast === 'function') _showToast('⚠️ Gagal copy. Izinkan akses clipboard di browser.');
    });
  };

  const _hdr3BtnGroup = document.createElement('div');
  _hdr3BtnGroup.style.cssText = 'display:flex;gap:4px;align-items:center;flex-shrink:0;';
  _hdr3BtnGroup.appendChild(_copyBtn3);
  _hdr3BtnGroup.appendChild(_t3DecBtn);
  hdr3.appendChild(_hdr3BtnGroup);

  blk3.appendChild(hdr3);
  blk3.appendChild(_t3Tbl);

  // Hitung data delta T4 terlebih dahulu — dipakai oleh T4 (render) dan T5 (sumber data)
  const _t4DeltaData = _computeT4Deltas(hist, proj, isTPAK);

  // 4. Table Analisa Proyeksi + tombol toggle T5 di headernya
  const blk4 = document.createElement('div');
  const hdr4 = makeFlexHeader('Tabel Bantu Analisa Proyeksi 1');
  hdr4.style.justifyContent = 'space-between';
  hdr4.style.alignItems = 'flex-end';

  // 5. Tabel Bantu ke-2 (awalnya tersembunyi penuh)
  // Rumus sama dengan T4, sumber data: T3 (proj) + T4 delta (_t4DeltaData)
  const blk5 = document.createElement('div');
  blk5.style.display = 'none';
  const hdr5 = makeFlexHeader('Tabel Bantu Analisa Proyeksi 2');
  hdr5.style.justifyContent = 'space-between';
  hdr5.style.alignItems = 'flex-end';

  hdr5.appendChild(document.createTextNode(''));
  blk5.appendChild(hdr5);
  blk5.appendChild(makeBimtekTable5(proj, _t4DeltaData, isTPAK));

  // Tombol toggle T5 — duduk di header T4, mengontrol seluruh blk5
  const _t5Btn = document.createElement('button');
  _t5Btn.textContent = '+ Tabel Bantu 2';
  _t5Btn.style.cssText = 'font-size:11px;padding:2px 10px;border:1px solid #93c5fd;border-radius:4px;background:#eff6ff;color:#1d4ed8;cursor:pointer;white-space:nowrap;flex-shrink:0;';
  _t5Btn.onclick = () => {
    const hidden = blk5.style.display === 'none';
    blk5.style.display = hidden ? '' : 'none';
    _t5Btn.textContent = hidden ? '− Tabel Bantu 2' : '+ Tabel Bantu 2';
    if (hidden) {
      requestAnimationFrame(() => {
        syncSplitViewHeights(pcWrap);
        blk5.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'end' });
      });
    } else {
      requestAnimationFrame(() => {
        blk4.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'start' });
      });
    }
  };
  hdr4.appendChild(_t5Btn);
  blk4.appendChild(hdr4);
  blk4.appendChild(makeBimtekTable4(hist, proj, isTPAK));

  pcWrap.appendChild(blk1);
  pcWrap.appendChild(blk2);
  pcWrap.appendChild(blk3);
  pcWrap.appendChild(blk4);
  pcWrap.appendChild(blk5);

  // Sinkronisasi dijalankan dari switchChar() saat panel menjadi visible,
  // bukan di sini — karena panel lain masih display:none saat ini.

  wrap.appendChild(pcWrap);
  return wrap;
}

// ══════════════════════════════════════════════════════════════
// RENDER PANEL INDIKATOR PUK
// ══════════════════════════════════════════════════════════════
function renderIndikatorPuk(charDef) {
  return _renderBimtekPanel(charDef);
}
