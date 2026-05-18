/* ══════════════════════════════════════════
   step3-indikator-tpak.js — RTK Makro v8
   Render panel indikator TPAK (Tingkat Partisipasi Angkatan Kerja)

   Menggunakan helper `_renderBimtekPanel()` yang sama dengan PUK
   (didefinisikan di step3-indikator-puk.js). Mode PUK vs TPAK
   dideteksi otomatis dari prefix histCode (2.C.* = PUK, 2.D.* = TPAK).
   ══════════════════════════════════════════ */

// ══════════════════════════════════════════════════════════════
// RENDER PANEL INDIKATOR TPAK
// ══════════════════════════════════════════════════════════════
function renderIndikatorTpak(charDef) {
  return _renderBimtekPanel(charDef);
}
