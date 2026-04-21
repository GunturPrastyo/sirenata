/* ══════════════════════════════════════════
   step3-edit-manager.js — RTK Makro v8
   Fungsi utilitas & edit inline:
     fmtNum(), hasData(), onCellEdit(),
     undo/redo, onLajuEdit(), onTargetEdit()
   Dipecah dari step3-ui-components.js
   ══════════════════════════════════════════ */

// ── Kode tabel yang nilainya dalam satuan persen ──
const PERCENT_CODES = new Set([
  '2.A.1', '4.A.1',                            // Laju Pertumbuhan PDRB (%)
  '2.A.2', '4.A.2',                            // Struktur PDRB (%)
  '2.D.1', '2.D.2', '2.D.3', '2.D.4',          // TPAK historis (%)
  '3.B.1', '3.B.2', '3.B.3', '3.B.4',          // TPAK proyeksi (%)
  '2.G.2', '2.G.4', '2.G.6', '2.G.8',          // TPT historis (%)
  '5.A.2', '5.B.2', '5.C.2', '5.D.2',          // TPT proyeksi (%)
]);

/** Kembalikan true jika kode tabel ini nilainya dalam persen */
function isPercentCode(code) { return !!(code && PERCENT_CODES.has(code)); }

/**
 * Format angka untuk tampilan tabel.
 *   isPercent = false → tanpa desimal (ribuan, jutaan, dsb.)
 *   isPercent = true  → maks 2 desimal (nilai persen)
 */
function fmtNum(v, isPercent = false) {
  if (v === null || v === undefined || v === '') return '-';
  if (typeof v === 'number') {
    if (isPercent) return v.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    return v.toLocaleString('id-ID', { maximumFractionDigits: 0 });
  }
  return String(v);
}

/** Shortcut: format angka persen (maks 2 desimal) */
function fmtPct(v) { return fmtNum(v, true); }

// ══════════════════════════════════════════════════════════════
// POPUP NILAI PENUH — tampil saat sel non-editable diklik
// (Aturan 2: semua sel menampilkan nilai lengkap saat diklik)
// ══════════════════════════════════════════════════════════════
function _showFullValue(td, rawValue) {
  // Bersihkan popup sebelumnya
  document.querySelectorAll('.cell-value-popup').forEach(el => el.remove());
  if (rawValue === null || rawValue === undefined || rawValue === '') return;

  // Format dengan presisi penuh (semua digit desimal yang ada)
  const fullStr = typeof rawValue === 'number'
    ? rawValue.toLocaleString('id-ID', { maximumSignificantDigits: 15 })
    : String(rawValue);

  const popup = document.createElement('div');
  popup.className = 'cell-value-popup';
  popup.textContent = '= ' + fullStr;

  // Posisi: di bawah sel yang diklik (fixed agar tidak terpengaruh scroll)
  const rect = td.getBoundingClientRect();
  popup.style.left = Math.max(4, Math.min(rect.left, window.innerWidth - 180)) + 'px';
  popup.style.top  = (rect.bottom + 4) + 'px';

  document.body.appendChild(popup);

  // Tutup saat klik di tempat lain, atau otomatis setelah 3 detik
  const dismiss = () => popup.remove();
  setTimeout(() => document.addEventListener('click', dismiss, { once: true, capture: true }), 80);
  setTimeout(dismiss, 3000);
}

function hasData(code) {
  const t = computed[code]; if (!t) return false;
  if (t.tableType === 'crossTab') return t.rows && t.rows.length > 0;
  if (t.tableType === 'multiHeader' || t.tableType === 'yearMatrix') return t.data && t.data.length > 0;
  return t.rows && t.rows.length > 0 && t.rows.some(r => r.vals && r.vals.some(v => v !== null));
}

// ══════════════════════════════════════════════════════════════
// EDIT INLINE — update rawSheets lalu recompute & re-render
// Dengan dukungan Undo / Redo
// ══════════════════════════════════════════════════════════════

