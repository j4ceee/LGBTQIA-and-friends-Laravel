<fieldset class="w-full">
    <legend class="text-lg font-semibold">{{ __('profile.sections.account_h') }}</legend>

    {{-- Name --}}
    <div class="mt-4">
        <x-input-label for="name" :value="__('auth.username')" />
        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $user->username ?? '')" required autocomplete="username" />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    {{-- Email Address --}}
    <div class="mt-4">
        <x-input-label for="email" :value="__('auth.email')" />
        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email ?? '')" required autocomplete="email" />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    {{-- Password --}}
    <div class="mt-4">
        @if (isset($user))
            <x-input-label for="password" :value="__('auth.password_new')" />

            <x-text-input id="password" class="block mt-1 w-full"
                          type="password"
                          name="password"
                          autocomplete="new-password" />

        @else
        <x-input-label for="password" :value="__('auth.password_field')" />

        <x-text-input id="password" class="block mt-1 w-full"
                      type="password"
                      name="password"
                      required autocomplete="password" />
        @endif

        <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>

    {{-- Confirm Password --}}
    <div class="mt-4">
        @if (isset($user))
            <x-input-label for="password_confirmation" :value="__('auth.password_confirm')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                          type="password"
                          name="password_confirmation" autocomplete="new-password" />

        @else
        <x-input-label for="password_confirmation" :value="__('auth.password_confirm')" />

        <x-text-input id="password_confirmation" class="block mt-1 w-full"
                      type="password"
                      name="password_confirmation" required autocomplete="new-password" />
        @endif

        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
    </div>

    {{-- Role --}}
    <div class="mt-4">
        <x-input-label for="role" :value="__('auth.role')" />
        <select id="role" name="role" class="bg-transparent border-gray-700 focus:border-blue-300 focus:ring-indigo-500 rounded-md shadow-sm text-white w-full" required>
            <option value="0" {{ old('role', $user->admin ?? '') == 0 ? 'selected' : '' }}>{{ __('admin.user') }}</option>
            <option value="1" {{ old('role', $user->admin ?? '') == 1 ? 'selected' : '' }}>{{ __('admin.admin') }}</option>
        </select>
        <x-input-error :messages="$errors->get('role')" class="mt-2" />
    </div>

    @if (isset($user))
        {{-- Visibility Checkbox --}}
        <div class="mt-4">
            <x-input-label class="win_dark_check_label" for="visibility">
                <span>{{ __('admin.enable_visible') }}</span>
                <x-input-checkbox class="win_dark_check_org" id="visibility"
                                  name="visibility" isChecked="{{ (bool)old('visibility', $user->display_enabled) }}"/>
                <span class="win_dark_check"></span>
            </x-input-label>
            <x-input-error class="mt-2" :messages="$errors->get('visibility')"/>
        </div>
    @endif
</fieldset>
