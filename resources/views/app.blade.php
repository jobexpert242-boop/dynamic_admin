<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $site = \App\Models\SiteSetting::first();
    @endphp

    @if ($site && $site->favaicon)
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $site->favaicon) }}">
    @else
        <link rel="shortcut icon" href="{{ asset('/storage/images/logo.jpg') }}" type="image/x-icon">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @routes
    @inertiaHead
</head>

<body>
    @inertia
</body>

</html>
