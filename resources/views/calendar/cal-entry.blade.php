@php use Illuminate\Support\Facades\Auth; @endphp
@props(['event', 'style'])

@php
    $locale = app()->getLocale();

    $event_name = $event->event_type->{"name_" . $locale};
    $event_id = $event->id;
    $event_location = $event->event_location->name;

    $event_desc = ''; // string, description of the event

    // cache
    $tmp_desc_override = $event->{"desc_".$locale."_override"};
    $tmp_desc_default = $event->event_type->{"desc_".$locale."_default"};

    if ($tmp_desc_override === "-") {
        // no description
    } elseif (($tmp_desc_override === null || $tmp_desc_override === "") &&
              ($tmp_desc_default !== "" && $tmp_desc_default !== null)) {
        // if override desc is empty & default desc exists
        $event_desc = $tmp_desc_default;
    } elseif ($tmp_desc_override) {
        $event_desc = $tmp_desc_override;
    }

    $tmp_date_start = $event->date_start; // cache
    $tmp_date_start = $tmp_date_start->setTimezone('Europe/Berlin');
    $date_start_day = $tmp_date_start->translatedFormat('jS');
    $date_start_month = $tmp_date_start->translatedFormat('F');
    $date_start_year = $tmp_date_start->translatedFormat('Y');

    $time_format = $locale === "de" ? 'H:i' : 'h:i a';
    $date_start_time = $tmp_date_start->translatedFormat($time_format);

    // booleans
    $eventPast = $tmp_date_start < now()->setTimezone('Europe/Berlin');
    $hasDesc = $event_desc != '';

    // calendar_item div classes
    $classes = ['calendar_item'];
    if ($eventPast) $classes[] = 'calendar_item_past';
    if ($hasDesc) $classes[] = 'calendar_button';

    $attributes = [];
    if ($hasDesc) {
        $attributes['id'] = "calendar_button_$event_id";
        $attributes['data-event-id'] = $event_id;
    }

@endphp

<li class='calendar_item_cont {{ Auth::check() ? "calendar_item_admin" : "" }}'>
    <div class="{{ implode(' ', $classes) }}" @foreach($attributes as $key => $value)
        {{ $key }}="{{ $value }}"
    @endforeach >
    <time class='calendar_item_date' datetime="{{ $tmp_date_start->translatedFormat('Y-m-d') }}">
        @if($locale == "de")
            <p class='calendar_item_day'>{{ $date_start_day }}</p>
            <p class='calendar_item_month'>{{ $date_start_month }}</p>
        @elseif($locale == "en")
            <p class='calendar_item_month'>{{ $date_start_month }}</p>
            <p class='calendar_item_day'>{{ $date_start_day }}</p>
        @endif
        <p class='calendar_item_year'>{{ $date_start_year }}</p>
    </time>

    <div class='calendar_item_info'>
        <p class='calendar_item_name'>{{ $event_name }}</p>
        @if($event_desc == "")
            <p class='calendar_item_desc' aria-hidden="true"></p>
        @else
            <details id="event_det_{{ $event_id }}" class="calendar_item_desc">
                <summary class="calendar_item_desc_ctrl" data-event-id="{{ $event_id }}">Details</summary>
                {!! nl2br(e($event_desc)) !!}
            </details>
        @endif
    </div>

    <div class='calendar_item_location'>
        <time class='calendar_item_loc_time'
              datetime="{{ $tmp_date_start->translatedFormat('H:i') }}">{{ $date_start_time }}</time>
        <p class='calendar_item_loc_name' lang='de'>{{ $event_location }}</p>
    </div>
    </div>

    {{-- Admin Stuff --}}
    @auth
        <div class='calendar_item_admin_ctrl'>
            <div class='calendar_item_admin_cont'>
                <a class='calendar_item_admin_link cal_admin_left' href="#" title="{{ __('lgbt_event_edit') }}"><span class='cal_admin_link_icon' style='mask: url({{ Vite::asset("resources/img/noun-edit-1047822.svg") }}) no-repeat center / contain; -webkit-mask-image: url({{ Vite::asset("resources/img/noun-edit-1047822.svg") }}); -webkit-mask-repeat:  no-repeat; -webkit-mask-position:  center; -webkit-mask-size: contain' aria-hidden='true'></span></a>
                <a class='calendar_item_admin_link cal_admin_right' href="#" title="{{ __('lgbt_event_delete') }}"><span class='cal_admin_link_icon' style='mask: url({{ Vite::asset("resources/img/noun-trash-2025467.svg") }}) no-repeat center / contain; -webkit-mask-image: url({{ Vite::asset("resources/img/noun-trash-2025467.svg") }}); -webkit-mask-repeat:  no-repeat; -webkit-mask-position:  center; -webkit-mask-size: contain' aria-hidden='true'></span></a>
            </div>
        </div>
    @endif

    {{-- TODO: add share button to every event --}}
</li>
