@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'py-1 bg-white', 'dialogLabel' => null])

@php
switch ($align) {
    case 'left':
        $alignmentClasses = 'ltr:origin-top-left rtl:origin-top-right start-0';
        break;
    case 'top':
        $alignmentClasses = 'bottom-10 right-0';
        break;
    case 'right':
        $alignmentClasses = 'ltr:origin-top-right rtl:origin-top-left end-0';
        break;
    default:
        $alignmentClasses = $align;
        break;
}

switch ($width) {
    case '48':
        $width = 'w-48';
        break;
}
@endphp

<div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
    <div @click="open = ! open">
        {{ $trigger }}
    </div>

    <dialog x-effect="open ? $el.show() : $el.close()"
            class="absolute bg-transparent z-50 {{ $width }} {{ $alignmentClasses }} dropdown_dialog"
            @click="open = false"
            {{ $dialogLabel ? 'aria-label=' . $dialogLabel : '' }}
            tabindex="-1">
        <div class="ring-1 ring-black ring-opacity-5 {{ $contentClasses }} dropdown_dialog_content">
            {{ $content }}
        </div>
    </dialog>
</div>
