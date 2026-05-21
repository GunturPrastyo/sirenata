/* ══════════════════════════════════════════════════════════════════
   db-save.js — RTK Makro
   Pola sederhana: serialize seluruh state localStorage → gzip+base64
   → POST satu blob ke api/save.php (REPLACE INTO).

   Kelebihan:
   • Tidak perlu mapping per-tabel — apa pun yang ditambah ke LS di
     masa depan otomatis ikut tersimpan.
   • Ukuran kecil (~85% lebih kecil dari JSON mentah berkat gzip).
   • Restorasi cukup: setItem('rtk_state', blob) → location.reload()
     → auto-restore di DOMContentLoaded melakukan sisanya.
   ══════════════════════════════════════════════════════════════════ */

const DB_API_URL = (typeof RTK_API_BASE !== 'undefined' ? RTK_API_BASE : '') + '/api/rtk/save';

async function saveToDatabase() {
  if (!IS_SERVER) {
    _dbStatus('err', '⚠️ Fitur database memerlukan web server. Akses via <code>http://localhost/...</code>');
    return;
  }
  if (!rawSheets || !Object.keys(rawSheets).length || !P || !P.nama) {
    _dbStatus('err', 'Data belum tersedia. Upload Excel & konfirmasi parameter dulu.');
    return;
  }

  // Pastikan state terbaru di localStorage
  saveStateToLS();
  const lsRaw = localStorage.getItem(LS_KEY) || localStorage.getItem('rtk_state');
  if (!lsRaw) { _dbStatus('err', 'localStorage kosong — tidak ada yang disimpan.'); return; }

  const kodeUser = (typeof RTK_PROJECT_ID !== 'undefined' && RTK_PROJECT_ID) ? String(RTK_PROJECT_ID) : ((P.kodeUser || '').trim() || 'e');

  _dbBtn('loading');
  _dbStatus('info', '⏳ Mengompresi & mengirim data...');

  try {
    // ── 1. Kompres dengan pako (gzip) → base64 ────────────────────
    const blob = _compressString(lsRaw);

    // ── 2. Kirim metadata + blob ──────────────────────────────────
    const payload = {
      kode_user:   kodeUser,
      project_id:  (typeof RTK_PROJECT_ID !== 'undefined') ? RTK_PROJECT_ID : null,
      nama_daerah: P.nama,
      hA:          P.hA || 0,
      hZ:          P.hZ || 0,
      pA:          P.pA || 0,
      pZ:          P.pZ || 0,
      jml_sheet:   Object.keys(rawSheets).length,
      data:        blob,
    };

    const headers = { 'Content-Type': 'application/json' };
    if (typeof RTK_CSRF_TOKEN !== 'undefined') headers['X-CSRF-TOKEN'] = RTK_CSRF_TOKEN;
    const res  = await fetch(DB_API_URL, {
      method:  'POST',
      headers: headers,
      body:    JSON.stringify(payload),
    });
    const text = await res.text();
    let json;
    try { json = JSON.parse(text); }
    catch {
      _dbBtn('idle');
      _dbStatus('err', `❌ Respons server tidak valid:<br><small style="font-family:monospace">${text.slice(0,200)}</small>`);
      return;
    }

    if (json.success) {
      // Catat kode_user di P agar konsisten di Save berikutnya
      P.kodeUser = kodeUser;
      P.userId   = json.user_id;
      saveStateToLS();

      _dbBtn('ok');
      const btnLoad = document.getElementById('btnDbLoad');
      if (btnLoad) btnLoad.disabled = false;

      const sizeKB = (json.size_bytes / 1024).toFixed(1);
      const ratio  = (json.size_bytes / lsRaw.length * 100).toFixed(0);
      _dbStatus('ok',
        `✅ <strong>Tersimpan!</strong> &nbsp;·&nbsp; ` +
        `<strong>${P.nama}</strong> (kode: <code>${kodeUser}</code>) &nbsp;·&nbsp; ` +
        `${sizeKB} KB <span style="opacity:.7">(${ratio}% dari raw)</span>`
      );
    } else {
      _dbBtn('idle');
      _dbStatus('err', `❌ Gagal: ${json.error || 'Error tidak diketahui'}`);
    }

  } catch (err) {
    _dbBtn('idle');
    _dbStatus('err',
      '❌ Tidak dapat terhubung ke server.<br>' +
      '<small>Pastikan jalan via XAMPP/Laragon, bukan <code>file://</code>. Detail: ' + err.message + '</small>'
    );
  }
}

// ══════════════════════════════════════════════════════════════════
// Helper kompresi
// ══════════════════════════════════════════════════════════════════
function _compressString(str) {
  // pako.deflate menerima Uint8Array → encode UTF-8 dulu
  const utf8 = new TextEncoder().encode(str);
  const gz   = pako.deflate(utf8);
  // Konversi ke base64 (chunked untuk hindari stack overflow di string besar)
  let bin = '';
  const chunk = 0x8000;
  for (let i = 0; i < gz.length; i += chunk) {
    bin += String.fromCharCode.apply(null, gz.subarray(i, i + chunk));
  }
  return btoa(bin);
}

function _decompressString(b64) {
  const bin  = atob(b64);
  const arr  = new Uint8Array(bin.length);
  for (let i = 0; i < bin.length; i++) arr[i] = bin.charCodeAt(i);
  const utf8 = pako.inflate(arr);
  return new TextDecoder().decode(utf8);
}

// ══════════════════════════════════════════════════════════════════
// UI helpers
// ══════════════════════════════════════════════════════════════════
function _dbBtn(state) {
  const btn = document.getElementById('btnDbSave');
  if (!btn) return;
  const map = {
    loading: { text: '⏳ Menyimpan...', disabled: true  },
    ok:      { text: '✅ Tersimpan',    disabled: false },
    idle:    { text: '🗄 Save',          disabled: false },
  };
  const s = map[state] || map.idle;
  btn.innerHTML = s.text;
  btn.disabled  = s.disabled;
  btn.classList.toggle('ok', state === 'ok');
  if (state === 'ok') setTimeout(() => _dbBtn('idle'), 5000);
}

let _toastTimer = null;
function _dbStatus(type, msg) {
  const el = document.getElementById('db-toast');
  if (!el) return;
  el.className     = type;
  el.innerHTML     = msg;
  el.style.display = 'block';
  if (_toastTimer) clearTimeout(_toastTimer);
  _toastTimer = setTimeout(() => { el.style.display = 'none'; }, type === 'err' ? 9000 : 6000);
}
