@php use Illuminate\Support\Facades\Auth; @endphp
@props(['event', 'style', 'locale'])

@php
    $event_name = $event->event_type->{"name_" . $locale};
    $event_id = $event->id;
    $event_location = $event->event_location->name;

    $event_desc = ''; // string, description of the event

    // cache
    $tmp_desc_override = $event->{"desc_".$locale."_override"};
    $tmp_desc_default = $event->event_type->{"desc_".$locale};

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

    $tmp_date_end = $event->date_end;
    $tmp_date_end = $tmp_date_end->setTimezone('Europe/Berlin');

    $share_date = $tmp_date_start->translatedFormat('d.m.Y');

    $date_start_day = $tmp_date_start->translatedFormat('jS');
    $date_start_month = $tmp_date_start->translatedFormat('F');
    $date_start_year = $tmp_date_start->translatedFormat('Y');

    $time_format = $locale === "de" ? 'H:i' : 'h:i a';
    $date_start_time = $tmp_date_start->translatedFormat($time_format);

    $share_time = $date_start_time;
    if ($locale === "de") {
        $share_time .= " Uhr";
    }

    // booleans
    $eventPast = $tmp_date_end < now()->setTimezone('Europe/Berlin');
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
            <div>
                <p class='calendar_item_name'>{{ $event_name }}</p>
                @if($event_desc == "")
                    {{-- <p class='calendar_item_desc' aria-hidden="true"></p> --}}
                @else
                    <details id="event_det_{{ $event_id }}" class="calendar_item_desc">
                        <summary class="calendar_item_desc_ctrl" data-event-id="{{ $event_id }}">Details</summary>
                        {!! nl2br(e($event_desc)) !!}
                    </details>
                @endif
            </div>

            <button class="lgbt_button event_share_btn" type="button" title="{{ __('lgbt_share') }}"
                    data-link="{{ route('event.show', ['id' => $event_id]) }}"
                    data-name="{{ $event_name }}"
                    data-time="{{ __('lgbt_ev_start') . ": " . $share_date . ", " . $share_time }}"
                    data-loc="{{ __('lgbt_ev_location') . ": " . $event_location }}"
            >
                <span class="cal_admin_add_icon cal_share_icon" style='mask: url({{ Vite::asset("resources/img/noun-share-7697480.svg") }}) no-repeat center / contain; -webkit-mask-image: url({{ Vite::asset("resources/img/noun-share-7697480.svg") }}); -webkit-mask-repeat:  no-repeat; -webkit-mask-position:  center; -webkit-mask-size: contain' aria-hidden='true'></span>
                <span class="cal_admin_add_icon cal_share_icon icon_glow" aria-hidden='true'></span>
                {{ __('lgbt_share') }}
            </button>
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
                <a class='calendar_item_admin_link cal_admin_left' href="{{ route("event.edit", ["id" => $event_id]) }}" title="{{ __('lgbt_event_edit') }}">
                    <span class='cal_admin_link_icon' style='mask: url({{ Vite::asset("resources/img/noun-edit-1047822.svg") }}) no-repeat center / contain; -webkit-mask-image: url({{ Vite::asset("resources/img/noun-edit-1047822.svg") }}); -webkit-mask-repeat:  no-repeat; -webkit-mask-position:  center; -webkit-mask-size: contain' aria-hidden='true'></span>
                </a>
                <form method="POST" action="{{ route('event.delete', ['id' => $event_id]) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" title="{{ __('lgbt_event_delete') }}" class="calendar_item_admin_link cal_admin_right">
                        <span class='cal_admin_link_icon' style='mask: url({{ Vite::asset("resources/img/noun-trash-2025467.svg") }}) no-repeat center / contain; -webkit-mask-image: url({{ Vite::asset("resources/img/noun-trash-2025467.svg") }}); -webkit-mask-repeat:  no-repeat; -webkit-mask-position:  center; -webkit-mask-size: contain' aria-hidden='true'></span>
                    </button>
                </form>
            </div>
        </div>
    @endauth
</li>
