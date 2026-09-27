<div class="min-h-[100vh] bg-black min-w-[220px] text-white
            max-[799px]:hidden">
        <div class="h-[68px] w-full p-[10px] py-[10px]">

          <div class="bg-[#000088] h-[48px] min-w-[180px] m-auto text-center
                     text-white text-[24px] font-bold border-white border-[1px]
                     rounded-[4px] pt-[3px] cursor-pointer">
              <span class="">
               Admin
              </span>
               
          </div>
        </div>

       <div class="h-[74px] bg-[#444] text-center text-[22px] pt-[16px] cursor-pointer">
          <span class=""
             wire:click="logout"
             wire:confirm="Voulez-vous vraiment vous déconnecter ?">
             Se Deconnecter 
          </span>
         
       </div>
</div>