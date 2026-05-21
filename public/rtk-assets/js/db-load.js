/* ══════════════════════════════════════════════════════════════════
   db-load.js — RTK Makro
   Pola sederhana:
     1. fetch daftar user dari api/sessions.php (metadata saja)
     2. user pilih → fetch blob dari api/load.php
     3. decompress → setItem('rtk_state', json) → location.reload()
        → DOMContentLoaded auto-restore lakukan sisanya
   ══════════════════════════════════════════════════════════════════ */

const DB_SESSIONS_URL = (typeof RTK_API_BASE !== 'undefined' ? RTK_API_BASE : '') + '/api/rtk/sessions';
const DB_LOAD_URL     = (typeof RTK_API_BASE !== 'undefined' ? RTK_API_BASE : '') + '/api/rtk/load';

// ── Cek koneksi DB saat halaman dimuat (best-effort) ─────────────
async function checkDbHasSessions() {
  if (!IS_SERVER) return;
  try {
    const res  = await fetch(DB_SESSIONS_URL);
    const json = await res.json();
    if (json.success) {
      const btn = document.getElementById('btnDbLoad');
      if (btn) btn.disabled = false;
    }
  } catch (e) { /* abaikan */ }
}

// ── Buka modal ───────────────────────────────────────────────────
async function openLoadModal() {
  if (!IS_SERVER) {
    alert('Fitur database memerlukan web server.\nAkses via http://localhost/...');
    return;
  }
  const modal = document.getElementById('load-modal');
  modal.style.display = 'flex';
  document.body.style.overflow = 'hidden';
  await _fetchUsers('');
}

function closeLoadModal() {
  document.getElementById('load-modal').style.display = 'none';
  document.body.style.overflow = '';
}

// ── Fetch daftar user ────────────────────────────────────────────
async function _fetchUsers(q) {
  const tbody = document.getElementById('load-table-body');
  const empty = document.getElementById('load-empty');
  const err   = document.getElementById('load-err');
  tbody.innerHTML = `<tr><td colspan="6" style="text-align:center;padding:24px;color:var(--muted)">⏳ Memuat daftar...</td></tr>`;
  empty.style.display = 'none';
  if (err) err.style.display = 'none';

  try {
    const url  = DB_SESSIONS_URL + (q ? '?q=' + encodeURIComponent(q) : '');
    const res  = await fetch(url);
    const json = await res.json();
    if (!json.success) throw new Error(json.error || 'Gagal mengambil daftar');
    _renderUserRows(json.users || []);
  } catch (e) {
    tbody.innerHTML = '';
    if (err) { err.textContent = '❌ ' + e.message; err.style.display = 'block'; }
  }
}

