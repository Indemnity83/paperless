<x-app-layout>

    <x-slot name="header">
        <div class="md:flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight my-2 md:my-0">
                    {{ $file->name }}
                </h2>
                <div class="flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6 md:mb-0 mb-4">
                    @if($file->generated_at)
                        <div class="mt-2 flex items-center text-sm text-gray-500">
                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                            </svg>
                            <time datetime="{{ $file->generated_at->toW3cString() }}">Created {{ $file->generated_at->diffForHumans() }}</time>
                        </div>
                    @else
                        <div class="mt-2 flex items-center text-sm text-gray-500">
                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                            </svg>
                            <time datetime="{{ $file->created_at->toW3cString() }}">Imported {{ $file->created_at->diffForHumans() }}</time>
                        </div>
                    @endif
                    <div class="mt-2 flex items-center text-sm text-gray-500">
                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z" />
                            <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd" />
                        </svg>
                        {{ $file->size }}
                    </div>
                    <div class="mt-2 flex items-center text-sm text-gray-500">
                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L14 2.586A2 2 0 0012.586 2H9z" />
                            <path d="M3 8a2 2 0 012-2v10h8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z" />
                        </svg>
                        {{ $file->meta('Pages') }} {{ Str::plural('page', $file->meta('Pages')) }}
                    </div>
                </div>
            </div>
            <div class="md:flex md:space-x-2">
                <form class="my-2 md:my-0" method="post" name="destroy" action="{{ route('files.destroy', $file) }}">
                    @csrf
                    @method('delete')
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-800 hover:text-pink-500 bg-white hover:bg-pink-50 hover:border-pink-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                        Move to Trash
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="p-0 md:py-6 lg:py-8">
        <div class="max-w-7xl mx-auto p-0 md:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                {{-- TODO: replace "object" with something better, I'm going insane trying to get this to be responsive --}}
                <object type="application/pdf" data="/files/{{ $file->id }}/download" class="w-full h-full" ></object>
            </div>
        </div>
    </div>

</x-app-layout>
