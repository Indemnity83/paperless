<div>
    <div class="mb-5 px-3 sm:px-0">
        <livewire:browse.breadcrumbs :object="$object" />
    </div>

    <div class="pb-3 sm:flex sm:items-center sm:justify-between">
        <div class="flex justify-between items-center px-3 sm:px-0">
            <div></div>

            <div class="inline-block sm:hidden">
                <x-jet-dropdown>
                    <x-slot name="trigger">
                        <x-jet-secondary-button>
                            <span class="sr-only">New</span>
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </x-jet-secondary-button>
                    </x-slot>
                    <x-slot name="content">
                        <x-jet-dropdown-link href="#" wire:click="$set('showMoveModal', true)">Move File</x-jet-dropdown-link>
                        <x-jet-dropdown-link href="#" wire:click="$set('showDeleteModal', true)">Delete File</x-jet-dropdown-link>
                    </x-slot>
                </x-jet-dropdown>
            </div>
        </div>

        <div class="hidden sm:inline-block">
            <x-jet-secondary-button wire:click="$set('showMoveModal', true)">Move</x-jet-secondary-button>
            <x-jet-secondary-button wire:click="$set('showDeleteModal', true)">Delete</x-jet-secondary-button>
        </div>
    </div>

    <script src="//mozilla.github.io/pdf.js/build/pdf.js"></script>
    <div id="pdfDocument" class="divide-y-8 divide-gray-200" wire:ignore></div>

    <script id="script">
        //
        // If absolute URL from the remote server is provided, configure the CORS
        // header on that server.
        //
        var url = '/files/{{ $object->item->id }}/download';

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

            panel = document.getElementById('pdfDocument')
            panel.appendChild( canvas );

            currPage++;
            if ( pdf !== null && currPage <= numPages )
            {
                pdf.getPage( currPage ).then( handlePages );
            }
        }
    </script>

    <x-jet-dialog-modal wire:model="showMoveModal">
        <x-slot name="title">
            Move file
        </x-slot>

        <x-slot name="content">
            <nav class="" aria-label="Sidebar">
                @foreach($this->folders as $folder)
                    <a href="#" wire:key="folder.{{ $folder['id'] }}" wire:click="$set('movingState.parent_id', {{ $folder['id'] }})" style="margin-left: {{ $folder['depth'] * 2 }}rem" class="text-gray-600 hover:bg-indigo-50 hover:text-gray-900 group flex items-center px-3 py-1.5 text-sm font-medium rounded-md {{ $movingState['parent_id'] === $folder['id'] ? 'bg-gray-100 text-gray-900' : '' }}">
                        <svg class="text-gray-400 group-hover:text-gray-500 flex-shrink-0 -ml-1 mr-2 h-6 w-6 {{ $movingState['parent_id'] === $folder['id'] ?'text-gray-500' : '' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                        </svg>
                        <span class="truncate">{{ $folder['name'] }}</span>
                    </a>
                @endforeach
            </nav>

            <x-jet-input-error for="movingState.parent_id" />
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$set('showMoveModal', false)" wire:loading.attr="disabled">
                Nevermind
            </x-jet-secondary-button>

            <x-jet-button class="ml-2" wire:click="move" wire:loading.attr="disabled">
                Move
            </x-jet-button>
        </x-slot>
    </x-jet-dialog-modal>

    <x-jet-confirmation-modal wire:model="showDeleteModal">
        <x-slot name="title">
            Delete file
        </x-slot>

        <x-slot name="content">
            <p class="mb-3">
                Are you sure you want to delete the file named "{{ $object->item->name }}"?
            </p>

            <x-jet-input-error for="deleteModal" />
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$set('showDeleteModal', false)" wire:loading.attr="disabled">
                Nevermind
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="delete" wire:loading.attr="disabled">
                Delete
            </x-jet-danger-button>
        </x-slot>
    </x-jet-confirmation-modal>
</div>
