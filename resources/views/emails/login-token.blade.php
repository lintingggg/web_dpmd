<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Akses Login DPMD</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9fafb;
            margin: 0;
            padding: 40px 20px;
        }
        .email-container {
            max-width: 500px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        .header {
            font-size: 20px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 20px;
            text-align: center;
        }
        .text {
            color: #4b5563;
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .btn-wrapper {
            text-align: center;
            margin: 30px 0;
        }
        .btn {
            background-color: #2563eb;
            color: #ffffff;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            display: inline-block;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 12px;
            color: #9ca3af;
            text-align: center;
        }
        .raw-link {
            word-break: break-all;
            color: #3b82f6;
        }
    </style>
</head>
<body>

    <div class="email-container">
        <div class="header">
            Portal Publikasi Dokumen DPMD
        </div>

        <div class="text">
            Halo,<br><br>
            Sistem kami menerima permintaan untuk masuk ke akun Anda. Silakan klik tombol di bawah ini untuk langsung masuk ke Dashboard.
        </div>

        <div class="btn-wrapper">
            <a href="{{ $url }}" class="btn">Masuk ke Dashboard</a>
        </div>

        <div class="text" style="font-size: 13px;">
            <strong>Catatan Penting:</strong> Link ini hanya berlaku selama <strong>15 menit</strong>. Jika Anda tidak pernah meminta link akses ini, Anda bisa mengabaikan email ini dengan aman.
        </div>

        <div class="footer">
            Jika tombol di atas tidak berfungsi, copy dan paste URL di bawah ini ke browser Anda:<br>
            <a href="{{ $url }}" class="raw-link">{{ $url }}</a>
        </div>
    </div>

</body>
</html>