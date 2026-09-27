<div >
    <button type="button"
        class="bg-[blue] text-white  gap-1
         p-[9px] rounded-[4px]"
        wire:click="openModal1('{{ $form }}')">
        <span>
           +
        </span>
        {{ $label }}
    </button>
</div>