@php
    if($event->id ?? false) {
        $date_start = $event->date_start;
        $date_start = $date_start->setTimezone('Europe/Berlin')->format('Y-m-d\TH:i');

        $date_end = $event->date_end;
        $date_end = $date_end->setTimezone('Europe/Berlin')->format('Y-m-d\TH:i');

    } else {
        $date_start = date('Y-m-d\T19:00');
        $date_end = date('Y-m-d\T23:00');
    }

@endphp

<fieldset class="event_general profile_bg">
    <legend>{{ __('lgbt_ev_general') }}</legend>

    <div class="event_time">
        <div class="event_detail">
            <x-input-label for="event_date_start" :value="__('lgbt_ev_start')"/>
            <x-text-input type="datetime-local" class="event_date_start" id="event_date_start"
                   name="event_date_start" required :value="old('event_date_start', $date_start)"/>
            <x-input-error class="mt-2" :messages="$errors->get('event_date_start')"/>
        </div>

        <span class="event_detail event_dur">—</span>

        <div class="event_detail">
            <x-input-label for="event_date_end" :value="__('lgbt_ev_end')"/>
            <x-text-input type="datetime-local" class="event_date_end" id="event_date_end"
                          name="event_date_end" required :value="old('event_date_end', $date_end)"/>
            <x-input-error class="mt-2" :messages="$errors->get('event_date_end')"/>
        </div>
    </div>

    <div class="event_detail event_detail_location">
        <x-input-label for="event_location" :value="__('lgbt_ev_location')"/>
        <x-text-input type="text" class="event_location" id="event_location"
                      name="event_location" placeholder="{{ __('lgbt_ev_location') }}" list="event_location_list" required :value="old('event_location', $event->event_location->name ?? null)" maxlength="100"/>
        <datalist id="event_location_list">
            @foreach($event_locs as $loc)
                <option value="{{ $loc->name }}"></option>
            @endforeach
        </datalist>
        <x-input-error class="mt-2" :messages="$errors->get('event_location')"/>
    </div>

    @php
        // if override is "-" (no description), disable the checkbox
        // if override is not "-" and there is a default description, enable the checkbox
        $desc_enabled = false;
        $lang = app()->getLocale();
        $old = (bool)old('enable_desc');

        if ($old) {
            $desc_enabled = $old;
        }
        elseif($event->id ?? false) {
            if ($event->{"desc_" . $lang . "_override"} !== "-" && $event->event_type->{"desc_" . $lang} !== "") {
                $desc_enabled = true;
            }
        }

    @endphp

    <x-input-label class="win_dark_check_label" for="enable_desc">
        <span>{{ __('lgbt_ev_use_desc') }}</span>
        <x-input-checkbox class="enable_desc win_dark_check_org" id="enable_desc"
                      name="enable_desc" isChecked="{{ $desc_enabled }}"/>
        <span class="win_dark_check"></span>
    </x-input-label>
    <x-input-error class="mt-2" :messages="$errors->get('enable_desc')"/>
</fieldset>

<div class="event_locales">
    @foreach(config('app.available_locales') as $locale)
        @include('event.partials.ev-info-lang', ['event_types' => $event_types, 'locale' => $locale, 'descEnabled' => $desc_enabled, 'event' => $event ?? null])
    @endforeach
</div>

<div class="w-full mt-8 flex justify-end align-center">
    <x-primary-button type="submit">{{ __('profile.save') }}</x-primary-button>
</div>

