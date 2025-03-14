<x-app-layout>
    <div class="welcome_slide">
        <canvas id="canvas" class="canvas_anim" aria-hidden="true"></canvas>
        <div class="welcome_slide_content">
            <h1 class="heading_start"><span class="heading_top">{{ __('lgbt_name') }}</span>
                <span class="heading_btm">{{ __('lgbt_uni') }}</span></h1>
            <img class="heading_logo" src="{{ Vite::asset("resources/img/lgbt_bunny_white_full_opt.svg") }}" alt="{{ __('alt_signet') }}">
        </div>
    </div>

    <div class="page_content home_slide_2">
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

    <div class="home_slide_3">
        <section class="page_content team">
            <div class="section_header">
                <h2 class="section_heading">{{__('lgbt_h_team')}}</h2>
                <div class="section_header_underline"></div>
            </div>

            <div class="staff_presentation">
                @foreach($team as $member)
                    <x-team-card :member="$member" />
                @endforeach
            </div>

        </section>
    </div>

    <div class="page_content">
        <x-socials></x-socials>
    </div>

    @vite(['resources/js/animated_bg.js', 'resources/js/view_calendar.js'])
</x-app-layout>
