<div class="h-[50px] flex">
  
   <div class="h-[50px] w-[30px]" 
                 x-data="{ openAction : false}" 
                 @click.outside="openAction = false">
     <div class="h-[50px] p-[10px] w-[30px] cursor-pointer"
            @click="openAction = !openAction">
      @include('adminannaba::composants.svg5')
     </div>
     <div class="p-[10px] w-[140px] bg-[white] relative z-2
                         border-[1px] border-gray-400 rounded"
          x-show="openAction && $wire.tabIds.length > 0">
           @foreach($bulks  as $key => $bulk)
           <div class="{{$bulk['class']}}"> 
            <div class="flex hover:underline cursor-pointer"
            @click="if (confirm('{{$bulk['confirmation']}}')) { $wire.{{ $key }}() }">
                <div class="pt-[2px] pr-[3px]">
                  <span class="material-icons text-[16px]">
                     {{ $bulk['icon'] }}
                  </span>
                </div>
                <div>
                  {{ $bulk['label'] }}
                </div>
             </div>
            </div> 
           @endforeach
     </div>

   </div>

   <div class="h-[50px] pt-[10px] pr-[10px]">
      <template x-if="$wire.tabIds.length != 0">
        <span x-text="$wire.tabIds.length"></span>
      </template>
       <template x-if="$wire.tabIds.length != 0">
        <span>Choisis</span>
     </template> 

   </div>

   <div class="h-[50px] pt-[16px]">

      <template x-if="$wire.tabIds.length != 0">
        <span @click="$wire.tabIds = []" class="cursor-pointer">
         @include('adminannaba::composants.svg3')
       </span>
     </template>
   </div>

</div>