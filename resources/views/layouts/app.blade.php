<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="color-scheme" content="dark">

        @if (request()->routeIs('home'))
            {{-- custom title for home --}}
            <title>{{ config('app.name', 'Laravel') }} | {{ __('lgbt_uni') }}</title>
            <meta name="og:title" content="{{ config('app.name', 'Laravel') }} | {{ __('lgbt_uni') }}">
        @elseif (trans()->has("lgbt_" . Route::currentRouteName()))
            {{-- defualt title --}}
            <title>{{ __("lgbt_" . Route::currentRouteName()) }} | {{ config('app.name', 'Laravel') }}</title>
            <meta name="og:title" content="{{ __("lgbt_" . Route::currentRouteName()) }} | {{ config('app.name', 'Laravel') }}">
        @else
            {{-- fallback title --}}
            <title>{{ config('app.name', 'Laravel') }}</title>
            <meta name="og:title" content="{{ config('app.name', 'Laravel') }}">
        @endif

        {{-- meta descriptions --}}
        @if (trans()->has("lgbt_meta_desc_" . Route::currentRouteName()))
            <meta name="description" content="{{ __("lgbt_meta_desc_" . Route::currentRouteName()) }}">
            <meta name="og:description" content="{{ __("lgbt_meta_desc_" . Route::currentRouteName()) }}">
        @endif

        {{-- meta tags --}}
        <meta name="og:type" content="website">
        <meta name="twitter:card" content="summary">
        <meta name="og:url" content="{{ route('home') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ Vite::asset("resources/img/lgbt_bunny.png") }}">

        {{-- Scripts --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div class="page_wrap">
            <header>
                @include('layouts.navigation')
            </header>

            {{-- Page Content --}}
            <main class="{{ request()->routeIs('home') ? 'main_home' : 'main' }}">
                <aside class="cont_lang">
                    <ul class="lang_selection">
                        @if (app()->getLocale() === 'de')
                            <li lang="de" class="lang_option"><div aria-label='Sprache: Deutsch (aktiv)' class='active'>🇩🇪 DE</div></li>
                            <li lang="en" class="lang_option"><a href="#" rel="nofollow" aria-label="Change language: English">🇬🇧 EN</a></li>
                        @else
                            <li lang="de" class="lang_option"><a href="#" rel="nofollow" aria-label="Change language: English">🇬🇧 EN</a></li>
                            <li lang="en" class="lang_option"><div aria-label='Language: English (active)' class='active'>🇬🇧 EN</div></li>
                        @endif
                    </ul>
                </aside>

                {{ $slot }}
            </main>
        </div>
    </body>
</html>
