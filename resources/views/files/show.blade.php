<x-layout>

    <div class="bg-white shadow">
        <div class="px-4 sm:px-6 lg:max-w-7xl lg:mx-auto lg:px-8">
            <div class="py-6 md:flex md:items-center md:justify-between lg:border-t lg:border-gray-200">
                <div class="flex-1 min-w-0">
                    <!-- Profile -->
                    <div class="flex items-center">
                        <div>
                            <div class="flex items-center">
                                <h1 class="ml-3 text-2xl font-bold leading-7 text-gray-900 sm:leading-9 sm:truncate">
                                    {{ $file->name }}
                                </h1>
                            </div>
                            <dl class="mt-6 flex flex-col sm:ml-3 sm:mt-1 sm:flex-row sm:flex-wrap">
                                @if($file->generated_at)
                                    <x-header-attribute attribute="Generated" icon="solid/calendar">
                                        <time datetime="{{ $file->generated_at->toW3cString() }}">Created {{ $file->generated_at->diffForHumans() }}</time>
                                    </x-header-attribute>
                                @else
                                    <x-header-attribute attribute="Imported" icon="solid/calendar">
                                        <time datetime="{{ $file->created_at->toW3cString() }}">Imported {{ $file->created_at->diffForHumans() }}</time>
                                    </x-header-attribute>
                                @endif

                                <x-header-attribute attribute="File Size" icon="solid/archive">
                                    {{ $file->size }}
                                </x-header-attribute>

                                <x-header-attribute attribute="Pages" icon="solid/document-duplicate">
                                    {{ $file->meta('Pages') }} {{ Str::plural('page', $file->meta('Pages')) }}
                                </x-header-attribute>


                            </dl>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex space-x-3 md:mt-0 md:ml-4">
                    <form class="hidden lg:block" method="post" name="destroy" action="{{ route('files.destroy', $file) }}">
                        @csrf
                        @method('delete')
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-chocolate-500">
                            Move to Trash
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="overflow-y-scroll h-full">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full">
            <object id="doc" class="w-full h-full shadow-2xl" type="application/pdf" data="/files/{{ $file->id }}/download"></object>
        </div>
    </div>

</x-layout>
