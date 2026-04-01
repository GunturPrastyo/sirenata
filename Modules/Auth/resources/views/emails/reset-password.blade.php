<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; color: #333; }
        .container { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }

        /* Header */
        .header { background-color: #1e3a8a; padding: 24px 30px; text-align: center; }
        .header-top { display: flex; align-items: center; justify-content: center; gap: 14px; margin-bottom: 6px; }
        .header img { width: 60px; height: 60px; }
        .header-text { text-align: left; }
        .header-text h1 { color: #ffffff; font-size: 20px; font-weight: bold; margin: 0; }
        .header-text p { color: #bfdbfe; font-size: 12px; margin: 2px 0 0 0; }
        .header-subtitle { color: #bfdbfe; font-size: 12px; margin-top: 8px; border-top: 1px solid #3b5fc0; padding-top: 8px; }

        /* Body */
        .body { padding: 30px 40px; }
        .body p { font-size: 15px; line-height: 1.6; color: #555; margin-bottom: 16px; }
        .btn { display: block; width: fit-content; margin: 24px auto; padding: 14px 32px; background-color: #1e3a8a; color: #ffffff !important; text-decoration: none; border-radius: 6px; font-size: 15px; font-weight: bold; }
        .btn:hover { background-color: #1e40af; }
        .divider { border: none; border-top: 1px solid #e5e7eb; margin: 24px 0; }
        .url-box { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px; padding: 12px 16px; word-break: break-all; font-size: 13px; color: #6b7280; }

        /* Footer */
        .footer { background-color: #f9fafb; padding: 20px 40px; text-align: center; font-size: 12px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="container">
        {{-- Header --}}
        <div class="header">
            <div class="header-top">
                {{-- <img src="{{ config('app.url') }}/images/kemnaker.png" alt="Logo Kemnaker"> --}}
                <img src="{{ asset('images/logo-white.png') }}" alt="Logo Kemnaker">
                <div class="header-text">
                    <h1>Sirenata</h1>
                    <p>Kementerian Ketenagakerjaan RI</p>
                </div>
            </div>
            <div class="header-subtitle">
                Sistem Informasi Reset Notifikasi Akun
            </div>
        </div>

        {{-- Body --}}
        <div class="body">
            <p>Halo, <strong>{{ $user->name }}</strong>!</p>
            <p>Kami menerima permintaan untuk mereset password akun Anda. Klik tombol di bawah untuk membuat password baru.</p>
            <a href="{{ $url }}" class="btn">Reset Password</a>
            <p>Link ini akan <strong>kadaluarsa dalam 60 menit</strong>. Jika Anda tidak merasa meminta reset password, abaikan email ini.</p>
            <hr class="divider">
            <p style="font-size: 13px;">Jika tombol tidak berfungsi, salin link berikut ke browser Anda:</p>
            <div class="url-box">{{ $url }}</div>
        </div>

        {{-- Footer --}}
        <div class="footer">
            <p>&copy; {{ date('Y') }} Sirenata. All rights reserved.</p>
            <p style="margin-top: 6px;">Email ini dikirim otomatis, mohon tidak membalas.</p>
        </div>
    </div>
</body>
</html>