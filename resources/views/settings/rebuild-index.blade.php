<x-jet-action-section>
    <x-slot name="title">
        {{ __('Search Index') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Manage and rebuild your searchable document index.') }}
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-gray-600">
            {{ __('If necessary, you may reindex all the files on the system. You may want to do this if the index is lost or corrupted. This process will be queued to run the background and can take a long time if you have a large number of files.') }}
        </div>

        <div class="flex items-center mt-5">
            <x-jet-button wire:click="rebuildSearchIndex" wire:loading.attr="disabled">
                {{ __('Rebuild search index') }}
            </x-jet-button>

            <x-jet-action-message class="ml-3" on="reindexStarted">
                {{ __('Done.') }}
            </x-jet-action-message>
        </div>

    </x-slot>
</x-jet-action-section>
