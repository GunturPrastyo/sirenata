/* ══════════════════════════════════════════
   step3-main.js — RTK Makro v8
   Kerangka utama Step 3:
     syncSplitViewHeights(), buildStep3(),
     switchIndikator(), switchChar(), updateCards()
   Dispatcher render per indikator: IND_RENDERERS
   Panel renderer per indikator dipindah ke step3-indikator-*.js
   ══════════════════════════════════════════ */

// ══════════════════════════════════════════════════════════════
// HELPER GLOBAL — Tambah baris Target + Selisih dari P.targetImports
// ke tabel proyeksi (non-bimtek panels: AK, KK, PT, TPT).
//
// Didefinisikan sebagai variabel global (function expression) supaya
// step3-goal-seek-ui.js bisa meng-override-nya dengan pola decorator
// untuk menambahkan tombol 🎯 Goal-Seek di sel Selisih.
//
// tbl       : <table> DOM element hasil makeTable()
// projCode  : kode indikator proyeksi, misal '3.C.1', '5.A.2'
// proj      : objek { years, rows } dari computed[projCode]
// projYears : array tahun proyeksi yang ditampilkan
// ══════════════════════════════════════════════════════════════
var _addTargetRows = function (tbl, projCode, proj, projYears) {
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
};

// ══════════════════════════════════════════════════════════════
// DISPATCHER — memetakan indikator ID → fungsi render panel
// Dipanggil secara lazy saat tab karakteristik menjadi aktif
// pertama kali (lihat _ensureCharPanelRendered).
// ══════════════════════════════════════════════════════════════
const IND_RENDERERS = {
  puk:         (ch) => renderIndikatorPuk(ch),
  tpak:        (ch) => renderIndikatorTpak(ch),
  ak:          (ch) => renderIndikatorAk(ch),
  pdrb:        (ch) => renderIndikatorPdrb(ch),
  elastisitas: (ch) => renderIndikatorElastisitas(ch),
  kk:          (ch) => renderIndikatorKk(ch),
  pt:          (ch) => renderIndikatorPt(ch),
  tpt:         (ch) => renderIndikatorTpt(ch),
  pelengkap:   (ch) => renderIndikatorPelengkap(ch),
};

// ══════════════════════════════════════════════════════════════
// LAZY RENDER — Render isi char-panel hanya sekali, saat pertama
// dibutuhkan. Panel menyimpan referensi charDef & indId di
// dataset + flag _rendered di property. Setelah render, tidak
// akan render ulang (kecuali buildStep3 dipanggil ulang).
// ══════════════════════════════════════════════════════════════
function _ensureCharPanelRendered(panel) {
  if (!panel || panel._rendered) return;
  const indId = panel.dataset.indId;
  const charId = panel.dataset.charId;
  if (!indId) return;
  const ind = INDIKATOR_DEFS.find(i => i.id === indId);
  if (!ind) return;
  const ch = (ind.chars || []).find(c => c.id === charId) || (ind.chars ? ind.chars[0] : null);
  const renderer = IND_RENDERERS[indId];
  if (!renderer) return;
  try {
    panel.appendChild(renderer(ch));
  } catch (err) {
    console.error('[IND_RENDERERS] Gagal render panel', indId, charId, err);
    const e = document.createElement('div');
    e.className = 'no-data-msg';
    e.innerHTML = `⚠️ Gagal render panel <code>${indId}</code>/<code>${charId}</code>: ${err && err.message ? err.message : err}`;
    panel.appendChild(e);
  }
  panel._rendered = true;
}

