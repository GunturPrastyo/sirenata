{{-- 
    Template Sertifikat E-Learning
    Ukuran: A4 Landscape (1122px x 793px @ 96dpi, atau 297mm x 210mm)
    
    Variabel yang tersedia:
    - $nama_peserta    : Nama lengkap peserta
    - $nama_kursus     : Nama kursus yang diselesaikan
    - $tanggal_selesai : Tanggal penyelesaian (format: "20 Mei 2026")
    - $nomor_sertifikat: Nomor unik sertifikat (format: "CERT-2026-XXX-001")
    - $background_url  : URL gambar background dari Canva
    - $signature_url   : URL gambar tanda tangan
    - $signer_name     : Nama penandatangan
    - $signer_title    : Jabatan penandatangan
--}}

<!-- Import Google Fonts untuk Tampilan Premium -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800&family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;1,500&family=Alex+Brush&display=swap" rel="stylesheet">

<style>
    @page {
        size: a4 landscape;
        margin: 0;
    }
    body {
        margin: 0;
        padding: 0;
    }
    .certificate-wrapper {
        width: 1122px;
        height: 793px;
        position: relative;
        overflow: hidden;
        font-family: 'Montserrat', sans-serif;
        background-color: #ffffff;
        margin: 0 auto;
        padding: 0;
        box-sizing: border-box;
    }
    .certificate-content {
        position: absolute;
        top: 0;
        left: 0;
        width: 1122px;
        height: 793px;
        z-index: 1;
        padding: 130px 80px 50px 80px;
        box-sizing: border-box;
        text-align: center;
    }
</style>

<div class="certificate-wrapper">
    {{-- Background Image dari Canva --}}
    @if($background_url)
        <img src="{{ $background_url }}" alt="Background" style="
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        ">
    @else
        {{-- Default Background Premium jika belum diunggah --}}
        <div style="
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: radial-gradient(circle, #ffffff 60%, #f4f6f9 100%);
            border: 15px double #b89c49;
            box-sizing: border-box;
            z-index: 0;
        ">
            {{-- Watermark Decorative Corner --}}
            <div style="position: absolute; top: 20px; left: 20px; width: 60px; height: 60px; border-top: 3px solid #b89c49; border-left: 3px solid #b89c49;"></div>
            <div style="position: absolute; top: 20px; right: 20px; width: 60px; height: 60px; border-top: 3px solid #b89c49; border-right: 3px solid #b89c49;"></div>
            <div style="position: absolute; bottom: 20px; left: 20px; width: 60px; height: 60px; border-bottom: 3px solid #b89c49; border-left: 3px solid #b89c49;"></div>
            <div style="position: absolute; bottom: 20px; right: 20px; width: 60px; height: 60px; border-bottom: 3px solid #b89c49; border-right: 3px solid #b89c49;"></div>
        </div>
    @endif

    {{-- Content Overlay --}}
    <div class="certificate-content">
        {{-- Header / Jenis Sertifikat --}}
        <p style="
            font-family: 'Cinzel', serif;
            font-size: 26px;
            font-weight: 700;
            letter-spacing: 8px;
            color: #1a2e40;
            margin: 0 0 5px 0;
            text-transform: uppercase;
        ">Sertifikat Kelulusan</p>
        
        <p style="
            font-size: 11px;
            letter-spacing: 4px;
            color: #b89c49;
            margin: 0 0 35px 0;
            text-transform: uppercase;
            font-weight: 600;
        ">Kementerian Ketenagakerjaan Republik Indonesia</p>

        <p style="
            font-size: 14px;
            color: #55606d;
            margin: 0 0 10px 0;
            font-weight: 400;
            letter-spacing: 1px;
        ">Diberikan secara hormat kepada:</p>

        {{-- Nama Peserta (Elegant Serif style) --}}
        <p style="
            font-family: 'Playfair Display', serif;
            font-size: 42px;
            font-style: italic;
            font-weight: 700;
            color: #1e3a5f;
            margin: 0 0 5px 0;
            line-height: 1.1;
        ">{{ $nama_peserta ?? 'Nama Peserta' }}</p>

        <div style="
            width: 380px;
            height: 1px;
            background: #b89c49;
            margin: 10px auto 20px;
        "></div>

        {{-- Pernyataan Kelulusan --}}
        <p style="
            font-size: 14px;
            color: #55606d;
            margin: 0 0 8px 0;
            line-height: 1.6;
            font-weight: 400;
        ">
            Telah dinyatakan <span style="font-weight: 700; color: #1e3a5f; letter-spacing: 1px;">LULUS</span> dengan hasil evaluasi memuaskan dan sukses menyelesaikan program pembelajaran:
        </p>

        {{-- Nama Kursus --}}
        <p style="
            font-family: 'Cinzel', serif;
            font-size: 20px;
            font-weight: 700;
            color: #1a2e40;
            margin: 0 0 10px 0;
            line-height: 1.3;
            letter-spacing: 1px;
        ">{{ $nama_kursus ?? 'Nama Kursus' }}</p>

        {{-- Tanggal Penyelesaian --}}
        <p style="
            font-size: 12px;
            color: #718096;
            margin: 0;
            font-style: italic;
        ">pada tanggal {{ $tanggal_selesai ?? '__ ________ ____' }}</p>
    </div>

    {{-- Nomor Sertifikat (kiri bawah) --}}
    <div style="
        position: absolute;
        bottom: 50px;
        left: 80px;
        z-index: 1;
        text-align: left;
    ">
        <p style="
            font-size: 10px;
            color: #718096;
            margin: 0 0 2px 0;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 500;
        ">Nomor Sertifikat</p>
        <p style="
            font-size: 11px;
            font-weight: 600;
            color: #1a2e40;
            margin: 0;
            letter-spacing: 1px;
        ">{{ $nomor_sertifikat ?? 'CERT-XXXX-XXX-000' }}</p>
    </div>

    {{-- Tanda Tangan (kanan bawah) --}}
    <div style="
        position: absolute;
        bottom: 45px;
        right: 80px;
        z-index: 1;
        width: 220px;
        text-align: center;
    ">
        @if($signature_url)
            <img src="{{ $signature_url }}" alt="Tanda Tangan" style="
                height: 70px;
                margin: 0 auto 5px;
                display: block;
            ">
        @else
            <div style="height: 75px;"></div>
        @endif

        <div style="
            width: 200px;
            border-bottom: 1.5px solid #b89c49;
            margin: 0 auto 8px;
        "></div>
        
        <p style="
            font-size: 13px;
            font-weight: 700;
            color: #1a2e40;
            margin: 0;
            letter-spacing: 0.5px;
        ">{{ $signer_name ?? 'Nama Penandatangan' }}</p>
        
        <p style="
            font-size: 10px;
            color: #718096;
            margin: 3px 0 0 0;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 500;
        ">{{ $signer_title ?? 'Jabatan' }}</p>
    </div>
</div>
