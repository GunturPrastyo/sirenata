/* ══════════════════════════════════════════
   step1-template.js — RTK Makro v8
   Generator Template Excel kosong untuk input data RTK Makro.
   Digunakan via tab "Unduh Template" di Langkah 1.

   Acuan daftar sheet: EXCEL_INPUT_SHEETS (step4-excel-sheets.js)
   Format sheet identik dengan Step 4 — hanya isinya kosong.

   Bergantung pada (dimuat sebelumnya di index.html):
     tpl-wilayah.js        → PROVINSI_DATA, KABKOTA_DATA, WILAYAH, loadWilayah()
     tpl-schema.js         → TABLE_SCHEMA
     tpl-tahun.js          → TAHUN_SCHEMA
     step4-excel-sheets.js → EXCEL_INPUT_SHEETS, THIN_BORDER, FONT_*, AL_*,
                             _styleCell, _styleHdr, _styleLink, _addBackRow,
                             buildDaftarIsi, _TARGET_INDK
     ExcelJS CDN           → ExcelJS
     FileSaver CDN         → saveAs
   ══════════════════════════════════════════ */

// ══════════════════════════════════════════════════════════════
// TAB SWITCHER LANGKAH 1
// ══════════════════════════════════════════════════════════════
function switchS1Tab(tab) {
    ['upload', 'template'].forEach(t => {
        document.getElementById('s1-panel-' + t).style.display = t === tab ? '' : 'none';
        document.getElementById('s1tab-' + t).classList.toggle('active', t === tab);
    });
}

// ── Isi dropdown provinsi ──
function initTplProvinsi() {
    const sel = document.getElementById('tplProv');
    if (!sel) return;
    sel.innerHTML = '<option value="" disabled selected>— Pilih Provinsi —</option>';
    Object.entries(window.PROVINSI_DATA || {}).forEach(([kode, nama]) => {
        const o = document.createElement('option');
        o.value  = kode;
        o.textContent = nama;
        sel.appendChild(o);
    });
}

// ══════════════════════════════════════════════════════════════
// HELPER LOKAL
// ══════════════════════════════════════════════════════════════

// Buat array string tahun dari a ke b (inklusif)
function _tplMakeYears(a, b) {
    const arr = [];
    for (let y = a; y <= b; y++) arr.push(String(y));
    return arr;
}

// Expand token "KABKOT" → daftar nama kabupaten/kota terpilih
function _tplExpandRows(rows) {
    const result = [];
    rows.forEach(r => {
        if (r === 'KABKOT') {
            (window.WILAYAH || []).forEach(kab => result.push(kab));
        } else {
            result.push(r);
        }
    });
    return result;
}

// ══════════════════════════════════════════════════════════════
// BUILDER: TARGET SHEET KOSONG (format = buildTargetSheet Step 4)
// ══════════════════════════════════════════════════════════════
function _buildTplTargetSheet(wb, projYears) {
    const ws = wb.addWorksheet('Target');

    ws.columns = [
        { width: 48 },
        ...projYears.map(() => ({ width: 14 })),
    ];

    _addBackRow(ws, 'Target Indikator Proyeksi', projYears.length);

    const hRow = ws.addRow(['Indikator', ...projYears.map(String)]);
    hRow.height = 18;
    hRow.eachCell(cell => _styleHdr(cell));

    _TARGET_INDK.forEach(ind => {
        const dr = ws.addRow([ind.label, ...projYears.map(() => '')]);
        dr.height = 16;
        _styleCell(dr.getCell(1), FONT_NORMAL, AL_LEFT, THIN_BORDER);
        for (let c = 2; c <= projYears.length + 1; c++)
            _styleCell(dr.getCell(c), FONT_NORMAL, AL_RIGHT, THIN_BORDER);
    });

    return ws;
}

