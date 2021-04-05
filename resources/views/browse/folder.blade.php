<div>
    <div class="mb-5 px-3 sm:px-0">
        @if($this->hasQuery())
            <nav class="text-sm font-medium text-gray-500 hover:text-gray-700 pt-5 md:pt-0">
                Found {{ $this->results->total() }}
            </nav>
        @else
            <livewire:browse.breadcrumbs :object="$object" />
        @endif
    </div>

    <div class="pb-3 sm:flex sm:items-center sm:justify-between">
        <div class="flex justify-between items-center px-3 sm:px-0">
            <x-search wire:model="query" />

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
                        <x-jet-dropdown-link href="#" wire:click="$set('creatingFolder', true)">New Folder</x-jet-dropdown-link>
                        <x-jet-dropdown-link href="#" wire:click="$set('showUploadModal', true)">
                            <form method="post" action="{{ route('files.store') }}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="parent_id" value="{{ $this->object->id }}" />
                                <label>
                                    Upload File
                                    <input type="file" class="hidden" name="document" accept="application/pdf" onchange="form.submit()"/>
                                </label>
                            </form>
                        </x-jet-dropdown-link>
                    </x-slot>
                </x-jet-dropdown>
            </div>
        </div>

        <div class="hidden sm:inline-block">
            <x-jet-secondary-button wire:click="$set('creatingFolder', true)">New Folder</x-jet-secondary-button>


                <x-jet-secondary-button wire:click="$set('showUploadModal', true)">
                    <form method="post" action="{{ route('files.store') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="parent_id" value="{{ $this->object->id }}" />
                        <label>
                            Upload File
                            <input type="file" class="hidden" name="document" accept="application/pdf" onchange="form.submit()"/>
                        </label>
                    </form>
                </x-jet-secondary-button>

        </div>
    </div>

    <div class="bg-white shadow rounded-lg">
        <ul class="divide-y divide-gray-200">
            @if($creatingFolder)
                <li class="py-2"><livewire:browse.create-folder :parent="$object" /></li>
            @endif

            @foreach($this->results as $result)
                <livewire:browse.folder-child :child="$result" key="object-{{ $result->hash }}" :search-result="$this->hasQuery()"/>
            @endforeach
        </ul>

        <div class="bg-gray-50 px-4 py-4 sm:px-6">
            {{ $this->results->links() }}
        </div>
    </div>

</div>
