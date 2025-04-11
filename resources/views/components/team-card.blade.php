@props(['member', 'lang_t' => null])
<div class="staff_card">
    <div class='staff_img_container'>
        @if ($member->avatar != null && $member->display_name != null)
            <img class="staff_img" src="{{ asset("storage/img/users/$member->avatar") }}" alt="{{ __('alt_team_avatar') }}{{ $member->display_name }}">
            <img class="staff_img staff_img_behind" src="{{ asset("storage/img/users/$member->avatar") }}" alt="{{ __('alt_team_avatar') }}{{ $member->display_name }}">
        @endif
    </div>

    @php
        if($member->pronouns != null) {
            // do pronouns
            $pronouns = $member->pronouns;
            $translated_pronouns = [];

            foreach($pronouns as $key) {
                $tmp = 'profile.pronouns_l.' . $key;
                $translated_pronouns[] = trans($tmp, [], $lang_t);
            }
        }

        $lang = $lang_t ?? app()->getLocale();
        if($member->{"desc_" . $lang} != null) {
            // do description
            $desc = $member->{"desc_" . $lang};
            $dec = nl2br(e($desc));
        }
    @endphp

    <div class='staff_info'>
        <p class='staff_info_field staff_name'><strong>{{ $member->display_name ?? __('profile.display_name') }}</strong></p>
        <p class='staff_info_field staff_pronouns'>({{ $member->pronouns ? implode("/", $translated_pronouns) : __('profile.pronouns') }})</p>
        <p class='staff_info_field staff_desc'>{!! $dec ?? e(__('profile.bio')) !!}</p>
    </div>
</div>
