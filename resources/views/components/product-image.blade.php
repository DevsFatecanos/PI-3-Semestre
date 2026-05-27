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
                var xhr = new XMLHttpRequest();
                xhr.timeout = 2000;
                xhr.open('HEAD', '{{ $eanPicturesUrl }}', true);
                xhr.onload = function() { if(xhr.status < 400) img.src = '{{ $eanPicturesUrl }}'; else img.src = '{{ $fallback }}'; };
                xhr.onerror = function() { img.src = '{{ $fallback }}'; };
                xhr.ontimeout = function() { img.src = '{{ $fallback }}'; };
                xhr.send();
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