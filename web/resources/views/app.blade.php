<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#ffffff">
    <link rel="shortcut icon" href="/favicon.ico">

    @if(Str::contains(Route::currentRouteName(), 'search.'))
        @if(Inertia\Inertia::getShared('preloadImages'))
            @foreach(Inertia\Inertia::getShared('preloadImages') as $preloadImage)
                @if(!is_null($preloadImage))
                    <link rel="preload" as="image" href="{{$preloadImage}}"/>
                @endif
            @endforeach
        @endif
        @include('includes.og-tags',[
            'meta_title' => Inertia\Inertia::getShared('title'),
            'meta_description' => Inertia\Inertia::getShared('description'),
            'photo_of_first_result' => Inertia\Inertia::getShared('photo_of_first_result'),
            'current_page_key' => Inertia\Inertia::getShared('current_page_key'),
            'current_url' => Inertia\Inertia::getShared('current_url'),
            'date_published' => Inertia\Inertia::getShared('date_published'),
            'date_update_of_first_result' => Inertia\Inertia::getShared('date_update_of_first_result'),
        ])
    @endif

    <!-- Google tag (gtag.js) -->
    @if(app()->environment('production'))
        <script>
            window.addEventListener('load', function () {
                setTimeout(function () {
                    var gtmScript = document.createElement('script');
                    gtmScript.src = 'https://www.googletagmanager.com/gtag/js?id=G-W4N8VP2P9E';
                    gtmScript.async = true;
                    document.head.appendChild(gtmScript);

                    window.dataLayer = window.dataLayer || [];

                    function gtag() {
                        dataLayer.push(arguments);
                    }

                    gtmScript.onload = function () {
                        gtag('js', new Date());
                        gtag('config', 'G-W4N8VP2P9E');
                    };
                }, 3000);
            });
        </script>
    @endif

    <!-- Scripts -->
    @routes
    @vite(['resources/js/app.js','resources/scss/app.scss', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
</head>
<body class="font-sans antialiased no-js">
<script>
    if (document) document.body.classList.remove('no-js');
</script>
@inertia
</body>
</html>
