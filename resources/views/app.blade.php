<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        @php
            $s           = $page['props']['site_settings'] ?? null;
            $siteName    = !empty($s?->site_name)  ? $s->site_name  : config('app.name', 'Distributor Forklift');
            $metaDesc    = !empty($s?->meta_description) ? $s->meta_description : 'Distributor forklift dan solusi material handling terpercaya untuk industri Indonesia.';
            $ogImageUrl  = !empty($s?->og_image)   ? url('storage/' . $s->og_image) : url('static/placeholder-produk.webp');
            $faviconUrl  = !empty($s?->favicon)    ? url('storage/' . $s->favicon)  : null;
            $canonical   = url()->current();
        @endphp

        <title inertia>{{ $siteName }}</title>

        <!-- SEO -->
        <meta name="description" content="{{ $metaDesc }}">
        <meta name="robots" content="index, follow">
        <link rel="canonical" href="{{ $canonical }}">

        <!-- Open Graph -->
        <meta property="og:type"        content="website">
        <meta property="og:locale"      content="id_ID">
        <meta property="og:site_name"   content="{{ $siteName }}">
        <meta property="og:url"         content="{{ $canonical }}">
        <meta property="og:title"       content="{{ $siteName }}">
        <meta property="og:description" content="{{ $metaDesc }}">
        <meta property="og:image"       content="{{ $ogImageUrl }}">
        <meta property="og:image:width"  content="1200">
        <meta property="og:image:height" content="630">

        <!-- Twitter Card -->
        <meta name="twitter:card"        content="summary_large_image">
        <meta name="twitter:title"       content="{{ $siteName }}">
        <meta name="twitter:description" content="{{ $metaDesc }}">
        <meta name="twitter:image"       content="{{ $ogImageUrl }}">

        <!-- Favicon -->
        @if($faviconUrl)
            <link rel="icon" type="image/png" href="{{ $faviconUrl }}">
            <link rel="shortcut icon" href="{{ $faviconUrl }}">
        @else
            <link rel="icon" type="image/x-icon" href="/favicon.ico">
            <link rel="shortcut icon" href="/favicon.ico">
        @endif

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
