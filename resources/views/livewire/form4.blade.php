<div>
    
        <div>
            @include('adminannaba::composants.wizard.wizardStep2',
                     ['form' => 'update1' ])
        </div>
     
        <div class="p-[10px]">
           <div>
             @if($annabaFormList['update1']['info']['wizardCurrent'] == 1)
               @include('adminannaba::fields.inputText',
                     ['form' => 'update1' , 'field' => 'name'] )
             @endif

             @if($annabaFormList['update1']['info']['wizardCurrent'] == 2)
               @include('adminannaba::fields.inputText',
                     ['form' => 'update1' , 'field' => 'city'] )
             @endif 
            
           </div>

           @include('adminannaba::composants.forms.btnWizardUpdate',
                  ['form' => 'update1'])
         </div>
        



</div>