// Helper: terapkan nilai ke rawSheets dan recalc baris Jumlah
function _applyRawEdit(code, rowLabel, yearIdx, val) {
  const sh = rawSheets[code];
  if (!sh || sh.tableType !== 'normal') return;
  const row = sh.rows.find(r => r.label === rowLabel);
  if (!row || yearIdx < 0 || yearIdx >= row.vals.length) return;
  row.vals[yearIdx] = val;
  // Hitung ulang baris "Jumlah" / "Total" (keduanya diperlakukan sebagai baris ringkasan)
  const SKIP = new Set(['Jumlah', 'JUMLAH', 'Total', 'TOTAL', 'TPAK', 'TPT']);
  const jmlRow = sh.rows.find(r => SKIP.has(r.label) && (r.label === 'Jumlah' || r.label === 'JUMLAH' || r.label === 'Total' || r.label === 'TOTAL'));
  if (jmlRow) {
    sh.years.forEach((_, yi) => {
      jmlRow.vals[yi] = sh.rows
        .filter(r => !SKIP.has(r.label))
        .reduce((s, r) => s + (r.vals[yi] || 0), 0);
    });
  }
}

// Helper: simpan & pulihkan posisi tab aktif di Step 3
function _saveTabState() {
  const ap = document.querySelector('.ind-panel.active');
  // Scope ke dalam panel indikator aktif agar tidak mengambil char-panel
  // dari indikator lain yang kebetulan juga punya .char-panel.active
  const ac = ap ? ap.querySelector('.char-panel.active') : null;
  const sbAct = document.querySelector('#s3-sidebar .sb-char-item.active');
  return {
    indId: ap ? ap.id.replace('ind-panel-', '') : null,
    charPanelId: ac ? ac.id : null,
    sbIndId: sbAct ? sbAct.dataset.ind : null,
    sbCharId: sbAct ? sbAct.dataset.char : null
  };
}

function _restoreTabState(state) {
  if (state.indId) switchIndikator(state.indId);
  if (state.charPanelId) {
    const parts = state.charPanelId.replace('char-panel-', '').split('-');
    if (parts.length >= 2) switchChar(parts[0], parts.slice(1).join('-'));
  }
  // Pulihkan highlight sidebar
  if (state.sbIndId && state.sbCharId) {
    setTimeout(() => highlightSbChar(state.sbIndId, state.sbCharId), 0);
  }
}


function onCellEdit(code, rowLabel, yearIdx, newVal) {
  const sh = rawSheets[code];
  if (!sh || sh.tableType !== 'normal') return;
  const row = sh.rows.find(r => r.label === rowLabel);
  if (!row || yearIdx < 0 || yearIdx >= row.vals.length) return;

  const oldVal = row.vals[yearIdx];
  if (newVal === oldVal) return;

  // Simpan posisi tab aktif sebelum rebuild
  const tabState = _saveTabState();

  // Push ke undo stack, kosongkan redo
  undoStack.push({ code, rowLabel, yearIdx, oldVal, newVal });
  redoStack = [];
  editedCells[code + '|' + rowLabel + '|' + yearIdx] = true;

  _applyRawEdit(code, rowLabel, yearIdx, newVal);
  buildComputed();
  buildStep3();
  // Pulihkan tab ke posisi semula
  _restoreTabState(tabState);
  saveStateToLS();
  _updateUndoRedoUI();
}

function _undoRedoEntry(entry, isUndo) {
  const v = isUndo ? entry.oldVal : entry.newVal;
  if (entry.editType === 'laju') {
      if (v === undefined || v === null) {
          if (P.lajuOverrides && P.lajuOverrides[entry.code] && P.lajuOverrides[entry.code][entry.rowLabel]) {
              delete P.lajuOverrides[entry.code][entry.rowLabel][entry.year];
          }
      } else {
          if (!P.lajuOverrides) P.lajuOverrides = {};
          if (!P.lajuOverrides[entry.code]) P.lajuOverrides[entry.code] = {};
          if (!P.lajuOverrides[entry.code][entry.rowLabel]) P.lajuOverrides[entry.code][entry.rowLabel] = {};
          P.lajuOverrides[entry.code][entry.rowLabel][entry.year] = v;
      }
  } else if (entry.editType === 'target') {
      if (v === undefined || v === null) {
          if (P.targets && P.targets[entry.code]) {
              delete P.targets[entry.code][entry.year];
          }
      } else {
          if (!P.targets) P.targets = {};
          if (!P.targets[entry.code]) P.targets[entry.code] = {};
          P.targets[entry.code][entry.year] = v;
      }
  } else {
      if (isUndo) delete editedCells[entry.code + '|' + entry.rowLabel + '|' + entry.yearIdx];
      else editedCells[entry.code + '|' + entry.rowLabel + '|' + entry.yearIdx] = true;
      _applyRawEdit(entry.code, entry.rowLabel, entry.yearIdx, v);
  }
}

