<div class="gap-[10px] pt-[10px]" style="display:flex;flex-wrap : wrap; ">
    @if ($annabaFormList[$form]['info']['wizardCurrent'] > 1)
      <div>
        <button wire:click="prexiousStep('{{$form}}')"
        class="bg-black p-[11px] text-white rounded-[4px]">Precedent</button>
      </div>
    @endif

    @if ($annabaFormList[$form]['info']['wizardCurrent'] < 
         $annabaFormList[$form]['info']['wizard']['wizardCount'] )
       <div>
          <button 
     @click="$wire.annabaFormList['{{ $form }}'].info.wizardAction = 'suivant'"
           wire:click="$wire.{{ $form }}()"
          class="bg-black p-[11px] text-white rounded-[4px]">Suivant</button> 
       </div> 
    @endif

    
    @if ($annabaFormList[$form]['info']['wizardCurrent'] < 
         $annabaFormList[$form]['info']['wizard']['wizardCount'])
       @if(in_array($annabaFormList[$form]['info']['wizardCurrent'],
       $annabaFormList[$form]['info']['wizard']['wizardStop']))
       <div>
         <button 
    @click="$wire.annabaFormList['{{ $form }}'].info.wizardAction = 'valider'"
          wire:click="$wire.{{ $form }}()"
          class="bg-[blue] min-w-[80px] text-white p-[11px] rounded-[4px]">
           {{$annabaFormList[$form]['info']['createLabel']}}  
         </button>
        </div>
       @endif
    @endif
    
    
       @if ($annabaFormList[$form]['info']['wizardCurrent'] == 
        $annabaFormList[$form]['info']['wizard']['wizardCount'])
       <div>
       <button 
    @click="$wire.annabaFormList['{{ $form }}'].info.wizardAction = 'valider'"
       wire:click="$wire.{{ $form }}()"
        class="bg-[blue] min-w-[80px] text-white p-[11px] rounded-[4px]">
           {{$annabaFormList[$form]['info']['createLabel']}} 
        </button>
      </div>
       @endif

        
    
</div>