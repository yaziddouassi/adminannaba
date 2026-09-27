<div class="min-[800px]:hidden w-full"
     x-data="{open1: false, open2: false}">
        
       <div class="bg-black h-[60px] w-full">
           <div class="bg-black h-[60px] w-full flex text-white fixed">
              <div class="w-[80px] h-[60px] pt-[9px] pl-[5px]">
                 <span class="material-icons text-[40px] cursor-pointer"
                   @click="open2=true">
                   menu
                 </span>
              </div>
              <div class="w-full h-[60px] text-center text-[22px]
                         pt-[10px]"> 
                  Admin
              </div>
              
              <div class="w-[60px] h-[60px] pt-[9px] pl-[5px]">
                 <span class="material-icons text-[40px] cursor-pointer"
                 @click="open1=true">
                   person
                 </span>
              </div>
           </div>
       </div>



       <div class="fixed inset-0 z-50 flex items-start justify-center bg-black overflow-y-auto
                  p-[20px] pt-[20px] pb-[20px] text-white" x-show="open1" >

              <div class="w-full">
                 <div>
                    <span class="material-icons text-[40px] cursor-pointer"
                     @click="open1=false">
                        arrow_back
                    </span>
                 </div>
                 <div class="h-[74px] bg-[#444] text-center text-[22px] pt-[16px] cursor-pointer">
                  <span class=""
                      wire:click="logout"
                      wire:confirm="Voulez-vous vraiment vous déconnecter ?">
                       Se Deconnecter 
                  </span>
                </div>
              </div>

              

       </div>









      <div class="fixed inset-0 z-50 flex items-start justify-center bg-black overflow-y-auto           pt-[20px] pb-[20px] text-white"  x-show="open2">

             <div class="w-full">
                 <div>
                    <span class="material-icons text-[40px] cursor-pointer"
                    @click="open2=false">
                        arrow_back
                    </span>
                 </div>
              </div>

       </div>














</div>