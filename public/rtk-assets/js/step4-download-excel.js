/* ══════════════════════════════════════════
   step4-download-excel.js — RTK Makro v8
   Fungsi utama downloadExcel()

   Acuan daftar sheet: EXCEL_INPUT_SHEETS (step4-excel-sheets.js)
   → identik dengan Step 1 template.
   Tiap sheet SELALU dibuat:
     • Ada di rawSheets → isi dengan data (buildNormalSheet / dll)
     • Tidak ada        → isi kosong sesuai schema (‌_buildTplDataSheet)
   ══════════════════════════════════════════ */

async function downloadExcel() {
    const st = document.getElementById('stDl');
    st.innerHTML = '<div class="msg msg-info">⏳ Membuat file Excel...</div>';
    await new Promise(r => setTimeout(r, 80));

    try {
        if (typeof ExcelJS === 'undefined')
            throw new Error('Library ExcelJS belum dimuat. Periksa koneksi internet.');

        if (!rawSheets || !Object.keys(rawSheets).length) {
            st.innerHTML = '<div class="msg msg-warn">⚠ Tidak ada data input tersedia. Pastikan sudah upload file di Langkah 1.</div>';
            return;
        }

        const wb = new ExcelJS.Workbook();
        wb.creator = 'RTK Makro v8';
        wb.created = new Date();

        // Acuan tunggal: EXCEL_INPUT_SHEETS — selalu semua sheet, urutan tetap
        // (identik dengan Step 1 template)
        const includedSheets = EXCEL_INPUT_SHEETS.map(def => ({ code: def.code, name: def.name }));

        // Tahun untuk sheet kosong (fallback)
        const hYears = (histYrs || []).map(String);
        const pYears = (projYrs || []).map(String);

        // 1. Daftar Isi — baris Target selalu ada di posisi pertama
        buildDaftarIsi(wb, includedSheets);

        // 2. Sheet Target — selalu dibuat
        //    Ada data (P.targets / P.targetImports) → terisi; belum ada → kosong
        buildTargetSheet(wb);

        // 3. Tiap sheet data input — SELALU dibuat
        EXCEL_INPUT_SHEETS.forEach(def => {
            const sh = rawSheets[def.code];

            if (sh) {
                // Sheet ada di file upload → pakai data aktual
                if (sh.tableType === 'multiHeader') {
                    buildMultiHdrSheet(wb, def.code, def.name, sh);
                } else if (sh.tableType === 'yearMatrix') {
                    buildYearMtxSheet(wb, def.code, def.name, sh);
                } else if (sh.tableType === 'crossTab') {
                    buildCrossTabSheet(wb, def.code, def.name, sh);
                } else {
                    buildNormalSheet(wb, def.code, def.name, sh);
                }
            } else {
                // Sheet tidak ada di file upload → buat kosong sesuai schema
                const schema = TABLE_SCHEMA[def.code];
                if (schema) {
                    _buildTplDataSheet(wb, def.code, def.name, schema, hYears, pYears);
                } else {
                    // Schema belum tersedia — sheet minimal
                    const ws = wb.addWorksheet(def.code);
                    _addBackRow(ws, def.name, 0);
                }
            }
        });

        // 4. Generate & download
        const buffer = await wb.xlsx.writeBuffer();
        const safe   = (P.nama || 'RTK').replace(/[^a-zA-Z0-9\s]/g, '').replace(/\s+/g, '_');
        const fname  = `Data_Input_RTK_${safe}.xlsx`;

        saveAs(new Blob([buffer], {
            type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        }), fname);

        const dataCount  = EXCEL_INPUT_SHEETS.filter(d => rawSheets[d.code]).length;
        const emptyCount = EXCEL_INPUT_SHEETS.length - dataCount;
        const note = emptyCount > 0 ? ` (${dataCount} berisi data, ${emptyCount} kosong)` : '';
        st.innerHTML = `<div class="msg msg-ok">✅ Berhasil diunduh: <strong>${fname}</strong> — ${EXCEL_INPUT_SHEETS.length} sheet INPUT + Sheet Target + Daftar Isi${note}</div>`;

    } catch (err) {
        st.innerHTML = `<div class="msg msg-err">❌ Error: ${err.message}</div>`;
        console.error(err);
    }
}
