/* ══════════════════════════════════════════
   step3-panels-standard.js — RTK Makro v8
   Panel renderer untuk indikator standar:
     _addTargetRows()     — helper baris target & selisih
     renderStandardPanel() — PUK, TPAK (bimtek), AK, KK, TPT, dan umum
   Dipecah dari step3-panels.js
   ══════════════════════════════════════════ */

// ══════════════════════════════════════════════════════════════
// HELPER — Tambah baris Target + Selisih dari P.targetImports
// ke tabel proyeksi (non-bimtek panels: AK, KK, PT, TPT)
//
// tbl       : <table> DOM element hasil makeTable()
// projCode  : kode indikator proyeksi, misal '3.C.1', '5.A.2'
// proj      : objek { years, rows } dari computed[projCode]
// projYears : array tahun proyeksi yang ditampilkan
// ══════════════════════════════════════════════════════════════
function _addTargetRows(tbl, projCode, proj, projYears) {
  const imports = P.targetImports && P.targetImports[projCode];
  if (!imports || !projYears.some(y => imports[y] !== undefined)) return;
  const totP = proj && proj.rows && proj.rows.find(r => r.label === 'Jumlah' || r.label === 'JUMLAH');
  const tbody = tbl.querySelector('tbody');
  if (!tbody) return;

  // ── Baris Target ──
  const trT = tbody.insertRow(); trT.className = 'row-target';
  const t0 = trT.insertCell(); t0.className = 'col-label'; t0.textContent = 'Target';
  projYears.forEach(y => {
    const tv = imports[y] !== undefined ? imports[y] : null;
    const td = trT.insertCell();
    td.className = 'num' + (tv !== null ? ' cell-imported' : '');
    td.textContent = tv !== null ? fmtNum(tv) : '-';
    if (tv !== null) {
      td.title = '📥 Target dari sheet Excel';
      td.style.cursor = 'pointer';
      td.onclick = function () { _showFullValue(this, tv); };
    }
  });

  // ── Baris Selisih (Target − Jumlah Proyeksi) ──
  const trS = tbody.insertRow(); trS.className = 'row-selisih';
  const s0 = trS.insertCell(); s0.className = 'col-label'; s0.textContent = 'Selisih (Target − Proyeksi)';
  projYears.forEach(y => {
    const tv = imports[y] !== undefined ? imports[y] : null;
    const projVal = totP && proj.years
      ? (proj.years.indexOf(y) >= 0 ? totP.vals[proj.years.indexOf(y)] : null)
      : null;
    const selisih = (tv !== null && projVal !== null) ? tv - projVal : null;
    const td = trS.insertCell(); td.className = 'num';
    td.style.cssText = `background:${selisih === null ? '#f8fafc' : selisih >= 0 ? '#dcfce7' : '#fee2e2'};font-weight:600;cursor:pointer;`;
    td.textContent = selisih !== null ? fmtNum(selisih) : '-';
    if (selisih !== null) td.onclick = function () { _showFullValue(this, selisih); };
  });
}

