{{-- Secondary Navigation Menu --}}
<nav class="nav-bottom navbar">
    <ul class="nav_list">
        <x-nav-link-li :href="route('home')" :active="request()->routeIs('home')">
            {{ __('lgbt_home') }}
        </x-nav-link-li>
        <x-nav-link-li :href="route('home')" :active="false">
            {{ __('lgbt_calendar') }}
        </x-nav-link-li>
    </ul>
</nav>

<div class="hidden sm:flex sm:items-center sm:justify-center">
    <x-dropdown align="auth_modal_pos" width="48" contentClasses="auth_modal" dialogLabel="{{ __('lgbt_account_menu') }}">
        <x-slot name="trigger">
            <button class="auth_button" aria-label="{{ __('lgbt_account_button') }}">
                @auth
                    <span>{{ Auth::user()->username }}</span>
                @endif

                <svg viewBox="0 0 1200 1200" class="auth_icon"
                     xmlns="http://www.w3.org/2000/svg">
                     <path
                         d="m600 75c144.89 0 262.5 117.61 262.5 262.5s-117.61 262.5-262.5 262.5-262.5-117.61-262.5-262.5 117.61-262.5 262.5-262.5zm187.5 600c144.98 0 262.5 117.52 262.5 262.5v75c0 29.859-11.859 58.406-32.906 79.594-21.188 21.047-49.734 32.906-79.594 32.906h-675c-29.859 0-58.406-11.859-79.594-32.906-21.047-21.188-32.906-49.734-32.906-79.594v-75c0-144.98 117.52-262.5 262.5-262.5z"
                         fill-rule="evenodd"/>
                </svg>
            </button>
        </x-slot>

        <x-slot name="content">
            @auth
            <x-dropdown-link :href="route('profile.edit')">
                {{ __('lgbt_profile.edit') }}
            </x-dropdown-link>

            {{-- Authentication --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <x-dropdown-link :href="route('logout')"
                                 onclick="event.preventDefault();
                        this.closest('form').submit();">
                    {{ __('lgbt_logout') }}
                </x-dropdown-link>
            </form>
            @else
                <x-dropdown-link :href="route('login')">
                    {{ __('lgbt_login') }}
                </x-dropdown-link>
            @endif
        </x-slot>
    </x-dropdown>
</div>
