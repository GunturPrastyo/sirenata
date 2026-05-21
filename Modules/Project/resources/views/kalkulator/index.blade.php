<?php
// No native session needed — Laravel handles auth
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Aplikasi RTK Daerah — Kemnaker RI</title>
  <link rel="stylesheet" href="{{ asset('rtk-assets/css/shared.css') }}">
  <link rel="stylesheet" href="{{ asset('rtk-assets/css/step2-parameter.css') }}">
  <link rel="stylesheet" href="{{ asset('rtk-assets/css/step3-tabel-terpadu.css') }}">
  <link rel="stylesheet" href="{{ asset('rtk-assets/css/step4-download.css') }}">
  <script>
    var RTK_MODE = '{{ $mode ?? "sandbox" }}';
    var RTK_PROJECT_ID = {{ $projectId ?? 'null' }};
    var RTK_API_BASE = '{{ rtrim(url("/"), "/") }}';
    var RTK_CSRF_TOKEN = '{{ csrf_token() }}';
  </script>

  <style>
    /* ── HERO BANNER (Step 1 hanya) ── */
    #hero-banner {
      background: linear-gradient(135deg, #0b1f3a 0%, #152d52 60%, #0f2547 100%);
      border-radius: 12px;
      padding: 26px 32px;
      margin-bottom: 24px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 24px;
      overflow: hidden;
      position: relative;
      box-shadow: 0 8px 32px rgba(11,31,58,.25);
    }
    #hero-banner::before {
      content: '';
      position: absolute;
      top: -60px; right: -60px;
      width: 280px; height: 280px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(59,130,246,.25) 0%, transparent 70%);
      pointer-events: none;
    }
    #hero-banner::after {
      content: '';
      position: absolute;
      bottom: -40px; left: 30%;
      width: 200px; height: 200px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(16,163,74,.12) 0%, transparent 70%);
      pointer-events: none;
    }
    .hero-left { flex: 1; position: relative; z-index: 1; }
    .hero-eyebrow {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: rgba(59,130,246,.2);
      border: 1px solid rgba(59,130,246,.35);
      color: #93c5fd;
      font-size: 11px;
      font-weight: 700;
      letter-spacing: .6px;
      text-transform: uppercase;
      padding: 4px 12px;
      border-radius: 20px;
      margin-bottom: 14px;
    }
    .hero-eyebrow span { width: 6px; height: 6px; border-radius: 50%; background: #60a5fa; display: inline-block; }
    .hero-title {
      font-size: 22px;
      font-weight: 800;
      color: white;
      line-height: 1.25;
      letter-spacing: -.5px;
      margin-bottom: 10px;
    }
    .hero-title em { color: #60a5fa; font-style: normal; }
    .hero-desc {
      font-size: 13px;
      color: rgba(255,255,255,.55);
      line-height: 1.65;
      max-width: 440px;
    }
    .hero-stats {
      display: flex;
      gap: 24px;
      margin-top: 22px;
    }
    .hero-stat { text-align: center; }
    .hero-stat-num {
      font-size: 18px;
      font-weight: 800;
      color: white;
      letter-spacing: -.5px;
      line-height: 1;
    }
    .hero-stat-lbl {
      font-size: 10.5px;
      color: rgba(255,255,255,.45);
      font-weight: 500;
      margin-top: 3px;
      white-space: nowrap;
    }
    .hero-stat-div {
      width: 1px;
      background: rgba(255,255,255,.12);
      align-self: stretch;
    }
    .hero-right {
      position: relative;
      z-index: 1;
      flex-shrink: 0;
    }
    .hero-visual {
      width: 160px;
      height: 120px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .hero-chart-svg { width: 100%; height: 100%; }

    /* ── STEP 1 upload re-style ── */
    .upload-zone { max-width: 600px; }
    .s1-two-col {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
      align-items: start;
    }
    .s1-info-panel {
      background: #f8fafc;
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 22px;
    }
    .s1-info-title {
      font-size: 13px;
      font-weight: 700;
      color: var(--navy);
      margin-bottom: 14px;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .s1-info-title svg { flex-shrink: 0; }
    .s1-step-list { list-style: none; display: flex; flex-direction: column; gap: 10px; }
    .s1-step-list li {
      display: flex;
      align-items: flex-start;
      gap: 10px;
      font-size: 12.5px;
      color: var(--muted);
      line-height: 1.5;
    }
    .s1-step-num {
      width: 22px; height: 22px;
      border-radius: 50%;
      background: var(--blue);
      color: white;
      font-size: 11px;
      font-weight: 700;
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
      margin-top: 1px;
    }
    .s1-format-chips { display: flex; gap: 6px; flex-wrap: wrap; margin-top: 14px; }
    .s1-chip {
      background: white;
      border: 1px solid var(--border);
      border-radius: 6px;
      padding: 4px 10px;
      font-size: 11px;
      font-weight: 600;
      color: var(--muted);
      display: flex; align-items: center; gap: 4px;
    }
    .s1-chip.green { background: #f0fdf4; border-color: #bbf7d0; color: #166534; }

    /* ── Step 2 custom ── */
    #s2 .nama-wrap {
      background: linear-gradient(135deg, #f0f7ff, #f8fafc);
      border: 1.5px solid #bfdbfe;
      border-radius: 12px;
      padding: 18px 20px;
      margin-bottom: 22px;
    }
    #s2 .nama-wrap label { color: var(--blue); }
    #s2 .nama-wrap input { background: white; }

    /* ── Step 4 download cards re-style ── */
    .dl-card {
      border-radius: 14px;
      padding: 26px;
      background: linear-gradient(145deg, #fff, #f8fafc);
    }
    .dl-card-header {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 14px;
    }
    .dl-card-icon {
      width: 48px; height: 48px;
      border-radius: 12px;
      display: flex; align-items: center; justify-content: center;
      font-size: 22px;
      flex-shrink: 0;
    }
    .dl-icon-prov { background: linear-gradient(135deg, #dbeafe, #eff6ff); }
    .dl-icon-kab  { background: linear-gradient(135deg, #dcfce7, #f0fdf4); }
    .dl-icon-xls  { background: linear-gradient(135deg, #fef3c7, #fffbeb); }
    .dl-icon-laju { background: linear-gradient(135deg, #e0f2fe, #f0f9ff); }

    /* ── TWEAKS PANEL ── */
    #tweaks-panel {
      position: fixed;
      bottom: 24px;
      right: 24px;
      z-index: 500;
      background: white;
      border: 1px solid var(--border);
      border-radius: 16px;
      box-shadow: 0 8px 40px rgba(0,0,0,.18);
      width: 280px;
      padding: 20px;
      display: none;
      flex-direction: column;
      gap: 16px;
      animation: slide-up .2s ease;
    }
    #tweaks-panel.open { display: flex; }
    @keyframes slide-up {
      from { opacity: 0; transform: translateY(12px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .tweaks-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      font-size: 13px;
      font-weight: 800;
      color: var(--navy);
    }
    .tweaks-close {
      width: 26px; height: 26px;
      border-radius: 6px;
      background: #f1f5f9;
      border: none;
      cursor: pointer;
      display: flex; align-items: center; justify-content: center;
      font-size: 14px; color: var(--muted);
      padding: 0;
      box-shadow: none;
      transition: background .15s;
    }
    .tweaks-close:hover { background: #e2e8f0; }
    .tweaks-section { display: flex; flex-direction: column; gap: 10px; }
    .tweaks-label {
      font-size: 10.5px;
      font-weight: 700;
      color: var(--muted);
      text-transform: uppercase;
      letter-spacing: .5px;
    }
    .tweak-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      font-size: 12.5px;
      color: var(--text);
      font-weight: 500;
    }
    .tweak-toggle {
      width: 36px; height: 20px;
      border-radius: 10px;
      background: #e2e8f0;
      cursor: pointer;
      position: relative;
      border: none;
      transition: background .2s;
      padding: 0;
      box-shadow: none;
    }
    .tweak-toggle::after {
      content: '';
      position: absolute;
      top: 2px; left: 2px;
      width: 16px; height: 16px;
      border-radius: 50%;
      background: white;
      box-shadow: 0 1px 3px rgba(0,0,0,.2);
      transition: transform .2s;
    }
    .tweak-toggle.on { background: var(--blue); }
    .tweak-toggle.on::after { transform: translateX(16px); }
    .tweak-swatch-row { display: flex; gap: 6px; }
    .tweak-swatch {
      width: 26px; height: 26px;
      border-radius: 6px;
      cursor: pointer;
      border: 2px solid transparent;
      transition: all .15s;
    }
    .tweak-swatch.active { border-color: var(--navy); transform: scale(1.1); }

    /* ── DB Save — tombol di topbar ── */
    .btn-db-topbar {
      display: flex;
      align-items: center;
      gap: 6px;
      background: rgba(59,130,246,.18);
      border: 1px solid rgba(59,130,246,.4);
      color: #93c5fd;
      padding: 0 14px;
      height: 34px;
      border-radius: 8px;
      font-size: 12.5px;
      font-weight: 700;
      cursor: pointer;
      font-family: var(--font);
      transition: background .15s, color .15s, border-color .15s;
      white-space: nowrap;
    }
    .btn-db-topbar:hover:not(:disabled) {
      background: rgba(59,130,246,.32);
      color: #bfdbfe;
      border-color: rgba(59,130,246,.65);
    }
    .btn-db-topbar:disabled {
      opacity: .35;
      cursor: not-allowed;
    }
    .btn-db-topbar.ok {
      background: rgba(16,185,129,.2);
      border-color: rgba(16,185,129,.45);
      color: #6ee7b7;
    }

    /* ── Load Modal ── */
    #load-modal {
      display: none;
      position: fixed;
      inset: 0;
      z-index: 900;
      background: rgba(11,31,58,.55);
      backdrop-filter: blur(3px);
      align-items: center;
      justify-content: center;
      padding: 20px;
    }
    .load-modal-box {
      background: white;
      border-radius: 18px;
      width: 100%;
      max-width: 760px;
      max-height: 88vh;
      display: flex;
      flex-direction: column;
      box-shadow: 0 24px 80px rgba(11,31,58,.3);
      overflow: hidden;
    }
    .load-modal-head {
      padding: 18px 22px 14px;
      border-bottom: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      flex-shrink: 0;
    }
    .load-modal-head-left { display: flex; align-items: center; gap: 10px; }
    .load-modal-icon {
      width: 36px; height: 36px;
      background: linear-gradient(135deg,#1a56db,#3b82f6);
      border-radius: 9px;
      display: flex; align-items: center; justify-content: center;
      font-size: 17px; flex-shrink: 0;
    }
    .load-modal-title { font-size: 15px; font-weight: 800; color: var(--navy); }
    .load-modal-sub   { font-size: 11.5px; color: var(--muted); margin-top: 1px; }
    .load-modal-close {
      width: 30px; height: 30px; border-radius: 8px;
      background: #f1f5f9; border: none; cursor: pointer;
      display: flex; align-items: center; justify-content: center;
      font-size: 15px; color: var(--muted);
      transition: background .15s; box-shadow: none; flex-shrink: 0;
    }
    .load-modal-close:hover { background: #e2e8f0; }
    .load-search-wrap {
      padding: 12px 22px;
      border-bottom: 1px solid var(--border);
      flex-shrink: 0;
    }
    .load-search-input {
      width: 100%;
      padding: 8px 12px 8px 34px;
      border: 1.5px solid var(--border);
      border-radius: 8px;
      font-size: 13px;
      font-family: var(--font);
      background: #f8fafc url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 16 16' fill='%2364748b'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.099zm-5.242 1.656a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11z'/%3E%3C/svg%3E") no-repeat 10px center;
      outline: none;
      color: var(--text);
      transition: border-color .15s;
    }
    .load-search-input:focus { border-color: var(--blue); background-color: white; }
    .load-table-wrap {
      flex: 1;
      overflow-y: auto;
    }
    .load-sessions-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 13px;
    }
    .load-sessions-table thead tr {
      background: #f8fafc;
      border-bottom: 2px solid var(--border);
    }
    .load-sessions-table th {
      padding: 9px 14px;
      text-align: left;
      font-size: 11px;
      font-weight: 700;
      color: var(--muted);
      text-transform: uppercase;
      letter-spacing: .4px;
      white-space: nowrap;
    }
    .load-sessions-table td {
      padding: 10px 14px;
      border-bottom: 1px solid #f1f5f9;
      vertical-align: middle;
    }
    .load-sessions-table tbody tr:hover { background: #f8fafc; }
    .btn-load-row {
      background: linear-gradient(135deg,#1a56db,#2563eb);
      color: white; border: none;
      padding: 6px 14px; border-radius: 7px;
      font-size: 12px; font-weight: 700;
      cursor: pointer; font-family: var(--font);
      white-space: nowrap;
      transition: opacity .15s;
      box-shadow: none;
    }
    .btn-load-row:hover { opacity: .87; }
    .btn-load-dup {
      background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0;
      padding: 6px 10px; border-radius: 7px; font-size: 13px;
      cursor: pointer; font-family: var(--font); margin-left: 4px;
      transition: background .15s;
    }
    .btn-load-dup:hover { background: #dcfce7; }
    .btn-load-del {
      background: #fef2f2; color: #991b1b; border: 1px solid #fecaca;
      padding: 6px 10px; border-radius: 7px; font-size: 13px;
      cursor: pointer; font-family: var(--font); margin-left: 4px;
      transition: background .15s;
    }
    .btn-load-del:hover { background: #fee2e2; }
    .load-empty {
      display: none;
      text-align: center;
      padding: 40px 20px;
      color: var(--muted);
      font-size: 13px;
    }
    .load-err {
      display: none;
      margin: 12px 22px;
      padding: 10px 14px;
      background: #fef2f2;
      border: 1px solid #fecaca;
      border-radius: 8px;
      color: #991b1b;
      font-size: 12.5px;
    }

    /* ── Toast notifikasi (fixed kanan atas) ── */
    #db-toast {
      position: fixed;
      top: 72px;
      right: 20px;
      z-index: 999;
      max-width: 380px;
      min-width: 260px;
      border-radius: 12px;
      padding: 13px 16px;
      font-size: 13px;
      line-height: 1.5;
      box-shadow: 0 8px 32px rgba(0,0,0,.18);
      display: none;
      animation: toast-in .2s ease;
    }
    @keyframes toast-in {
      from { opacity:0; transform: translateY(-8px); }
      to   { opacity:1; transform: translateY(0); }
    }
    #db-toast.ok  { background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; }
    #db-toast.err { background:#fef2f2; border:1px solid #fecaca; color:#991b1b; }
    #db-toast.info{ background:#eff6ff; border:1px solid #bfdbfe; color:#1e40af; }

    /* ── DB Save button (Step 4) — sudah dipindah ke topbar ── */
    .db-save-bar {
      margin-top: 20px;
      padding: 18px 22px;
      background: linear-gradient(135deg, #f0f7ff, #e8f4f8);
      border: 1.5px solid #bfdbfe;
      border-radius: 14px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      flex-wrap: wrap;
    }
    .db-save-bar-info { display: flex; align-items: center; gap: 12px; }
    .db-save-bar-icon {
      width: 42px; height: 42px;
      background: linear-gradient(135deg, #1a56db, #3b82f6);
      border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      font-size: 20px; flex-shrink: 0;
    }
    .db-save-bar-text { font-size: 13px; color: var(--navy); }
    .db-save-bar-text strong { display: block; font-weight: 700; font-size: 13.5px; }
    .db-save-bar-text span { color: var(--muted); font-size: 12px; }
    .btn-db-save {
      background: linear-gradient(135deg, #1a56db, #2563eb);
      color: white;
      border: none;
      padding: 10px 22px;
      border-radius: 9px;
      font-size: 13px;
      font-weight: 700;
      cursor: pointer;
      font-family: var(--font);
      display: flex; align-items: center; gap: 8px;
      white-space: nowrap;
      transition: opacity .15s, transform .1s;
      box-shadow: 0 2px 8px rgba(26,86,219,.3);
    }
    .btn-db-save:hover { opacity: .9; transform: translateY(-1px); }
    .btn-db-save:disabled { opacity: .5; cursor: not-allowed; transform: none; }

    /* ── DB Modal ── */
    #db-modal {
      display: none;
      position: fixed;
      inset: 0;
      z-index: 900;
      background: rgba(11,31,58,.55);
      backdrop-filter: blur(3px);
      align-items: center;
      justify-content: center;
      padding: 20px;
    }
    .db-modal-box {
      background: white;
      border-radius: 18px;
      width: 100%;
      max-width: 820px;
      max-height: 90vh;
      display: flex;
      flex-direction: column;
      box-shadow: 0 24px 80px rgba(11,31,58,.35);
      overflow: hidden;
    }
    .db-modal-head {
      padding: 20px 24px 16px;
      border-bottom: 1px solid var(--border);
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 12px;
      flex-shrink: 0;
    }
    .db-modal-title-row { display: flex; align-items: center; gap: 10px; }
    .db-modal-icon {
      width: 38px; height: 38px;
      background: linear-gradient(135deg,#1a56db,#3b82f6);
      border-radius: 9px;
      display: flex; align-items: center; justify-content: center;
      font-size: 18px; flex-shrink: 0;
    }
    .db-modal-title { font-size: 15px; font-weight: 800; color: var(--navy); }
    .db-modal-sub   { font-size: 11.5px; color: var(--muted); margin-top: 1px; }
    .db-modal-close {
      width: 30px; height: 30px;
      border-radius: 8px;
      background: #f1f5f9;
      border: none; cursor: pointer;
      display: flex; align-items: center; justify-content: center;
      font-size: 15px; color: var(--muted);
      flex-shrink: 0;
      transition: background .15s;
      box-shadow: none;
    }
    .db-modal-close:hover { background: #e2e8f0; }
    .db-modal-stats {
      display: flex;
      gap: 0;
      padding: 10px 24px;
      background: #f8fafc;
      border-bottom: 1px solid var(--border);
      flex-shrink: 0;
    }
    .db-stat-item {
      flex: 1;
      text-align: center;
      padding: 6px 0;
    }
    .db-stat-item + .db-stat-item { border-left: 1px solid var(--border); }
    .db-stat-val  { font-size: 14px; font-weight: 800; color: var(--navy); line-height: 1.2; }
    .db-stat-lbl  { font-size: 10.5px; color: var(--muted); font-weight: 500; }
    .db-modal-tabs {
      display: flex;
      gap: 4px;
      padding: 12px 24px 0;
      flex-shrink: 0;
    }
    .db-tab-btn {
      padding: 7px 18px;
      border-radius: 8px 8px 0 0;
      border: 1px solid var(--border);
      border-bottom: none;
      background: #f8fafc;
      font-size: 12.5px;
      font-weight: 600;
      color: var(--muted);
      cursor: pointer;
      font-family: var(--font);
      transition: background .15s, color .15s;
      box-shadow: none;
    }
    .db-tab-btn.active {
      background: white;
      color: var(--blue);
      border-color: var(--border);
      position: relative;
    }
    .db-tab-btn.active::after {
      content: '';
      position: absolute;
      bottom: -1px; left: 0; right: 0;
      height: 1px;
      background: white;
    }
    .db-modal-body {
      flex: 1;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      border-top: 1px solid var(--border);
      margin: 0 24px;
    }
    .db-tab-pane { flex: 1; overflow: hidden; display: flex; flex-direction: column; }
    .db-code-wrap {
      flex: 1;
      overflow-y: auto;
      background: #0d1117;
      border-radius: 0 8px 8px 8px;
      padding: 16px 18px;
    }
    .db-code-wrap pre {
      font-family: 'DM Mono', 'Fira Code', 'Consolas', monospace;
      font-size: 11.5px;
      line-height: 1.7;
      color: #e6edf3;
      white-space: pre;
      tab-size: 2;
      margin: 0;
    }
    .db-modal-foot {
      padding: 14px 24px;
      border-top: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
      flex-shrink: 0;
    }
    .db-foot-hint { font-size: 11.5px; color: var(--muted); }
    .db-foot-actions { display: flex; gap: 8px; }
    .btn-db-copy {
      background: linear-gradient(135deg, #1a56db, #2563eb);
      color: white; border: none;
      padding: 9px 18px; border-radius: 8px;
      font-size: 12.5px; font-weight: 700;
      cursor: pointer; font-family: var(--font);
      transition: opacity .15s;
      box-shadow: none;
    }
    .btn-db-copy:hover { opacity: .88; }
    .btn-db-dl {
      background: #f1f5f9;
      color: var(--navy); border: 1px solid var(--border);
      padding: 9px 16px; border-radius: 8px;
      font-size: 12.5px; font-weight: 600;
      cursor: pointer; font-family: var(--font);
      transition: background .15s;
      box-shadow: none;
    }
    .btn-db-dl:hover { background: #e2e8f0; }

    .app-footer {
      text-align: center;
      font-size: 11.5px;
      color: var(--muted);
      padding: 28px 0 0;
      border-top: 1px solid var(--border);
      margin-top: 40px;
    }
    .app-footer strong { color: var(--navy); }
  </style>
<template id="__bundler_thumbnail" data-bg-color="#0b1f3a">
  <svg viewBox="0 0 1200 800" xmlns="http://www.w3.org/2000/svg">
    <rect width="1200" height="800" fill="#0b1f3a"/>
    <rect x="100" y="120" width="400" height="560" rx="16" fill="rgba(255,255,255,.06)"/>
    <rect x="540" y="120" width="560" height="260" rx="16" fill="rgba(255,255,255,.06)"/>
    <rect x="540" y="400" width="260" height="280" rx="16" fill="rgba(255,255,255,.06)"/>
    <rect x="820" y="400" width="280" height="280" rx="16" fill="rgba(255,255,255,.06)"/>
    <rect x="120" y="300" width="360" height="8" rx="4" fill="rgba(96,165,250,.5)"/>
    <rect x="120" y="325" width="280" height="6" rx="3" fill="rgba(255,255,255,.2)"/>
    <rect x="120" y="348" width="310" height="6" rx="3" fill="rgba(255,255,255,.15)"/>
    <text x="600" y="80" font-family="sans-serif" font-size="28" font-weight="bold" fill="white" text-anchor="middle">Aplikasi RTK Daerah — Kemnaker RI</text>
  </svg>
</template>
</head>
<body>

  <!-- TOPBAR -->
  <div class="topbar">
    <div class="topbar-logo">
      <div class="logo-icon-wrap">📊</div>
      <div>
        <div class="logo-text">Aplikasi RTK Daerah</div>
        <div class="logo-sub">Kementerian Ketenagakerjaan RI</div>
      </div>
    </div>
    <div class="topbar-right">
      <button class="btn-db-topbar" id="btnDbLoad" disabled onclick="openLoadModal()" title="Ambil data yang tersimpan di database">
        <svg width="13" height="13" viewBox="0 0 16 16" fill="currentColor"><path d="M8 1C4.686 1 2 2.343 2 4v8c0 1.657 2.686 3 6 3s6-1.343 6-3V4c0-1.657-2.686-3-6-3zm0 1.5c2.76 0 4.5.895 4.5 1.5S10.76 5.5 8 5.5 3.5 4.605 3.5 4s1.74-1.5 4.5-1.5zM3.5 6.373C4.538 7.01 6.19 7.5 8 7.5s3.462-.49 4.5-1.127V8c0 .605-1.74 1.5-4.5 1.5S3.5 8.605 3.5 8V6.373zm0 3C4.538 10.01 6.19 10.5 8 10.5s3.462-.49 4.5-1.127V11c0 .605-1.74 1.5-4.5 1.5S3.5 11.605 3.5 11V9.373z"/></svg>
        Load
      </button>
      <button class="btn-db-topbar" id="btnDbSave" disabled onclick="saveToDatabase()" title="Simpan semua data ke database MySQL">
        <svg width="13" height="13" viewBox="0 0 16 16" fill="currentColor"><path d="M8 1C4.686 1 2 2.343 2 4v8c0 1.657 2.686 3 6 3s6-1.343 6-3V4c0-1.657-2.686-3-6-3zm0 1.5c2.76 0 4.5.895 4.5 1.5S10.76 5.5 8 5.5 3.5 4.605 3.5 4s1.74-1.5 4.5-1.5zM3.5 6.373C4.538 7.01 6.19 7.5 8 7.5s3.462-.49 4.5-1.127V8c0 .605-1.74 1.5-4.5 1.5S3.5 8.605 3.5 8V6.373zm0 3C4.538 10.01 6.19 10.5 8 10.5s3.462-.49 4.5-1.127V11c0 .605-1.74 1.5-4.5 1.5S3.5 11.605 3.5 11V9.373z"/></svg>
        Save
      </button>
      <button class="btn-reset" onclick="resetData()" title="Hapus semua data & mulai dari awal">
        <svg width="13" height="13" viewBox="0 0 16 16" fill="currentColor"><path d="M2 8a6 6 0 1 0 6-6V0L4 3l4 3V4a4 4 0 1 1-4 4H2z"/></svg>
        Reset
      </button>
    </div>
  </div>

  <div class="app-wrap">

    <!-- STEPPER -->
    <div class="stepper">
      <div class="step-item active" id="pg1" onclick="goTo(1)">
        <div class="step-circle">1</div>
        <div class="step-info">
          <div class="step-num">Langkah 1</div>
          <div class="step-name">Unggah Data</div>
        </div>
      </div>
      <div class="step-item" id="pg2" onclick="goTo(2)">
        <div class="step-circle">2</div>
        <div class="step-info">
          <div class="step-num">Langkah 2</div>
          <div class="step-name">Konfigurasi Awal</div>
        </div>
      </div>
      <div class="step-item" id="pg3" onclick="goTo(3)">
        <div class="step-circle">3</div>
        <div class="step-info">
          <div class="step-num">Langkah 3</div>
          <div class="step-name">Lembar Kerja</div>
        </div>
      </div>
      <div class="step-item" id="pg4" onclick="goTo(4)">
        <div class="step-circle">4</div>
        <div class="step-info">
          <div class="step-num">Langkah 4</div>
          <div class="step-name">Unduh Hasil</div>
        </div>
      </div>
    </div>

    <!-- ════════════════════════════════
         LANGKAH 1: Upload / Template
         ════════════════════════════════ -->
    <div class="sbox active" id="s1">

      <!-- Hero Banner -->
      <div id="hero-banner">
        <div class="hero-left">
          <div class="hero-eyebrow"><span></span>Alat Bantu Penghitungan</div>
          <div class="hero-title">Rencana Tenaga Kerja<br><em>Daerah</em></div>
          <div class="hero-desc">Unggah data Excel, atur parameter, dan hasilkan draft tabel serta dokumen Word sebagai bahan penyusunan RTK daerah.</div>
          <div class="hero-stats">
            <div class="hero-stat">
              <div class="hero-stat-num">Draft</div>
              <div class="hero-stat-lbl">Dokumen Word</div>
            </div>
            <div class="hero-stat-div"></div>
            <div class="hero-stat">
              <div class="hero-stat-num">9</div>
              <div class="hero-stat-lbl">Indikator</div>
            </div>
            <div class="hero-stat-div"></div>
            <div class="hero-stat">
              <div class="hero-stat-num">38</div>
              <div class="hero-stat-lbl">Provinsi</div>
            </div>
          </div>
        </div>
        <div class="hero-right">
          <div class="hero-visual">
            <svg class="hero-chart-svg" viewBox="0 0 160 120" fill="none" xmlns="http://www.w3.org/2000/svg">
              <!-- grid lines -->
              <line x1="20" y1="100" x2="155" y2="100" stroke="rgba(255,255,255,.1)" stroke-width="1"/>
              <line x1="20" y1="75"  x2="155" y2="75"  stroke="rgba(255,255,255,.07)" stroke-width="1"/>
              <line x1="20" y1="50"  x2="155" y2="50"  stroke="rgba(255,255,255,.07)" stroke-width="1"/>
              <line x1="20" y1="25"  x2="155" y2="25"  stroke="rgba(255,255,255,.07)" stroke-width="1"/>
              <!-- bars -->
              <rect x="30"  y="60" width="16" height="40" rx="3" fill="rgba(96,165,250,.5)"/>
              <rect x="54"  y="48" width="16" height="52" rx="3" fill="rgba(96,165,250,.6)"/>
              <rect x="78"  y="35" width="16" height="65" rx="3" fill="rgba(96,165,250,.75)"/>
              <rect x="102" y="42" width="16" height="58" rx="3" fill="rgba(96,165,250,.65)"/>
              <rect x="126" y="22" width="16" height="78" rx="3" fill="#60a5fa"/>
              <!-- trend line -->
              <polyline points="38,55 62,43 86,30 110,37 134,17" stroke="#34d399" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
              <circle cx="38"  cy="55" r="3" fill="#34d399"/>
              <circle cx="62"  cy="43" r="3" fill="#34d399"/>
              <circle cx="86"  cy="30" r="3" fill="#34d399"/>
              <circle cx="110" cy="37" r="3" fill="#34d399"/>
              <circle cx="134" cy="17" r="4" fill="#34d399" stroke="white" stroke-width="1.5"/>
            </svg>
          </div>
        </div>
      </div>

      <!-- Tab switcher -->
      <div class="s1-tabs">
        <button id="s1tab-upload"   class="s1-tab active" onclick="switchS1Tab('upload')">📂 Upload Data Excel</button>
        <button id="s1tab-template" class="s1-tab"        onclick="switchS1Tab('template')">📥 Download Template</button>
      </div>

      <!-- ── Panel Upload ── -->
      <div id="s1-panel-upload">
        <div class="s1-two-col">
          <div>
            <input type="file" id="fileIn" accept=".xlsx,.xls" style="display:none" onchange="onFile(this)">
            <div class="upload-zone" id="uploadZone" onclick="document.getElementById('fileIn').click()">
              <div class="uz-icon">📋</div>
              <div class="uz-title">Seret file ke sini atau klik untuk memilih</div>
              <div class="uz-sub">Format yang didukung: .xlsx dan .xls</div>
              <button class="uz-btn" type="button"
                onclick="event.stopPropagation();document.getElementById('fileIn').click()">
                📂 Pilih File Excel
              </button>
            </div>

            <div id="fileBadge" style="display:none" class="file-badge">
              <span class="fb-icon">✔</span>
              <span id="fnm">—</span>
            </div>

            <div id="stEx"></div>

            <div style="margin-top:18px;">
              <button class="btn-green" id="btnProses" disabled onclick="prosesExcel()">
                ✔ Proses &amp; Lanjutkan →
              </button>
            </div>
          </div>

          <div class="s1-info-panel">
            <div class="s1-info-title">
              <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><circle cx="8" cy="8" r="7" stroke="#1a56db" stroke-width="1.5"/><path d="M8 7v5M8 5h.01" stroke="#1a56db" stroke-width="1.5" stroke-linecap="round"/></svg>
              Cara Penggunaan
            </div>
            <ul class="s1-step-list">
              <li><div class="s1-step-num">1</div><span>Siapkan file Excel RTK Makro (.xlsx/.xls) yang telah diisi data historis. Belum punya template? Unduh di tab <strong>Download Template</strong> di sebelah.</span></li>
              <li><div class="s1-step-num">2</div><span>Upload file dan klik <strong>Proses</strong> untuk memuat data</span></li>
              <li><div class="s1-step-num">3</div><span>Atur parameter historis dan proyeksi di Langkah 2</span></li>
              <li><div class="s1-step-num">4</div><span>Review dan edit tabel di Lembar Kerja</span></li>
              <li><div class="s1-step-num">5</div><span>Unduh dokumen Word atau Excel hasil penghitungan</span></li>
            </ul>
          
          </div>
        </div>
      </div>

      <!-- ── Panel Download Template ── -->
      <div id="s1-panel-template" style="display:none">
        <div class="sbox-header" style="border:none;padding:0;margin-bottom:20px;">
          <div class="sbox-icon">📥</div>
          <div>
            <div class="sbox-title">Download Template Excel</div>
            <div class="sbox-desc">Generate file Excel kosong berisi semua sheet input RTK Makro sesuai provinsi dan rentang tahun yang dipilih.</div>
          </div>
        </div>

        <div class="tpl-form">
          <div class="tpl-field">
            <label for="tplProv">Provinsi</label>
            <select id="tplProv">
              <option value="" disabled selected>— Pilih Provinsi —</option>
            </select>
          </div>
          <div class="tpl-field-group">
            <div class="tpl-field">
              <label for="tplHA">Tahun Historis Awal</label>
              <input id="tplHA" type="number" min="1990" max="2099" value="2019" placeholder="2019">
            </div>
            <div class="tpl-field">
              <label for="tplHZ">Tahun Historis Akhir</label>
              <input id="tplHZ" type="number" min="1990" max="2099" value="2023" placeholder="2023">
            </div>
          </div>
          <div class="tpl-field-group">
            <div class="tpl-field">
              <label for="tplPA">Tahun Proyeksi Awal</label>
              <input id="tplPA" type="number" min="1990" max="2099" value="2024" placeholder="2024">
            </div>
            <div class="tpl-field">
              <label for="tplPZ">Tahun Proyeksi Akhir</label>
              <input id="tplPZ" type="number" min="1990" max="2099" value="2028" placeholder="2028">
            </div>
          </div>
          <div>
            <button class="btn-green" onclick="generateTemplate()">
              📥 Generate &amp; Download Template
            </button>
          </div>
          <div id="stTpl" style="margin-top:4px;"></div>
        </div>
      </div>

    </div><!-- /s1 -->

    <!-- ════════════════════════════════
         LANGKAH 2: Parameter
         ════════════════════════════════ -->
    <div class="sbox" id="s2">
      <div class="sbox-header">
        <div class="sbox-icon">⚙️</div>
        <div>
          <div class="sbox-title">Konfigurasi Awal</div>
          <div class="sbox-desc">Tentukan rentang tahun historis serta proyeksi.</div>
        </div>
      </div>

      <input id="inSession" type="hidden" value="">
      <input id="inNama"   type="hidden" value="">
      <div id="sessionNamaWrap" class="nama-wrap" style="margin-bottom:22px;display:none;">
        <span id="sessionNamaDisplay" style="font-size:13px;color:var(--muted);font-style:italic;"></span>
      </div>

      <div class="param-grid">
        <div class="param-group">
          <div class="param-group-title">📊 Data Historis (BAB II)</div>
          <div class="param-row">
            <label>Tahun Awal</label>
            <select id="sHA"></select>
          </div>
          <div class="param-row">
            <label>Tahun Akhir</label>
            <select id="sHZ"></select>
          </div>
        </div>
        <div class="param-group">
          <div class="param-group-title">📈 Tahun Proyeksi (BAB III–V)</div>
          <div class="param-row">
            <label>Tahun Awal</label>
            <select id="sPA"></select>
          </div>
          <div class="param-row">
            <label>Tahun Akhir</label>
            <select id="sPZ"></select>
          </div>
        </div>
      </div>

      <div style="max-width:360px;margin-bottom:24px;">
        <div class="param-group">
          <div class="param-group-title">⭐ Tahun Dasar Penghitungan</div>
          <div class="param-row">
            <label>Tahun Dasar</label>
            <select id="sTD"></select>
          </div>
          <div id="infoTD" style="font-size:11px;color:var(--muted);margin-top:6px;"></div>
        </div>
      </div>

      <div style="display:flex;gap:10px;flex-wrap:wrap;">
        <button class="btn-gray" onclick="goTo(1)">← Kembali</button>
        <button class="btn-green" onclick="konfirmasi()">✔ Konfirmasi &amp; Tampilkan →</button>
      </div>
      <div id="stPrm"></div>
    </div>

    <!-- ════════════════════════════════
         LANGKAH 3: Tabel
         ════════════════════════════════ -->
    <div class="sbox" id="s3">
      <div class="sbox-header">
        <div class="sbox-icon">📋</div>
        <div>
          <div class="sbox-title">Lembar Kerja — Tabel Terpadu</div>
          <div class="sbox-desc">Data historis dan proyeksi per indikator dan karakteristik tenaga kerja.</div>
        </div>
      </div>

      <div id="s3-param-info" class="msg msg-info" style="margin-bottom:16px;margin-top:10px;display:none;"></div>

      <div class="edit-toolbar" id="editToolbar" style="display:none;">
        <button id="btnUndo" disabled onclick="undoEdit()" title="Undo (Ctrl+Z)">↩ Undo</button>
        <button id="btnRedo" disabled onclick="redoEdit()" title="Redo (Ctrl+Y)">↪ Redo</button>
        <span class="edit-badge" id="editCount"></span>
        <span class="toolbar-hint"><kbd>Ctrl</kbd>+<kbd>Z</kbd> undo &nbsp;|&nbsp; <kbd>Ctrl</kbd>+<kbd>Y</kbd> redo</span>
      </div>

      <div class="spinner" id="spin"></div>
      <div id="s3area"></div>

      <div id="s3act" style="display:none;margin-top:24px;">
        <button class="btn-gray" onclick="goTo(2)">← Kembali</button>
        <button class="btn-green" onclick="goTo(4)" style="margin-left:10px;">✔ Lanjut ke Download →</button>
      </div>
    </div>

    <!-- ════════════════════════════════
         LANGKAH 4: Download
         ════════════════════════════════ -->
    <div class="sbox" id="s4">
      <div class="sbox-header">
        <div class="sbox-icon">📥</div>
        <div>
          <div class="sbox-title">Unduh Draft Dokumen</div>
          <div class="sbox-desc">Pilih jenis draft sesuai tingkat daerah. Dokumen yang dihasilkan adalah bahan awal — perlu diperiksa dan disempurnakan lebih lanjut.</div>
        </div>
      </div>

      <div id="sumInfo" class="msg msg-info" style="margin-bottom:22px;"></div>

      <div class="dl-grid">
        <!-- RTK Provinsi -->
        <div class="dl-card">
          <div class="dl-card-header">
            <div class="dl-card-icon dl-icon-prov">📘</div>
            <div>
              <div class="dl-card-num">77</div>
              <h4 style="font-size:14px;font-weight:700;color:var(--navy);">RTK Provinsi</h4>
            </div>
          </div>
          <p>Semua tabel termasuk 12 tabel breakdown Kabupaten/Kota (★). Dihasilkan sebagai draft — perlu review sebelum finalisasi.</p>
          <button class="dl-btn dl-btn-prov" id="btnProv" disabled onclick="downloadWord(false)">
            ⬇ Download RTK Provinsi (.docx)
          </button>
        </div>
        <!-- RTK Kab/Kota -->
        <div class="dl-card">
          <div class="dl-card-header">
            <div class="dl-card-icon dl-icon-kab">📗</div>
            <div>
              <div class="dl-card-num" style="color:var(--green);">65</div>
              <h4 style="font-size:14px;font-weight:700;color:var(--navy);">RTK Kab/Kota</h4>
            </div>
          </div>
          <p>Tanpa tabel breakdown Kabupaten/Kota. Sesuai untuk RTK tingkat Kabupaten/Kota.</p>
          <button class="dl-btn dl-btn-kab" id="btnKab" disabled onclick="downloadWord(true)">
            ⬇ Download RTK Kab/Kota (.docx)
          </button>
        </div>
        <!-- Data Input Excel -->
        <div class="dl-card">
          <div class="dl-card-header">
            <div class="dl-card-icon dl-icon-xls">📊</div>
            <div>
              <div class="dl-card-num" style="color:var(--amber);font-size:32px;">XLS</div>
              <h4 style="font-size:14px;font-weight:700;color:var(--navy);">Data Input Excel</h4>
            </div>
          </div>
          <p>File Excel (.xlsx) berisi semua sheet INPUT — siap diupload kembali di Langkah 1.</p>
          <button class="dl-btn dl-btn-xls" id="btnXls" disabled onclick="downloadExcel()">
            ⬇ Download Data Input (.xlsx)
          </button>
        </div>
        <!-- Laju Pertumbuhan -->
        <div class="dl-card">
          <div class="dl-card-header">
            <div class="dl-card-icon dl-icon-laju">📈</div>
            <div>
              <div class="dl-card-num" style="color:#0369a1;font-size:32px;">LP</div>
              <h4 style="font-size:14px;font-weight:700;color:var(--navy);">Laju Pertumbuhan</h4>
            </div>
          </div>
          <p>File Excel (.xlsx) berisi nilai laju pertumbuhan per indikator beserta catatan per baris.</p>
          <button class="dl-btn dl-btn-xls" style="background:linear-gradient(135deg,#075985,#0369a1);" id="btnLaju" disabled onclick="downloadLajuExcel()">
            ⬇ Download Laju Pertumbuhan (.xlsx)
          </button>
        </div>
      </div>

      <div style="margin-top:8px;">
        <button class="btn-gray" onclick="goTo(3)">← Kembali ke Tabel</button>
      </div>
      <div id="stDl" style="margin-top:12px;"></div>
    </div>

    <!-- FOOTER -->
    <div class="app-footer">
      <strong>Alat Bantu RTK Daerah</strong> · Kementerian Ketenagakerjaan Republik Indonesia
    </div>

  </div><!-- /app-wrap -->

  <!-- TWEAKS PANEL -->
  <div id="tweaks-panel">
    <div class="tweaks-header">
      Tweaks
      <button class="tweaks-close" id="tweaks-close-btn">✕</button>
    </div>
    <div class="tweaks-section">
      <div class="tweaks-label">Tampilan</div>
      <div class="tweak-row">
        <span>Tampilkan Hero Banner</span>
        <button class="tweak-toggle on" id="tweak-hero" onclick="toggleHero(this)"></button>
      </div>
      <div class="tweak-row">
        <span>Layout Lebar Penuh</span>
        <button class="tweak-toggle" id="tweak-wide" onclick="toggleWide(this)"></button>
      </div>
    </div>
    <div class="tweaks-section">
      <div class="tweaks-label">Tema Warna</div>
      <div class="tweak-swatch-row">
        <div class="tweak-swatch active" style="background:#0b1f3a;" title="Navy (default)" onclick="setTheme('navy',this)"></div>
        <div class="tweak-swatch" style="background:#1e3a5f;" title="Biru Tua" onclick="setTheme('darkblue',this)"></div>
        <div class="tweak-swatch" style="background:#064e3b;" title="Hijau Tua" onclick="setTheme('darkgreen',this)"></div>
        <div class="tweak-swatch" style="background:#3730a3;" title="Ungu" onclick="setTheme('indigo',this)"></div>
      </div>
    </div>
    <div class="tweaks-section">
      <div class="tweaks-label">Ukuran Font</div>
      <div class="tweak-row">
        <span>Teks Tabel</span>
        <select id="tweak-fontsize" onchange="setFontSize(this.value)"
          style="width:90px;font-size:12px;padding:4px 8px;border-radius:6px;border:1px solid var(--border);font-family:var(--font);">
          <option value="12">Kecil (12px)</option>
          <option value="13.5" selected>Normal (13.5px)</option>
          <option value="15">Besar (15px)</option>
        </select>
      </div>
    </div>
  </div>


  <!-- Toast notifikasi DB save -->
  <div id="db-toast"></div>

  <!-- Modal: Muat dari Database -->
  <div id="load-modal" onclick="if(event.target===this)closeLoadModal()">
    <div class="load-modal-box">
      <div class="load-modal-head">
        <div class="load-modal-head-left">
          <div class="load-modal-icon">📂</div>
          <div>
            <div class="load-modal-title">Muat Data dari Database</div>
            <div class="load-modal-sub">Pilih data user/daerah yang tersimpan di database</div>
          </div>
        </div>
        <button class="load-modal-close" onclick="closeLoadModal()">✕</button>
      </div>
      <div class="load-search-wrap">
        <input class="load-search-input" type="text" placeholder="🔍 Cari nama daerah..." oninput="onLoadSearch(this.value)">
      </div>
      <div id="load-err" class="load-err" style="display:none"></div>
      <div class="load-table-wrap">
        <table class="load-sessions-table">
          <thead>
            <tr>
              <th>Kode</th>
              <th>Nama Daerah</th>
              <th>Historis</th>
              <th>Proyeksi</th>
              <th>Disimpan</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody id="load-table-body"></tbody>
        </table>
        <div id="load-empty" class="load-empty" style="display:none">Belum ada data tersimpan di database.</div>
      </div>
    </div>
  </div>

  <!-- KaTeX — render rumus matematika -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.11/dist/katex.min.css">
  <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.11/dist/katex.min.js"></script>

  <!-- Libraries -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
  <script src="https://unpkg.com/docx@7.8.2/build/index.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/exceljs@4.4.0/dist/exceljs.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/decimal.js/10.4.3/decimal.min.js"></script>
  <!-- pako: kompresi gzip untuk blob DB -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pako/2.1.0/pako.min.js"></script>

  <!-- Data Template -->
  <script src="{{ asset('rtk-assets/js/step1-download-wilayah.js') }}"></script>
  <script src="{{ asset('rtk-assets/js/step1-download-schema.js') }}"></script>
  <script src="{{ asset('rtk-assets/js/step1-download-toc.js') }}"></script>
  <script src="{{ asset('rtk-assets/js/step1-download-tahun.js') }}"></script>
  <script src="{{ asset('rtk-assets/js/step1-template.js') }}"></script>

  <!-- App -->
  <script src="{{ asset('rtk-assets/js/data-state.js') }}"></script>
  <script src="{{ asset('rtk-assets/js/step1-upload-excel.js') }}"></script>
  <script src="{{ asset('rtk-assets/js/step2-parameter.js') }}"></script>
  <script src="{{ asset('rtk-assets/js/step3-config.js') }}"></script>
  <script src="{{ asset('rtk-assets/js/step3-compute.js') }}"></script>
  <script src="{{ asset('rtk-assets/js/goal-seek.js') }}"></script>
  <script src="{{ asset('rtk-assets/js/step3-edit-manager.js') }}"></script>
  <script src="{{ asset('rtk-assets/js/step3-ui-components.js') }}"></script>
  <script src="{{ asset('rtk-assets/js/step3-sidebar.js') }}"></script>
  <script src="{{ asset('rtk-assets/js/step3-indikator-puk.js') }}"></script>
  <script src="{{ asset('rtk-assets/js/step3-indikator-tpak.js') }}"></script>
  <script src="{{ asset('rtk-assets/js/step3-indikator-ak.js') }}"></script>
  <script src="{{ asset('rtk-assets/js/step3-indikator-pdrb.js') }}"></script>
  <script src="{{ asset('rtk-assets/js/step3-indikator-elastisitas.js') }}"></script>
  <script src="{{ asset('rtk-assets/js/step3-indikator-kk.js') }}"></script>
  <script src="{{ asset('rtk-assets/js/step3-indikator-pt.js') }}"></script>
  <script src="{{ asset('rtk-assets/js/step3-indikator-tpt.js') }}"></script>
  <script src="{{ asset('rtk-assets/js/step3-indikator-pelengkap.js') }}"></script>
  <script src="{{ asset('rtk-assets/js/step3-main.js') }}"></script>
  <script src="{{ asset('rtk-assets/js/step3-goal-seek-ui.js') }}"></script>
  <script src="{{ asset('rtk-assets/js/step4-download-word.js') }}"></script>
  <script src="{{ asset('rtk-assets/js/step4-excel-sheets.js') }}"></script>
  <script src="{{ asset('rtk-assets/js/step4-download-excel.js') }}"></script>
  <script src="{{ asset('rtk-assets/js/step4-download-laju.js') }}"></script>
  <script src="{{ asset('rtk-assets/js/db-save.js') }}"></script>
  <script src="{{ asset('rtk-assets/js/db-load.js') }}"></script>

  <script>
    if (RTK_MODE === 'sandbox') {
      document.addEventListener('DOMContentLoaded', function() {
        var btnSave = document.getElementById('btnDbSave');
        var btnLoad = document.getElementById('btnDbLoad');
        if (btnSave) btnSave.style.display = 'none';
        if (btnLoad) btnLoad.style.display = 'none';
      });
    }
  </script>

  <script>
    (function () {
      const zone = document.getElementById('uploadZone');
      if (!zone) return;
      zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('dragover'); });
      zone.addEventListener('dragleave', () => zone.classList.remove('dragover'));
      zone.addEventListener('drop', e => {
        e.preventDefault(); zone.classList.remove('dragover');
        const files = e.dataTransfer.files;
        if (files.length) {
          const file = files[0];
          document.getElementById('fnm').textContent = file.name;
          document.getElementById('fileBadge').style.display = 'flex';
          document.getElementById('uploadZone').style.display = 'none';
          document.getElementById('btnProses').disabled = false;
          window._droppedFile = file;
        }
      });
    })();

    document.addEventListener('DOMContentLoaded', function () {
      if (typeof initTplProvinsi === 'function') initTplProvinsi();
      if (IS_SERVER) {
        document.getElementById('btnDbLoad').disabled = false;
      }
      if (typeof checkDbHasSessions === 'function') checkDbHasSessions();

      if (loadStateFromLS()) {
        if (P.nama) {
          try { fillDrops(); } catch(e) { console.warn('fillDrops:', e); }
          document.getElementById('inNama').value = P.nama;
          document.getElementById('sHA').value    = P.hA;
          document.getElementById('sHZ').value    = P.hZ;
          document.getElementById('sPA').value    = P.pA;
          document.getElementById('sPZ').value    = P.pZ;
          document.getElementById('sTD').value    = P.td;
          const _inSes = document.getElementById('inSession');
          const _disp  = document.getElementById('sessionNamaDisplay');
          if (_inSes) _inSes.value = P.kodeUser || P.sessionId || '';
          if (_disp) {
            _disp.textContent      = P.nama;
            _disp.style.color      = '#166534';
            _disp.style.fontStyle  = 'normal';
            _disp.style.fontWeight = '700';
          }

          let step3Ok = false;
          try {
            buildComputed();
            buildStep3();
            step3Ok = true;
          } catch (err) {
            console.error('Gagal build Step 3 dari localStorage:', err);
          }

          if (step3Ok) {
            goTo(3);
            try { updateCards(); } catch(e) {}
            document.getElementById('s3act').style.display = 'block';
            document.getElementById('sumInfo').innerHTML =
              '<strong>' + P.nama + '</strong> | Historis ' + P.hA + '–' + P.hZ + ' | Proyeksi ' + P.pA + '–' + P.pZ;
            const s3Param = document.getElementById('s3-param-info');
            if (s3Param) {
              s3Param.innerHTML = `✅ <strong>${P.nama}</strong> | Historis ${P.hA}–${P.hZ} | Proyeksi ${P.pA}–${P.pZ} | Tahun Dasar ${P.td}`;
              s3Param.style.display = 'block';
            }
          } else {
            goTo(2);
            document.getElementById('stPrm').innerHTML =
              '<div class="msg msg-err">⚠️ Error saat membangun tabel. Klik <strong>Konfirmasi &amp; Tampilkan</strong> untuk coba lagi. Lihat console browser (F12) untuk detail.</div>';
          }

          ['btnProv','btnKab','btnXls','btnLaju','btnDbSave'].forEach(function(id) {
            document.getElementById(id).disabled = false;
          });
          if (IS_SERVER) document.getElementById('btnDbLoad').disabled = false;

          if (step3Ok) {
            document.getElementById('stPrm').innerHTML =
              '<div class="msg msg-info">♻️ Data dipulihkan dari sesi sebelumnya.</div>';
          }
        } else {
          try { fillDrops(); } catch(e) {}
          goTo(2);
        }
      }

      try {
        const tw = JSON.parse(localStorage.getItem('rtk_tweaks') || '{}');
        if (tw.wide) { document.querySelector('.app-wrap').classList.add('fullwidth'); document.getElementById('tweak-wide').classList.add('on'); }
        if (tw.hero === false) { document.getElementById('hero-banner').style.display='none'; document.getElementById('tweak-hero').classList.remove('on'); }
        if (tw.theme) setTheme(tw.theme, null, true);
        if (tw.fs) { document.body.style.fontSize = tw.fs + 'px'; document.getElementById('tweak-fontsize').value = tw.fs; }
      } catch(e) {}
    });

    function resetData() {
      if (!confirm('Hapus semua data dan mulai dari awal?')) return;
      clearStateLS();
      location.reload();
    }

    document.addEventListener('keydown', function (e) {
      const tag = (e.target.tagName || '').toLowerCase();
      if (tag === 'input' || tag === 'textarea') return;
      if (e.target.contentEditable === 'true') return;
      if (e.ctrlKey && e.key === 'z') { e.preventDefault(); undoEdit(); }
      if (e.ctrlKey && e.key === 'y') { e.preventDefault(); redoEdit(); }
    });

    window.addEventListener('message', function(e) {
      if (e.data && e.data.type === '__activate_edit_mode') {
        document.getElementById('tweaks-panel').classList.add('open');
      }
      if (e.data && e.data.type === '__deactivate_edit_mode') {
        document.getElementById('tweaks-panel').classList.remove('open');
      }
    });
    window.parent.postMessage({ type: '__edit_mode_available' }, '*');

    document.getElementById('tweaks-close-btn').addEventListener('click', function() {
      document.getElementById('tweaks-panel').classList.remove('open');
      window.parent.postMessage({ type: '__deactivate_edit_mode' }, '*');
    });

    function saveTweaks(obj) {
      try {
        const prev = JSON.parse(localStorage.getItem('rtk_tweaks') || '{}');
        const next = Object.assign(prev, obj);
        localStorage.setItem('rtk_tweaks', JSON.stringify(next));
        window.parent.postMessage({ type: '__edit_mode_set_keys', edits: next }, '*');
      } catch(e) {}
    }

    function toggleHero(btn) {
      btn.classList.toggle('on');
      const hero = document.getElementById('hero-banner');
      const on = btn.classList.contains('on');
      hero.style.display = on ? '' : 'none';
      saveTweaks({ hero: on });
    }

    function toggleWide(btn) {
      btn.classList.toggle('on');
      const on = btn.classList.contains('on');
      document.querySelector('.app-wrap').classList.toggle('fullwidth', on);
      saveTweaks({ wide: on });
    }

    const themeMap = {
      navy:      '#0b1f3a',
      darkblue:  '#1e3a5f',
      darkgreen: '#064e3b',
      indigo:    '#3730a3',
    };
    function setTheme(key, el, silent) {
      document.documentElement.style.setProperty('--navy', themeMap[key] || themeMap.navy);
      document.querySelectorAll('.tweak-swatch').forEach(s => s.classList.remove('active'));
      if (el) el.classList.add('active');
      else {
        const idx = Object.keys(themeMap).indexOf(key);
        const swatches = document.querySelectorAll('.tweak-swatch');
        if (swatches[idx]) swatches[idx].classList.add('active');
      }
      if (!silent) saveTweaks({ theme: key });
    }

    function setFontSize(v) {
      document.body.style.fontSize = v + 'px';
      saveTweaks({ fs: parseFloat(v) });
    }
  </script>

</body>
</html>
