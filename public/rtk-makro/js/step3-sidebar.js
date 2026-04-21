/* ══════════════════════════════════════════
   step3-sidebar.js — RTK Makro v8
   Sidebar navigasi Step 3:
     Tab 1: Indikator → Karakteristik (tree)
     Tab 2: BAB → Sub-bab → 77 Tabel
   Dipecah dari step3-config.js
   ══════════════════════════════════════════ */

// ══════════════════════════════════════════════════════════════
// BUILD SIDEBAR
// ══════════════════════════════════════════════════════════════
function buildSidebar() {
  const sb = document.createElement('div');
  sb.id = 's3-sidebar';

  // ── Tab switch ──
  const tabs = document.createElement('div'); tabs.className = 'sb-tabs';
  const t1 = document.createElement('div'); t1.className = 'sb-tab active'; t1.textContent = '📊 Indikator'; t1.dataset.sb = 'ind';
  const t2 = document.createElement('div'); t2.className = 'sb-tab'; t2.textContent = '📋 Tabel Sub Bab'; t2.dataset.sb = 'bab';
  tabs.appendChild(t1); tabs.appendChild(t2);
  [t1, t2].forEach(t => { t.onclick = () => { tabs.querySelectorAll('.sb-tab').forEach(x => x.classList.toggle('active', x === t)); sb.querySelectorAll('.sb-panel').forEach(p => p.classList.toggle('active', p.dataset.sb === t.dataset.sb)); }; });
  sb.appendChild(tabs);

  // ── Search box ──
  const srch = document.createElement('div'); srch.className = 'sb-search';
  const inp = document.createElement('input'); inp.type = 'text'; inp.placeholder = '🔍 Cari…';
  inp.oninput = () => filterSidebar(inp.value);
  srch.appendChild(inp); sb.appendChild(srch);

  // ── Body ──
  const body = document.createElement('div'); body.className = 'sb-body';

  // Panel 1: INDIKATOR → KARAKTERISTIK
  const p1 = document.createElement('div'); p1.className = 'sb-panel active'; p1.dataset.sb = 'ind';

  INDIKATOR_DEFS.forEach(ind => {
    const grp = document.createElement('div'); grp.className = 'sb-ind-group'; grp.dataset.ind = ind.id;

    const hdr = document.createElement('div'); hdr.className = 'sb-ind-header open';
    const dot = document.createElement('span'); dot.className = 'sb-ind-dot';
    dot.style.background = IND_COLORS[ind.id] || '#94a3b8';
    const chev = document.createElement('span'); chev.className = 'sb-ind-chevron'; chev.textContent = '▶';
    const lbl = document.createElement('span'); lbl.textContent = ind.label + (ind.title ? ' – ' + ind.title.replace(/Proyeksi /, '').replace(/\(.*\)/, '').trim() : '');
    lbl.style.cssText = 'font-size:11px;flex:1;';
    hdr.appendChild(dot); hdr.appendChild(chev); hdr.appendChild(lbl);
    hdr.onclick = () => {
      hdr.classList.toggle('open');
      chars.classList.toggle('open');
    };
    grp.appendChild(hdr);

    const chars = document.createElement('div'); chars.className = 'sb-chars open';

    if (ind.pdrbMode || ind.elastisitasMode) {
      // Satu item tunggal
      const item = document.createElement('div'); item.className = 'sb-char-item';
      const ddot = document.createElement('span'); ddot.className = 'sb-char-dot';
      item.appendChild(ddot);
      item.appendChild(document.createTextNode('Lapangan Usaha'));
      item.onclick = () => {
          switchIndikator(ind.id);
          highlightSbChar(ind.id, 'lu');
          document.getElementById('s3-main').scrollIntoView({ behavior: 'smooth', block: 'start' });
      };
      chars.appendChild(item);
    } else {
      ind.chars.forEach(ch => {
        const item = document.createElement('div');
        item.className = 'sb-char-item' + (!(hasData(ch.histCode) || hasData(ch.projCode)) ? ' empty' : '');
        item.dataset.ind = ind.id; item.dataset.char = ch.id;
        const ddot = document.createElement('span'); ddot.className = 'sb-char-dot';
        item.appendChild(ddot);
        item.appendChild(document.createTextNode(ch.label));
        if (ch.provOnly) { const star = document.createElement('span'); star.textContent = ' ★'; star.style.cssText = 'color:#f59e0b;font-size:10px;'; item.appendChild(star); }
        item.onclick = () => {
          switchIndikator(ind.id);
          switchChar(ind.id, ch.id);
          highlightSbChar(ind.id, ch.id);

          setTimeout(() => {
            const activePanel = document.querySelector('.ind-panel.active');
            const targetParent = activePanel || document;
            const badges = Array.from(targetParent.querySelectorAll('.badge-code'));
            const codeToFind = ch.histCode || ch.projCode;

            if (codeToFind) {
              const targetBadge = badges.find(b => b.textContent.includes('[' + codeToFind + ']'));
              if (targetBadge) {
                targetBadge.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
              }
            }
            document.getElementById('s3-main').scrollIntoView({ behavior: 'smooth', block: 'start' });
          }, 50);
        };
        chars.appendChild(item);
      });
    }

    grp.appendChild(chars);
    p1.appendChild(grp);
  });

  // Panel 2: BAB → 77 TABEL
  const p2 = document.createElement('div'); p2.className = 'sb-panel'; p2.dataset.sb = 'bab';

  BAB_TREE.forEach(bab => {
    const babEl = document.createElement('div'); babEl.className = 'sb-bab';
    const babHdr = document.createElement('div'); babHdr.className = 'sb-bab-header open';
    const babChev = document.createElement('span'); babChev.className = 'sb-bab-chevron'; babChev.textContent = '▶';
    const babLbl = document.createElement('span'); babLbl.style.flex = '1'; babLbl.textContent = bab.bab;
    babHdr.appendChild(babChev); babHdr.appendChild(babLbl);

    const babItems = document.createElement('div'); babItems.className = 'sb-bab-items open';
    babHdr.onclick = () => { babHdr.classList.toggle('open'); babItems.classList.toggle('open'); };
    babEl.appendChild(babHdr);

    bab.items.forEach(itm => {
      const row = document.createElement('div');
      row.className = 'sb-tbl-item' + (!hasData(itm.code) ? ' empty' : '');
      row.dataset.code = itm.code;

      const codeSpan = document.createElement('span'); codeSpan.className = 'sb-tbl-code'; codeSpan.textContent = itm.code;
      const nameSpan = document.createElement('span'); nameSpan.className = 'sb-tbl-name'; nameSpan.textContent = itm.name;
      const dot2 = document.createElement('span'); dot2.className = 'sb-tbl-dot';
      row.appendChild(codeSpan); row.appendChild(nameSpan); row.appendChild(dot2);

      row.onclick = () => {
        // Navigasi ke indikator + karakteristik yang sesuai
        if (itm.indId) switchIndikator(itm.indId);
        if (itm.charId && itm.indId) switchChar(itm.indId, itm.charId);
        // Highlight aktif
        p2.querySelectorAll('.sb-tbl-item').forEach(x => x.classList.remove('active'));
        row.classList.add('active');
        // Scroll konten ke tabel spesifik
        setTimeout(() => {
            const activePanel = document.querySelector('.ind-panel.active');
            const targetParent = activePanel || document;
            const badges = Array.from(targetParent.querySelectorAll('.badge-code'));
            const targetBadge = badges.find(b => b.textContent.includes('[' + itm.code + ']'));
            if (targetBadge) {
                targetBadge.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } else {
                document.getElementById('s3-main').scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }, 50);
      };
      babItems.appendChild(row);
    });

    babEl.appendChild(babItems);
    p2.appendChild(babEl);
  });

  body.appendChild(p1); body.appendChild(p2);
  sb.appendChild(body);
  return sb;
}

// Highlight item aktif di tab 1 sidebar
function highlightSbChar(indId, charId) {
  document.querySelectorAll('#s3-sidebar .sb-char-item').forEach(x => {
    x.classList.toggle('active', x.dataset.ind === indId && x.dataset.char === charId);
  });
}

// Filter kedua panel sidebar berdasarkan teks
function filterSidebar(q) {
  const kw = q.toLowerCase().trim();

  // Panel 1: tampilkan/sembunyikan grup
  document.querySelectorAll('#s3-sidebar .sb-ind-group').forEach(grp => {
    let any = false;
    grp.querySelectorAll('.sb-char-item').forEach(item => {
      const match = !kw || item.textContent.toLowerCase().includes(kw);
      item.style.display = match ? '' : 'none';
      if (match) any = true;
    });
    grp.style.display = any || !kw ? '' : 'none';
    if (kw && any) { grp.querySelector('.sb-chars').classList.add('open'); grp.querySelector('.sb-ind-header').classList.add('open'); }
  });

  // Panel 2: tampilkan/sembunyikan tabel
  document.querySelectorAll('#s3-sidebar .sb-tbl-item').forEach(row => {
    const match = !kw || row.textContent.toLowerCase().includes(kw);
    row.style.display = match ? '' : 'none';
  });
  document.querySelectorAll('#s3-sidebar .sb-bab').forEach(bab => {
    const any = [...bab.querySelectorAll('.sb-tbl-item')].some(r => r.style.display !== 'none');
    bab.style.display = any || !kw ? '' : 'none';
    if (kw && any) { bab.querySelector('.sb-bab-items').classList.add('open'); bab.querySelector('.sb-bab-header').classList.add('open'); }
  });
}
