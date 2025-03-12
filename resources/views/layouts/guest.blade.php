<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include('layouts.head')
    <body class="font-sans text-gray-900 antialiased">
        <header class="site_header">
            @include('layouts.navigation')
        </header>
        <main class="min-h-screen flex flex-col justify-center items-center pt-6 sm:pt-0">
            <aside class="cont_lang">
                <x-lang-switcher/>
            </aside>

            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 profile_bg">
                {{ $slot }}
            </div>
        </main>
    </body>
</html>
