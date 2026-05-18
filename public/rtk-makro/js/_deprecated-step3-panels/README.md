# Folder Arsip — Panel Renderer Step 3 (Lama)

Folder ini menyimpan file panel renderer Step 3 **versi lama** yang sudah
**tidak dipakai lagi** oleh aplikasi RTK Makro v8.

## Tanggal arsip
2026-04-11

## Alasan diarsipkan
Pada sesi refaktor 2026-04-11, Step 3 dipecah menjadi **satu file JS per
indikator** (PUK, TPAK, AK, PDRB, Elastisitas, KK, PT, TPT, Data Dukung)
agar lebih mudah dikelola secara modular. File-file di folder ini adalah
versi generic yang melayani banyak indikator sekaligus dan sekarang
digantikan oleh file `js/step3-indikator-*.js`.

## File di folder ini

| File lama | Fungsi lama | Digantikan oleh |
| --- | --- | --- |
| `step3-panels.js` | Stub/deprecated comment saja | — (kosong) |
| `step3-panels-standard.js` | `renderStandardPanel(charDef)` + `_addTargetRows()` — melayani PUK, TPAK (Bimtek), AK, KK, PT, TPT | `step3-indikator-puk.js`, `step3-indikator-tpak.js`, `step3-indikator-ak.js`, `step3-indikator-kk.js`, `step3-indikator-pt.js`, `step3-indikator-tpt.js` |
| `step3-panels-special.js` | `renderPdrbPanel()`, `renderElastisitasPanel()`, `renderPelengkapPanel()` | `step3-indikator-pdrb.js`, `step3-indikator-elastisitas.js`, `step3-indikator-pelengkap.js` |

## Catatan penting

- **File-file ini TIDAK dimuat di `index.html`.** Aman jika tetap disimpan
  sebagai referensi.
- **Jangan digunakan lagi** — semua perbaikan & pengembangan Step 3
  dilakukan di file `step3-indikator-*.js` yang baru.
- **Fungsi global yang dihapus dari aplikasi utama:**
  - `renderStandardPanel()` — diganti per indikator
  - `renderPdrbPanel()` — dipindah ke `step3-indikator-pdrb.js` dengan nama `renderIndikatorPdrb()`
  - `renderElastisitasPanel()` — dipindah ke `step3-indikator-elastisitas.js` dengan nama `renderIndikatorElastisitas()`
  - `renderPelengkapPanel()` — dipindah ke `step3-indikator-pelengkap.js` dengan nama `renderIndikatorPelengkap()`
  - `_addTargetRows()` — disalin secara lokal ke masing-masing file indikator yang membutuhkannya (AK, KK, TPT)

## Jika perlu rollback

1. Pindahkan 3 file `step3-panels*.js` dari folder ini kembali ke `js/`
2. Hapus file-file `js/step3-indikator-*.js`
3. Edit `index.html` — kembalikan referensi ke `step3-panels-standard.js`
   dan `step3-panels-special.js`, hapus referensi ke `step3-indikator-*.js`
4. Edit `js/step3-main.js` — kembalikan dispatcher lama yang memanggil
   `renderStandardPanel/renderPdrbPanel/renderElastisitasPanel/renderPelengkapPanel`
