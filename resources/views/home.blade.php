@vite(['resources/js/animated_bg.js', 'resources/js/view_calendar.js'])

<x-app-layout>
    {{--
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Home') }}
        </h2>
    </x-slot>
    --}}

    <div id="canvas_light" style="position: fixed; top: 50%; left: 50%; width: 0; height: 0; transform: translateZ(0); filter: blur(2rem)"></div>

    <div class="welcome_slide">
        <canvas id="canvas" class="canvas_anim"></canvas>
        <div class="welcome_slide_content">
            <h1 class="heading_start"><span class="heading_top">{{ __('lgbt_name') }}</span>
                <span class="heading_btm">{{ __('lgbt_uni') }}</span></h1>
            <img class="heading_logo" src="{{ Vite::asset("resources/img/lgbt_bunny_white_full_opt.svg") }}" alt="{{ __('alt_signet') }}">
        </div>
    </div>

    <div class="page_content">
        <section class="about">
            <div class="section_header">
                <h2 class="section_heading">{{__('lgbt_h_about')}}</h2>
                <div class="section_header_underline"></div>
            </div>
            <div class="about_txt_container">
                <p class="about_text">
                    @local2br('lgbt_about_text')
                </p>
            </div>
        </section>

        @include('calendar.cal-calendar', ['events' => $events, 'style' => 'test'])
    </div>

</x-app-layout>
