/* ══════════════════════════════════════════
   step2-parameter.js — RTK Makro v8
   Langkah 2: isi dropdown tahun, konfirmasi
   parameter, dan pemicu komputasi proyeksi
   ══════════════════════════════════════════ */

// ── Isi dropdown tahun dari hasil parsing ──
function fillDrops() {
  function fill(id, arr, def) { const s = document.getElementById(id); s.innerHTML = arr.map(y => `<option value="${y}">${y}</option>`).join(''); if (def !== undefined) s.value = def; }
  
  if (histYrs.length) { 
      fill('sHA', histYrs, histYrs[0]); 
      fill('sHZ', histYrs, histYrs[histYrs.length - 1]); 
      fill('sTD', histYrs, histYrs[histYrs.length - 1]); 
  }
  
  // Buat array pilihan tahun yang panjang untuk Proyeksi (agar user bisa bebas memilih)
  const baseTahun = histYrs.length ? histYrs[histYrs.length - 1] : new Date().getFullYear();
  const defPA = projYrs.length ? projYrs[0] : baseTahun + 1;
  const defPZ = projYrs.length ? projYrs[projYrs.length - 1] : baseTahun + 5;
  
  const wideYrs = [];
  for (let y = baseTahun - 10; y <= baseTahun + 25; y++) wideYrs.push(y);
  
  fill('sPA', wideYrs, defPA); 
  fill('sPZ', wideYrs, defPZ);

  document.getElementById('infoTD').textContent = '→ Batas historis ↔ proyeksi';

  // Otomatisasi: HZ berubah -> PA = HZ + 1, PZ = HZ + 5, TD = HZ
  const elHZ = document.getElementById('sHZ');
  if (elHZ) {
      elHZ.onchange = function() {
          const valHZ = parseInt(this.value);
          if (!isNaN(valHZ)) {
              const elPA = document.getElementById('sPA');
              const elPZ = document.getElementById('sPZ');
              const elTD = document.getElementById('sTD');
              
              if (elPA) {
                  const targetPA = valHZ + 1;
                  if (Array.from(elPA.options).some(o => parseInt(o.value) === targetPA)) {
                      elPA.value = targetPA;
                  }
              }
              if (elPZ) {
                  const targetPZ = valHZ + 5;
                  if (Array.from(elPZ.options).some(o => parseInt(o.value) === targetPZ)) {
                      elPZ.value = targetPZ;
                  }
              }
              if (elTD) {
                  if (Array.from(elTD.options).some(o => parseInt(o.value) === valHZ)) {
                      elTD.value = valHZ;
                  }
              }
          }
      };
  }
}

// ── Konfirmasi parameter dan lanjut ke Langkah 3 ──
function konfirmasi() {
  const nama = document.getElementById('inNama').value.trim();
  const hA = parseInt(document.getElementById('sHA').value), hZ = parseInt(document.getElementById('sHZ').value);
  const pA = parseInt(document.getElementById('sPA').value), pZ = parseInt(document.getElementById('sPZ').value);
  const td = parseInt(document.getElementById('sTD').value);
  if (!nama) { alert('Nama daerah harus diisi!'); return; }
  if (isNaN(hA) || isNaN(hZ) || hA > hZ) { alert('Rentang historis tidak valid!'); return; }
  P = {
    nama, hA, hZ,
    pA: isNaN(pA) ? hZ + 1 : pA,
    pZ: isNaN(pZ) ? hZ + 5 : pZ,
    td: isNaN(td) ? hZ : td,
    // Pertahankan data yang sudah diisi sebelum konfirmasi (override manual & import Excel)
    targets:       P.targets       || {},
    targetImports: P.targetImports || {},
    lajuOverrides: P.lajuOverrides || {},
  };
  saveStateToLS();
  document.getElementById('stPrm').innerHTML = `<div class="msg msg-ok">✅ <strong>${nama}</strong> | Historis ${hA}–${hZ} | Proyeksi ${P.pA}–${P.pZ} | Dasar: ${P.td}</div>`;
  goTo(3);
  document.getElementById('spin').classList.add('on');
  document.getElementById('s3area').style.display = 'none';
  document.getElementById('s3act').style.display = 'none';
  setTimeout(() => {
    buildComputed();
    buildStep3();
    document.getElementById('spin').classList.remove('on');
    document.getElementById('s3area').style.display = 'block';
    document.getElementById('s3act').style.display = 'block';
    updateCards();
    document.getElementById('sumInfo').innerHTML = `<strong>${P.nama}</strong> | Historis ${P.hA}–${P.hZ} | Proyeksi ${P.pA}–${P.pZ}`;
    const s3Param = document.getElementById('s3-param-info');
    if (s3Param) {
        s3Param.innerHTML = `✅ <strong>${P.nama}</strong> | Historis ${P.hA}–${P.hZ} | Proyeksi ${P.pA}–${P.pZ} | Tahun Dasar ${P.td}`;
        s3Param.style.display = 'block';
    }
    document.getElementById('btnProv').disabled = false;
    document.getElementById('btnKab').disabled = false;
    document.getElementById('btnXls').disabled = false;
    document.getElementById('btnLaju').disabled = false;
  }, 100);
}
