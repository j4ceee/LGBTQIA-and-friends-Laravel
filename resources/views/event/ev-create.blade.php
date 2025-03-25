<x-app-layout>
    <x-slot name="header">
        {{ __('lgbt_event.add') }}
    </x-slot>

    <div class="page_content manage_event_content">
        <form class="manage_event_form" action="{{ route('event.store') }}" method="post" autocomplete="off">
            @method('POST')
            @csrf
            @include('event.partials.ev-manage', ['event_locs' => $event_locs])
        </form>
    </div>

    @vite(['resources/js/manage_event.js', 'resources/css/manage_event.css'])
</x-app-layout>
