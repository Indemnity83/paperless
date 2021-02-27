<x-layout>

    <div class="bg-white shadow">
        <div class="px-4 sm:px-6 lg:max-w-7xl lg:mx-auto lg:px-8">
            <div class="py-6 md:flex md:items-center md:justify-between lg:border-t lg:border-gray-200">
                <div class="flex-1 min-w-0">
                    <!-- Profile -->
                    <div class="flex items-center">
                        <div>
                            <div class="flex items-center">
                                @if(request()->has('q'))
                                    <h1 class="ml-3 text-2xl font-bold leading-7 text-gray-400 sm:leading-9 sm:truncate">
                                        Search results for<span class="italic font-bold text-gray-800">&nbsp;{{ request('q') }}</span>
                                    </h1>
                                @else
                                    <h1 class="ml-3 text-2xl font-bold leading-7 text-gray-900 sm:leading-9 sm:truncate">
                                        All Files
                                    </h1>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex space-x-3 md:mt-0 md:ml-4">
                    <form method="post" action="{{ route('files.store') }}" enctype="multipart/form-data">
                        @csrf
                        <label class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-chocolate-500">
                            Upload a File
                            <input type="file" class="hidden" name="document" accept="application/pdf" onchange="form.submit()"/>
                        </label>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(!count($files))

        <div class="px-4 sm:px-6 lg:max-w-7xl lg:mx-auto lg:px-8 py-8">
            <div class="bg-gray-200 overflow-hidden rounded-lg">
                <div class="px-4 py-5 sm:p-6 text-center text-2xl">
                    Nothing Found
                </div>
            </div>
        </div>

    @else

        <div class="px-4 sm:px-6 lg:max-w-7xl lg:mx-auto lg:px-8 mt-8">
            <div class="flex justify-between space-x-8 py-3 border-gray-300 border-b">
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

                <div class="w-28 text-right flex-none hidden sm:block">
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

                <div class="w-20 text-right flex-none hidden md:block">
                    <a class="{{ trim(request('pages'), '-') === 'pages' ? 'font-normal text-black' : ''  }}" href="{{ route('files.index', array_merge(request()->all(), ['sort' => request('sort') === 'pages' ? '-pages' : 'pages'])) }}">
                        Pages
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

                <div class="w-28 text-right flex-none hidden sm:block">
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
            </div>

            @foreach($files as $file)
                <div class="flex justify-between space-x-8 py-5 border-gray-300 border-b {{ $file->trashed() ? 'bg-red-50 line-through' : '' }}">
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
                    <div class="w-28 flex-none text-right text-gray-500 hidden sm:block">{{ $file->size }}</div>
                    <div class="w-20 flex-none text-right text-gray-500 hidden md:block">{{ $file->pages }} {{ Str::plural('page', $file->pages) }}</div>
                    <div class="w-28 flex-none text-right text-gray-500 hidden sm:block">{{ $file->generated_at ? $file->generated_at->format('M d, Y') : 'Unknown' }}</div>
                </div>
            @endforeach

            <div class="my-8">
            {{ $files->links() }}
            </div>

        </div>

    @endif

</x-layout>
