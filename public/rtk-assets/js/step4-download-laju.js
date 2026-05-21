/* ══════════════════════════════════════════
   step4-download-laju.js — RTK Makro v8
   Download Excel Laju Pertumbuhan:
   • Sheet "Daftar Isi" dengan hyperlink
   • Satu sheet per projCode yang memiliki
     data rataTambahanRows (makeBimtekTable2)
   • Setiap sheet: Uraian | Laju(r) | Tahun... | Catatan
   ══════════════════════════════════════════ */

/* ── Kumpulkan semua definisi sheet laju dari MERGED_GROUPS ── */
function _getLajuSheetDefs() {
    const defs = [];
    MERGED_GROUPS.forEach(group => {
        group.layers.forEach(layer => {
            if (!layer.projCode) return;
            const proj = computed[layer.projCode];
            const hist = computed[layer.code];
            if (!proj || !proj.rataTambahanRows) return;
            if (!hist || !hist.rows || !hist.rows.length) return;
            defs.push({
                projCode:   layer.projCode,
                histCode:   layer.code,
                layerLabel: layer.label,
                section:    group.section,
                groupTitle: group.title,
                sheetName:  layer.projCode,   // nama tab Excel (max 31 karakter)
            });
        });
    });
    // Urutkan sesuai projCode: 3.A.1, 3.A.2, ..., 3.B.1, 3.B.2, ...
    defs.sort((a, b) => {
        const pa = a.projCode.split('.').map((s, i) => i === 0 ? parseInt(s) : s);
        const pb = b.projCode.split('.').map((s, i) => i === 0 ? parseInt(s) : s);
        for (let i = 0; i < Math.max(pa.length, pb.length); i++) {
            const va = pa[i] ?? '', vb = pb[i] ?? '';
            if (va < vb) return -1;
            if (va > vb) return 1;
        }
        return 0;
    });
    return defs;
}

/* ── Helper styling sel ExcelJS ── */
function _lajuCell(ws, row, col, value, opts = {}) {
    const cell = ws.getCell(row, col);
    cell.value = value;
    cell.font   = { name: 'Arial', size: 10, bold: !!opts.bold, color: opts.fontColor ? { argb: opts.fontColor } : undefined };
    cell.fill   = opts.fill ? { type: 'pattern', pattern: 'solid', fgColor: { argb: opts.fill } } : undefined;
    cell.border = {
        top:    { style: 'thin', color: { argb: 'FFCCCCCC' } },
        bottom: { style: 'thin', color: { argb: 'FFCCCCCC' } },
        left:   { style: 'thin', color: { argb: 'FFCCCCCC' } },
        right:  { style: 'thin', color: { argb: 'FFCCCCCC' } },
    };
    cell.alignment = { vertical: 'middle', horizontal: opts.align || 'left', wrapText: true };
    return cell;
}

/* ── Sheet Daftar Isi ── */
function _buildLajuDaftarIsi(wb, defs) {
    const ws = wb.addWorksheet('Daftar Isi');
    ws.properties.defaultRowHeight = 18;

    // Judul
    ws.mergeCells(1, 1, 1, 4);
    const titleCell = ws.getCell(1, 1);
    titleCell.value = `Laju Pertumbuhan — ${P.nama || ''}`;
    titleCell.font  = { name: 'Arial', size: 13, bold: true };
    titleCell.alignment = { vertical: 'middle', horizontal: 'center' };
    titleCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF1A3A5C' } };
    titleCell.font = { name: 'Arial', size: 13, bold: true, color: { argb: 'FFFFFFFF' } };
    ws.getRow(1).height = 28;

    // Header kolom
    const HDR_FILL = 'FFD9E1F2';
    _lajuCell(ws, 2, 1, 'No',          { bold: true, fill: HDR_FILL, align: 'center' });
    _lajuCell(ws, 2, 2, 'Kode',        { bold: true, fill: HDR_FILL, align: 'center' });
    _lajuCell(ws, 2, 3, 'Judul Tabel', { bold: true, fill: HDR_FILL });
    _lajuCell(ws, 2, 4, 'Aksi',        { bold: true, fill: HDR_FILL, align: 'center' });
    ws.getRow(2).height = 20;

    defs.forEach((def, i) => {
        const r = i + 3;
        const judul = `${def.section} — ${def.layerLabel}`;
        _lajuCell(ws, r, 1, i + 1,     { align: 'center' });
        _lajuCell(ws, r, 2, def.projCode, { align: 'center' });
        _lajuCell(ws, r, 3, judul);

        // Hyperlink ke sheet
        const linkCell = ws.getCell(r, 4);
        linkCell.value = { text: 'Lihat ▶', hyperlink: `#'${def.sheetName}'!A1` };
        linkCell.font  = { name: 'Arial', size: 10, color: { argb: 'FF0563C1' }, underline: true };
        linkCell.alignment = { vertical: 'middle', horizontal: 'center' };
        linkCell.border = {
            top: { style: 'thin', color: { argb: 'FFCCCCCC' } },
            bottom: { style: 'thin', color: { argb: 'FFCCCCCC' } },
            left: { style: 'thin', color: { argb: 'FFCCCCCC' } },
            right: { style: 'thin', color: { argb: 'FFCCCCCC' } },
        };
        ws.getRow(r).height = 18;
    });

    ws.getColumn(1).width = 6;
    ws.getColumn(2).width = 10;
    ws.getColumn(3).width = 70;
    ws.getColumn(4).width = 10;
}

