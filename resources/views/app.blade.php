<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'DPMD Bangkalan') }}</title>
        <!-- Favicon kecil (32x32, bukan 1.7MB!) -->
        <link rel="icon" type="image/png" href="/assets/favicon-32.png">

        <!-- Preload logo WebP untuk LCP (Largest Contentful Paint) -->
        <link rel="preload" as="image" href="/assets/logo-kabupaten.webp" type="image/webp">

        <!-- DNS Prefetch & Preconnect -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

        <!-- Fonts — Non-blocking (media=print trick) -->
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700&display=swap"
              rel="stylesheet" media="print" onload="this.media='all'">
        <noscript>
            <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;700&display=swap" rel="stylesheet">
        </noscript>

        <!-- Material Symbols — Non-blocking (hanya diload jika dipakai) -->
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,400,0,0&display=swap"
              rel="stylesheet" media="print" onload="this.media='all'">
        <noscript>
            <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,400,0,0&display=swap" rel="stylesheet">
        </noscript>

        <!-- Font Awesome DIHAPUS dari sini — dipindah ke Dashboard Admin saja -->

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
