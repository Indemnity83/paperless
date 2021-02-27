<!-- Current: "bg-chocolate-800 text-white", Default: "text-chocolate-100 hover:text-white hover:bg-chocolate-600" -->
<a href="{{ $href ?? '#' }}" class="group flex items-center px-2 py-2 text-sm leading-6 font-medium rounded-md text-chocolate-100 hover:text-white hover:bg-chocolate-600">
    <div class="mr-4 h-6 w-6 text-chocolate-200">
        {!! file_get_contents(resource_path('icons/'.$icon.'.svg')) !!}
    </div>
    {{ $slot }}
</a>
