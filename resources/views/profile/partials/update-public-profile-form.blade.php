<section>
    <header>
        <h2 class="text-lg font-medium text-white">
            {{ __('profile.sections.profile_h') }}
        </h2>

        <p class="mt-1 text-sm text-gray-300">
            {{ __("profile.sections.profile_d") }}
        </p>
    </header>

    <div class="mt-6 space-y-6 p-6 rounded-xl flex items-center justify-center">
        {{-- Profile Preview --}}
        <x-team-card :member="$user"></x-team-card>
    </div>

    <form method="post" action="{{ route('profile.update.pro') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="display_name" :value="__('profile.display_name')"/>
            <x-text-input id="display_name" name="display_name" type="text" class="mt-1 block w-full" :value="old('display_name', $user->display_name)"
                          required autocomplete="name"/>
            <x-input-error class="mt-2" :messages="$errors->get('display_name')"/>
        </div>

        <div>
            <x-input-label for="pronouns" :value="__('profile.pronouns')"/>
            <select id="pronouns" name="pronouns[]" multiple class="mt-1 block w-full bg-transparent border-gray-700 focus:border-blue-300 focus:ring-indigo-500 rounded-md shadow-sm text-white" required>
                <option class="focus:bg-blue-300 focus:ring-indigo-500" value="all" {{ in_array('all', old('pronouns', $user->pronouns ?? [])) ? 'selected' : '' }}>{{ __('profile.pronouns_l.all') }}</option>
                <option class="mt-1" value="f" {{ in_array('f', old('pronouns', $user->pronouns ?? [])) ? 'selected' : '' }}>{{ __('profile.pronouns_l.f') }}</option>
                <option class="mt-1" value="m" {{ in_array('m', old('pronouns', $user->pronouns ?? [])) ? 'selected' : '' }}>{{ __('profile.pronouns_l.m') }}</option>
                <option class="mt-1" value="d" {{ in_array('d', old('pronouns', $user->pronouns ?? [])) ? 'selected' : '' }}>{{ __('profile.pronouns_l.d') }}</option>
                <option class="mt-1" value="o" {{ in_array('o', old('pronouns', $user->pronouns ?? [])) ? 'selected' : '' }}>{{ __('profile.pronouns_l.o') }}</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('pronouns')"/>
        </div>

        <div>
            <x-input-label for="bio_de" :value="__('profile.bio_de')"/>
            <x-textarea-input id="bio_de" name="bio_de" type="text" class="mt-1 block w-full resize-none" :rows="3" :max="50" :value="old('bio_de', $user->desc_de)"
                          required/>
            <x-input-error class="mt-2" :messages="$errors->get('bio_de')"/>
        </div>

        <div>
            <x-input-label for="bio_en" :value="__('profile.bio_en')"/>
            <x-textarea-input id="bio_en" name="bio_en" type="text" class="mt-1 block w-full resize-none" :rows="3" :max="50" :value="old('bio_en', $user->desc_en)"
                          required/>
            <x-input-error class="mt-2" :messages="$errors->get('bio_en')"/>
        </div>

        <div>
            <x-input-label for="avatar" :value="__('profile.avatar')"/>
            <p class="my-1 text-gray-400 text-sm">{{ __('profile.avatar_d') }}</p>
            <x-text-input id="avatar" name="avatar" type="file" class="mt-1 block w-full" accept="image/jpeg, image/webp, image/png" :value="old('avatar', $user->avatar)"
                          />
            <x-input-error class="mt-2" :messages="$errors->get('avatar')"/>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('profile.save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-sm text-gray-600"
                >{{ __('profile.saved') }}.</p>
            @elseif (session('status') === 'no-changes')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-sm text-red-400"
                >{{ __('profile.no_changes') }}</p>
            @endif
        </div>
    </form>
</section>
