<section class="socials">
    <div class="section_header">
        <h2 class="section_heading">{{ __('lgbt_h_socials') }}</h2>
        <div class="section_header_underline"></div>
    </div>
    <div class="socials_container">
        <a href="https://www.instagram.com/lgbt_hs_ansbach/" class="social_link">
            <div class="social_icon_container" id="insta_cont">
                <img class="social_icon_white" src="{{ Vite::asset("resources/img/Instagram_Glyph_White.svg") }}" alt="Instagram Logo">
                <div aria-hidden="true" class="icon_bg"></div>
            </div>
        </a>
        <a href="https://chat.whatsapp.com/GhgPYEHw0tnGevVa4xcQiM" class="social_link">
            <div class="social_icon_container" id="whatsapp_cont">
                <img class="social_icon_white" src="{{ Vite::asset("resources/img/WhatsApp_Glyph_White.svg") }}" alt="WhatsApp Logo">
                <div aria-hidden="true" class="icon_bg"></div>
            </div>
        </a>
        <a href="https://discord.gg/d9qd2QVJJS" class="social_link">
            <div class="social_icon_container" id="discord_cont">
                <img class="social_icon_white" src="{{ Vite::asset("resources/img/Discord_Glyph_White.svg") }}" alt="Discord Logo">
                <div aria-hidden="true" class="icon_bg"></div>
            </div>
        </a>
        <a href="https://github.com/j4ceee/LGBTQIA-and-friends-Laravel" class="social_link">
            <div class="social_icon_container" id="github_cont">
                <img class="social_icon_white" src="{{ Vite::asset("resources/img/github-mark-white.svg") }}" alt="GitHub Logo">
                <div aria-hidden="true" class="icon_bg"></div>
            </div>
        </a>
        <a href="mailto:contact@lgbt-hs-ansbach.de" class="social_link">
            <div class="social_icon_container" id="email_cont">
                <div role="img" aria-label="{{ __('auth.email') }} Icon" class="auth_input_icon social_icon_white" style="mask: url({{ Vite::asset("resources/img/noun-6922977.svg") }}) no-repeat center / contain; -webkit-mask-image: url({{ Vite::asset("resources/img/noun-6922977.svg") }}); -webkit-mask-repeat:  no-repeat; -webkit-mask-position:  center; -webkit-mask-size: contain"></div>
                <div aria-hidden="true" class="icon_bg"></div>
            </div>
        </a>
    </div>
</section>
