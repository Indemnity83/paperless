<x-app-layout>

    <x-slot name="header">
        <div class="md:flex justify-between items-center">
            @if(request()->has('q'))
                <h2 class="font-semibold text-xl text-gray-400 leading-tight my-2 md:my-0">
                    Search results for<span class="italic font-bold text-gray-800">&nbsp;{{ request('q') }}</span>
                </h2>
            @else
                <h2 class="font-semibold text-xl text-gray-800 leading-tight my-2 md:my-0">
                    {{ __('All Files') }}
                </h2>
            @endif
            <div class="md:flex md:space-x-2">
                <form class="my-2 md:my-0" method="post" action="{{ route('files.store') }}" enctype="multipart/form-data">
                    @csrf
                    <label class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500" role="menuitem">
                        Upload a File
                        <input type="file" class="hidden" name="document" accept="application/pdf" onchange="form.submit()"/>
                    </label>
                </form>
                <form class="my-2 md:my-0" method="get" action="{{ route('files.index') }}" >
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
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="flex justify-between space-x-8 py-3 px-4 border-gray-300 border-b">
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
                            @if($file->thumbnail)
                                <img class="inline object-cover object-top w-8 h-8 mr-3" src="/files/{{ $file->id }}/thumbnail" />
                            @else
                                <svg class="inline animate-pulse w-8 h-8 mr-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            @endif
                            <a class="hover:underline" href="{{ route('files.show', $file) }}">{{ $file->name }}</a>
                        </div>
                        <div class="w-48 flex-none text-gray-500 hidden md:block">{{ $file->generated_at ? $file->generated_at->diffForHumans() : 'Unknown' }}</div>
                        <div class="w-28 flex-none text-gray-500 hidden sm:block">{{ $file->size }}</div>
                        <div class="w-28 flex-none text-gray-500 hidden sm:block">{{ $file->pages }} {{ Str::plural('page', $file->pages) }}</div>
                    </div>
                @endforeach

                <div class="px-4 py-3 bg-gray-200 bg-opacity-25">
                    {{ $files->links() }}
                </div>
            </div>
        </div>
    </div>

<div class="max-w-7xl px-4 mx-auto pt-6">




</div>

</x-app-layout>