/* ── Sheet per projCode ── */
function _buildLajuSheet(wb, def) {
    const ws = wb.addWorksheet(def.sheetName);
    ws.properties.defaultRowHeight = 18;

    const proj = computed[def.projCode];
    const hist = computed[def.histCode];
    const years = proj.years || [];

    // ── Baris 1: back link + judul ──
    const backCell = ws.getCell(1, 1);
    backCell.value = { text: '◀ Daftar Isi', hyperlink: `#'Daftar Isi'!A1` };
    backCell.font  = { name: 'Arial', size: 10, color: { argb: 'FF0563C1' }, underline: true };
    backCell.alignment = { vertical: 'middle', horizontal: 'center' };

    ws.mergeCells(1, 2, 1, 2 + years.length);
    const titleCell = ws.getCell(1, 2);
    titleCell.value = `${def.section} — ${def.layerLabel} (${def.projCode})`;
    titleCell.font  = { name: 'Arial', size: 11, bold: true };
    titleCell.alignment = { vertical: 'middle', horizontal: 'left' };
    titleCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFDCE6F1' } };
    ws.getRow(1).height = 22;

    // ── Baris 2: header kolom ──
    const HDR_FILL    = 'FF1A3A5C';
    const HDR_FONT    = { name: 'Arial', size: 10, bold: true, color: { argb: 'FFFFFFFF' } };
    const HDR_ALIGN   = (h) => ({ vertical: 'middle', horizontal: h, wrapText: true });

    let col = 1;
    const hdrUraian = ws.getCell(2, col++);
    hdrUraian.value = 'Uraian';
    hdrUraian.font  = HDR_FONT; hdrUraian.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: HDR_FILL } };
    hdrUraian.alignment = HDR_ALIGN('left');

    const hdrLaju = ws.getCell(2, col++);
    hdrLaju.value = 'Laju (r)';
    hdrLaju.font  = HDR_FONT; hdrLaju.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: HDR_FILL } };
    hdrLaju.alignment = HDR_ALIGN('center');

    years.forEach(y => {
        const c = ws.getCell(2, col++);
        c.value = y; c.font = HDR_FONT;
        c.fill  = { type: 'pattern', pattern: 'solid', fgColor: { argb: HDR_FILL } };
        c.alignment = HDR_ALIGN('center');
    });

    const hdrCat = ws.getCell(2, col++);
    hdrCat.value = 'Catatan';
    hdrCat.font  = HDR_FONT; hdrCat.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: HDR_FILL } };
    hdrCat.alignment = HDR_ALIGN('left');
    ws.getRow(2).height = 20;

    // ── Buat peta label → rataTambahan ──
    const rataMap = {};
    if (proj.rows && proj.rataTambahanRows) {
        proj.rows.forEach((pr, pri) => {
            if (proj.rataTambahanRows[pri] !== undefined) rataMap[pr.label] = proj.rataTambahanRows[pri];
        });
    }

    // ── Baris data ──
    const SKIP = new Set(['Jumlah', 'JUMLAH']);
    const dataRows = hist.rows.filter(r => !SKIP.has(r.label));

    const DATA_FILL_LAJU = 'FFD9F7BE';  // hijau muda — laju historis
    const DATA_FILL_PROJ = 'FFFDE8F0';  // pink muda — perkiraan
    const DATA_FILL_CAT  = 'FFFFFDE7';  // kuning muda — catatan

    dataRows.forEach((r, ri) => {
        const rowNum = ri + 3;
        const rataD  = rataMap[r.label] || null;
        const baseLaju = rataD ? rataD.laju : 0;

        let c = 1;
        // Uraian
        _lajuCell(ws, rowNum, c++, r.label);

        // Laju (r) historis
        const lajuCell = ws.getCell(rowNum, c++);
        lajuCell.value = baseLaju;
        lajuCell.numFmt = '0.##########';
        lajuCell.font   = { name: 'Arial', size: 10 };
        lajuCell.fill   = { type: 'pattern', pattern: 'solid', fgColor: { argb: DATA_FILL_LAJU } };
        lajuCell.alignment = { vertical: 'middle', horizontal: 'right' };
        lajuCell.border = { top: { style: 'thin', color: { argb: 'FFCCCCCC' } }, bottom: { style: 'thin', color: { argb: 'FFCCCCCC' } }, left: { style: 'thin', color: { argb: 'FFCCCCCC' } }, right: { style: 'thin', color: { argb: 'FFCCCCCC' } } };

        // Perkiraan laju per tahun
        years.forEach(y => {
            let val = baseLaju;
            if (P.lajuOverrides && P.lajuOverrides[def.projCode] &&
                P.lajuOverrides[def.projCode][r.label] &&
                P.lajuOverrides[def.projCode][r.label][y] !== undefined) {
                val = P.lajuOverrides[def.projCode][r.label][y];
            }
            const cell = ws.getCell(rowNum, c++);
            cell.value = val;
            cell.numFmt = '0.##########';
            cell.font   = { name: 'Arial', size: 10 };
            cell.fill   = { type: 'pattern', pattern: 'solid', fgColor: { argb: DATA_FILL_PROJ } };
            cell.alignment = { vertical: 'middle', horizontal: 'right' };
            cell.border = { top: { style: 'thin', color: { argb: 'FFCCCCCC' } }, bottom: { style: 'thin', color: { argb: 'FFCCCCCC' } }, left: { style: 'thin', color: { argb: 'FFCCCCCC' } }, right: { style: 'thin', color: { argb: 'FFCCCCCC' } } };
        });

        // Catatan
        const comment = (P.lajuComments && P.lajuComments[def.projCode] && P.lajuComments[def.projCode][r.label]) || '';
        const catCell = ws.getCell(rowNum, c++);
        catCell.value = comment;
        catCell.font  = { name: 'Arial', size: 10, italic: !!comment, color: comment ? undefined : { argb: 'FFAAAAAA' } };
        catCell.fill  = { type: 'pattern', pattern: 'solid', fgColor: { argb: DATA_FILL_CAT } };
        catCell.alignment = { vertical: 'top', wrapText: true };
        catCell.border = { top: { style: 'thin', color: { argb: 'FFCCCCCC' } }, bottom: { style: 'thin', color: { argb: 'FFCCCCCC' } }, left: { style: 'thin', color: { argb: 'FFCCCCCC' } }, right: { style: 'thin', color: { argb: 'FFCCCCCC' } } };

        ws.getRow(rowNum).height = 18;
    });

    // ── Lebar kolom ──
    ws.getColumn(1).width = 36;   // Uraian
    ws.getColumn(2).width = 10;   // Laju (r)
    years.forEach((_, i) => { ws.getColumn(3 + i).width = 10; });
    ws.getColumn(3 + years.length).width = 40;  // Catatan
}

