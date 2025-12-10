<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? 'Notification' }}</title>
    <style>
        body { font-family: 'Arial', sans-serif; background-color: #f4f4f7; margin:0; padding:0; color:#51545e; }
        .email-wrapper { width:100%; padding:20px 0; background-color:#f4f4f7; }
        .email-content { width:100%; max-width:600px; margin:0 auto; background:#fff; border-radius:6px; padding:20px; box-shadow:0 2px 6px rgba(0,0,0,0.1); }
        .email-header { text-align:center; padding-bottom:20px; border-bottom:1px solid #eaeaec; }
        .email-header img { max-height:50px; }
        .email-body { padding:20px 0; line-height:1.6; font-size:16px; }
        .email-footer { font-size:12px; text-align:center; color:#a8aaaf; padding-top:20px; border-top:1px solid #eaeaec; }
        .btn { display:inline-block; background:#22c55e; color:#fff !important; padding:10px 20px; border-radius:6px; text-decoration:none; font-weight:bold; }
    </style>
</head>
<body>
<div class="email-wrapper">
    <div class="email-content">

        <div class="email-header">
            {{-- <img src="{{ $logo }}" alt="Logo"> --}}
            {{-- <img src="{{ asset('storage/images/logo.jpg') }}" alt="Logo"> --}}
            <img src="https://support.comitsbd.com/public//images/logo_1755536796.jpg" alt="Logo">
        </div>

        <div class="email-body">
            {{-- {!! nl2br($body) !!} --}}
            {!! $body !!}
        </div>

        <div class="email-footer">
            &copy; {{ date('Y') }} {{ $company }}<br>
            {{ $company_address }} | {{ $company_email }}
        </div>

    </div>
</div>
</body>
</html>