// ══════════════════════════════════════════════════════════════
// SYNC TINGGI BARIS — Paksa semua baris tabel dalam split-view
// sejajar secara presisi. Dipanggil setiap tab char menjadi visible.
// ══════════════════════════════════════════════════════════════
function syncSplitViewHeights(scope) {
    const splitViews = (scope || document).querySelectorAll('.split-view');
    splitViews.forEach(sv => {
        // Ambil blok anak langsung (.split-view > div) — tiap blok = 1 tabel
        const blks = Array.from(sv.children);
        const tbls = blks.map(b => b.querySelector('.rtk-tbl')).filter(Boolean);
        if (tbls.length < 2) return;

        // ── 0. Reset semua inline height agar pengukuran bersih ──
        blks.forEach(b => { const hdr = b.querySelector('.tbl-block-h1'); if (hdr) hdr.style.height = ''; });
        tbls.forEach(tbl => {
            Array.from(tbl.querySelectorAll('thead th')).forEach(th => { th.style.height = ''; });
            Array.from(tbl.querySelectorAll('tbody td')).forEach(td => { td.style.height = ''; });
        });

        // ── 1. Samakan tinggi div judul tabel (makeFlexHeader) ──
        // Ini menentukan titik-Y awal <table> — harus identik antar blok
        let maxHdrH = 0;
        blks.forEach(b => {
            const hdr = b.querySelector('.tbl-block-h1');
            if (hdr) { const h = hdr.getBoundingClientRect().height; if (h > maxHdrH) maxHdrH = h; }
        });
        if (maxHdrH > 0) {
            blks.forEach(b => {
                const hdr = b.querySelector('.tbl-block-h1');
                if (hdr) hdr.style.height = maxHdrH + 'px';
            });
        }

        // ── 2. Sync th[rowspan="2"] — paksa semua sama tinggi ──
        const rowspanThs = sv.querySelectorAll('.rtk-tbl thead th[rowspan="2"]');
        let maxRsH = 0;
        rowspanThs.forEach(th => { const h = th.getBoundingClientRect().height; if (h > maxRsH) maxRsH = h; });
        if (maxRsH > 0) rowspanThs.forEach(th => { th.style.height = maxRsH + 'px'; });

        // ── 3. Sync baris thead ke-1 (non-rowspan cells di row pertama) ──
        let maxTh1H = 0;
        tbls.forEach(tbl => {
            const r1 = tbl.tHead && tbl.tHead.rows[0];
            if (r1) { const h = r1.getBoundingClientRect().height; if (h > maxTh1H) maxTh1H = h; }
        });
        if (maxTh1H > 0) {
            tbls.forEach(tbl => {
                const r1 = tbl.tHead && tbl.tHead.rows[0];
                if (r1) Array.from(r1.cells).forEach(c => { if (!c.rowSpan || c.rowSpan < 2) c.style.height = maxTh1H + 'px'; });
            });
        }

        // ── 4. Sync baris thead ke-2 (baris tahun) ──
        let maxTh2H = 0;
        tbls.forEach(tbl => {
            const r2 = tbl.tHead && tbl.tHead.rows[1];
            if (r2) { const h = r2.getBoundingClientRect().height; if (h > maxTh2H) maxTh2H = h; }
        });
        if (maxTh2H > 0) {
            tbls.forEach(tbl => {
                const r2 = tbl.tHead && tbl.tHead.rows[1];
                if (r2) Array.from(r2.cells).forEach(c => { c.style.height = maxTh2H + 'px'; });
            });
        }

        // ── 5. Sync setiap baris tbody ke-i lintas tabel ──
        const maxRows = Math.max(...tbls.map(t => t.tBodies[0] ? t.tBodies[0].rows.length : 0));
        for (let i = 0; i < maxRows; i++) {
            let maxRowH = 0;
            tbls.forEach(tbl => {
                const row = tbl.tBodies[0] && tbl.tBodies[0].rows[i];
                if (row) { const h = row.getBoundingClientRect().height; if (h > maxRowH) maxRowH = h; }
            });
            if (maxRowH > 0) {
                tbls.forEach(tbl => {
                    const row = tbl.tBodies[0] && tbl.tBodies[0].rows[i];
                    if (row) Array.from(row.cells).forEach(cell => { cell.style.height = maxRowH + 'px'; });
                });
            }
        }
    });
}

