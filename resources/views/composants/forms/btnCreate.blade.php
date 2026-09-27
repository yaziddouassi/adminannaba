<div class="pt-[10px]">
    <button type="button"
    class="bg-[blue] text-white p-[11px] rounded-[6px] min-w-[100px]"
    wire:click="$wire.{{ $form }}()">
     {{$annabaFormList[$form]['info']['createLabel']}}
    </button>
 </div>