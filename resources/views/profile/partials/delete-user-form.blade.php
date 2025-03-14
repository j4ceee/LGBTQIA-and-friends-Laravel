<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-white">
            {{ __('profile.sections.delete_h') }}
        </h2>

        <p class="mt-1 text-sm text-gray-300">
            {{ __('profile.sections.delete_d') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('profile.delete_account') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 profile_bg">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-white">
                {{ __('profile.sections.delete_2_h') }}
            </h2>

            <p class="mt-1 text-sm text-gray-300">
                @local2br('profile.sections.delete_2_d')
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('auth.password_field') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4 text-black"
                    placeholder="{{ __('auth.password_field') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

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
</section>
