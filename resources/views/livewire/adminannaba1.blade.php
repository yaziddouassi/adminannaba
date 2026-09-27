<div class="flex w-full">


    @livewire('adminannaba.sidebar')
    

    <div class=" bg-[#DDE1E6] w-full min-h-[100vh]">
      

        @livewire('adminannaba.navbar')


        <div 
         class="grid max-[600px]:grid-cols-1
              max-[1000px]:grid-cols-2 grid-cols-3 p-[10px] pb-[0px] gap-[10px]">

                 @livewire('adminannaba.widget1') 

                 @livewire('adminannaba.widget1') 

                 @livewire('adminannaba.widget1')  
        </div> 

             


        <div 
         class="grid max-[600px]:grid-cols-1
              max-[1000px]:grid-cols-2 grid-cols-3 p-[10px] pb-[0px] gap-[10px]">

                 @livewire('adminannaba.chart1') 

                 @livewire('adminannaba.chart1')

                 @livewire('adminannaba.chart1')          
        </div>

         <div 
         class="grid max-[600px]:grid-cols-1
              max-[1000px]:grid-cols-2 grid-cols-3 p-[10px] pb-[10px] gap-[10px]">

                 @livewire('adminannaba.widget1') 

                 @livewire('adminannaba.widget1') 

                 @livewire('adminannaba.widget1')  
        </div>
       
         

    </div>



</div>