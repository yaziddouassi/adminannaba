<div class="gap-[10px] pt-[10px]" style="display:flex;flex-wrap : wrap; ">
    @if ($annabaFormList[$currentFormOpen]['info']['wizardCurrent'] > 1)
      <div>
        <button wire:click="prexiousStep('{{$currentFormOpen}}')"
        class="bg-black p-[11px] text-white rounded-[4px]">Precedent</button>
      </div>
    @endif

    @if ($annabaFormList[$currentFormOpen]['info']['wizardCurrent'] < 
         $annabaFormList[$currentFormOpen]['info']['wizard']['wizardCount'] )
       <div>
          <button 
     @click="$wire.annabaFormList['{{ $currentFormOpen }}'].info.wizardAction = 'suivant'"
           wire:click="$wire.{{ $currentFormOpen }}()"
          class="bg-black p-[11px] text-white rounded-[4px]">Suivant</button> 
       </div> 
    @endif

    
    @if ($annabaFormList[$currentFormOpen]['info']['wizardCurrent'] < 
         $annabaFormList[$currentFormOpen]['info']['wizard']['wizardCount'])
       @if(in_array($annabaFormList[$currentFormOpen]['info']['wizardCurrent'],
       $annabaFormList[$currentFormOpen]['info']['wizard']['wizardStop']))
       <div>
         <button 
    @click="$wire.annabaFormList['{{ $currentFormOpen }}'].info.wizardAction = 'valider'"
          wire:click="$wire.{{ $currentFormOpen }}()"
          class="bg-[blue] min-w-[80px] text-white p-[11px] rounded-[4px]">
           {{$annabaFormList[$currentFormOpen]['info']['createLabel']}}  
         </button>
        </div>
       @endif
    @endif
    
    
       @if ($annabaFormList[$currentFormOpen]['info']['wizardCurrent'] == 
        $annabaFormList[$currentFormOpen]['info']['wizard']['wizardCount'])
       <div>
       <button 
    @click="$wire.annabaFormList['{{ $currentFormOpen }}'].info.wizardAction = 'valider'"
       wire:click="$wire.{{ $currentFormOpen }}()"
        class="bg-[blue] min-w-[80px] text-white p-[11px] rounded-[4px]">
           {{$annabaFormList[$currentFormOpen]['info']['createLabel']}} 
        </button>
      </div>
       @endif

        @if($annabaFormList[$currentFormOpen]['info']['btnFermer'] == 'yes') 
        <div>
         <button type="button"
           class="border-[#aaa] border-[1px] p-[11px] rounded-[6px] min-w-[100px]"
          @click="$wire.showModal=false">
           Fermer</button>
        </div> 
        @endif
    
</div>