<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-8">
                <dl class="grid grid-cols-3 gap-2 sm:gap-5 ">
                    <div class="px-4 py-5 bg-white shadow rounded-lg overflow-hidden sm:p-6">
                        <dt class="text-sm font-medium text-gray-500 truncate">
                            Total Files
                        </dt>
                        <dd class="mt-1 text-lg sm:text-3xl font-semibold text-gray-900">
                            {{ number_format(\App\Models\File::count()) }}
                        </dd>
                    </div>

                    <div class="px-4 py-5 bg-white shadow rounded-lg overflow-hidden sm:p-6">
                        <dt class="text-sm font-medium text-gray-500 truncate">
                            Disk Usage
                        </dt>
                        <dd class="mt-1 text-lg sm:text-3xl font-semibold text-gray-900">
                            {{ Str::bytesForHumans(\App\Models\File::sum('bytes')) }}
                        </dd>
                    </div>

                    <div class="px-4 py-5 bg-white shadow rounded-lg overflow-hidden sm:p-6">
                        <dt class="text-sm font-medium text-gray-500 truncate">
                            Pages
                        </dt>
                        <dd class="mt-1 text-lg sm:text-3xl font-semibold text-gray-900">
                            {{ number_format(\App\Models\File::sum('pages')) }}
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- This example requires Tailwind CSS v2.0+ -->
            <div class="pb-5 border-b border-gray-200 mb-8">
                <h3 class="ml-3 sm:ml-0 text-lg leading-6 font-medium text-gray-900">
                    Recent Files
                </h3>
            </div>

            <!-- This example requires Tailwind CSS v2.0+ -->
            <ul class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 mb-8">
                @foreach(\App\Models\Obj::with('item')->where('item_type', 'file')->orderBy('created_at', 'DESC')->take(8)->get() as $object)
                <li class="col-span-1 flex flex-col text-center bg-white rounded-lg shadow divide-y divide-gray-200">
                    <a href="{{ route('browse', ['o' => $object->hash]) }}" class="group">
                        <div class="border-b border-gray-200">
                            <img class="object-cover object-top w-full h-48" src="/files/{{ $object->item->id }}/thumbnail" alt="">
                        </div>
                        <div class="flex-1 flex flex-col p-8">
                            <h3 class="text-gray-900 text-sm font-medium truncate">
                                {{ $object->item->name }}
                            </h3>
                            <ul class="flex justify-center space-x-2 text-sm text-gray-500 truncate">
                                <li>
                                    {{ Str::bytesForHumans($object->item->bytes) }}
                                </li>
                                <li class="text-gray-300">&bull;</li>
                                <li>
                                    {{ Str::relativePrecisionDate($object->item->created_at) }}
                                </li>
                            </ul>
                        </div>
                    </a>
                </li>
                @endforeach

            </ul>


        </div>
    </div>
</x-app-layout>
