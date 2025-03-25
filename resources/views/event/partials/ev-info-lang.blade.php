@props(['event_types', 'locale', 'descEnabled', 'event' => null])

<fieldset class="event_info_{{ $locale }} event_info profile_bg">
    <legend>{{ __('events.info_' . $locale) }}</legend>

    <x-input-label for="event_name_{{ $locale }}" :value="__('events.title')"/>
    <x-text-input type="text" class="event_name_{{ $locale }}" id="event_name_{{ $locale }}"
                  name="event_name_{{ $locale }}" required placeholder="{{ __('events.title') }}"
                  :value="old('event_name_'.$locale, $event->event_type->{'name_'.$locale} ?? null)"
                  list="event_name_{{ $locale }}_list" maxlength="50"/>
    <x-input-error class="mt-2" :messages="$errors->get('event_name_'.$locale)"/>

    <datalist id="event_name_{{ $locale }}_list">
        @foreach($event_types as $type)
            <option value="{{ $type->{'name_'.$locale} }}" data-value="{{ $type->id }}" {!! $type->{'desc_'.$locale} ? 'data-desc="'.e($type->{'desc_'.$locale}).'"' : null !!}></option>
        @endforeach
    </datalist>

    @php
        $desc = null;
        $placeholder_prepend = __('lgbt_ev_def_desc');
        $placeholder = $event->event_type->{"desc_".$locale} ?? false ?  $placeholder_prepend."\n".$event->event_type->{"desc_".$locale} : null;
        // at this point: override is not "-" (meaning description is enabled) and there is a default description
        // so: we need to check if there is an override description (not null or empty)
        if ($descEnabled) {
            //old('event_name_'.$locale, $event->event_type->{'name_'.$locale} ?? null);
            if ($event !== null) {
                if($event->{"desc_".$locale."_override"} !== null && $event->{"desc_".$locale."_override"} !== "") {
                    $desc = $event->{"desc_".$locale."_override"};
                }
            }
        }
    @endphp

    <x-input-label for="event_desc_{{ $locale }}" :value="__('events.desc')"/>
    <x-input-text-area class="event_desc_{{ $locale }}" id="event_desc_{{ $locale }}" maxlength="1500"
                  name="event_desc_{{ $locale }}" placeholder="{{ $placeholder }}" data-prepend="{{ $placeholder_prepend }}" disabled="{{ !$descEnabled }}">
        {{ $desc }}
    </x-input-text-area>
    <x-input-error class="mt-2" :messages="$errors->get('event_desc_'.$locale)"/>
</fieldset>
