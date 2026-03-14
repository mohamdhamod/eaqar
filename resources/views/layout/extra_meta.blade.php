@section('extra_meta')
    @php
        $description = \App\Models\ConfigTitle::where('key', \App\Enums\ConfigEnum::ABOUT_US_HERO)
            ->first()
            ?->description ?? env('DESCRIPTION');
    @endphp
    <meta name="description" content="{{ $description }}">

    <!-- Google / Search Engine Tags -->
    <meta itemprop="name" content="{{ __('translation.app.name') }}">
    <meta itemprop="description" content="{{ $description }}">
    <meta itemprop="image" content="{{env('APP_URL')}}">

    <!-- Facebook Meta Tags -->
    <meta property="og:url" content="{{env('APP_URL')}}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ __('translation.app.name') }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:image" content="{{ $config_images[\App\Enums\ConfigEnum::FAVICON]->image_path ?? asset('images/img.png') }}">

    <!-- Twitter Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ __('translation.app.name') }}">
    <meta name="twitter:description" content="{{ $description }}">
    <meta name="twitter:image" content="{{ $config_images[\App\Enums\ConfigEnum::FAVICON]->image_path ?? asset('images/img.png') }}">
@endsection
