<div class="header_span" x-data="{ open: false }">
    {{-- Primary Navigation Menu --}}
    <div class="cont_logo_nav">

                {{-- Logo --}}
                <div class="logo-header">
                    <a href="{{ route('home') }}" aria-label="{{ __('alt_signet_link') }}">
                        <x-application-logo class="logo"/>
                    </a>
                </div>

                {{-- Navigation Links --}}
                <nav class="navbar nav-top">
                    <ul class="nav_list">
                        <x-nav-link-li :href="route('home')" :active="request()->routeIs('home')">
                            {{ __('lgbt_home') }}
                        </x-nav-link-li>
                        <x-nav-link-li :href="route('home')" :active="false">
                            {{ __('lgbt_calendar') }}
                        </x-nav-link-li>
                    </ul>
                </nav>

            {{-- Hamburger --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md transition duration-150 ease-in-out nav_hamburger" aria-label="{{ __('alt_nav_resp_open') }}">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                        <path class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
    </div>

    {{-- Responsive Navigation Menu --}}
    <dialog id="auth_dialog" class="sm:hidden w-full h-full p-0 m-0 bg-transparent" x-effect="open ? $el.showModal() : $el.close()">
        <div class="fixed w-full h-screen cont_logo_nav_respo">
            <div class="cont_logo_nav">

                {{-- Logo --}}
                <x-application-logo class="logo" label="{{ __('alt_signet') }}" />

                {{-- Hamburger --}}
                <div class="-me-2 flex items-center sm:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md transition duration-150 ease-in-out nav_hamburger" aria-label="{{ __('alt_nav_resp_close') }}">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                            <path class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="fixed top-px w-full h-screen p-10 flex justify-center flex-col -z-10">
                <nav class="pt-2 pb-3 space-y-1 navbar nav-respo">
                    <ul>
                        <x-responsive-nav-link-li :href="route('home')" :active="request()->routeIs('home')">
                            {{ __('lgbt_home') }}
                        </x-responsive-nav-link-li>

                        <x-responsive-nav-link-li :href="route('home')" :active="false">
                            {{ __('lgbt_calendar') }}
                        </x-responsive-nav-link-li>

                        @auth
                            <x-responsive-nav-link-li :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">
                                {{ __('lgbt_profile.edit') }}
                            </x-responsive-nav-link-li>
                        @endif
                    </ul>
                </nav>

                {{-- Responsive Settings Options --}}
                <section class="pt-4 pb-3 border-t border-gray-200" aria-label="{{ __('alt_nav_resp_profile') }}">
                    @auth
                    <div class="px-2">
                        <p class="font-medium text-base text-gray-500">{{ Auth::user()->username }}</p>
                        <p class="font-medium text-sm text-gray-400">{{ Auth::user()->email }}</p>
                    </div>
                    @endif

                    <div class="mt-3 space-y-1 navbar nav-respo">
                        @auth
                        {{-- Logout --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-responsive-nav-link :href="route('logout')" :active="false"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('auth.logout') }}
                            </x-responsive-nav-link>
                        </form>
                        @else
                            <x-responsive-nav-link :href="route('login')" :active="false">
                                {{ __('auth.login') }}
                            </x-responsive-nav-link>
                        @endif
                    </div>
                </section>

                <aside>
                    <x-lang-switcher classes="pt-6 pb-1 border-t border-gray-200"/>
                </aside>
            </div>
        </div>
    </dialog>
</div>
