@props([
    'url' => null,
    'ean' => null,
    'alt' => '',
    'class' => 'h-full w-full object-contain',
    'wrapperClass' => 'relative grid place-items-center overflow-hidden rounded-xl bg-slate-100',
    'lazy' => true,
])

@php
    $fallback = asset('/LOGO_FOCCUS.png');
    $primaryUrl = $url ?? $fallback;
    $eanPicturesUrl = $ean
        ? 'http://www.eanpictures.com.br:9000/api/gtin/' . preg_replace('/\D/', '', $ean)
        : null;
    $loadingAttr = $lazy ? 'loading="lazy"' : '';
@endphp

<div class="{{ $wrapperClass }}">
    <img
        src="{{ $primaryUrl }}"
        alt="{{ $alt }}"
        class="{{ $class }}"
        {{ $loadingAttr }}
        decoding="async"
        onerror="
            var img = this;
            if (!img._fallbackAttempted) {
                img._fallbackAttempted = true;
                @if($eanPicturesUrl)
                img.src = '{{ $eanPicturesUrl }}';
                @else
                img.onerror = null;
                img.src = '{{ $fallback }}';
                @endif
            } else {
                img.onerror = null;
                img.src = '{{ $fallback }}';
            }
        "
    >
</div>