// ── Render baris ─────────────────────────────────────────────────
function _renderUserRows(users) {
  const tbody = document.getElementById('load-table-body');
  const empty = document.getElementById('load-empty');

  if (!users || users.length === 0) {
    tbody.innerHTML = '';
    empty.style.display = 'block';
    return;
  }

  tbody.innerHTML = users.map(u => {
    const tgl = u.updated_at
      ? new Date(u.updated_at).toLocaleString('id-ID', { day:'2-digit', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit' })
      : '—';
    const histRange = u.tahun_hist_awal > 0 ? `${u.tahun_hist_awal}–${u.tahun_hist_akhir}` : '—';
    const projRange = u.tahun_proj_awal > 0 ? `${u.tahun_proj_awal}–${u.tahun_proj_akhir}` : '—';
    const sizeKB    = (u.size_bytes / 1024).toFixed(1);
    return `<tr>
      <td style="font-size:12px"><code>${_esc(u.kode_user)}</code></td>
      <td><strong>${_esc(u.nama_daerah)}</strong></td>
      <td style="font-size:12px">${histRange}</td>
      <td style="font-size:12px">${projRange}</td>
      <td style="font-size:12px;color:var(--muted)">${tgl}<br>
        <span style="font-size:11px">${u.jml_sheet} sheet · ${sizeKB} KB</span>
      </td>
      <td style="white-space:nowrap">
        <button class="btn-load-row" onclick="loadUser(${u.id}, '${_esc(u.nama_daerah)}')">📂 Muat</button>
        <button class="btn-load-del" onclick="deleteUser(${u.id}, '${_esc(u.kode_user)}', '${_esc(u.nama_daerah)}')" title="Hapus data ini">🗑</button>
      </td>
    </tr>`;
  }).join('');
}

// ── Muat satu user ───────────────────────────────────────────────
async function loadUser(userId, nama) {
  if (!confirm(`Muat data "${nama}" dari database?\n\nData yang sedang aktif di browser akan digantikan.`)) return;

  const tbody = document.getElementById('load-table-body');
  tbody.innerHTML = `<tr><td colspan="6" style="text-align:center;padding:24px;color:var(--muted)">⏳ Mengambil & dekompresi data...</td></tr>`;

  try {
    const res  = await fetch(DB_LOAD_URL + '?id=' + userId);
    const text = await res.text();
    let json;
    try { json = JSON.parse(text); }
    catch { throw new Error('Respons server tidak valid: ' + text.slice(0, 100)); }

    if (!json.success) throw new Error(json.error || 'Gagal memuat data');
    if (!json.data)    throw new Error('Blob data kosong');

    // ── Decompress blob ──────────────────────────────────────────
    let lsJson;
    try {
      lsJson = _decompressString(json.data);
    } catch (e) {
      throw new Error('Gagal dekompresi: ' + e.message);
    }

    // Validasi: harus JSON valid yang punya struktur LS RTK
    let parsed;
    try { parsed = JSON.parse(lsJson); }
    catch { throw new Error('Data setelah dekompresi bukan JSON valid'); }

    if (!parsed.rawSheets || !parsed.P) {
      throw new Error('Struktur data tidak valid (rawSheets / P tidak ditemukan)');
    }

    // ── Tulis ke localStorage & reload halaman ───────────────────
    localStorage.setItem('rtk_state', lsJson);
    closeLoadModal();

    // Tampilkan toast singkat sebelum reload
    _dbStatus('ok', `✅ Data <strong>${_esc(json.nama_daerah)}</strong> dimuat. Memuat ulang halaman...`);
    setTimeout(() => location.reload(), 600);

  } catch (e) {
    const err = document.getElementById('load-err');
    if (err) { err.textContent = '❌ ' + e.message; err.style.display = 'block'; }
    await _fetchUsers('');
  }
}

// ── Pencarian ────────────────────────────────────────────────────
let _searchTimer = null;
function onLoadSearch(val) {
  clearTimeout(_searchTimer);
  _searchTimer = setTimeout(() => _fetchUsers(val.trim()), 350);
}

// ── Hapus user ───────────────────────────────────────────────────
async function deleteUser(userId, kode, nama) {
  if (!confirm(`Hapus data "${nama}" (kode: ${kode}) secara permanen?`)) return;

  try {
    const headers = { 'Content-Type': 'application/json' };
    if (typeof RTK_CSRF_TOKEN !== 'undefined') headers['X-CSRF-TOKEN'] = RTK_CSRF_TOKEN;
    const res  = await fetch((typeof RTK_API_BASE !== 'undefined' ? RTK_API_BASE : '') + '/api/rtk/delete', {
      method:  'POST',
      headers: headers,
      body:    JSON.stringify({ user_id: userId }),
    });
    const json = await res.json();
    if (json.success) {
      _dbStatus('ok', `🗑 Data <strong>${_esc(nama)}</strong> dihapus`);
      await _fetchUsers('');
    } else {
      alert('Gagal menghapus: ' + (json.error || 'Error'));
    }
  } catch (e) {
    alert('Gagal terhubung ke server: ' + e.message);
  }
}

// ── Escape HTML ──────────────────────────────────────────────────
function _esc(s) {
  return String(s || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
