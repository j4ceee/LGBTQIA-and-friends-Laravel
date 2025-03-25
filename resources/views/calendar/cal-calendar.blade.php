@props(['headerLevel' => 2, 'style' => "compact", 'events'])

@php
$headerLevel = (int)$headerLevel;
if ($headerLevel < 1 || $headerLevel > 6) {
    $headerLevel = 2;
}
$header = '<h'.$headerLevel.' class="section_heading"></h'.$headerLevel.'>';

$event_count = count($events);
@endphp

<section class="calendar">
    <div class='section_header'>
        <h{{ $headerLevel }} class="section_heading">{{ __('lgbt_h_events') }}</h{{ $headerLevel }}>
        <div class='section_header_underline'></div>
    </div>

    @auth
        <div class="button_container">
            <a class="lgbt_button add_event_button" href="{{ route("event.add") }}">
                <span class="cal_admin_add_icon" style='mask: url({{ Vite::asset("resources/img/noun-plus-5505304.svg") }}) no-repeat center / contain; -webkit-mask-image: url({{ Vite::asset("resources/img/noun-plus-5505304.svg") }}); -webkit-mask-repeat:  no-repeat; -webkit-mask-position:  center; -webkit-mask-size: contain' aria-hidden='true'></span>
                <span class="cal_admin_add_icon icon_glow" aria-hidden='true'></span>
                {{ __('lgbt_event_add') }}
            </a>
        </div>
    @endauth

    <div class='calendar_container'>
        @if ($event_count == 0)
            <div class='calendar_list calendar_error'>
                <p class='calendar_item_name'>{{ __('err_no_events') }}</p>
            </div>
        @else
        <ul class='calendar_list {{ $style == "compact" ? "calendar_compact" : "" }}'>
            @foreach($events as $event)
                @include('calendar.cal-entry', ['event' => $event, 'style' => $style])
            @endforeach
        </ul>
        @endif
    </div>

    {{-- TODO: add iCal link when iCal functions are done --}}
    <button class="lgbt_button cal_subscribe_btn" id="default_calendar_copy_button" data-link="#" data-desc="{{ __('lgbt_cal_sub_cop') }}" type="button">
        <span class="cal_admin_add_icon cal_subscribe_icon" style='mask: url({{ Vite::asset("resources/img/noun-copy-4584147.svg") }}) no-repeat center / contain; -webkit-mask-image: url({{ Vite::asset("resources/img/noun-copy-4584147.svg") }}); -webkit-mask-repeat:  no-repeat; -webkit-mask-position:  center; -webkit-mask-size: contain' aria-hidden='true'></span>
        <span class="cal_admin_add_icon cal_subscribe_icon icon_glow" aria-hidden='true'></span>
        {{ __('lgbt_cal_sub') }}
    </button>
    {{-- TODO: notice that registration is required via socials / email to attend events --}}
</section>
