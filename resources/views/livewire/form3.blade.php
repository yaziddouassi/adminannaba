<div>
    
        <div>
            @include('adminannaba::composants.wizard.wizardStep2',
                     ['form' => 'create' ])
        </div>
     
        <div class="p-[10px]">
           <div>
             @if($annabaFormList['create']['info']['wizardCurrent'] == 1)
               @include('adminannaba::fields.inputText',
                     ['form' => 'create' , 'field' => 'name'] )
             @endif

             @if($annabaFormList['create']['info']['wizardCurrent'] == 2)
               @include('adminannaba::fields.inputText',
                     ['form' => 'create' , 'field' => 'city'] )
             @endif 
            
           </div>

           @include('adminannaba::composants.forms.btnWizard',
                  ['form' => 'create'])
         </div>
        



</div>