<x-app-layout>
    @include('components.status-msg')

    <div class="page_content home_slide_2">
        @include('calendar.cal-calendar', ['events' => $events, 'style' => 'def'])
    </div>

    <div class="page_content">
        <x-socials></x-socials>
    </div>

    @vite(['resources/js/view_calendar.js'])
</x-app-layout>
