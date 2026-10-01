<div class="">
    <button type="button"
        class="bg-[red] text-white p-[13px] rounded-[6px] 
         text-white flex items-center gap-1"
        wire:click="deleteById('{{$ide}}')"
        wire:confirm="'{{$message}}'">
        <span class="material-icons text-[16px]">
            edit
        </span>
    </button>
</div>