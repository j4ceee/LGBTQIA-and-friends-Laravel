<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="color-scheme" content="dark">

    @if (trans()->has("lgbt_" . Route::currentRouteName()) && !request()->routeIs('home'))
        {{-- default title --}}
        <title>{{ __("lgbt_" . Route::currentRouteName()) }} | {{ config('app.name', 'Laravel') }}</title>
        <meta name="og:title" content="{{ __("lgbt_" . Route::currentRouteName()) }} | {{ config('app.name', 'Laravel') }}">
    @else
        {{-- fallback title & title for home --}}
        <title>{{ config('app.name', 'Laravel') }} | {{ __('lgbt_uni') }}</title>
        <meta name="og:title" content="{{ config('app.name', 'Laravel') }} | {{ __('lgbt_uni') }}">
    @endif

    {{-- meta descriptions --}}
    @if (trans()->has("lgbt_meta_desc_" . Route::currentRouteName()))
        <meta name="description" content="{{ __("lgbt_meta_desc_" . Route::currentRouteName()) }}">
        <meta name="og:description" content="{{ __("lgbt_meta_desc_" . Route::currentRouteName()) }}">
    @else
        <meta name="description" content="{{ __("lgbt_meta_desc_def") }}">
        <meta name="og:description" content="{{ __("lgbt_meta_desc_def") }}">
    @endif

    {{-- meta tags --}}
    <meta name="og:image" content="{{ Vite::asset("resources/img/logo_base.png") }}">
    <meta name="og:url" content="{{ route('home') }}">
    <meta name="og:type" content="website">
    <meta name="twitter:card" content="summary">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ Vite::asset("resources/img/lgbt_bunny.png") }}">
    <link rel="canonical" content="{{ route('home') }}">

    {{-- Scripts --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
