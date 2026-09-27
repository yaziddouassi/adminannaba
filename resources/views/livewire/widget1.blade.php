<div class="pb-[15px] pt-[15px]
 bg-[#eee] flex
 items-center justify-center">


 <div>
    <div class="flex justify-center text-[darkblue]">
      <span class="block pr-[3px]">
         {{$title}} 
      </span>
      </span>
        <span class="material-icons block text-[blue]">
           {{$icon}}
         </span>
     </div>

     <div class="text-center font-bold text-[24px]">
     {{$value}}
      </div>

     

     <div class="text-center mt-[5px]">
       <button class="text-black border-[1px] border-[black] rounded-[3px] w-[140px] p-[10px]"
       wire:click="updateValue()">
           Update  </button>
      </div>
   </div>

</div>