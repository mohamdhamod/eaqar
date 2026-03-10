@props(['property'])
@php
    $opCode    = $property->operationType?->code ?? '';
    $opName    = $property->operationType?->name ?? '';
    $typeName  = $property->propertyType?->name ?? '';
    $cityName  = $property->city?->name ?? '';
    $currency  = $property->currency?->name ?? '';
    $image     = $property->mainImage?->image ?? null;
    $priceNum  = (float) $property->price;
    $price     = number_format($priceNum, 0, '.', ',');
    $url       = route('properties.show', $property->slug);
@endphp

<article class="prop-card">
    {{-- Image --}}
    <div class="prop-card__img-wrap">
        <a href="{{ $url }}">
            @if($image)
                <img src="{{ $image }}" alt="{{ $property->title }}" class="prop-card__img" loading="lazy">
            @else
                <div class="prop-card__img-placeholder">
                    <i class="bi bi-buildings text-secondary" style="font-size:2.5rem"></i>
                </div>
            @endif
        </a>

        {{-- Badges --}}
        <div class="prop-card__badges">
            <div class="d-flex flex-column gap-1">
                @if($property->is_featured)
                    <span class="badge prop-badge prop-badge--featured">
                        <i class="bi bi-star-fill me-1"></i>{{ __('translation.properties.featured') }}
                    </span>
                @endif
                @if($opCode)
                    <span class="badge prop-badge prop-badge--{{ $opCode }}">{{ $opName }}</span>
                @endif
            </div>
            @if($typeName)
                <span class="badge prop-badge prop-badge--type">{{ $typeName }}</span>
            @endif
        </div>
    </div>

    {{-- Body --}}
    <div class="prop-card__body">
        <div class="prop-card__price">
            {{ $price }}
            @if($currency)
                <span class="prop-card__price-currency">{{ $currency }}</span>
            @endif
        </div>

        <h3 class="prop-card__title">
            <a href="{{ $url }}">{{ $property->title }}</a>
        </h3>

        @if($cityName || $property->address)
            <p class="prop-card__location">
                <i class="bi bi-geo-alt-fill text-danger"></i>
                {{ $property->address ?: $cityName }}
            </p>
        @endif

        <ul class="prop-card__specs">
            @if($property->area)
                <li><i class="bi bi-aspect-ratio"></i> {{ number_format((float)$property->area, 0) }} {{ __('translation.properties.m2') }}</li>
            @endif
            @if($property->rooms)
                <li><i class="bi bi-door-open"></i> {{ $property->rooms }} {{ __('translation.properties.rooms') }}</li>
            @endif
            @if($property->bathrooms)
                <li><i class="bi bi-droplet"></i> {{ $property->bathrooms }} {{ __('translation.properties.bathrooms') }}</li>
            @endif
        </ul>
    </div>

    {{-- Footer --}}
    <div class="prop-card__footer">
        <span>{{ $property->created_at?->diffForHumans() }}</span>
        @if($typeName)
            <span class="prop-card__type-tag">{{ $typeName }}</span>
        @endif
    </div>
</article>