// ══════════════════════════════════════════════════════════════
// BUILDER: DATA SHEET KOSONG
// Format identik dengan buildNormalSheet/buildMultiHdrSheet/buildYearMtxSheet Step 4
// Dipanggil oleh generateTemplate() (Step 1) dan downloadExcel() (Step 4)
// saat sheet tidak ada di rawSheets.
// ══════════════════════════════════════════════════════════════
function _buildTplDataSheet(wb, sheetId, name, schema, histYears, projYears) {
    const ws = wb.addWorksheet(sheetId);

    // ── yearMatrix ───────────────────────────────────────────
    if (schema.yearMatrix) {
        const headerRow = schema.yearMatrix[0];
        const catCols   = headerRow.slice(1);

        ws.columns = [
            { width: 12 },
            ...catCols.map(() => ({ width: 20 })),
        ];

        _addBackRow(ws, name, catCols.length);

        const hRow = ws.addRow(headerRow);
        hRow.height = 18;
        hRow.eachCell(cell => _styleHdr(cell));

        const years = TAHUN_SCHEMA.HIST.includes(sheetId) ? histYears
            : TAHUN_SCHEMA.PROJ.includes(sheetId)         ? projYears
            : (schema.rows || []);

        years.forEach(y => {
            const dr = ws.addRow([y, ...catCols.map(() => '')]);
            dr.height = 16;
            _styleCell(dr.getCell(1), FONT_NORMAL, AL_CENTER, THIN_BORDER);
            for (let c = 2; c <= catCols.length + 1; c++)
                _styleCell(dr.getCell(c), FONT_NORMAL, AL_RIGHT, THIN_BORDER);
        });

    // ── multiHeader ─────────────────────────────────────────
    } else if (schema.multiHeader) {
        const hdr0     = schema.multiHeader[0];
        const hdr1     = schema.multiHeader[1];
        const dataCols = hdr0.length - 1;

        ws.columns = [
            { width: 12 },
            ...Array(dataCols).fill(null).map(() => ({ width: 18 })),
        ];

        _addBackRow(ws, name, dataCols);

        [hdr0, hdr1].forEach(hdr => {
            const hr = ws.addRow(hdr);
            hr.height = 18;
            hr.eachCell(cell => _styleHdr(cell));
        });

        const years = TAHUN_SCHEMA.HIST.includes(sheetId) ? histYears
            : TAHUN_SCHEMA.PROJ.includes(sheetId)         ? projYears
            : (schema.rows || []);

        years.forEach(y => {
            const dr = ws.addRow([y, ...Array(dataCols).fill('')]);
            dr.height = 16;
            _styleCell(dr.getCell(1), FONT_NORMAL, AL_CENTER, THIN_BORDER);
            for (let c = 2; c <= dataCols + 1; c++)
                _styleCell(dr.getCell(c), FONT_NORMAL, AL_RIGHT, THIN_BORDER);
        });

    // ── normal ───────────────────────────────────────────────
    } else {
        let columns = [...schema.columns];
        if (TAHUN_SCHEMA.HIST.includes(sheetId)) {
            columns = [columns[0], ...histYears];
        } else if (TAHUN_SCHEMA.PROJ.includes(sheetId)) {
            columns = [columns[0], ...projYears];
        }
        const dataCols = columns.length - 1;

        ws.columns = [
            { width: 68 },
            ...Array(dataCols).fill(null).map(() => ({ width: 10 })),
        ];

        _addBackRow(ws, name, dataCols);

        const hRow = ws.addRow(columns);
        hRow.height = 18;
        hRow.eachCell(cell => _styleHdr(cell));

        let rows = [];
        if (Array.isArray(schema.rows)) {
            rows = _tplExpandRows(schema.rows);
        } else if (schema.rows === 'KABKOT') {
            rows = (window.WILAYAH || []).slice();
        }
        rows.forEach(r => {
            const dr = ws.addRow([r, ...Array(dataCols).fill('')]);
            dr.height = 16;
            _styleCell(dr.getCell(1), FONT_NORMAL, AL_LEFT,  THIN_BORDER);
            for (let c = 2; c <= dataCols + 1; c++)
                _styleCell(dr.getCell(c), FONT_NORMAL, AL_RIGHT, THIN_BORDER);
        });
    }

    return ws;
}