// ══════════════════════════════════════════════════════════════
// BUILD STEP 3 — Tab navbar + sub-tab + panel konten
// ══════════════════════════════════════════════════════════════
function buildStep3() {
  // --- MULAI PENYIMPANAN STATE SCROLL & TAB ---
  let activeIndId = null; let activeCharId = null;
  const cInd = document.querySelector('.ind-tab.active');
  if (cInd) {
    activeIndId = cInd.dataset.ind;
    // Scope ke panel indikator aktif agar tidak mengambil char-tab dari indikator lain
    const cCh = document.querySelector(`#ind-panel-${activeIndId} .char-tab.active`);
    if (cCh) activeCharId = cCh.dataset.char;
  }
  
  const sList = [];
  document.querySelectorAll('.split-view, .tbl-scroll, #s3-main').forEach(el => {
      sList.push({ L: el.scrollLeft, T: el.scrollTop });
  });
  // --- SELESAI PENYIMPANAN ---

  // Bersihkan popup rumus T4 yang mungkin tertinggal di body dari sesi sebelumnya
  document.querySelectorAll('.t4-formula-popup').forEach(el => el.remove());
  const area = document.getElementById('s3area'); area.innerHTML = '';

  // ── Layout wrapper: sidebar kiri + konten kanan ──
  const layout = document.createElement('div'); layout.id = 's3-layout'; area.appendChild(layout);
  layout.appendChild(buildSidebar());

  // ── Helper: simpan / muat preferensi UI layout ke localStorage ──
  const UI_PREFS_KEY = 'rtk_ui_prefs';
  const _loadUIPrefs  = () => { try { return JSON.parse(localStorage.getItem(UI_PREFS_KEY) || '{}'); } catch(e) { return {}; } };
  const _saveUIPrefs  = (patch) => {
    try {
      const prefs = _loadUIPrefs();
      Object.assign(prefs, patch);
      localStorage.setItem(UI_PREFS_KEY, JSON.stringify(prefs));
    } catch(e) { /* silent */ }
  };

  // ── Toggle sidebar button (dikaitkan ke layout setelah buildSidebar) ──
  const toggleBtn = document.createElement('button');
  toggleBtn.id   = 'sb-toggle';
  toggleBtn.className = 'sb-toggle-btn';
  toggleBtn.innerHTML = '&#8249;'; // ‹
  toggleBtn.title = 'Sembunyikan / Tampilkan Sidebar';
  toggleBtn.onclick = () => {
    layout.classList.toggle('sb-collapsed');
    const isCollapsed = layout.classList.contains('sb-collapsed');
    toggleBtn.innerHTML = isCollapsed ? '&#8250;' : '&#8249;'; // › atau ‹
    _saveUIPrefs({ sidebarCollapsed: isCollapsed });
  };
  layout.appendChild(toggleBtn);

  // ── Toggle fullwidth button ──
  const fwBtn = document.createElement('button');
  fwBtn.id = 'fw-toggle';
  fwBtn.className = 'fw-toggle-btn';
  fwBtn.innerHTML = '⛶';
  fwBtn.title = 'Perluas / Kembalikan Lebar Layar';
  fwBtn.onclick = () => {
    const wrap = document.querySelector('.app-wrap');
    if (wrap) wrap.classList.toggle('fullwidth');
    const isFullwidth = !!(wrap && wrap.classList.contains('fullwidth'));
    fwBtn.classList.toggle('active', isFullwidth);
    _saveUIPrefs({ fullwidth: isFullwidth });
  };
  area.insertBefore(fwBtn, area.firstChild);

  // ── Pulihkan preferensi UI dari localStorage ──
  const _uiPrefs = _loadUIPrefs();
  if (_uiPrefs.sidebarCollapsed) {
    layout.classList.add('sb-collapsed');
    toggleBtn.innerHTML = '&#8250;'; // ›
  }
  if (_uiPrefs.fullwidth) {
    const wrap = document.querySelector('.app-wrap');
    if (wrap) wrap.classList.add('fullwidth');
    fwBtn.classList.add('active');
  }

  const main = document.createElement('div'); main.id = 's3-main'; layout.appendChild(main);

  // Tab indikator atas (masuk ke #s3-main)
  const indTabsEl = document.createElement('div'); indTabsEl.className = 'ind-tabs'; main.appendChild(indTabsEl);

  // Container panel dengan border (masuk ke #s3-main)
  const indContent = document.createElement('div'); indContent.className = 'ind-panels'; main.appendChild(indContent);

  INDIKATOR_DEFS.forEach((ind, ii) => {
    // Tab button
    const tab = document.createElement('div'); tab.className = 'ind-tab' + (ii === 0 ? ' active' : '');
    tab.dataset.ind = ind.id; tab.textContent = ind.label;
    tab.onclick = () => switchIndikator(ind.id); indTabsEl.appendChild(tab);

    // Panel indikator
    const panel = document.createElement('div'); panel.className = 'ind-panel' + (ii === 0 ? ' active' : '');
    panel.id = 'ind-panel-' + ind.id;

    // Judul dalam panel
    const ptitle = document.createElement('div'); ptitle.className = 'ind-panel-title';
    ptitle.textContent = ind.title; panel.appendChild(ptitle);

    if (ind.pdrbMode) {
      // PDRB: tidak ada sub-tab, langsung render via dispatcher (lazy).
      // Kerangka panel tetap dibuat di sini, konten isi panel di-render
      // oleh _ensureCharPanelRendered saat indikator menjadi aktif.
      const cpanel = document.createElement('div');
      cpanel.className = 'char-panel active';
      cpanel.id = `char-panel-${ind.id}-${(ind.chars && ind.chars[0] ? ind.chars[0].id : 'main')}`;
      cpanel.dataset.indId = ind.id;
      cpanel.dataset.charId = (ind.chars && ind.chars[0] ? ind.chars[0].id : 'main');
      panel.appendChild(cpanel);
    } else if (ind.elastisitasMode) {
      // Elastisitas: sub-tab tunggal LAPANGAN USAHA (kerangka), render lazy.
      const charTabsEl = document.createElement('div'); charTabsEl.className = 'char-tabs'; panel.appendChild(charTabsEl);
      const ct = document.createElement('div'); ct.className = 'char-tab active';
      ct.textContent = 'LAPANGAN USAHA'; charTabsEl.appendChild(ct);
      const charContent = document.createElement('div'); charContent.id = 'char-content-' + ind.id; panel.appendChild(charContent);
      const cpanel = document.createElement('div');
      cpanel.className = 'char-panel active';
      cpanel.id = `char-panel-${ind.id}-${(ind.chars && ind.chars[0] ? ind.chars[0].id : 'lu')}`;
      cpanel.dataset.indId = ind.id;
      cpanel.dataset.charId = (ind.chars && ind.chars[0] ? ind.chars[0].id : 'lu');
      charContent.appendChild(cpanel);
    } else {
      // Semua indikator standar (termasuk pelengkap): sub-tab per karakteristik.
      // Semua panel char dibuat kerangkanya di sini, tapi render konten
      // dilakukan lazy oleh _ensureCharPanelRendered saat tab diaktifkan.
      const charTabsEl = document.createElement('div'); charTabsEl.className = 'char-tabs'; panel.appendChild(charTabsEl);
      const charContent = document.createElement('div'); charContent.id = 'char-content-' + ind.id; panel.appendChild(charContent);

      ind.chars.forEach((ch, ci) => {
        // Sub-tab
        const ctab = document.createElement('div'); ctab.className = 'char-tab' + (ci === 0 ? ' active' : '');
        ctab.dataset.char = ch.id; ctab.dataset.ind = ind.id; ctab.textContent = ch.label;
        const chHasData = hasData(ch.histCode) || hasData(ch.projCode);
        if (!chHasData) ctab.style.opacity = '0.4';
        ctab.onclick = () => switchChar(ind.id, ch.id); charTabsEl.appendChild(ctab);

        // Panel karakteristik (kerangka kosong, isi di-render lazy)
        const cpanel = document.createElement('div'); cpanel.className = 'char-panel' + (ci === 0 ? ' active' : '');
        cpanel.id = `char-panel-${ind.id}-${ch.id}`;
        cpanel.dataset.indId = ind.id;
        cpanel.dataset.charId = ch.id;
        charContent.appendChild(cpanel);
      });
    }

    indContent.appendChild(panel);
  });

  // Set cards
  updateCards();
  _updateUndoRedoUI();

  // --- RENDER AWAL LAZY: panel yang aktif sekarang (indikator pertama + char pertama) ---
  // Semua panel lain dibiarkan kosong sampai tab-nya diklik.
  const firstActivePanel = document.querySelector('.ind-panel.active .char-panel.active');
  if (firstActivePanel) _ensureCharPanelRendered(firstActivePanel);

  // --- RESTORASI STATE SCROLL & TAB ---
  if (activeIndId) {
      switchIndikator(activeIndId);
      if (activeCharId) switchChar(activeIndId, activeCharId);
  }
  setTimeout(() => {
      document.querySelectorAll('.split-view, .tbl-scroll, #s3-main').forEach((el, index) => {
          if (sList[index]) {
             if (sList[index].L > 0) el.scrollLeft = sList[index].L;
             if (sList[index].T > 0) el.scrollTop = sList[index].T;
          }
      });
  }, 10);
  // --- SELESAI RESTORASI ---

  // Sync hanya panel yang benar-benar visible saat load pertama
  // (selector .ind-panel.active .char-panel.active → pastikan parent juga aktif)
  requestAnimationFrame(() => {
    const visiblePanel = document.querySelector('.ind-panel.active .char-panel.active');
    if (visiblePanel) syncSplitViewHeights(visiblePanel);
  });
}

