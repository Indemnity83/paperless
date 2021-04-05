<x-jet-action-section>
    <x-slot name="title">
        {{ __('Thumbnail Generation') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Manage and rebuild your thumbnails.') }}
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-gray-600">
            {{ __('If necessary, you may re-generate all thumbnails across the application. You may want to do this if the quality settings have changed, or some thumbnails are missing or corrupt. This process will be queued to run the background and can take a long time if you have a large number of files.') }}
        </div>

        <div class="flex items-center mt-5">
            <x-jet-button wire:click="rebuildAllThumbnails" wire:loading.attr="disabled">
                {{ __('Rebuild all thumbnails') }}
            </x-jet-button>

            <x-jet-action-message class="ml-3" on="rebuildStarted">
                {{ __('Done.') }}
            </x-jet-action-message>
        </div>

    </x-slot>
</x-jet-action-section>
