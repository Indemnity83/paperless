<li class="py-2 hover:bg-gray-50">
    <div class="flex items-center space-x-4 px-4">
        @if($showRenameForm)
            <form class="flex items-center flex-grow space-x-2">
                <x-jet-input wire:model="renameState.name" type="text" class="w-full" />
                <x-jet-button wire:click.prevent="rename" type="submit">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </x-jet-button>
                <x-jet-secondary-button wire:click="$set('showRenameForm', false)">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </x-jet-secondary-button>
            </form>
        @else
            <a href="{{ route('browse', ['o' => $child->hash]) }}" class="flex flex-grow items-center space-x-4">
                <div class="flex-shrink-0">
                    @if($child->item_type === 'folder')
                        <svg class="w-8 h-8 text-gray-300"  xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                        </svg>
                    @elseif($child->item_type === 'file' && $child->item->thumbnail !== null)
                        <img class="h-8 w-8" src="/files/{{$child->item->id}}/thumbnail" alt="">
                    @else
                        <svg class="w-8 h-8 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    @endif
                </div>
                <div class="flex-1 min-w-0 overflow-hidden overflow-ellipsis">
                    <span class="text-sm font-medium text-gray-900 truncate">
                        {{ $child->item->name }}
                    </span>

                    <ul class="flex space-x-2 text-sm text-gray-500 truncate">
                        @if($searchResult)
                            <li>{{ $child->ancestors->pluck('item.name')->reverse()->join('/') }}</li>
                            <li class="text-gray-300">&bull;</li>
                        @endif
                        @if($child->item_type == 'file')
                            <li>{{ Str::bytesForHumans($child->item->bytes) }}</li>
                            <li class="text-gray-300">&bull;</li>
                        @endif
                        <li>{{ Str::relativePrecisionDate($child->item->created_at) }}</li>

                    </ul>
                </div>
            </a>

            <!-- Actions -->
            <div class="relative inline-block text-right flex items-center space-x-1" >
                <x-text-button wire:click="$set('showRenameForm', true)" class="hidden md:inline">Rename</x-text-button>
                <x-text-button wire:click="$set('showMoveModal', true)" class="hidden md:inline">Move</x-text-button>
                <x-danger-text-button wire:click="$set('showDeleteModal', true)" class="hidden md:inline">Trash</x-danger-text-button>

                <div class="inline sm:hidden">
                    <x-jet-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <x-text-button>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                                </svg>
                            </x-text-button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="w-48">
                                <x-jet-dropdown-link href="#" wire:click="$set('showRenameForm', true)">Rename</x-jet-dropdown-link>
                                <x-jet-dropdown-link href="#" wire:click="$set('showMoveModal', true)">Move</x-jet-dropdown-link>
                                <x-jet-dropdown-link href="#" wire:click="$set('showDeleteModal', true)">Trash</x-jet-dropdown-link>
                            </div>
                        </x-slot>
                    </x-jet-dropdown>
                </div>
            </div>
        @endif

        <x-jet-dialog-modal wire:model="showMoveModal">
            <x-slot name="title">
                Move {{ $child->item_type }}
            </x-slot>

            <x-slot name="content">
                {{ $movingState['parent_id'] }}
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
                Delete {{ $child->item_type }}
            </x-slot>

            <x-slot name="content">
                <p class="mb-3">
                    Are you sure you want to delete the {{ $child->item_type }} named "{{ $child->item->name }}"?
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
</li>
