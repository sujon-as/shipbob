@php
    use App\Models\Setting;

    $setting = Setting::first();
@endphp

    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>System Updating</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
            text-align: center;
        }
    </style>
</head>
<body>
<h1 class="text-danger mb-3">🛠️ {{ $setting->maintain_title_text ?? 'System Updating' }}</h1>
<p class="lead text-secondary">{{ $setting->maintain_desc_text ?? 'Dear Member, our platform is currently under maintenance. Please wait a while.' }}</p>
</body>
</html>
