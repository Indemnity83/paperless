<div>

    @if($this->object->item_type === 'file')
        <livewire:browse.file :object="$this->object" />
    @else
        <livewire:browse.folder :object="$this->object" />
    @endif

</div>
