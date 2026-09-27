 <div class="min-w-[180px] max-w-[180px] border-[1px] border-gray-300 rounded-[22px] h-[44px] p-[10px] flex">
             <div class="min-w-[140px] max-w-[140px]">
               <input type=""
                wire:model.live.debounce.500ms="search"
                class="w-[140px] border-none outline-none focus:outline-none"
                placeholder="Recherche..."  />
             </div> 
             <div class="p-[5px] pt-[3px]">
               <span @click="$wire.set('search', '', true)"
                 class="cursor-pointer">
                  @include('adminannaba::composants.svg3')
               </span>
             </div>
  </div>