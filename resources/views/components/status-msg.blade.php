<div class="flex justify-center"
     x-data="{ show: true }"
     x-show="show"
     x-transition
     x-init="setTimeout(() => show = false, 5000)"
>
    @if (Session::has('success'))
        <div class="alert alert-success max-w-7xl sm:mx-6 lg:mx-8 w-full">
            <p>{{ Session::get('success') }}</p>
        </div>
    @elseif (Session::has('error'))
        <div class="alert alert-error max-w-7xl sm:mx-6 lg:mx-8 w-full">
            <p>{{ Session::get('error') }}</p>
        </div>
    @elseif (Session::has('info'))
        <div class="alert alert-info max-w-7xl sm:mx-6 lg:mx-8 w-full">
            <p>{{ Session::get('info') }}</p>
        </div>
    @endif
</div>
