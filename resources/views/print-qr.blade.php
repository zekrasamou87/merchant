<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>طباعة رمز QR - {{ $store->store_name }}</title>
    <style>
        body { font-family: 'Arial', sans-serif; text-align: center; padding: 50px; }
        .card { border: 5px solid #2d5a27; padding: 20px; display: inline-block; border-radius: 15px; }
        .logo { width: 150px; }
        .store-name { font-size: 24px; font-weight: bold; margin: 10px 0; }
        .qr-container { margin: 20px 0; }
        .footer-text { color: #666; font-size: 14px; }
    </style>
</head>
<body onload="window.print()"> <div class="card">
        <img src="{{ asset('assets/images/logo.png') }}" class="logo">
        <div class="store-name">{{ $store->store_name }}</div>
        <div class="qr-container">
            {!! QrCode::size(250)->generate($store->qr_code) !!}
        </div>
        <p class="footer-text">مبادرة التاجر الملتزم - محافظة اللاذقية</p>
        <p><strong>رمز التحقق: {{ $store->qr_code }}</strong></p>
    </div>
</body>
</html>