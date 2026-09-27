<div class="fixed inset-0 z-50 flex items-start justify-center bg-black/50 overflow-y-auto pt-[20px] pb-[20px]"
x-show="$wire.showModal" id="robert">
   @if($currentFormOpen != '')
     <div class=" w-full max-w-[600px]" @click.outside="$wire.showModal = false">
      
      @if($annabaFormList[$currentFormOpen]['info']['wizardActive'] == 'yes')
         @include('adminannaba::composants.wizard.wizardStep')
      @endif

    @if($annabaFormList[$currentFormOpen]['info']['wizardActive'] == 'no')
      <div class="w-full h-[10vh]">
       </div>
        @endif
         <div class="w-full max-w-[600px] max-h-[75vh] overflow-y-auto bg-white rounded-lg shadow-lg p-6">
        <!-- Contenu du modal -->
       <div class="w-full">
        
        @include('adminannaba::composants.formConteneur')

        @include('adminannaba::composants.btnConteneur')

        

     </div>
    
  </div>
 </div> 
 @endif
</div>