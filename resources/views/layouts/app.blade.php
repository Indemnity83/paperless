<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&family=Amatic+SC:wght@700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.7.570/pdf_viewer.min.css" integrity="sha512-srhhMuiYWWC5y1i9GDsrZwGM/+rZn0fsyBW/jYzbmSiwGs8I2iAX9ivxctNznU+WndPgbqtbYECLD8KYgEB3fg==" crossorigin="anonymous" />
        @livewireStyles

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.7.570/pdf.min.js" integrity="sha512-g4FwCPWM/fZB1Eie86ZwKjOP+yBIxSBM/b2gQAiSVqCgkyvZ0XxYPDEcN2qqaKKEvK6a05+IPL1raO96RrhYDQ==" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.7.570/pdf.worker.entry.min.js" integrity="sha512-NJEHr6hlBM4MkVxJu+7FBk+pn7r+KD8rh+50DPglV/8T8I9ETqHJH0bO7NRPHaPszzYTxBWQztDfL6iJV6CQTw==" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.7.570/pdf.worker.min.js" integrity="sha512-QVzIOQH0mGpLAOwHfVSOGsVjh4UGon7+hQwoqIUHbTMvcyS76Ee3AUDep58mU2TvdkPgzZ4aQqxbZ0v2wsyvpA==" crossorigin="anonymous"></script>

    </head>
    <body class="font-sans antialiased">
        <div class="h-screen flex overflow-hidden bg-white">

            <!-- Sidebar -->
            @livewire('navigation-menu')

            <!-- Main column -->
            <div class="flex flex-col w-0 flex-1 overflow-hidden">
                @include('search-header')

                <main class="flex-1 relative z-0 overflow-y-auto focus:outline-none" tabindex="0">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @stack('modals')

        @livewireScripts
    </body>
</html>
