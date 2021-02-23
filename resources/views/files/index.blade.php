<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="apple-mobile-web-app-capable" content="yes">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <title>Paperless | {{ request()->has('q') ? 'Search results for ' . request('q') : 'All Files' }}</title>

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

<div class="max-w-7xl px-4 mx-auto pt-6">
    <div class="pb-5 border-gray-200 sm:flex sm:items-center sm:justify-between">
        @if(request()->has('q'))
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center">
                    <li>
                        <div>
                            <a href="{{ route('files.index') }}" class="text-tomato-500 hover:text-tomato-400 flex items-center">
                                <img src="/img/app-icon.svg" class="h-12 -ml-2 mr-1.5" />
                                <h3 class="text-3xl leading-6 font-medium hidden md:block">
                                    Paperless
                                </h3>
                                <h3 class="text-3xl leading-6 font-medium md:hidden block mr-2">|</h3>
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center text-2xl font-bold leading-7 text-gray-400 sm:text-3xl sm:truncate">
                            <svg class="flex-shrink-0 h-10 w-10 text-gray-400 hidden md:block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                            Search results for<span class="italic font-bold text-gray-800">&nbsp;{{ request('q') }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        @else
            <div class="flex items-center">
                <img src="/img/app-icon.svg" class="h-12 -ml-2 mr-1.5" />
                <h3 class="text-3xl leading-6 font-medium text-tomato-500">
                    Paperless
                </h3>
            </div>
        @endif
        <div class="mt-3 sm:mt-0 sm:ml-4 flex space-x-4">

            @error('document')
            <div class="rounded-md bg-red-50 px-4 py-2">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">
                            {{ $message }}
                        </p>
                    </div>
                </div>
            </div>
            @enderror

            @if (session('status'))
            <div class="rounded-md bg-green-50 px-4 py-2">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">
                            {{ session('status') }}
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <div>
                <!-- This example requires Tailwind CSS v2.0+ -->
                <div x-data="{ isOpen: false }" class="relative inline-block text-left">
                    <div>
                        <button type="button" @click="isOpen = !isOpen" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500" id="options-menu" aria-haspopup="true" aria-expanded="true">
                            Actions
                            <!-- Heroicon name: solid/chevron-down -->
                            <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>

                    <div
                        x-show="isOpen"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="origin-top-left absolute left-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                        <div class="py-1">
                            <form method="post" action="{{ route('files.store') }}" enctype="multipart/form-data">
                                @csrf
                                <label class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                                    <!-- Heroicon name: solid/pencil-alt -->
                                    <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V8z" clip-rule="evenodd" />
                                    </svg>
                                    Upload a File
                                    <input type="file" class="hidden" name="document" accept="application/pdf" onchange="form.submit()"/>
                                </label>
                            </form>
                            <form method="post" name="consume" action="{{ route('files.consume') }}">
                                @csrf
                                <a href="#" onclick="document.forms['consume'].submit(); return false;" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                                    <!-- Heroicon name: solid/duplicate -->
                                    <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path d="M8.707 7.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l2-2a1 1 0 00-1.414-1.414L11 7.586V3a1 1 0 10-2 0v4.586l-.293-.293z" />
                                        <path d="M3 5a2 2 0 012-2h1a1 1 0 010 2H5v7h2l1 2h4l1-2h2V5h-1a1 1 0 110-2h1a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5z" />
                                    </svg>
                                    Run Consumer
                                </a>
                            </form>
                        </div>
                    </div>
                </div>

            </div>

            <div>
                <form method="get" action="{{ route('files.index') }}" >
                <label for="search" class="sr-only">Search</label>
                <div class="flex rounded-md shadow-sm">
                    <div class="relative flex-grow focus-within:z-10">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" name="q" id="search" value="{{ request('q') }}" class="focus:ring-indigo-500 focus:border-indigo-500 w-full rounded-none rounded-l-md pl-10 sm:text-sm border-gray-300" placeholder="Search files">
                    </div>
                    <button type="submit" class="-ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-r-md text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                        <span class="">Search</span>
                    </button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <div class="flex justify-between space-x-8 py-3 pr-4 border-gray-300 border-b">
        <div class="flex-grow">
                <a class="{{ trim(request('sort', 'name'), '-') === 'name' ? 'font-normal text-black' : ''  }}" href="{{ route('files.index', array_merge(request()->all(), ['sort' => request('sort', 'name') === 'name' ? '-name' : 'name'])) }}">
                    Name
                    @if(request()->get('sort', 'name') === 'name')
                        <svg class="h-4 inline text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                    @elseif(request()->get('sort') === '-name')
                        <svg class="h-4 inline text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    @endif
                </a>
        </div>
        <div class="w-48 flex-none hidden md:block">
            <a class="{{ trim(request('sort'), '-') === 'age' ? 'font-normal text-black' : ''  }}" href="{{ route('files.index', array_merge(request()->all(), ['sort' => request('sort') === 'age' ? '-age' : 'age'])) }}">
                Age
                @if(request()->get('sort') === 'age')
                    <svg class="h-4 inline text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                @elseif(request()->get('sort') === '-age')
                    <svg class="h-4 inline text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                @endif
            </a>
        </div>
        <div class="w-28 flex-none hidden sm:block">
            <a class="{{ trim(request('sort'), '-') === 'size' ? 'font-normal text-black' : ''  }}" href="{{ route('files.index', array_merge(request()->all(), ['sort' => request('sort') === 'size' ? '-size' : 'size'])) }}">
                Size
                @if(request()->get('sort') === 'size')
                    <svg class="h-4 inline text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                @elseif(request()->get('sort') === '-size')
                    <svg class="h-4 inline text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                @endif
            </a>
        </div>

        <div class="w-28 flex-none hidden sm:block">
            <a class="{{ trim(request('pages'), '-') === 'pages' ? 'font-normal text-black' : ''  }}" href="{{ route('files.index', array_merge(request()->all(), ['sort' => request('sort') === 'pages' ? '-pages' : 'pages'])) }}">
                Length
                @if(request()->get('sort') === 'pages')
                    <svg class="h-4 inline text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                @elseif(request()->get('sort') === '-pages')
                    <svg class="h-4 inline text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                @endif
            </a>
        </div>
    </div>

    @if(!count($files))
        <div class="rounded-md bg-yellow-50 p-4 mt-6 max-w-lg mx-auto">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">
                        No files matched criteria
                    </h3>
                </div>
            </div>
        </div>
    @endif

        @foreach($files as $file)
        <div class="flex justify-between space-x-8 py-5 px-6 border-gray-300 border-b {{ $file->trashed() ? 'bg-red-50 line-through' : '' }}">
            <div class="flex-grow whitespace-nowrap overflow-hidden overflow-ellipsis">
                <img class="inline object-cover object-top w-8 h-8 mr-3" src="/files/{{ $file->id }}/thumbnail" />
                <a class="hover:underline" href="{{ route('files.show', $file) }}">{{ $file->name }}</a>
            </div>
            <div class="w-48 flex-none text-gray-500 hidden md:block">{{ $file->generated_at ? $file->generated_at->diffForHumans() : 'Unknown' }}</div>
            <div class="w-28 flex-none text-gray-500 hidden sm:block">{{ $file->size }}</div>
            <div class="w-28 flex-none text-gray-500 hidden sm:block">{{ $file->pages }} {{ Str::plural('page', $file->pages) }}</div>
        </div>
    @endforeach

    <div class="my-3">
    {{ $files->links() }}
    </div>

</div>


</body>
</html>
