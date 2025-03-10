@props(['classes'])

<ul class="lang_selection {{ $classes ?? "" }}">
    @if (app()->getLocale() === 'de')
        <li lang="de" class="lang_option"><p class="active" aria-label="Ausgewählte Sprache: Deutsch" aria-current="true">🇩🇪 DE</p></li>
        <li lang="en" class="lang_option"><a href="{{ route("language.set", ['en']) }}" rel="nofollow" aria-label="Change language to: English">🇬🇧 EN</a></li>
    @else
        <li lang="de" class="lang_option"><a href="{{ route("language.set", ['de']) }}" rel="nofollow" aria-label="Sprache wechseln zu: Deutsch">🇩🇪 DE</a></li>
        <li lang="en" class="lang_option"><p class="active" aria-label="Current Language: English" aria-current="true">🇬🇧 EN</p></li>
    @endif
</ul>