// ══════════════════════════════════════════════════════════════
// FUNGSI UTAMA: generateTemplate()
// Acuan daftar sheet: EXCEL_INPUT_SHEETS (step4-excel-sheets.js)
// ══════════════════════════════════════════════════════════════
async function generateTemplate() {
    const stEl = document.getElementById('stTpl');
    stEl.innerHTML = '<div class="msg msg-info">⏳ Membuat template Excel...</div>';
    await new Promise(r => setTimeout(r, 80));

    try {
        if (typeof ExcelJS === 'undefined')
            throw new Error('Library ExcelJS belum dimuat. Periksa koneksi internet.');

        const provKode = document.getElementById('tplProv').value;
        if (!provKode) {
            stEl.innerHTML = '<div class="msg msg-warn">⚠ Pilih provinsi terlebih dahulu.</div>';
            return;
        }

        const histStart = parseInt(document.getElementById('tplHA').value);
        const histEnd   = parseInt(document.getElementById('tplHZ').value);
        const projStart = parseInt(document.getElementById('tplPA').value);
        const projEnd   = parseInt(document.getElementById('tplPZ').value);

        if (isNaN(histStart) || isNaN(histEnd) || histStart > histEnd) {
            stEl.innerHTML = '<div class="msg msg-warn">⚠ Rentang tahun historis tidak valid.</div>';
            return;
        }
        if (isNaN(projStart) || isNaN(projEnd) || projStart > projEnd) {
            stEl.innerHTML = '<div class="msg msg-warn">⚠ Rentang tahun proyeksi tidak valid.</div>';
            return;
        }

        await loadWilayah(provKode);

        const histYears = _tplMakeYears(histStart, histEnd);
        const projYears = _tplMakeYears(projStart, projEnd);

        const wb = new ExcelJS.Workbook();
        wb.creator = 'RTK Makro v8 — Template Generator';
        wb.created = new Date();

        // Daftar sheet = EXCEL_INPUT_SHEETS (acuan tunggal, sama dengan Step 4)
        // 'Target' tidak masuk sini — ditambahkan otomatis oleh buildDaftarIsi()
        const includedSheets = EXCEL_INPUT_SHEETS.map(def => ({ code: def.code, name: def.name }));

        // 1. Daftar Isi (format identik Step 4)
        buildDaftarIsi(wb, includedSheets);

        // 2. Sheet Target kosong — kolom = projStart s/d projEnd
        const targetYears = _tplMakeYears(projStart, projEnd);
        _buildTplTargetSheet(wb, targetYears);

        // 3. Sheet data input kosong (format identik buildNormalSheet/dll Step 4)
        let sheetCount = 0;
        EXCEL_INPUT_SHEETS.forEach(def => {
            const schema = TABLE_SCHEMA[def.code];
            if (!schema) {
                const ws = wb.addWorksheet(def.code);
                _addBackRow(ws, def.name, 0);
                const nr = ws.addRow(['Schema belum tersedia untuk sheet ini']);
                _styleCell(nr.getCell(1), FONT_NORMAL, AL_LEFT, THIN_BORDER);
                return;
            }
            _buildTplDataSheet(wb, def.code, def.name, schema, histYears, projYears);
            sheetCount++;
        });

        // Download
        const buf = await wb.xlsx.writeBuffer();
        const blob = new Blob([buf], {
            type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        });

        const provNama = (window.PROVINSI_DATA[provKode] || 'Daerah')
            .replace(/[^a-zA-Z0-9\s]/g, '')
            .replace(/\s+/g, '_');
        const fname = `Template_RTK_Makro_${provNama}.xlsx`;
        saveAs(blob, fname);

        stEl.innerHTML = `<div class="msg msg-ok">✅ Template berhasil diunduh: <strong>${fname}</strong> — ${sheetCount} sheet INPUT + Sheet Target + Daftar Isi</div>`;

    } catch (err) {
        stEl.innerHTML = `<div class="msg msg-err">❌ Error: ${err.message}</div>`;
        console.error(err);
    }
}
