<dt class="sr-only">{{ $attribute ?? '' }}</dt>
<dd class="flex items-center text-sm text-gray-500 font-medium capitalize sm:mr-6">
    @if(isset($icon))
    <div class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400">
        {!! file_get_contents(resource_path('icons/'.$icon.'.svg')) !!}
    </div>
    @endif
    {{ $slot }}
</dd>
