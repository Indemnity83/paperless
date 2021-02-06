<x-app-layout>
    <div class="py-4 xl:py-6">
        <div class="mx-auto px-4 sm:px-6 lg:px-8">

            <div class="pb-5 sm:flex sm:items-center sm:justify-between">
                <h3 class="text-lg leading-6 font-bold text-gray-900">
                    Search results for {{ request()->get('q') }}
                </h3>
            </div>

            <!-- This example requires Tailwind CSS v2.0+ -->
            <ul class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                @foreach($documents as $document)

                    <li class="col-span-1 flex flex-col text-center bg-white rounded-lg shadow divide-y divide-gray-200">
                        <a href="{{ route('documents.show', $document->id) }}">
                            <div class="flex-1 flex flex-col p-8">
                                <img class="w-full h-32 flex-shrink-0 mx-auto bg-black object-top object-cover" src="{{ \Storage::disk('media')->url($document->thumbnail) }}" alt="">
                                <h3 class="mt-6 text-gray-900 text-sm font-medium">{{ $document->attachment_name }}</h3>
                                <dl class="mt-1 flex-grow flex flex-col justify-between">
                                    <dt class="sr-only">Size</dt>
                                    <dd class="text-gray-500 text-sm">
                                        {{ \FileHelper::bytesForHumans($document->attachment_size) }}
                                    </dd>
                                    <dt class="sr-only">Tags</dt>
                                    <dd class="mt-3">
                                        <span class="px-2 py-1 text-green-800 text-xs font-medium bg-green-100 rounded-full">Admin</span>
                                    </dd>
                                </dl>
                            </div>
                        </a>
                    </li>

                @endforeach
            </ul>
        </div>
    </div>
</x-app-layout>
