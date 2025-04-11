<x-app-layout>
    <div class="flex flex-col justify-center items-center pt-6 sm:pt-0">
        <x-page-header>
            {{ isset($user) ? __('admin.user_edit') : __('admin.user_add')}}
        </x-page-header>
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 profile_bg">
                @if (isset($user))
                    <form method="POST" action="{{ route('users.update', $user->id) }}">
                    @method('PATCH')
                @else
                    <form method="POST" action="{{ route('users.store') }}">
                    @method('POST')
                @endif

                @csrf

                <div class="flex gap-20 flex-wrap justify-evenly">
                    @if (isset($user))
                        @include('users.data_user', ['user' => $user])
                    @else
                        @include('users.data_user')
                    @endif
                </div>


                <div class="flex items-center justify-end mt-8">
                    <x-primary-button class="ms-4">
                        {{ isset($user) ? __('admin.user_update') : __('admin.user_add')}}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
    @vite(['resources/css/manage_event.css'])
</x-app-layout>
