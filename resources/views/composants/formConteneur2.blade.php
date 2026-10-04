           
      @if($annabaFormList[$currentFormOpen]['info']['formType'] == 'creator')
           @if($field['type'] == 'Text')
                @include('adminannaba::fields.inputText',
                  ['form' =>  $currentFormOpen, 'field' => $field['field']] )
           @endif
            @if($field['type'] == 'file')
                @include('adminannaba::fields.inputFile',
                  ['form' =>  $currentFormOpen, 'field' => $field['field']] )
           @endif

            @if($field['type'] == 'SelectCollection')
                @include('adminannaba::fields.inputSelectCollection',
                  ['form' =>  $currentFormOpen, 'field' => $field['field']] )
           @endif

           @if($field['type'] == 'Quill')
                @include('adminannaba::fields.quill',
                  ['form' =>  $currentFormOpen, 'field' => $field['field']] )
           @endif

           @if($field['type'] == 'Checkbox')
                @include('adminannaba::fields.inputCheckbox',
                  ['form' =>  $currentFormOpen, 'field' => $field['field']] )
           @endif

           @if($field['type'] == 'Select')
                @include('adminannaba::fields.inputSelect',
                  ['form' =>  $currentFormOpen, 'field' => $field['field']] )
           @endif

           @if($field['type'] == 'Radio')
                @include('adminannaba::fields.inputRadio',
                  ['form' =>  $currentFormOpen, 'field' => $field['field']] )
           @endif

           @if($field['type'] == 'CheckboxList')
                @include('adminannaba::fields.multipleCheckbox',
                  ['form' =>  $currentFormOpen, 'field' => $field['field']] )
           @endif

          @if($field['type'] == 'Password')
                @include('adminannaba::fields.inputPassword',
                  ['form' =>  $currentFormOpen, 'field' => $field['field']] )
          @endif


      @endif 