function switchIndikator(indId) {
  document.querySelectorAll('.ind-tab').forEach(t => t.classList.toggle('active', t.dataset.ind === indId));
  document.querySelectorAll('.ind-panel').forEach(p => p.classList.toggle('active', p.id === 'ind-panel-' + indId));
  // Lazy render: pastikan panel char aktif di indikator ini sudah ter-render
  const activeCharPanel = document.querySelector(`#ind-panel-${indId} .char-panel.active`);
  if (activeCharPanel) _ensureCharPanelRendered(activeCharPanel);
  // Sync char panel pertama yang aktif di indikator ini setelah visible
  requestAnimationFrame(() => {
    const visiblePanel = document.querySelector(`#ind-panel-${indId} .char-panel.active`);
    if (visiblePanel) syncSplitViewHeights(visiblePanel);
  });
}

function switchChar(indId, charId) {
  document.querySelectorAll(`.char-tab[data-ind="${indId}"]`).forEach(t => t.classList.toggle('active', t.dataset.char === charId));
  document.querySelectorAll(`[id^="char-panel-${indId}-"]`).forEach(p => p.classList.toggle('active', p.id === `char-panel-${indId}-${charId}`));
  if (typeof highlightSbChar === 'function') highlightSbChar(indId, charId);
  // Lazy render: render panel char saat pertama kali diaktifkan
  const panel = document.getElementById(`char-panel-${indId}-${charId}`);
  if (panel) _ensureCharPanelRendered(panel);
  // Jalankan sync SETELAH panel visible (display:block) agar getBoundingClientRect akurat
  requestAnimationFrame(() => {
    if (panel) syncSplitViewHeights(panel);
  });
}

function updateCards() {
  // Highlight awal sidebar jika belum ada yang aktif
  if (!document.querySelector('#s3-sidebar .sb-char-item.active')) {
    setTimeout(() => highlightSbChar('puk', 'umur'), 0);
  }
}

// ══════════════════════════════════════════════════════════════
// DEFINISI 77 TABEL — BAB TREE untuk Tab 2 sidebar
// Dikelompokkan sesuai sistematika dokumen RTK
// ══════════════════════════════════════════════════════════════
