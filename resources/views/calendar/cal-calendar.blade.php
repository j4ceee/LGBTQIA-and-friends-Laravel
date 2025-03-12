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

    <div class='calendar_container'>
        @if ($event_count == 0)
            <div class='calendar_list calendar_error'>
                <p class='calendar_item_name'>{{ __('err_no_events') }}</p>
            </div>
        @else
        <ul class='calendar_list {{ $style == "compact" ? "calendar_compact" : "" }}'>
            @foreach($events as $event)
                @include('calendar.cal-entry', ['event' => $event, '$style' => $style])
            @endforeach
        </ul>
        @endif
    </div>

</section>
