<div x-data="{open : false ,
             putValue(a) {
              $wire.annabaFormList.{{$form}}.fields.{{$field}}.value = a ;
             } 
               }">
   <div class="w-full mb-[5px]">
      <span class="text-black font-bold">
        {{$annabaFormList[$form]['fields'][$field]['options']['label']}}</span>
   </div>
  

   <div class="h-[50px]" @click="open = true" 
        @click.outside="open = false">
     <div class=" h-[50px] border-gray-800 border-[1px] bg-white flex rounded-[4px]">
             <div class="w-full p-[5px] pt-[13px]">
               <input type=""
       wire:model.live.debounce.500ms="annabaFormList.{{$form}}.fields.{{$field}}.options.querySearch"          
                class="w-full border-none outline-none focus:outline-none"
                placeholder="Recherche..."  />
             </div> 
             <div class="p-[5px] pt-[18px]">
               <span 
                 class="cursor-pointer"
                 wire:click="resetSearchCollection('{{$form}}','{{$field}}')">
                  @include('adminannaba::composants.svg3')
               </span>
             </div>
     </div>


    <div class="bg-white border-gray-800 border-[1px] p-[10px] relative z-2"
     x-show="open">
         

       <div class="flex"
        x-show="$wire.annabaFormList.{{$form}}.fields.{{$field}}.value != ''">
         <div class="border-gray-800 border-[1px] px-[7px] py-[2px]
          rounded-[2px] flex gap-[5px]">
            <div><span x-text="$wire.annabaFormList.{{$form}}.fields.{{$field}}.value"></span></div> 
            <div class="pt-[5px] cursor-pointer"
              @click="putValue('')">
               @include('adminannaba::composants.svg6')
            </div>
           
         </div>
       </div>
     
       @php
          $lecontenu = $annabaFormList[$form]['fields'][$field]['options']['colonneContent'];
          $lelabel = $annabaFormList[$form]['fields'][$field]['options']['colonneLabel'];
       @endphp

       <div>
          @foreach($selectedRecords[$form][$field] as $key => $record) 
           <div>
            <span class="cursor-pointer" @click="putValue('{{$record[$lecontenu]}}')"
            > {{$record[$lecontenu]}} - {{$record[$lelabel]}} </span>
          </div>
        @endforeach
       </div>

        <div class="mt-[10px]">
         {{ $selectedRecords[$form][$field]->links() }}
        </div>

    </div>


   </div>


      <div class="flex mt-[5px]"
        x-show="$wire.annabaFormList.{{$form}}.fields.{{$field}}.value != ''">
         <div class="border-gray-800 border-[1px] px-[7px] py-[2px]
          rounded-[2px] flex gap-[5px] bg-white">
            <div><span x-text="$wire.annabaFormList.{{$form}}.fields.{{$field}}.value"></span></div> 
            <div class="pt-[5px] cursor-pointer"
              @click="putValue('')">
               @include('adminannaba::composants.svg6')
            </div>
           
         </div>
       </div>


   @error("annabaFormList.$form.fields.$field.value")
   <div class="text-[red] pt-[5px]">
        <span class="error">{{ $message }}</span> 
   </div>
   @enderror

</div>