<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="apple-mobile-web-app-capable" content="yes">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <title>Paperless | {{ $file->name }}</title>

    <script src="{{ asset('js/app.js') }}" defer></script>

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
<body class="text-gray-800 font-light">
<div class="max-w-7xl px-4 mx-auto py-6 h-screen flex flex-col">
    <div class="lg:flex lg:items-center lg:justify-between">
        <div class="flex-1 min-w-0">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center">
                    <li class="flex-none">
                        <div>
                            <a href="{{ route('files.index') }}" class="text-tomato-500 hover:text-tomato-400 flex items-center">
                                <img src="/img/app-icon.svg" class="h-12 -ml-2 mr-1.5" style="margin-left: -0.5rem;" />
                                <h3 class="text-3xl leading-6 font-medium hidden md:block">
                                    Paperless
                                </h3>
                                <h3 class="text-3xl leading-6 font-medium md:hidden block mr-2">|</h3>
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate overflow-hidden overflow-ellipsis">
                            <svg class="flex-shrink-0 h-10 w-10 text-gray-400 hidden md:block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            {{ $file->name }}
                        </div>
                    </li>
                </ol>
            </nav>
            <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                @if($file->generated_at)
                    <div class="mt-2 flex items-center text-sm text-gray-500">
                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                        </svg>
                        <time datetime="{{ $file->generated_at->toW3cString() }}">Created {{ $file->generated_at->diffForHumans() }}</time>
                    </div>
                @else
                    <div class="mt-2 flex items-center text-sm text-gray-500">
                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                        </svg>
                        <time datetime="{{ $file->created_at->toW3cString() }}">Imported {{ $file->created_at->diffForHumans() }}</time>
                    </div>
                @endif
                <div class="mt-2 flex items-center text-sm text-gray-500">
                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z" />
                        <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd" />
                    </svg>
                    {{ $file->size }}
                </div>
                <div class="mt-2 flex items-center text-sm text-gray-500">
                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L14 2.586A2 2 0 0012.586 2H9z" />
                        <path d="M3 8a2 2 0 012-2v10h8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z" />
                    </svg>
                    {{ $file->meta('Pages') }} {{ Str::plural('page', $file->meta('Pages')) }}
                </div>
            </div>
        </div>
        <div class="mt-5 flex lg:mt-0 lg:ml-4">
            <form class="hidden lg:block" method="post" name="destroy" action="{{ route('files.destroy', $file) }}">
                @csrf
                @method('delete')
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    Move to Trash
                </button>
            </form>
            <span class="relative lg:hidden" x-data="{ isOpen: false }">
                <button @click="isOpen = !isOpen" type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" id="mobile-menu" aria-haspopup="true">
                    Actions
                    <svg class="-mr-1 ml-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div
                    x-show="isOpen"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="origin-top-left absolute left-0 mt-2 -mr-1 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5" aria-labelledby="mobile-menu" role="menu">
                    <form method="post" name="destroy" action="{{ route('files.destroy', $file) }}">
                        @csrf
                        @method('delete')
                        <a href="#" onclick="document.forms['destroy'].submit(); return false;" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Move to Trash</a>
                    </form>
                </div>
            </span>
        </div>
    </div>
    <object id="doc" class="w-full h-full mt-5 shadow-2xl border-2 border-gray-100" type="application/pdf" data="/files/{{ $file->id }}/download"></object>
</div>
</body>
</html>
