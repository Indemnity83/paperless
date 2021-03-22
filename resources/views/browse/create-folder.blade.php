<div class="flex items-center space-x-4 px-4">
    <form class="flex items-center flex-grow space-x-2">
        <x-jet-input wire:model="name" type="text" class="w-full" autofocus></x-jet-input>
        <x-jet-button wire:click.prevent="createFolder" type="submit">
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </x-jet-button>
        <x-jet-secondary-button wire:click="$emitUp('closeNewFolderDrawer')">
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </x-jet-secondary-button>
    </form>
</div>