// ══════════════════════════════════════════════════════════════
// DEFINISI INDIKATOR & KARAKTERISTIK
// Urutan dan label mengikuti aplikasi rtkmakro.kemnaker.go.id
// ══════════════════════════════════════════════════════════════
function renderStandardPanel(charDef) {
  const wrap = document.createElement('div');
  wrap.className = 'char-content';
  // ID dipakai untuk anchor nav sidebar Model A/B (misal: 'puk-umur', 'tpak-didik')
  // Akan diassign div wrapper id secara terpisah dari file pemanggilnya jika perlu,
  // tapi kita juga bisa tambahkan id-anchor di dalam sini.
  
  const isPUK = charDef.histCode && charDef.histCode.startsWith('2.C');
  const isTPAK = charDef.histCode && charDef.histCode.startsWith('2.D');
  const isBimtekStyle = isPUK || isTPAK; // PUK & TPAK pakai gaya baru bimtek

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

  // Deteksi mode panel non-PUK/TPAK
  const isAK = !!charDef.srcPukHist;
  const isTPT = !!charDef.tptHistCode;
  const isKK = !!charDef.srcPybCode;

  // ─── CABANG 1: GAYA EXCEL BIMTEK (PUK & TPAK) ───
  if (isBimtekStyle) {
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
      // Header + tombol paste + tombol ℹ Rumus sejajar dalam satu baris
      const hdr2 = makeFlexHeader('Perubahan Laju Pertumbuhan');
      hdr2.style.justifyContent = 'space-between';
      hdr2.style.alignItems = 'flex-end';
      const _editRowLabels2 = hist && hist.rows
        ? hist.rows.filter(r => !(r.label === 'Jumlah' || r.label === 'JUMLAH')).map(r => r.label)
        : [];
      const _pasteBtn2 = document.createElement('button');
      _pasteBtn2.className = 'btn-paste-excel';
      _pasteBtn2.innerHTML = '📋 Paste dari Excel';
      _pasteBtn2.title = `Paste ${_editRowLabels2.length} baris × ${(proj && proj.years ? proj.years.length : 0)} kolom nilai laju dari Excel\n(Otomatis dari clipboard)`;
      _pasteBtn2.onclick = () => _directLajuPaste(_editRowLabels2, proj.years, charDef.projCode);

      // ─── Tombol ℹ Rumus T2 ───
      const _t2pYears    = (proj && proj.years) ? proj.years : [];
      const _t2HistRange = (hist && hist.years && hist.years.length)
          ? `${hist.years[0]}–${hist.years[hist.years.length - 1]}` : '?';
      const _t2InfoBtn = document.createElement('button');
      _t2InfoBtn.textContent = 'ℹ Rumus';
      _t2InfoBtn.style.cssText = 'font-size:11px;padding:2px 8px;border:1px solid #a5b4fc;border-radius:4px;background:#eef2ff;color:#4338ca;cursor:pointer;white-space:nowrap;flex-shrink:0;';

      const _t2Popup = document.createElement('div');
      _t2Popup.className = 't4-formula-popup';
      _t2Popup.style.cssText = 'display:none;position:fixed;z-index:9999;background:#1e293b;color:#f1f5f9;border-radius:10px;padding:14px 16px;font-size:12px;line-height:1.7;width:320px;max-height:80vh;overflow-y:auto;box-shadow:0 8px 24px rgba(0,0,0,0.45);pointer-events:auto;';
      const _t2ColRows = _t2pYears.map(y => `
          <tr>
            <td style="padding:2px 8px 2px 0;color:#fde68a;font-weight:600;white-space:nowrap;">Kolom ${y}</td>
            <td style="padding:2px 0;font-size:11px;">Default = rata laju historis.<br><span style="color:#86efac;">Bisa diedit manual (klik angka)</span></td>
          </tr>`).join('');
      _t2Popup.innerHTML = `
          <div style="font-weight:700;font-size:12.5px;margin-bottom:10px;color:#a5f3fc;">📐 Rumus Perubahan Laju Pertumbuhan</div>
          <table style="border-collapse:collapse;width:100%;font-size:11.5px;margin-bottom:10px;">
            <tr>
              <td style="padding:2px 8px 6px 0;color:#fde68a;font-weight:600;white-space:nowrap;">Laju<br>Pertumbuhan (r)</td>
              <td style="padding:2px 0;font-size:11px;">Rata laju historis, dihitung otomatis dari data tahun <code style="background:#334155;padding:1px 5px;border-radius:3px;">${_t2HistRange}</code></td>
            </tr>
            <tr style="border-top:1px solid #334155;">
              <td colspan="2" style="padding:6px 0 4px;color:#94a3b8;font-size:10.5px;">Kolom Perkiraan Laju Pertumbuhan (dapat diedit):</td>
            </tr>
            ${_t2ColRows}
          </table>
          <div style="padding-top:8px;border-top:1px solid #334155;color:#64748b;font-size:10.5px;">
            Nilai laju ini digunakan sebagai <code style="background:#334155;padding:1px 4px;border-radius:3px;">r</code> dalam rumus proyeksi (Tabel 3).<br>
            Klik di luar untuk menutup.
          </div>`;
      document.body.appendChild(_t2Popup);

      let _t2PopupOpen = false;
      const _positionT2Popup = () => {
          _t2Popup.style.top = '-9999px';
          _t2Popup.style.display = 'block';
          const _r2 = _t2InfoBtn.getBoundingClientRect();
          const ph2 = _t2Popup.offsetHeight, pw2 = _t2Popup.offsetWidth;
          const vh2 = window.innerHeight, vw2 = window.innerWidth;
          const topBelow2 = _r2.bottom + 6, topAbove2 = _r2.top - ph2 - 6;
          _t2Popup.style.top  = (topBelow2 + ph2 > vh2 && topAbove2 >= 0 ? topAbove2 : topBelow2) + 'px';
          _t2Popup.style.left = Math.max(8, Math.min(_r2.right - pw2, vw2 - pw2 - 8)) + 'px';
      };
      _t2InfoBtn.onclick = (e) => {
          e.stopPropagation();
          _t2PopupOpen = !_t2PopupOpen;
          if (_t2PopupOpen) _positionT2Popup(); else _t2Popup.style.display = 'none';
      };
      document.addEventListener('click', (e) => {
          if (_t2PopupOpen && !_t2Popup.contains(e.target) && e.target !== _t2InfoBtn) {
              _t2PopupOpen = false; _t2Popup.style.display = 'none';
          }
      });

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

      // Grup tombol kanan hdr2: [ℹ Rumus] [↺ Reset] [📋 Paste]
      const _hdr2BtnGroup = document.createElement('div');
      _hdr2BtnGroup.style.cssText = 'display:flex;gap:4px;align-items:center;flex-shrink:0;';
      _hdr2BtnGroup.appendChild(_t2InfoBtn);
      _hdr2BtnGroup.appendChild(_resetBtn2);
      _hdr2BtnGroup.appendChild(_pasteBtn2);
      hdr2.appendChild(_hdr2BtnGroup);
      blk2.appendChild(hdr2);
      blk2.appendChild(makeBimtekTable2(hist, proj, charDef.projCode));

      // 3. Table Proyeksi & Target
      const blk3 = document.createElement('div');
      const hdr3 = makeFlexHeader(`Proyeksi ${isPUK ? 'Penduduk Usia Kerja' : 'TPAK'} (${P.nama})`, charDef.projCode);

      // ─── Tombol ℹ Rumus T3 ───
      const _t3pYears   = (proj && proj.years) ? proj.years : [];
      const _t3HistLast = (hist && hist.years && hist.years.length) ? hist.years[hist.years.length - 1] : '?';
      const _t3InfoBtn = document.createElement('button');
      _t3InfoBtn.textContent = 'ℹ Rumus';
      _t3InfoBtn.style.cssText = 'font-size:11px;padding:2px 8px;border:1px solid #a5b4fc;border-radius:4px;background:#eef2ff;color:#4338ca;cursor:pointer;white-space:nowrap;flex-shrink:0;';

      const _t3Popup = document.createElement('div');
      _t3Popup.className = 't4-formula-popup';
      _t3Popup.style.cssText = 'display:none;position:fixed;z-index:9999;background:#1e293b;color:#f1f5f9;border-radius:10px;padding:14px 16px;font-size:12px;line-height:1.7;width:320px;max-height:80vh;overflow-y:auto;box-shadow:0 8px 24px rgba(0,0,0,0.45);pointer-events:auto;';
      const _t3ProjRows = _t3pYears.map((y, i) => {
          const prev = i === 0 ? _t3HistLast : _t3pYears[i - 1];
          return `<tr>
            <td style="padding:2px 8px 2px 0;color:#fde68a;font-weight:600;white-space:nowrap;">Kolom ${y}</td>
            <td style="padding:2px 0;font-size:11px;">
              <code style="background:#334155;padding:1px 5px;border-radius:3px;">P<sub>${prev}</sub> × (1 + r<sub>${y}</sub> / 100)</code>
              ${i === 0 ? '<br><span style="color:#64748b;font-size:10px;">← P hist terakhir × (1 + r / 100)</span>' : ''}
            </td>
          </tr>`;
      }).join('');
      _t3Popup.innerHTML = `
          <div style="font-weight:700;font-size:12.5px;margin-bottom:10px;color:#a5f3fc;">📐 Rumus Tabel Proyeksi</div>
          <div style="margin-bottom:6px;font-size:11.5px;">
            Rumus umum: <code style="background:#334155;padding:2px 7px;border-radius:4px;">Pₜ = Pₜ₋₁ × (1 + r / 100)</code>
          </div>
          <div style="margin-bottom:8px;color:#94a3b8;font-size:10.5px;">
            Pₜ = proyeksi tahun t &nbsp;|&nbsp; Pₜ₋₁ = nilai tahun sebelumnya &nbsp;|&nbsp; r = laju dari Tabel 2
          </div>
          <table style="border-collapse:collapse;width:100%;font-size:11.5px;margin-bottom:10px;">
            ${_t3ProjRows}
          </table>
          <div style="padding-top:8px;border-top:1px solid #334155;margin-bottom:6px;font-size:11.5px;">
            <span style="color:#fde68a;font-weight:600;">Baris Target</span>:
            Override manual <span style="background:#3b82f6;color:#fff;padding:0 4px;border-radius:3px;font-size:10px;">biru</span>
            atau import sheet Excel <span style="background:#22c55e;color:#fff;padding:0 4px;border-radius:3px;font-size:10px;">hijau</span>.
            Dapat diedit.
          </div>
          <div style="font-size:11.5px;">
            <span style="color:#fde68a;font-weight:600;">Selisih</span>:
            <code style="background:#334155;padding:1px 5px;border-radius:3px;">Target − Jumlah Proyeksi</code>
          </div>
          <div style="padding-top:8px;border-top:1px solid #334155;margin-top:6px;color:#64748b;font-size:10.5px;">
            Klik di luar untuk menutup.
          </div>`;
      document.body.appendChild(_t3Popup);

      let _t3PopupOpen = false;
      const _positionT3Popup = () => {
          _t3Popup.style.top = '-9999px';
          _t3Popup.style.display = 'block';
          const _r3 = _t3InfoBtn.getBoundingClientRect();
          const ph3 = _t3Popup.offsetHeight, pw3 = _t3Popup.offsetWidth;
          const vh3 = window.innerHeight, vw3 = window.innerWidth;
          const topBelow3 = _r3.bottom + 6, topAbove3 = _r3.top - ph3 - 6;
          _t3Popup.style.top  = (topBelow3 + ph3 > vh3 && topAbove3 >= 0 ? topAbove3 : topBelow3) + 'px';
          _t3Popup.style.left = Math.max(8, Math.min(_r3.right - pw3, vw3 - pw3 - 8)) + 'px';
      };
      _t3InfoBtn.onclick = (e) => {
          e.stopPropagation();
          _t3PopupOpen = !_t3PopupOpen;
          if (_t3PopupOpen) _positionT3Popup(); else _t3Popup.style.display = 'none';
      };
      document.addEventListener('click', (e) => {
          if (_t3PopupOpen && !_t3Popup.contains(e.target) && e.target !== _t3InfoBtn) {
              _t3PopupOpen = false; _t3Popup.style.display = 'none';
          }
      });
      hdr3.appendChild(_t3InfoBtn);

      // Tombol toggle 5 desimal
      const _t3DecBtn = document.createElement('button');
      _t3DecBtn.textContent = '.00000';
      _t3DecBtn.title = 'Tampilkan/sembunyikan 5 digit desimal';
      _t3DecBtn.style.cssText = 'font-size:11px;padding:2px 8px;border:1px solid #86efac;border-radius:4px;background:#f0fdf4;color:#166534;cursor:pointer;white-space:nowrap;flex-shrink:0;margin-left:6px;font-family:"DM Mono",monospace;';
      const _t3Tbl = makeBimtekTable3(hist, proj, lbl, charDef);
      _t3DecBtn.onclick = function () {
          if (_t3Tbl._toggleDecimal) _t3Tbl._toggleDecimal();
          const active = _t3Tbl._show5dec;
          _t3DecBtn.style.background = active ? '#166534' : '#f0fdf4';
          _t3DecBtn.style.color = active ? '#fff' : '#166534';
          _t3DecBtn.style.borderColor = active ? '#166534' : '#86efac';
      };
      hdr3.appendChild(_t3DecBtn);

      blk3.appendChild(hdr3);
      blk3.appendChild(_t3Tbl);
      
      // Hitung data delta T4 terlebih dahulu — dipakai oleh T4 (render) dan T5 (sumber data)
      const _t4DeltaData = _computeT4Deltas(hist, proj, isTPAK);

      // 4. Table Analisa Proyeksi + tombol toggle T5 di headernya
      const blk4 = document.createElement('div');
      const hdr4 = makeFlexHeader('Tabel Bantu Analisa Proyeksi');
      hdr4.style.justifyContent = 'space-between';
      hdr4.style.alignItems = 'flex-end';

      // 5. Tabel Bantu ke-2 (awalnya tersembunyi penuh)
      // Rumus sama dengan T4, sumber data: T3 (proj) + T4 delta (_t4DeltaData)
      const blk5 = document.createElement('div');
      blk5.style.display = 'none';
      const hdr5 = makeFlexHeader('Tabel Bantu Analisa Proyeksi 2');
      hdr5.style.justifyContent = 'space-between';
      hdr5.style.alignItems = 'flex-end';

      // ─── Tombol ℹ Rumus T5 ───
      const _t5InfoBtn = document.createElement('button');
      _t5InfoBtn.textContent = 'ℹ Rumus';
      _t5InfoBtn.style.cssText = 'font-size:11px;padding:2px 8px;border:1px solid #a5b4fc;border-radius:4px;background:#eef2ff;color:#4338ca;cursor:pointer;white-space:nowrap;flex-shrink:0;';
      const _t5Popup = document.createElement('div');
      _t5Popup.className = 't4-formula-popup';
      _t5Popup.style.cssText = 'display:none;position:fixed;z-index:9999;background:#1e293b;color:#f1f5f9;border-radius:10px;padding:14px 16px;font-size:12px;line-height:1.7;width:340px;max-height:80vh;overflow-y:auto;box-shadow:0 8px 24px rgba(0,0,0,0.45);pointer-events:auto;';
      const _t5pYears = (proj && proj.years) ? proj.years : [];
      const _t5ColRows = _t5pYears.map((y, i) => {
          if (i === 0) return `<tr>
            <td style="padding:2px 8px 2px 0;color:#fde68a;font-weight:600;white-space:nowrap;">Kolom ${y}</td>
            <td style="padding:2px 0;font-size:11px;">
              <code style="background:#334155;padding:1px 5px;border-radius:3px;">T4[${y}] − T3[${y}]</code>
              <span style="color:#64748b;font-size:10px;margin-left:4px;">← delta T4 dikurangi nilai proyeksi T3 tahun ${y}</span>
            </td>
          </tr>`;
          return `<tr>
            <td style="padding:2px 8px 2px 0;color:#fde68a;font-weight:600;white-space:nowrap;">Kolom ${y}</td>
            <td style="padding:2px 0;font-size:11px;">
              <code style="background:#334155;padding:1px 5px;border-radius:3px;">T4[${y}] − T4[${_t5pYears[i-1]}]</code>
            </td>
          </tr>`;
      }).join('');
      const _t5AllPairs = _t5pYears.map((y, i) =>
          i === 0 ? `(T4[${y}]−T3[${y}])` : `(T4[${y}]−T4[${_t5pYears[i-1]}])`
      ).join(' + ');
      _t5Popup.innerHTML = `
        <div style="font-weight:700;font-size:12.5px;margin-bottom:10px;color:#a5f3fc;">📐 Rumus Tabel Bantu Analisa Proyeksi 2</div>
        <div style="margin-bottom:8px;font-size:11px;color:#94a3b8;">
          Pola rumus sama dengan Tabel 4 (selisih absolut), sumber data:<br>
          <span style="color:#86efac;">Tabel 3 (Proyeksi)</span> sebagai acuan awal &amp;
          <span style="color:#7dd3fc;">Tabel 4 (Delta)</span> sebagai nilai setiap kolom.
        </div>
        <table style="border-collapse:collapse;width:100%;margin-bottom:10px;font-size:11.5px;">
          ${_t5ColRows}
          <tr style="border-top:1px solid #334155;">
            <td style="padding:6px 8px 2px 0;color:#fde68a;font-weight:600;white-space:nowrap;">Rata / Tahun</td>
            <td style="padding:6px 0 2px 0;">
              <code style="background:#334155;padding:1px 6px;border-radius:4px;font-size:11px;">AVERAGE(${_t5AllPairs})</code>
            </td>
          </tr>
        </table>
        <div style="padding-top:8px;border-top:1px solid #334155;color:#64748b;font-size:10.5px;">
          Klik di luar untuk menutup
        </div>`;
      document.body.appendChild(_t5Popup);
      let _t5PopupOpen = false;
      const _positionT5Popup = () => {
          _t5Popup.style.top = '-9999px';
          _t5Popup.style.display = 'block';
          const _r5 = _t5InfoBtn.getBoundingClientRect();
          const ph5 = _t5Popup.offsetHeight, pw5 = _t5Popup.offsetWidth;
          const vh5 = window.innerHeight, vw5 = window.innerWidth;
          const topBelow5 = _r5.bottom + 6, topAbove5 = _r5.top - ph5 - 6;
          _t5Popup.style.top  = (topBelow5 + ph5 > vh5 && topAbove5 >= 0 ? topAbove5 : topBelow5) + 'px';
          _t5Popup.style.left = Math.max(8, Math.min(_r5.right - pw5, vw5 - pw5 - 8)) + 'px';
      };
      _t5InfoBtn.onclick = (e) => {
          e.stopPropagation();
          _t5PopupOpen = !_t5PopupOpen;
          if (_t5PopupOpen) _positionT5Popup(); else _t5Popup.style.display = 'none';
      };
      document.addEventListener('click', (e) => {
          if (_t5PopupOpen && !_t5Popup.contains(e.target) && e.target !== _t5InfoBtn) {
              _t5PopupOpen = false; _t5Popup.style.display = 'none';
          }
      });
      hdr5.appendChild(_t5InfoBtn);
      blk5.appendChild(hdr5);
      blk5.appendChild(makeBimtekTable5(proj, _t4DeltaData, isTPAK));

      // Tombol ℹ Rumus T4
      const _t4InfoBtn = document.createElement('button');
      _t4InfoBtn.textContent = 'ℹ Rumus';
      _t4InfoBtn.style.cssText = 'font-size:11px;padding:2px 8px;border:1px solid #a5b4fc;border-radius:4px;background:#eef2ff;color:#4338ca;cursor:pointer;white-space:nowrap;flex-shrink:0;';

      // Popup penjelasan rumus
      const _t4Popup = document.createElement('div');
      _t4Popup.className = 't4-formula-popup';
      _t4Popup.style.cssText = [
          'display:none', 'position:fixed', 'z-index:9999',
          'background:#1e293b', 'color:#f1f5f9',
          'border-radius:10px', 'padding:14px 16px',
          'font-size:12px', 'line-height:1.7', 'width:320px',
          'max-height:80vh', 'overflow-y:auto',
          'box-shadow:0 8px 24px rgba(0,0,0,0.45)',
          'pointer-events:auto'
      ].join(';');
      const _pYears   = (proj && proj.years) ? proj.years : [];
      const _histLast = (hist && hist.years && hist.years.length) ? hist.years[hist.years.length - 1] : '?';
      // Buat baris rumus tiap kolom: kolom-1 = projYear[0]−histLast, kolom-n = projYear[n]−projYear[n-1]
      const _colRows = _pYears.map((y, i) => {
          const prev = i === 0 ? _histLast : _pYears[i - 1];
          return `<tr>
            <td style="padding:2px 8px 2px 0;color:#fde68a;font-weight:600;white-space:nowrap;">Kolom ${y}</td>
            <td style="padding:2px 0;">
              <code style="background:#334155;padding:1px 6px;border-radius:4px;font-size:11px;">${y} − ${prev}</code>
              ${i === 0 ? '<span style="color:#64748b;font-size:10px;margin-left:4px;">← proj pertama − hist terakhir</span>' : ''}
            </td>
          </tr>`;
      }).join('');
      const _allPairs = _pYears.map((y, i) => `(${y}−${i === 0 ? _histLast : _pYears[i-1]})`).join(' + ');
      _t4Popup.innerHTML = `
        <div style="font-weight:700;font-size:12.5px;margin-bottom:10px;color:#a5f3fc;">📐 Rumus Tabel Bantu Analisa Proyeksi</div>
        <table style="border-collapse:collapse;width:100%;margin-bottom:10px;font-size:11.5px;">
          ${_colRows}
          <tr style="border-top:1px solid #334155;">
            <td style="padding:6px 8px 2px 0;color:#fde68a;font-weight:600;white-space:nowrap;">Rata / Tahun</td>
            <td style="padding:6px 0 2px 0;">
              <code style="background:#334155;padding:1px 6px;border-radius:4px;font-size:11px;">
                AVERAGE(${_allPairs})
              </code>
            </td>
          </tr>
        </table>
        <div style="padding-top:8px;border-top:1px solid #334155;color:#64748b;font-size:10.5px;">
          Klik di luar untuk menutup
        </div>`;
      // Posisi popup: fixed (viewport), otomatis flip ke atas jika tidak muat di bawah
      const _positionPopup = () => {
          _t4Popup.style.top = '-9999px'; // render dulu agar bisa ukur tinggi
          _t4Popup.style.display = 'block';
          const r   = _t4InfoBtn.getBoundingClientRect();
          const ph  = _t4Popup.offsetHeight;
          const pw  = _t4Popup.offsetWidth;
          const vh  = window.innerHeight;
          const vw  = window.innerWidth;
          // Vertikal: muncul di bawah tombol, atau di atas jika tidak muat
          const topBelow = r.bottom + 6;
          const topAbove = r.top - ph - 6;
          _t4Popup.style.top  = (topBelow + ph > vh && topAbove >= 0 ? topAbove : topBelow) + 'px';
          // Horizontal: rata kanan tombol, clamp agar tidak keluar layar
          const leftIdeal = r.right - pw;
          _t4Popup.style.left = Math.max(8, Math.min(leftIdeal, vw - pw - 8)) + 'px';
      };
      document.body.appendChild(_t4Popup);

      let _t4PopupOpen = false;
      _t4InfoBtn.onclick = (e) => {
          e.stopPropagation();
          _t4PopupOpen = !_t4PopupOpen;
          if (_t4PopupOpen) {
              _positionPopup(); // sudah set display:block di dalamnya
          } else {
              _t4Popup.style.display = 'none';
          }
      };
      const _closeT4Popup = (e) => {
          if (_t4PopupOpen && !_t4Popup.contains(e.target) && e.target !== _t4InfoBtn) {
              _t4PopupOpen = false;
              _t4Popup.style.display = 'none';
          }
      };
      document.addEventListener('click', _closeT4Popup);

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
      hdr4.appendChild(_t4InfoBtn);
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

  // ─── CABANG 2: PANEL LAMA (AK, KK, TPT) ───

  // ══ BLOK 1: DATA HISTORIS / ADJUSMENT ══
  if (!noHist) {
    const adjWrap = document.createElement('div'); adjWrap.className = 'tbl-block tbl-adj';
    const adjH = document.createElement('div'); adjH.className = 'tbl-block-h1';
    adjH.textContent = isAK ? 'DATA HISTORIS — Sumber Komputasi AK'
      : isTPT ? 'DATA HISTORIS — Penganggur & TPT'
        : isKK ? 'DATA HISTORIS — PYB (Dapat Diedit)'
          : 'ADJUSMENT';
    adjWrap.appendChild(adjH);

    if (isAK) {
      const pukHist = computed[charDef.srcPukHist];
      if (pukHist && hasData(charDef.srcPukHist)) {
        adjWrap.appendChild(makeCodeBadge(charDef.srcPukHist, 'PUK — Penduduk Usia Kerja'));
        const sc = document.createElement('div'); sc.className = 'tbl-scroll';
        sc.appendChild(makeTable(pukHist, charDef.label || 'Uraian', 'normal', null, { editable: true, code: charDef.srcPukHist }));
        adjWrap.appendChild(sc);
      }
      const tpakHist = computed[charDef.srcTpakHist];
      if (tpakHist && hasData(charDef.srcTpakHist)) {
        adjWrap.appendChild(makeCodeBadge(charDef.srcTpakHist, 'TPAK — Tingkat Partisipasi AK (%)'));
        const sc = document.createElement('div'); sc.className = 'tbl-scroll';
        sc.appendChild(makeTable(tpakHist, charDef.label || 'Uraian', 'normal', null, { editable: true, code: charDef.srcTpakHist }));
        adjWrap.appendChild(sc);
      }
      adjWrap.appendChild(makeCodeBadge(charDef.histCode, 'Angkatan Kerja'));
      adjWrap.appendChild(makeRumusNote('AK = PUK × TPAK / 100'));
      const sc2 = document.createElement('div'); sc2.className = 'tbl-scroll';
      sc2.appendChild(makeTable(hist, charDef.label || 'Uraian', 'normal'));
      adjWrap.appendChild(sc2);

    } else if (isTPT) {
      adjWrap.appendChild(makeCodeBadge(charDef.histCode, 'Penganggur Terbuka'));
      const sc1 = document.createElement('div'); sc1.className = 'tbl-scroll';
      sc1.appendChild(makeTable(hist, charDef.label || 'Uraian', 'normal'));
      adjWrap.appendChild(sc1);
      if (charDef.tptHistCode && hasData(charDef.tptHistCode)) {
        adjWrap.appendChild(makeCodeBadge(charDef.tptHistCode, 'Tingkat Pengangguran Terbuka (TPT)'));
        adjWrap.appendChild(makeRumusNote('TPT = Penganggur / Angkatan Kerja × 100'));
        const sc2 = document.createElement('div'); sc2.className = 'tbl-scroll';
        sc2.appendChild(makeTable(computed[charDef.tptHistCode], charDef.label || 'Uraian', 'normal'));
        adjWrap.appendChild(sc2);
      }

    } else if (isKK) {
      adjWrap.appendChild(makeCodeBadge(charDef.histCode, 'PYB / Orang Bekerja — Input Excel'));
      const sc = document.createElement('div'); sc.className = 'tbl-scroll';
      sc.appendChild(makeTable(hist, charDef.label || 'Uraian', 'normal', null, { editable: true, code: charDef.histCode }));
      adjWrap.appendChild(sc);

    } else {
      adjWrap.appendChild(makeCodeBadge(charDef.histCode, null));
      const sc = document.createElement('div'); sc.className = 'tbl-scroll';
      sc.appendChild(makeTable(hist, charDef.label || 'Uraian', 'normal', null, { editable: true, code: charDef.histCode }));
      adjWrap.appendChild(sc);
    }
    wrap.appendChild(adjWrap);
  }

  // ══ BLOK 2+3: HASIL PROYEKSI | PERUBAHAN ══
  const pcWrap = document.createElement('div'); pcWrap.className = 'proj-change-wrap';

  const projBlock = document.createElement('div'); projBlock.className = 'tbl-block tbl-proj';
  const projH = document.createElement('div'); projH.className = 'tbl-block-h1';
  projH.textContent = 'HASIL PROYEKSI'; projBlock.appendChild(projH);

  if (!noProj) {
    if (isAK) {
      if (charDef.srcPukProj && hasData(charDef.srcPukProj)) {
        projBlock.appendChild(makeCodeBadge(charDef.srcPukProj, 'Proyeksi PUK (Regresi Linear)'));
        const sc = document.createElement('div'); sc.className = 'tbl-scroll';
        sc.appendChild(makeTable(computed[charDef.srcPukProj], charDef.label || 'Uraian', 'normal'));
        projBlock.appendChild(sc);
      }
      if (charDef.srcTpakProj && hasData(charDef.srcTpakProj)) {
        projBlock.appendChild(makeCodeBadge(charDef.srcTpakProj, 'Proyeksi TPAK (Regresi Linear)'));
        const sc = document.createElement('div'); sc.className = 'tbl-scroll';
        sc.appendChild(makeTable(computed[charDef.srcTpakProj], charDef.label || 'Uraian', 'normal'));
        projBlock.appendChild(sc);
      }
      projBlock.appendChild(makeCodeBadge(charDef.projCode, 'Angkatan Kerja Proyeksi'));
      projBlock.appendChild(makeRumusNote('AK Proyeksi = PUK proj × TPAK proj / 100'));
      const sc2 = document.createElement('div'); sc2.className = 'tbl-scroll';
      const _akProjTbl = makeTable(proj, charDef.label || 'Uraian', 'normal');
      _addTargetRows(_akProjTbl, charDef.projCode, proj, projYears);
      sc2.appendChild(_akProjTbl);
      projBlock.appendChild(sc2);

    } else if (isTPT) {
      projBlock.appendChild(makeCodeBadge(charDef.projCode, 'Perkiraan Penganggur Terbuka'));
      const sc1 = document.createElement('div'); sc1.className = 'tbl-scroll';
      const _ptProjTbl = makeTable(proj, charDef.label || 'Uraian', 'normal');
      _addTargetRows(_ptProjTbl, charDef.projCode, proj, projYears);
      sc1.appendChild(_ptProjTbl);
      projBlock.appendChild(sc1);
      if (charDef.tptProjCode && hasData(charDef.tptProjCode)) {
        projBlock.appendChild(makeCodeBadge(charDef.tptProjCode, 'Perkiraan TPT'));
        projBlock.appendChild(makeRumusNote('TPT Proyeksi = Penganggur proj / AK proj × 100'));
        const sc2 = document.createElement('div'); sc2.className = 'tbl-scroll';
        const _tptProjData = computed[charDef.tptProjCode];
        const _tptProjTbl = makeTable(_tptProjData, charDef.label || 'Uraian', 'normal');
        _addTargetRows(_tptProjTbl, charDef.tptProjCode, _tptProjData, projYears);
        sc2.appendChild(_tptProjTbl);
        projBlock.appendChild(sc2);
      }

    } else if (isKK) {
      projBlock.appendChild(makeCodeBadge(charDef.projCode, 'Kesempatan Kerja (Regresi Linear dari PYB)'));
      projBlock.appendChild(makeRumusNote('KK = linProj(PYB historis)', charDef.projCode));
      const projTbl = makeTable(proj, charDef.label || 'Uraian', 'normal');
      if (hist && hist.rows && projYears.length) {
        const totH = hist.rows.find(r => r.label === 'Jumlah'), totP = proj.rows.find(r => r.label === 'Jumlah');
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
              const td = trS.insertCell(); const i = proj.years.indexOf(y); const v = i >= 0 ? totP.vals[i] : null;
              if (v !== null && tv !== null) {
                const d = v - tv; td.className = d < 0 ? 'num-neg' : 'num';
                td.textContent = fmtNum(d); td.style.cursor = 'pointer';
                td.onclick = function () { _showFullValue(this, d); };
              } else { td.textContent = '-'; td.style.color = '#bbb'; }
            });
          }
        }
      }
      // Target dari sheet Excel (melengkapi benchmark historis di atas)
      _addTargetRows(projTbl, charDef.projCode, proj, projYears);
      const sc = document.createElement('div'); sc.className = 'tbl-scroll'; sc.appendChild(projTbl); projBlock.appendChild(sc);

    } else {
      const projTbl = makeTable(proj, charDef.label || 'Uraian', 'normal');
      if (hist && hist.rows && projYears.length) {
        const totH = hist.rows.find(r => r.label === 'Jumlah'), totP = proj.rows.find(r => r.label === 'Jumlah');
        if (totH && totP) {
          const lhIdx = hist.years.indexOf(histYears[histYears.length - 1]);
          const tv = lhIdx >= 0 ? totH.vals[lhIdx] : null;
          if (tv !== null) {
            const tbody = projTbl.querySelector('tbody');
            const trT = tbody.insertRow(); trT.className = 'row-target';
            const t0 = trT.insertCell(); t0.className = 'col-label'; t0.textContent = 'Target (Provinsi)';
            projYears.forEach(() => {
              const td = trT.insertCell(); td.className = 'num';
              td.textContent = fmtNum(tv); td.style.cursor = 'pointer';
              td.onclick = function () { _showFullValue(this, tv); };
            });
            const trS = tbody.insertRow(); trS.className = 'row-selisih';
            const s0 = trS.insertCell(); s0.className = 'col-label'; s0.textContent = 'Selisih';
            projYears.forEach(y => {
              const td = trS.insertCell(); const i = proj.years.indexOf(y); const v = i >= 0 ? totP.vals[i] : null;
              if (v !== null && tv !== null) {
                const d = v - tv; td.className = d < 0 ? 'num-neg' : 'num';
                td.textContent = fmtNum(d); td.style.cursor = 'pointer';
                td.onclick = function () { _showFullValue(this, d); };
              } else { td.textContent = '-'; td.style.color = '#bbb'; }
            });
          }
        }
      }
      const sc = document.createElement('div'); sc.className = 'tbl-scroll'; sc.appendChild(projTbl); projBlock.appendChild(sc);
    }
  } else {
    projBlock.innerHTML += '<div class="no-data-msg">⬜ Data proyeksi belum tersedia.</div>';
  }
  pcWrap.appendChild(projBlock);

  if (!noProj && projYears.length > 1) {
    const chBlock = document.createElement('div'); chBlock.className = 'tbl-block tbl-change';
    const chH = document.createElement('div'); chH.className = 'tbl-block-h1'; chH.textContent = 'PERUBAHAN'; chBlock.appendChild(chH);
    
    function addDeltaHeader(title) {
      const d = document.createElement('div');
      d.style.cssText = 'margin:12px 0 6px; font-size:11.5px; font-weight:700; color:#4c1d95; letter-spacing:0.3px;';
      d.textContent = '∆ ' + title;
      chBlock.appendChild(d);
    }

    if (isTPT && charDef.tptProjCode) {
      if (charDef.projCode) addDeltaHeader('Selisih Penganggur Terbuka');
      const sc1 = document.createElement('div'); sc1.className = 'tbl-scroll';
      sc1.appendChild(makeTable(proj, charDef.label || 'Uraian', 'change', null, { histData: hist }));
      chBlock.appendChild(sc1);
      
      addDeltaHeader('Selisih TPT');
      const sc2 = document.createElement('div'); sc2.className = 'tbl-scroll';
      const histTpt = computed[charDef.tptHistCode];
      const projTpt = computed[charDef.tptProjCode];
      sc2.appendChild(makeTable(projTpt, charDef.label || 'Uraian', 'change', null, { histData: histTpt }));
      chBlock.appendChild(sc2);
      
    } else if (isAK && charDef.srcPukProj && charDef.srcTpakProj) {
      addDeltaHeader('Selisih PUK');
      const sc1 = document.createElement('div'); sc1.className = 'tbl-scroll';
      const histPuk = computed[charDef.srcPukHist || charDef.histCode];
      sc1.appendChild(makeTable(computed[charDef.srcPukProj], charDef.label || 'Uraian', 'change', null, { histData: histPuk }));
      chBlock.appendChild(sc1);

      addDeltaHeader('Selisih TPAK');
      const sc2 = document.createElement('div'); sc2.className = 'tbl-scroll';
      const histTpak = computed[charDef.srcTpakHist];
      sc2.appendChild(makeTable(computed[charDef.srcTpakProj], charDef.label || 'Uraian', 'change', null, { histData: histTpak }));
      chBlock.appendChild(sc2);

      addDeltaHeader('Selisih Angkatan Kerja');
      const sc3 = document.createElement('div'); sc3.className = 'tbl-scroll';
      const akSurfix = charDef.histCode.split('.')[2];
      const histAk = computed['2.E.' + akSurfix] || hist;
      sc3.appendChild(makeTable(proj, charDef.label || 'Uraian', 'change', null, { histData: histAk }));
      chBlock.appendChild(sc3);

    } else {
      if (charDef.projCode) addDeltaHeader('Selisih ' + (charDef.label || 'Proyeksi'));
      const sc2 = document.createElement('div'); sc2.className = 'tbl-scroll';
      sc2.appendChild(makeTable(proj, charDef.label || 'Uraian', 'change', null, { histData: hist }));
      chBlock.appendChild(sc2);
    }
    pcWrap.appendChild(chBlock);
  }
  wrap.appendChild(pcWrap);
  return wrap;
}
