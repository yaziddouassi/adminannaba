 <div class="pt-[10px]">
    <button type="button"
    class="bg-[blue] text-white p-[11px] rounded-[6px] min-w-[100px]"
    wire:click="$wire.{{ $currentFormOpen }}()">
     {{$annabaFormList[$currentFormOpen]['info']['createLabel']}}
    </button>

     @if($annabaFormList[$currentFormOpen]['info']['btnFermer'] == 'yes') 
     <button type="button"
    class="border-[#aaa] border-[1px] p-[11px] rounded-[6px] min-w-[100px]"
    @click="$wire.showModal=false">
     Fermer</button> 
   @endif
 </div>