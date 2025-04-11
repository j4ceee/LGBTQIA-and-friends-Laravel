<x-app-layout>
    <x-slot name="header">
        {{ __('lgbt_admin') }}
    </x-slot>

    @include('components.status-msg')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex justify-end mb-6">
                <a class="lgbt_button add_event_button" href="{{ route('users.create') }}">
                    <span class="cal_admin_add_icon" style='mask: url({{ Vite::asset("resources/img/noun-plus-5505304.svg") }}) no-repeat center / contain; -webkit-mask-image: url({{ Vite::asset("resources/img/noun-plus-5505304.svg") }}); -webkit-mask-repeat:  no-repeat; -webkit-mask-position:  center; -webkit-mask-size: contain' aria-hidden='true'></span>
                    <span class="cal_admin_add_icon icon_glow" aria-hidden='true'></span>
                    {{ __('admin.user_add') }}
                </a>
            </div>

            <div class="user_list_bg profile_bg">
                <div class="user_grid">
                    <div class="ausgabe_user_head">
                        <p><span>{{__('admin.user')}}</span></p>
                        <p class="actions_header"><span>{{__('admin.actions')}}</span></p>
                    </div>
                    <div class="user_list">
                        @foreach ($users as $user)
                            <div class="user_card">
                                <div class="user_card_content">
                                    <div class="user_info user_info_grid">
                                        <p class="user_grid_items"><strong>{{__('auth.username')}}</strong>:</p> <p class="user_grid_items">{{ $user->username }}</p><br>
                                        <p class="user_grid_items"><strong>{{__('auth.email')}}</strong>:</p> <a class="underline" href="mailto:{{ $user->email }}">{{ $user->email }}</a><br>
                                        <p class="user_grid_items"><strong>{{__('auth.role')}}</strong>:</p>
                                        @if ($user->s_admin == 1)
                                            <p class="user_grid_items"><strong class="text-green-700">&#x2B24; S-{{__('admin.admin')}}</strong></p>
                                        @elseif ($user->admin == 1)
                                            <p class="user_grid_items"><strong class="text-green-600">&#x2B24; {{__('admin.admin')}}</strong></p>
                                        @else
                                            <p class="user_grid_items"><strong class="text-slate-600">&#x2B24; {{__('admin.user')}}</strong></p>
                                        @endif
                                        <br>
                                        <p class="user_grid_items"><strong>{{__('admin.visible_home')}}</strong>:</p> <p class="user_grid_items {{ $user->display_enabled ? "text-green-600" : "text-red-600" }}">{{ $user->display_enabled ? "✔" : "✘" }}</p>
                                        <br>
                                    </div>
                                    <div class="flex h-full items-center gap-3 user_actions">
                                        <x-secondary-button-link @class(["admin-users-action"]) href="{{ route('users.edit', ['id' => $user->id]) }}">
                                            <img class="admin-users-icons" src="{{ Vite::asset("resources/img/noun-edit-1047822.svg") }}" title="{{__('admin.user_n_edit', ['name' => $user->username])}}" alt="{{__('admin.user_n_edit', ['name' => $user->username])}}">
                                        </x-secondary-button-link>

                                        <x-danger-button @class(["admin-users-danger"]) x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion-{{ $user->id }}')">
                                            <img class="admin-users-icons" src="{{ Vite::asset("resources/img/noun-trash-2025467.svg") }}" title="{{__('admin.user_n_delete', ['name' => $user->username])}}" alt="{{__('admin.user_n_delete', ['name' => $user->username])}}">
                                        </x-danger-button>

                                        <x-modal name="confirm-user-deletion-{{ $user->id }}" :show="$errors->userDeletion->isNotEmpty()" focusable>
                                            <form method="post" action="{{ route('users.destroy', ['id' => $user->id]) }}" class="p-6 profile_bg">
                                                @csrf
                                                @method('DELETE')

                                                <h2 class="text-lg font-medium text-white">
                                                    {{ __('admin.delete_2_h', ['name' => $user->username]) }}
                                                </h2>

                                                <p class="mt-1 text-sm text-gray-400">
                                                    {{ __('admin.delete_2_d') }}
                                                </p>

                                                <div class="mt-6 flex justify-end">
                                                    <x-secondary-button x-on:click="$dispatch('close')">
                                                        {{ __('profile.cancel') }}
                                                    </x-secondary-button>

                                                    <x-danger-button class="ms-3">
                                                        {{ __('profile.delete_account') }}
                                                    </x-danger-button>
                                                </div>
                                            </form>
                                        </x-modal>
                                    </div>
                                    <details class="staff_preview">
                                        <summary>{{ __('admin.staff_prev') }}</summary>
                                        <div class="flex justify-center items-start flex-wrap gap-8 lg:justify-start">
                                            @foreach(config('app.available_locales') as $lang)
                                                <x-team-card :member="$user" :lang_t="$lang" />
                                            @endforeach
                                        </div>
                                    </details>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    @vite(['resources/css/admin.css'])
</x-app-layout>
