@props(['member'])
<div class="staff_card">
    <div class='staff_img_container'>
        @if ($member->avatar != null)
            <img class="staff_img" src="{{ asset("storage/img/users/$member->avatar") }}" alt="{{ __('alt_team_avatar') }}{{ $member->display_name }}">
            <img class="staff_img staff_img_behind" src="{{ asset("storage/img/users/$member->avatar") }}" alt="{{ __('alt_team_avatar') }}{{ $member->display_name }}">
        @endif
    </div>

    @php
        // do pronouns
        $pronouns = $member->pronouns;
        $translated_pronouns = [];

        foreach($pronouns as $key) {
            $tmp = 'profile.pronouns_l.' . $key;
            $translated_pronouns[] = trans($tmp);
        }

        // do description
        $desc = $member->{"desc_" . app()->getLocale()}
    @endphp

    <div class='staff_info'>
        <p class='staff_info_field staff_name'><strong>{{ $member->display_name }}</strong></p>
        <p class='staff_info_field staff_pronouns'>({{ implode("/", $translated_pronouns) }})</p>
        <p class='staff_info_field staff_desc'>{!! nl2br(e($desc)) !!}</p>
    </div>
</div>
