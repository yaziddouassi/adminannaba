<div class="">
    <button type="button"
        class="{{ $class }} bg-[blue] text-white flex items-center gap-1"
        wire:click="openModal2('{{ $form }}',{{$record}})">
        <span class="material-icons text-[16px]">
            {{$icon}}
        </span>
        {{ $label }}
    </button>
</div>