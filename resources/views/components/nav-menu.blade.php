<nav class="mt-5 flex-1 flex flex-col divide-y divide-chocolate-800 overflow-y-auto" aria-label="Sidebar">
    <div class="px-2 space-y-1">
        <x-nav-item href="/" icon="outline/home">Home</x-nav-item>
        <x-nav-item href="{{ route('files.index') }}" icon="outline/document-duplicate">All Files</x-nav-item>
        <x-nav-item href="{{ route('files.index', ['sort' => '-updated']) }}" icon="outline/clock">Recent</x-nav-item>
        <x-nav-item href="{{ route('files.index', ['filter[trashed]' => 'only']) }}" icon="outline/trash">Trash</x-nav-item>
    </div>
    <div class="mt-6 pt-6">
        <div class="px-2 space-y-1">
            <x-nav-sub-item href="#" icon="outline/cog">Settings</x-nav-sub-item>
            <x-nav-sub-item href="#" icon="outline/question-mark-circle">Help</x-nav-sub-item>
            <x-nav-sub-item href="#" icon="outline/shield-check">Privacy</x-nav-sub-item>
        </div>
    </div>
</nav>