/* ── Fungsi utama download ── */
async function downloadLajuExcel() {
    const st = document.getElementById('stDl');
    st.innerHTML = '<div class="msg msg-info">⏳ Membuat file Laju Pertumbuhan...</div>';
    await new Promise(r => setTimeout(r, 80));

    try {
        if (typeof ExcelJS === 'undefined')
            throw new Error('Library ExcelJS belum dimuat. Periksa koneksi internet.');

        const defs = _getLajuSheetDefs();
        if (!defs.length) {
            st.innerHTML = '<div class="msg msg-warn">⚠ Belum ada data laju tersedia. Pastikan sudah upload file dan isi parameter di Langkah 1–2.</div>';
            return;
        }

        const wb = new ExcelJS.Workbook();
        wb.creator = 'RTK Makro v8';
        wb.created = new Date();

        _buildLajuDaftarIsi(wb, defs);
        defs.forEach(def => _buildLajuSheet(wb, def));

        const buffer = await wb.xlsx.writeBuffer();
        const safe   = (P.nama || 'RTK').replace(/[^a-zA-Z0-9\s]/g, '').replace(/\s+/g, '_');
        const fname  = `Laju_Pertumbuhan_${safe}.xlsx`;

        saveAs(new Blob([buffer], {
            type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        }), fname);

        st.innerHTML = `<div class="msg msg-ok">✅ Berhasil diunduh: <strong>${fname}</strong> — ${defs.length} sheet laju + Daftar Isi</div>`;

    } catch (err) {
        st.innerHTML = `<div class="msg msg-err">❌ Error: ${err.message}</div>`;
        console.error(err);
    }
}
