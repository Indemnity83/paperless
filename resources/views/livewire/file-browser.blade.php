<div class="py-6 sm:py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">



        <div class="px-4 pb-5 sm:pr-0 flex items-center justify-between">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-1">
                    @foreach($this->ancestors as $ancestor)
                    <li>
                        <div class="flex items-center">
                            @if($loop->first)
                                <a href="{{ route('browse', ['o' => $ancestor->hash]) }}" class="text-gray-400 hover:text-gray-500">
                                    <!-- Heroicon name: solid/home -->
                                    <svg class="flex-shrink-0 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                                    </svg>
                                    <span class="sr-only">Home</span>
                                </a>
                            @else
                                <svg class="flex-shrink-0 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                                <a href="{{ route('browse', ['o' => $ancestor->hash]) }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">
                                    {{ $ancestor->object->name }}
                                </a>
                            @endif
                        </div>
                    </li>
                    @endforeach
                </ol>
            </nav>

            <div class="flex items-center space-x-2">
                @if($this->directoryTree->object_type === 'folder')
                    <x-jet-secondary-button wire:click="$set('creatingFolder', true)" class="hidden md:inline">
                        New folder
                    </x-jet-secondary-button>
                    <form class="hidden md:inline" method="post" action="{{ route('files.store') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="parent_id" value="{{ $this->directoryTree->id }}" />
                        <label class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150" role="menuitem">
                            Upload a File
                            <input type="file" class="hidden" name="document" accept="application/pdf" onchange="form.submit()"/>
                        </label>
                    </form>
                @endif

                @if($this->directoryTree->object_type === 'file')
                    <x-jet-secondary-button wire:click="$set('deletingChild', {{ $this->directoryTree->id }})" class="hidden md:inline">
                        Trash
                    </x-jet-secondary-button>
                @endif
                <!-- This example requires Tailwind CSS v2.0+ -->
                <div class="relative inline-block text-left inline md:hidden" x-data="{ isOpen: false }">
                    <div>
                        <button @click="isOpen = !isOpen" type="button" class="-my-2 p-2 flex items-center text-gray-400 hover:text-gray-600" id="menu-1" aria-expanded="false" aria-haspopup="true">
                            <span class="sr-only">Open options</span>
                            @if($this->directoryTree->object_type === 'folder')
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            @else
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            @endif
                        </button>
                    </div>
                    <div
                        x-show="isOpen"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transform opacity-100 scale-100"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="z-50 origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                        <div class="py-1" role="none">
                            @if($this->directoryTree->object_type === 'folder')
                                <a href="#" wire:click="$set('creatingFolder', true)" @click="isOpen = false" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                                    New Folder
                                </a>
                                <form method="post" action="{{ route('files.store') }}" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="parent_id" value="{{ $this->directoryTree->id }}" />
                                    <label class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                                        Upload a File
                                        <input type="file" class="hidden" name="document" accept="application/pdf" onchange="form.submit()"/>
                                    </label>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="bg-white shadow-xl sm:rounded-lg">

            <!-- This example requires Tailwind CSS v2.0+ -->
            @if($this->directoryTree->object_type === 'folder')
                <div class="">
                    <div class="flow-root">
                        <ul class="divide-y divide-gray-200">
                            @if($creatingFolder)
                                <li class="py-2">
                                    <div class="flex items-center space-x-4 px-4">
                                        <form class="flex items-center flex-grow space-x-2">
                                            <x-jet-input wire:model="createFolderState.name" type="text" class="w-full" autofocus />
                                            <x-jet-button wire:click.prevent="createFolder" type="submit">
                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </x-jet-button>
                                            <x-jet-secondary-button wire:click="$set('creatingFolder', false)">
                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </x-jet-secondary-button>
                                        </form>
                                    </div>
                                </li>
                            @endif
                            @foreach($this->directoryTree->children as $child)
                            <li class="py-2 hover:bg-gray-50" wire:key="{{ $child->path }}">
                                <div class="flex items-center space-x-4 px-4">
                                    @if($renamingChild === $child->id)
                                        <form class="flex items-center flex-grow space-x-2">
                                            <x-jet-input wire:model="renamingChildState.name" type="text" class="w-full" />
                                            <x-jet-button wire:click.prevent="renameChild" type="submit">
                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </x-jet-button>
                                            <x-jet-secondary-button wire:click="$set('renamingChild', false)">
                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </x-jet-secondary-button>
                                        </form>
                                    @else
                                        <a href="{{ route('browse', ['o' => $child->hash]) }}" class="flex flex-grow items-center space-x-4">
                                            <div class="flex-shrink-0">
                                                @if($child->object_type === 'folder')
                                                    <svg class="w-8 h-8 text-gray-300"  xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                                    </svg>
                                                @elseif($child->object_type === 'file' && $child->object->thumbnail !== null)
                                                    <img class="h-8 w-8" src="/files/{{$child->object->id}}/thumbnail" alt="">
                                                @else
                                                    <svg class="w-8 h-8 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                    </svg>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0 overflow-hidden overflow-ellipsis">
                                                <span class="text-sm font-medium text-gray-900 truncate">
                                                    {{ $child->object->name }}
                                                </span>
                                                <ul class="flex space-x-2 text-sm text-gray-500 truncate">
                                                    <li>
                                                        {{ Str::bytesForHumans($child->descendantsAndSelf->where('object_type', 'file')->sum('object.bytes')) }}
                                                    </li>
                                                    <li class="text-gray-300">&bull;</li>
                                                    <li>
                                                        {{ Str::relativePrecisionDate($child->object->created_at) }}
                                                    </li>
                                                </ul>
                                            </div>
                                        </a>
                                        <div class="relative inline-block text-right flex items-center" >
                                            <button wire:click="$set('renamingChild', {{ $child->id }})" class="hidden md:inline p-3 text-sm font-bold leading-5 text-gray-400 hover:text-gray-600">
                                                Rename
                                            </button>
                                            <button wire:click="$set('movingChild', {{ $child->id }})" class="hidden md:inline p-3 text-sm font-bold leading-5 text-gray-400 hover:text-gray-600">
                                                Move
                                                {{ optional($movingChildState)->parent_id  }}
                                            </button>
                                            <button wire:click="$set('deletingChild', {{ $child->id }})" class="hidden md:inline p-3 text-sm font-bold leading-5 text-pink-400 hover:text-red-600">
                                                Trash
                                            </button>
                                            <div x-data="{ isOpen: false }">
                                                <button @click="isOpen = !isOpen" class="inline-flex md:hidden items-center p-3 text-sm leading-5 font-medium text-gray-400 hover:text-gray-800">
                                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                                                    </svg>
                                                </button>
                                                <div
                                                    x-show="isOpen"
                                                    x-transition:enter="transition ease-out duration-100"
                                                    x-transition:enter-start="transform opacity-0 scale-95"
                                                    x-transition:enter-end="transform opacity-100 scale-100"
                                                    x-transition:leave="transform opacity-100 scale-100"
                                                    x-transition:leave-start="opacity-100 scale-100"
                                                    x-transition:leave-end="transform opacity-0 scale-95"
                                                    class="z-50 origin-top-right absolute right-0 -mt-1.5 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="options-menu"
                                                >
                                                    <div class="py-1" role="none">
                                                        <a href="#" wire:click="$set('renamingChild', {{ $child->id }})" @click="isOpen = false" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                                                            Rename
                                                        </a>
                                                        <a href="#" wire:click="$set('movingChild', {{ $child->id }})" @click="isOpen = false" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                                                            Move
                                                        </a>
                                                        <a href="#" wire:click="$set('deletingChild', {{ $child->id }})" @click="isOpen = false" class="block px-4 py-2 text-sm text-pink-500 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                                                            Trash
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    @if($this->directoryTree->children->count() == 0)
                        <div class="w-full flex justify-center items-center px-4 py-2 font-medium rounded-md text-gray-700">
                            This folder is empty
                        </div>
                    @endif
                </div>
            @endif

            @if($this->directoryTree->object_type === 'file')

                <script src="//mozilla.github.io/pdf.js/build/pdf.js"></script>
                <div id="pages" class="divide-y-8 divide-gray-200"></div>

                <script id="script">
                    //
                    // If absolute URL from the remote server is provided, configure the CORS
                    // header on that server.
                    //
                    var url = '/files/{{ $this->directoryTree->object->id }}/download';

                    //
                    // Loaded via <script> tag, create shortcut to access PDF.js exports.
                    //
                    var pdfjsLib = window['pdfjs-dist/build/pdf'];

                    //
                    // The workerSrc property shall be specified.
                    //
                    pdfjsLib.GlobalWorkerOptions.workerSrc = '//mozilla.github.io/pdf.js/build/pdf.worker.js';

                    var currPage = 1; //Pages are 1-based not 0-based
                    var numPages = 0;
                    var pdf = null;

                    //
                    // Asynchronous download PDF
                    //
                    var loadingTask = pdfjsLib.getDocument(url);
                    loadingTask.promise.then(function(doc) {
                        pdf = doc;
                        numPages = doc.numPages;
                        doc.getPage(1).then( handlePages );
                    });

                    function handlePages(page) {
                        var scale = 1.5;
                        var viewport = page.getViewport({ scale: scale, });

                        //
                        // Prepare canvas using PDF page dimensions
                        //
                        var canvas = document.createElement( 'canvas' );
                        canvas.classList.add('w-full');
                        // canvas.classList.add('border-b-4');
                        // canvas.classList.add('border-grey-500');
                        var context = canvas.getContext('2d');
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;

                        //
                        // Render PDF page into canvas context
                        //
                        var renderContext = {
                            canvasContext: context,
                            viewport: viewport,
                        };
                        page.render(renderContext);

                        panel = document.getElementById('pages')
                        panel.appendChild( canvas );

                        currPage++;
                        if ( pdf !== null && currPage <= numPages )
                        {
                            pdf.getPage( currPage ).then( handlePages );
                        }
                    }
                </script>

            @endif

            <x-jet-dialog-modal maxWidth="md" wire:model="movingChild">
                <x-slot name="title">
                    Move {{ $movingChildState['object_type'] }} {{ $movingChildState['name'] }}
                </x-slot>

                <x-slot name="content">

                    <div class="max-h-full overflow-y-scroll">
                    <nav class="" aria-label="Sidebar">
                        @foreach($this->folders as $folder)
                            <a href="#" wire:key="{{ $folder->path }}"
                                wire:click="$set('movingChildState.parent_id', {{ $folder->id }})"
                                style="margin-left: {{ $folder->depth * 2 }}rem"
                                @if($movingChildState['parent_id'] === $folder->id)
                                    class="bg-indigo-200 text-gray-900 group flex items-center px-3 py-1.5 text-sm font-medium rounded-md"
                                @else
                                    class="text-gray-600 hover:bg-indigo-50 hover:text-gray-900 group flex items-center px-3 py-1.5 text-sm font-medium rounded-md"
                                @endif
                            >
                                <svg
                                    @if($movingChildState['parent_id'] === $folder->id)
                                        class="text-gray-500 flex-shrink-0 -ml-1 mr-2 h-6 w-6"
                                    @else
                                        class="text-gray-400 group-hover:text-gray-500 flex-shrink-0 -ml-1 mr-2 h-6 w-6"
                                    @endif
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" aria-hidden="true"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                </svg>
                                <span class="truncate">
                                   {{ $folder->object->name }}
                                </span>
                            </a>
                        @endforeach
                    </nav>
                    </div>

                    <x-jet-input-error for="movingChildState.parent_id" />
                </x-slot>

                <x-slot name="footer">
                    <x-jet-secondary-button wire:click="$set('movingChild', null)" wire:loading.attr="disabled">
                        Nevermind
                    </x-jet-secondary-button>

                    <x-jet-button class="ml-2" wire:click="moveChild" wire:loading.attr="disabled">
                        Move
                    </x-jet-button>
                </x-slot>
            </x-jet-dialog-modal>

            <x-jet-confirmation-modal wire:model="deletingChild">
                <x-slot name="title">
                    Delete {{ $deletingChildState['object_type'] }}
                </x-slot>

                <x-slot name="content">
                    <p class="mb-3">
                        Are you sure you want to delete the {{ $deletingChildState['object_type'] }} named "{{ $deletingChildState['name'] }}"?
                    </p>

                    <x-jet-input-error for="deletingChildState" />
                </x-slot>

                <x-slot name="footer">
                    <x-jet-secondary-button wire:click="$set('deletingChild', null)" wire:loading.attr="disabled">
                        Nevermind
                    </x-jet-secondary-button>

                    <x-jet-danger-button class="ml-2" wire:click="deleteChild" wire:loading.attr="disabled">
                        Delete
                    </x-jet-danger-button>
                </x-slot>
            </x-jet-confirmation-modal>

        </div>
    </div>
</div>
