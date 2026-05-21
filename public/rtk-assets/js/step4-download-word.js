/* ══════════════════════════════════════════
   step4-download-word.js — RTK Makro v8
   Langkah 4: generate dan download dokumen
   Word (.docx) format RTK sesuai
   Kepmen 309/2013 — Provinsi atau Kab/Kota
   ══════════════════════════════════════════ */

/* ┌─────────────────────────────────────────────────────────────────┐
   │  📐 PENGATURAN DOKUMEN WORD                                    │
   │  Ubah nilai di sini — seluruh dokumen akan menyesuaikan.       │
   │                                                                │
   │  Catatan satuan:                                               │
   │  • Ukuran kertas & margin → twips (1 inch = 1440 twips)        │
   │  • 1 cm ≈ 567 twips                                           │
   │  • Font size → half-point (size 22 = 11pt, size 20 = 10pt)    │
   │                                                                │
   │  Ukuran kertas umum (width × height dalam twips):              │
   │  • A4     = 11906 × 16838                                     │
   │  • B5 JIS = 10319 × 14572  (182mm × 257mm)                     │
  │  • B5 ISO =  9979 × 14173  (176mm × 250mm)                     │
   │  • Letter = 12240 × 15840                                     │
   │  • Legal  = 12240 × 20160                                     │
   │                                                                │
   │  Orientasi:                                                    │
   │  • Portrait  → width < height                                  │
   │  • Landscape → tukar width ↔ height                            │
   └─────────────────────────────────────────────────────────────────┘ */
const WORD_CFG = {

  // ── Kertas ──────────────────────────────────────────────────────
  page: {
    width:   9978,    // B5 ISO portrait width  (176 mm)
    height: 14173,    // B5 ISO portrait height (250 mm)
  },

  // ── Margin ──────────────────────────────────────────────────────
  margin: {
    top:    1134,     // 2 cm
    bottom:  851,     // 1.5 cm
    left:   1134,     // 2 cm
    right:   851,     // 1.5 cm
  },

  // ── Font ────────────────────────────────────────────────────────
  fontFamily: 'Arial',

  // Ukuran font (dalam half-point):
  //   size 28 = 14pt | 24 = 12pt | 22 = 11pt | 20 = 10pt | 18 = 9pt
  fontSize: {
    cover:      28,   // Judul cover utama (14pt)
    coverSub:   24,   // Sub-judul cover — nama daerah (12pt)
    coverYear:  22,   // Tahun cover (11pt)
    bab:        24,   // Nama BAB / Heading 1 (12pt)
    subBab:     20,   // Sub-bab / Heading 2 (10pt)
    paragraf:   20,   // Teks paragraf biasa (10pt)
    tabel:      16,   // Isi sel tabel (8pt)
    caption:    16,   // Judul/caption tabel (8pt)
  },

  // ── Warna ───────────────────────────────────────────────────────
  color: {
    text:       '000000',   // Teks biasa — hitam
    tblHeader:  '1A3A5C',   // Background header tabel — biru tua
    tblHdrText: 'FFFFFF',   // Teks header tabel — putih
    target:     'E2F0CB',   // Baris target — hijau muda
    selisih:    'FFE4E1',   // Baris selisih — pink muda
    disabled:   '888888',   // Teks data tidak tersedia — abu
  },

  // ── Teks Placeholder ───────────────────────────────────────────
  // Ubah isi teks di bawah ini sesuai kebutuhan.
  // Gunakan {nama}, {hA}, {hZ}, {pA}, {pZ}, {td} untuk data dinamis.
  // Gunakan {code} dan {title} untuk kode & judul bagian/sub-bab.
  // Gunakan {sub} untuk nama sub-kebijakan BAB VI.
  // Kosongkan ('') jika tidak ingin menampilkan teks placeholder.
  teks: {
    // ── Halaman Awal ──────────────────────────────────────────────
    sambutan:       '...',
    kataPengantar:  '...',
    execSummary:    '...',

    // ── BAB I Pendahuluan ─────────────────────────────────────────
    bab1: {
      latarBelakang:  '...',
      dasarHukum:     '...',
      tujuan:         '...',
      metodologi:     '...',
      sumberData:     '...',
      pengertian:     '...',
      sistematika:    '...',
    },

    // ── BAB II–V (per sub-bab) ────────────────────────────────────
    // {code} = kode bagian (A, B, C, ...)
    // {title} = judul bagian (Kondisi Ekonomi, Potensi Daerah, ...)
    isiBagian:      '...',

    // ── BAB VI Kebijakan ──────────────────────────────────────────
    // {sub} = nama sub-kebijakan (Kebijakan Umum, Kebijakan Sektoral, ...)
    isiKebijakan:   '...',

    // ── BAB VII & Daftar Pustaka ──────────────────────────────────
    penutup:        '...',
    daftarPustaka:  '...',

    // ── Tabel tidak tersedia ──────────────────────────────────────
    // {no} = nomor tabel (misal: 2.A.1)
    tabelKosong:    '...',
  },
};