function undoEdit() {
  if (!undoStack.length) return;
  const entry = undoStack.pop();
  redoStack.push(entry);
  const tabState = _saveTabState();
  _undoRedoEntry(entry, true);
  buildComputed(); buildStep3(); _restoreTabState(tabState); saveStateToLS(); _updateUndoRedoUI();
}

function redoEdit() {
  if (!redoStack.length) return;
  const entry = redoStack.pop();
  undoStack.push(entry);
  const tabState = _saveTabState();
  _undoRedoEntry(entry, false);
  buildComputed(); buildStep3(); _restoreTabState(tabState); saveStateToLS(); _updateUndoRedoUI();
}

function onLajuEdit(code, rowLabel, year, oldVal, newVal) {
  if (oldVal === newVal) return;
  const tabState = _saveTabState();
  undoStack.push({ editType: 'laju', code, rowLabel, year, oldVal, newVal });
  redoStack = [];
  _undoRedoEntry(undoStack[undoStack.length-1], false);
  buildComputed(); buildStep3(); _restoreTabState(tabState); saveStateToLS(); _updateUndoRedoUI();
}

function onTargetEdit(code, year, oldVal, newVal) {
  if (oldVal === newVal) return;
  const tabState = _saveTabState();
  undoStack.push({ editType: 'target', code, year, oldVal, newVal });
  redoStack = [];
  _undoRedoEntry(undoStack[undoStack.length-1], false);
  buildComputed(); buildStep3(); _restoreTabState(tabState); saveStateToLS(); _updateUndoRedoUI();
}

/**
 * Terapkan banyak perubahan laju sekaligus (batch paste dari Excel).
 * Hanya memanggil buildComputed() + buildStep3() SATU KALI di akhir,
 * sehingga tidak ada render berulang per sel.
 *
 * edits = [{ code, rowLabel, year, oldVal, newVal }, ...]
 */
function applyBatchLajuEdits(edits) {
  if (!edits || !edits.length) return;
  const tabState = _saveTabState();

  edits.forEach(e => {
    // Terapkan override ke P.lajuOverrides
    if (!P.lajuOverrides) P.lajuOverrides = {};
    if (!P.lajuOverrides[e.code]) P.lajuOverrides[e.code] = {};
    if (!P.lajuOverrides[e.code][e.rowLabel]) P.lajuOverrides[e.code][e.rowLabel] = {};
    P.lajuOverrides[e.code][e.rowLabel][e.year] = e.newVal;

    // Setiap sel masuk undo stack sendiri → user bisa undo satu per satu
    undoStack.push({
      editType: 'laju',
      code: e.code, rowLabel: e.rowLabel, year: e.year,
      oldVal: e.oldVal, newVal: e.newVal
    });
  });
  redoStack = [];

  buildComputed();
  buildStep3();
  _restoreTabState(tabState);
  saveStateToLS();
  _updateUndoRedoUI();
}

function _updateUndoRedoUI() {
  const toolbar = document.getElementById('editToolbar');
  const btnU = document.getElementById('btnUndo');
  const btnR = document.getElementById('btnRedo');
  const badge = document.getElementById('editCount');
  if (toolbar) toolbar.style.display = 'flex';
  if (btnU) btnU.disabled = !undoStack.length;
  if (btnR) btnR.disabled = !redoStack.length;
  if (badge) {
    const n = Object.keys(editedCells).length;
    badge.textContent = n ? n + ' edit' : '';
    badge.style.display = n ? 'inline-block' : 'none';
  }
}
