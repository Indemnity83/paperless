<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport"                       content="width=device-width, initial-scale=1">
        <meta name="apple-mobile-web-app-capable"   content="yes">
        <meta name="csrf-token"                     content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        @livewireStyles

        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}" defer></script>

        <!-- Icons -->
        <link rel="shortcut icon" href="favicon.ico">
        <link rel="apple-touch-icon" type="image/png" href="/img/apple-touch-icon-57x57-precomposed.png" sizes="57x57" >
        <link rel="apple-touch-icon" type="image/png" href="/img/apple-touch-icon-60x60-precomposed.png" sizes="60x60" >
        <link rel="apple-touch-icon" type="image/png" href="/img/apple-touch-icon-72x72-precomposed.png" sizes="72x72" >
        <link rel="apple-touch-icon" type="image/png" href="/img/apple-touch-icon-76x76-precomposed.png" sizes="76x76" >
        <link rel="apple-touch-icon" type="image/png" href="/img/apple-touch-icon-114x114-precomposed.png" sizes="114x114" >
        <link rel="apple-touch-icon" type="image/png" href="/img/apple-touch-icon-120x120-precomposed.png" sizes="120x120" >
        <link rel="apple-touch-icon" type="image/png" href="/img/apple-touch-icon-144x144-precomposed.png" sizes="144x144" >
        <link rel="apple-touch-icon" type="image/png" href="/img/apple-touch-icon-152x152-precomposed.png" izes="152x152" >
        <link rel="apple-touch-icon" type="image/png" href="/img/apple-touch-icon-180x180-precomposed.png" sizes="180x180" >
        <link rel="icon" type="image/png" href="/img/favicon-16x16.png" sizes="16x16" >
        <link rel="icon" type="image/png" href="/img/favicon-32x32.png" sizes="32x32" >
        <link rel="icon" type="image/png" href="/img/favicon-72x72.png" sizes="72x72" >
        <link rel="icon" type="image/png" href="/img/favicon-96x96.png" sizes="96x96" >
        <link rel="icon" type="image/png" href="/img/favicon-128x128.png" sizes="128x128" >
        <link rel="icon" type="image/png" href="/img/favicon-196x196.png" sizes="196x196" >
        <link rel="manifest" href="site.webmanifest">
        <meta name="msapplication-TileColor" content="#2b5797">
        <meta name="msapplication-config" content="browserconfig.xml">
        <meta name="theme-color" content="#ffffff">
        <!-- <link rel='mask-icon' href='safari-pinned-tab.svg' color='#5bbad5'>  you'll have to put your svg here -->
    </head>
    <body class="font-sans antialiased">
        <x-jet-banner />

        <div class="min-h-screen bg-gray-100">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @livewireScripts
    </body>
</html>