/* ── Helper: replace placeholder {key} dengan data ─────────────── */
function _fillTeks(template, data) {
  if (!template) return '';
  return template.replace(/\{(\w+)\}/g, (_, key) => data[key] ?? '');
}

async function downloadWord(isKabKota){
  const st=document.getElementById('stDl');
  st.innerHTML='<div class="msg msg-info">⏳ Membuat dokumen Word...</div>';
  await new Promise(r=>setTimeout(r,80));
  try{
    const{Document,Packer,Paragraph,TextRun,AlignmentType,HeadingLevel,PageNumber,Footer,
          Table,TableRow,TableCell,WidthType,BorderStyle,TabStopType,TabStopPosition,LeaderType,NumberFormat}=docx;

    // Shortcut dari config
    const CFG = WORD_CFG;
    const FF  = CFG.fontFamily;
    const FS  = CFG.fontSize;
    const CLR = CFG.color;
    const TXT = CFG.teks;
    const pData = { nama: P.nama, hA: P.hA, hZ: P.hZ, pA: P.pA, pZ: P.pZ, td: P.td };

    const filteredTOC=isKabKota
      ?TOC_WORD.map(bab=>({...bab,sections:bab.sections.map(sec=>({...sec,tables:sec.tables.filter(t=>!t.prov)}))}))
      :TOC_WORD;

    const mkFtr=()=>new Footer({children:[new Paragraph({alignment:AlignmentType.RIGHT,children:[new TextRun({children:[PageNumber.CURRENT],font:FF,size:FS.paragraf})]})]}); 
    const pg={size:{width:CFG.page.width,height:CFG.page.height},margin:{top:CFG.margin.top,right:CFG.margin.right,bottom:CFG.margin.bottom,left:CFG.margin.left}};

    const bdr={top:{style:BorderStyle.SINGLE,size:4,color:'000000'},bottom:{style:BorderStyle.SINGLE,size:4,color:'000000'},left:{style:BorderStyle.SINGLE,size:4,color:'000000'},right:{style:BorderStyle.SINGLE,size:4,color:'000000'}};

    // Helper: sel tabel (rata kiri)
    const mkC=(txt,bold=false,bg=null)=>new TableCell({borders:bdr,shading:bg?{fill:bg}:undefined,children:[new Paragraph({children:[new TextRun({text:String(txt??''),bold,size:FS.tabel,font:FF,color:bg?CLR.tblHdrText:CLR.text})],spacing:{before:30,after:30}})]});
    // Helper: sel tabel (rata kanan — untuk angka)
    const mkCR=(txt,bold=false,bg=null)=>new TableCell({borders:bdr,shading:bg?{fill:bg}:undefined,children:[new Paragraph({alignment:AlignmentType.RIGHT,children:[new TextRun({text:String(txt??''),bold,size:FS.tabel,font:FF,color:bg?CLR.tblHdrText:CLR.text})],spacing:{before:30,after:30}})]});

    function buildWordTbl(code){
      const t=computed[code]; if(!t) return null;

      // ── multiHeader (contoh: 2.B.2, 4.B.1) ──────────────────
      if(t.tableType==='multiHeader'){
        const hdrs=t.headers||[],data=t.data||[];
        if(!data.length) return null;

        const hr1=[mkC('Tahun',true,CLR.tblHeader)];
        let hi=0;
        while(hi<hdrs.length){
          const parent=hdrs[hi].parent||hdrs[hi].child||hdrs[hi].key||'';
          let span=1;
          while(hi+span<hdrs.length && (hdrs[hi+span].parent||hdrs[hi+span].child||hdrs[hi+span].key||'')===parent) span++;
          hr1.push(new TableCell({
            columnSpan:span,
            borders:bdr,
            shading:{fill:CLR.tblHeader},
            children:[new Paragraph({children:[new TextRun({text:parent,bold:true,size:FS.tabel,font:FF,color:CLR.tblHdrText})],spacing:{before:30,after:30}})]
          }));
          hi+=span;
        }

        const hr2=[mkC('',true,CLR.tblHeader)];
        hdrs.forEach(h=>hr2.push(mkC(h.child!=null?h.child:'',true,CLR.tblHeader)));

        const rows=[new TableRow({children:hr1,tableHeader:true}),new TableRow({children:hr2,tableHeader:true})];
        data.forEach(row=>{const cells=[mkC(String(row.year))];hdrs.forEach(h=>{const v=row.values[h.key];cells.push(mkCR(fmtNum(v)));});rows.push(new TableRow({children:cells}));});
        return new Table({rows,width:{size:100,type:WidthType.PERCENTAGE}});
      }

      // ── yearMatrix (contoh: 2.K.2, 4.G.2) ───────────────────
      if(t.tableType==='yearMatrix'){
        const hdrs=t.headers||[],data=t.data||[];
        if(!data.length) return null;
        const hr=[mkC('Tahun',true,CLR.tblHeader)];hdrs.forEach(h=>hr.push(mkC(h,true,CLR.tblHeader)));
        const rows=[new TableRow({children:hr,tableHeader:true})];
        data.forEach(row=>{const cells=[mkC(String(row.year))];hdrs.forEach(h=>cells.push(mkCR(fmtNum(row.values[h]))));rows.push(new TableRow({children:cells}));});
        return new Table({rows,width:{size:100,type:WidthType.PERCENTAGE}});
      }

      // ── crossTab (contoh: 4.E.1, 4.F.1) ─────────────────────
      if(t.tableType==='crossTab'){
        const cols=t.colHeaders||[],rows_ct=t.rows||[];
        if(!rows_ct.length) return null;

        const hr1=[mkC('Uraian',true,CLR.tblHeader)];
        let ci=0;
        while(ci<cols.length){
          const group=cols[ci].group||'';
          let span=1;
          while(ci+span<cols.length && (cols[ci+span].group||'')===group) span++;
          hr1.push(new TableCell({
            columnSpan:span,
            borders:bdr,
            shading:{fill:CLR.tblHeader},
            children:[new Paragraph({children:[new TextRun({text:group,bold:true,size:FS.tabel,font:FF,color:CLR.tblHdrText})],spacing:{before:30,after:30}})]
          }));
          ci+=span;
        }

        const hr2=[mkC('',true,CLR.tblHeader)];
        cols.forEach(h=>hr2.push(mkC(h.sub!=null?h.sub:'',true,CLR.tblHeader)));

        const wrows=[new TableRow({children:hr1,tableHeader:true}),new TableRow({children:hr2,tableHeader:true})];
        rows_ct.forEach(row=>{
          const isT=row.label==='Jumlah'||row.label==='JUMLAH';
          const cells=[mkC(row.label,isT,null)];
          (row.vals||[]).forEach(v=>cells.push(mkCR(fmtNum(v),isT,null)));
          wrows.push(new TableRow({children:cells}));
        });
        return new Table({rows:wrows,width:{size:100,type:WidthType.PERCENTAGE}});
      }

      // ── normal ────────────────────────────────────────────────
      const years=t.years||[],rows_data=t.rows||[];
      if(!rows_data.length) return null;
      const isHistBimtek=!!t.rataTambahanRows;
      const hr=[mkC('Uraian',true,CLR.tblHeader)];
      years.forEach(y=>hr.push(mkC(String(y),true,CLR.tblHeader)));
      if(isHistBimtek&&t.status!=='puk_tpak_proj'){
        hr.push(mkC('Laju (r)',true,CLR.tblHeader));
        hr.push(mkC('Rata Tambahan',true,CLR.tblHeader));
      }
      const wrows=[new TableRow({children:hr,tableHeader:true})];
      rows_data.forEach((row,ri)=>{
        const isT=row.label==='Jumlah'||row.label==='JUMLAH'||row.label==='TPT'||row.label==='TPAK'||row.isTarget||row.isSelisih;
        const bg=row.isTarget?CLR.target:row.isSelisih?CLR.selisih:null;
        const cells=[mkC(row.label,isT,bg)];
        row.vals.forEach(v=>cells.push(mkCR(fmtNum(v),isT,bg)));
        if(isHistBimtek&&t.status!=='puk_tpak_proj'){
          if(row.label!=='Jumlah'&&row.label!=='JUMLAH'&&!row.isTarget&&!row.isSelisih){
            const rData=t.rataTambahanRows[ri];
            cells.push(mkCR(fmtNum(Math.round((rData?rData.laju:0)*100)/100)+'%',false));
            cells.push(mkCR(fmtNum(rData?rData.rata:0),false));
          }else{cells.push(mkCR('',false));cells.push(mkCR('',false));}
        }
        wrows.push(new TableRow({children:cells}));
      });
      return new Table({rows:wrows,width:{size:100,type:WidthType.PERCENTAGE}});
    }

    const sections=[];

    // Cover
    sections.push({properties:{page:pg},children:[
      new Paragraph({text:'',spacing:{before:3000}}),
      new Paragraph({alignment:AlignmentType.CENTER,spacing:{after:200},children:[new TextRun({text:'RENCANA TENAGA KERJA MAKRO',bold:true,size:FS.cover,font:FF})]}),
      new Paragraph({alignment:AlignmentType.CENTER,spacing:{after:200},children:[new TextRun({text:P.nama.toUpperCase(),bold:true,size:FS.coverSub,font:FF})]}),
      new Paragraph({alignment:AlignmentType.CENTER,children:[new TextRun({text:`TAHUN ${P.hA} – ${P.pZ}`,size:FS.coverYear,font:FF})]}),
    ]});

    // Sambutan
    sections.push({properties:{page:{...pg,pageNumbers:{start:2,formatType:NumberFormat.LOWER_ROMAN}}},footers:{default:mkFtr()},children:[
      new Paragraph({text:'SAMBUTAN',heading:HeadingLevel.HEADING_1,alignment:AlignmentType.CENTER,spacing:{after:400}}),
      new Paragraph({text:_fillTeks(TXT.sambutan, pData),spacing:{after:200}})
    ]});

    // Kata Pengantar
    sections.push({properties:{page:{...pg,pageNumbers:{formatType:NumberFormat.LOWER_ROMAN}}},footers:{default:mkFtr()},children:[
      new Paragraph({text:'KATA PENGANTAR',heading:HeadingLevel.HEADING_1,alignment:AlignmentType.CENTER,spacing:{after:400}}),
      new Paragraph({text:_fillTeks(TXT.kataPengantar, pData),spacing:{after:200}})
    ]});

    // Executive Summary
    sections.push({properties:{page:{...pg,pageNumbers:{formatType:NumberFormat.LOWER_ROMAN}}},footers:{default:mkFtr()},children:[
      new Paragraph({text:'EXECUTIVE SUMMARY',heading:HeadingLevel.HEADING_1,alignment:AlignmentType.CENTER,spacing:{after:400}}),
      new Paragraph({text:_fillTeks(TXT.execSummary, pData),spacing:{after:200}})
    ]});

    // Daftar Isi
    const mkDI=(text,indent=false)=>new Paragraph({children:[new TextRun({text:indent?'    '+text:text,font:FF,size:FS.paragraf}),new TextRun({text:'\t'}),new TextRun({text:''})],tabStops:[{type:TabStopType.RIGHT,position:TabStopPosition.MAX,leader:LeaderType.DOT}],spacing:{after:60}});
    const diItems=[mkDI('COVER'),mkDI('SAMBUTAN'),mkDI('KATA PENGANTAR'),mkDI('EXECUTIVE SUMMARY'),mkDI('DAFTAR ISI'),mkDI('DAFTAR TABEL'),mkDI('BAB I PENDAHULUAN')];
    [{c:'A',t:'Latar Belakang'},{c:'B',t:'Dasar Hukum'},{c:'C',t:'Tujuan'},{c:'D',t:'Metodologi'},{c:'E',t:'Sumber Data'},{c:'F',t:'Pengertian'},{c:'G',t:'Sistematika'}].forEach(x=>diItems.push(mkDI(`${x.c}. ${x.t}`,true)));
    filteredTOC.forEach(bab=>{
      diItems.push(mkDI(`${bab.bab} ${bab.title.toUpperCase()}`));
      bab.sections.forEach(sec=>diItems.push(mkDI(`${sec.code}. ${sec.title}`,true)));
    });
    diItems.push(mkDI('BAB VI KEBIJAKAN, STRATEGI DAN PROGRAM'));
    diItems.push(mkDI('BAB VII PENUTUP'));
    diItems.push(mkDI('DAFTAR PUSTAKA'));
    sections.push({properties:{page:{...pg,pageNumbers:{formatType:NumberFormat.LOWER_ROMAN}}},footers:{default:mkFtr()},children:[new Paragraph({text:'DAFTAR ISI',heading:HeadingLevel.HEADING_1,alignment:AlignmentType.CENTER,spacing:{after:400}}),...diItems]});

    // Daftar Tabel
    const dtItems=[new Paragraph({text:'DAFTAR TABEL',heading:HeadingLevel.HEADING_1,alignment:AlignmentType.CENTER,spacing:{after:400}})];
    filteredTOC.forEach(bab=>bab.sections.forEach(sec=>sec.tables.forEach(tbl=>dtItems.push(new Paragraph({children:[new TextRun({text:`Tabel ${tbl.no}  `,bold:true,font:FF,size:FS.caption}),new TextRun({text:tbl.name,font:FF,size:FS.caption})],spacing:{after:100}})))));
    sections.push({properties:{page:{...pg,pageNumbers:{formatType:NumberFormat.LOWER_ROMAN}}},footers:{default:mkFtr()},children:dtItems});

    // BAB I — Pendahuluan
    const b1 = TXT.bab1;
    sections.push({properties:{page:{...pg,pageNumbers:{start:1,formatType:NumberFormat.DECIMAL}}},footers:{default:mkFtr()},children:[
      new Paragraph({text:'BAB I',heading:HeadingLevel.HEADING_1,alignment:AlignmentType.CENTER}),
      new Paragraph({text:'PENDAHULUAN',heading:HeadingLevel.HEADING_1,alignment:AlignmentType.CENTER,spacing:{after:400}}),
      ...[
        ['A. Latar Belakang',  _fillTeks(b1.latarBelakang, pData)],
        ['B. Dasar Hukum',     _fillTeks(b1.dasarHukum, pData)],
        ['C. Tujuan',          _fillTeks(b1.tujuan, pData)],
        ['D. Metodologi',      _fillTeks(b1.metodologi, pData)],
        ['E. Sumber Data',     _fillTeks(b1.sumberData, pData)],
        ['F. Pengertian',      _fillTeks(b1.pengertian, pData)],
        ['G. Sistematika',     _fillTeks(b1.sistematika, pData)],
      ].flatMap(([sub,isi])=>[
        new Paragraph({text:sub,heading:HeadingLevel.HEADING_2,spacing:{before:240,after:100}}),
        new Paragraph({text:isi,spacing:{after:200}})
      ]),
    ]});

    // BAB II–V — Tabel Data
    filteredTOC.forEach(bab=>{
      const children=[
        new Paragraph({text:bab.bab,heading:HeadingLevel.HEADING_1,alignment:AlignmentType.CENTER}),
        new Paragraph({text:bab.title.toUpperCase(),heading:HeadingLevel.HEADING_1,alignment:AlignmentType.CENTER,spacing:{after:400}}),
      ];
      bab.sections.forEach(sec=>{
        children.push(new Paragraph({text:`${sec.code}. ${sec.title}`,heading:HeadingLevel.HEADING_2,spacing:{before:280,after:120}}));
        const isiBagianTxt = _fillTeks(TXT.isiBagian, { ...pData, code: sec.code, title: sec.title });
        if (isiBagianTxt) children.push(new Paragraph({text:isiBagianTxt,spacing:{after:160}}));
        sec.tables.forEach(tDef=>{
          // ── Catatan dari makeBimtekTable2 (sebelum judul tabel) ───
          const tblComments = P.lajuComments && P.lajuComments[tDef.no];
          const commentEntries = tblComments ? Object.entries(tblComments).filter(([,v])=>v) : [];
          const catatanTxt = commentEntries.length
            ? commentEntries.map(([label, note]) => `${label}: ${note}`).join('\n')
            : '...';
          children.push(new Paragraph({
            children:[
              new TextRun({text:'Catatan: ',bold:true,font:FF,size:FS.paragraf}),
              new TextRun({text:catatanTxt,font:FF,size:FS.paragraf,italics:!commentEntries.length,color:commentEntries.length?CLR.text:CLR.disabled})
            ],
            spacing:{before:240,after:60}
          }));
          children.push(new Paragraph({alignment:AlignmentType.CENTER,children:[new TextRun({text:`Tabel ${tDef.no} `,bold:true,font:FF,size:FS.caption}),new TextRun({text:tDef.name,font:FF,size:FS.caption})],spacing:{before:60,after:100}}));
          const wt=buildWordTbl(tDef.no);
          if(wt){children.push(wt);}
          else{
            const kosongTxt = _fillTeks(TXT.tabelKosong, { no: tDef.no });
            children.push(new Paragraph({children:[new TextRun({text:kosongTxt,italics:true,color:CLR.disabled,size:FS.tabel})],spacing:{after:160}}));
          }
        });
      });
      sections.push({properties:{page:{...pg,pageNumbers:{formatType:NumberFormat.DECIMAL}}},footers:{default:mkFtr()},children});
    });

    // BAB VI — Kebijakan
    const bab6subs = [
      {c:'A',t:'Kebijakan Umum'},
      {c:'B',t:'Kebijakan Pengendalian Tambahan Angkatan Kerja'},
      {c:'C',t:'Kebijakan Pendidikan dan Pelatihan'},
      {c:'D',t:'Kebijakan Sektoral'},
      {c:'E',t:'Kebijakan Penempatan Tenaga Kerja'},
      {c:'F',t:'Kebijakan Hubungan Industrial dan Jaminan Sosial'},
      {c:'G',t:'Kebijakan Pengawasan'},
      {c:'H',t:'Kebijakan Lainnya'},
    ];
    sections.push({properties:{page:{...pg,pageNumbers:{formatType:NumberFormat.DECIMAL}}},footers:{default:mkFtr()},children:[
      new Paragraph({text:'BAB VI',heading:HeadingLevel.HEADING_1,alignment:AlignmentType.CENTER}),
      new Paragraph({text:'KEBIJAKAN, STRATEGI DAN PROGRAM PEMBANGUNAN KETENAGAKERJAAN',heading:HeadingLevel.HEADING_1,alignment:AlignmentType.CENTER,spacing:{after:400}}),
      ...bab6subs.flatMap(x=>[
        new Paragraph({text:`${x.c}. ${x.t}`,heading:HeadingLevel.HEADING_2,spacing:{before:200,after:100}}),
        new Paragraph({text:_fillTeks(TXT.isiKebijakan, { ...pData, sub: x.t }),spacing:{after:160}})
      ])
    ]});

    // BAB VII — Penutup
    sections.push({properties:{page:{...pg,pageNumbers:{formatType:NumberFormat.DECIMAL}}},footers:{default:mkFtr()},children:[
      new Paragraph({text:'BAB VII',heading:HeadingLevel.HEADING_1,alignment:AlignmentType.CENTER}),
      new Paragraph({text:'PENUTUP',heading:HeadingLevel.HEADING_1,alignment:AlignmentType.CENTER,spacing:{after:400}}),
      new Paragraph({text:_fillTeks(TXT.penutup, pData),spacing:{after:200}})
    ]});

    // Daftar Pustaka
    sections.push({properties:{page:pg},footers:{default:mkFtr()},children:[
      new Paragraph({text:'DAFTAR PUSTAKA',heading:HeadingLevel.HEADING_1,alignment:AlignmentType.CENTER,spacing:{after:400}}),
      new Paragraph({text:_fillTeks(TXT.daftarPustaka, pData),spacing:{after:200}})
    ]});

    const doc=new Document({
      styles:{
        default:{document:{run:{font:FF,size:FS.paragraf,color:CLR.text}}},
        paragraphStyles:[
          {id:'Heading1',name:'Heading 1',basedOn:'Normal',next:'Normal',quickFormat:true,
           run:{font:FF,size:FS.bab,bold:true,color:CLR.text},
           paragraph:{spacing:{before:240,after:120}}},
          {id:'Heading2',name:'Heading 2',basedOn:'Normal',next:'Normal',quickFormat:true,
           run:{font:FF,size:FS.subBab,bold:true,color:CLR.text},
           paragraph:{spacing:{before:180,after:100}}}
        ]
      },
      sections
    });
    const blob=await Packer.toBlob(doc);
    const fname=isKabKota?`RTK_KabKota_${P.nama.replace(/\s+/g,'_')}.docx`:`RTK_Provinsi_${P.nama.replace(/\s+/g,'_')}.docx`;
    saveAs(blob,fname);
    st.innerHTML=`<div class="msg msg-ok">✅ Berhasil diunduh: <strong>${fname}</strong></div>`;
  }catch(err){
    document.getElementById('stDl').innerHTML=`<div class="msg msg-err">❌ Error: ${err.message}</div>`;
    console.error(err);
  }
}
