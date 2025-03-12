<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include('layouts.head')
    <body>
        <div class="page_wrap">
            <header class="site_header">
                @include('layouts.navigation')
            </header>

            {{-- Page Content --}}
            @php
                if (request()->routeIs('home')) {
                    $class = 'main_home';
                } elseif (request()->routeIs('profile.edit')) {
                    $class = 'main_profile';
                } else {
                    $class = 'main';
                }
            @endphp
            <main class="{{ $class }}">
                <aside class="cont_lang">
                    <x-lang-switcher/>
                </aside>

                {{ $slot }}
            </main>

            <footer>
                @include('layouts.navigation-footer')
            </footer>
        </div>
    </body>
</html>
