    @if($annabaFormList[$currentFormOpen]['info']['formType'] == 'creator')
            @include('adminannaba::composants.btnCreateModal')
        @endif

        @if($annabaFormList[$currentFormOpen]['info']['formType'] == 'updator')
            @include('adminannaba::composants.btnUpdateModal')
        @endif

        @if($annabaFormList[$currentFormOpen]['info']['formType'] == 'wizardCreator')
            @include('adminannaba::composants.btnWizardModal')
        @endif

         @if($annabaFormList[$currentFormOpen]['info']['formType'] == 'wizardUpdator')
            @include('adminannaba::composants.btnWizardUpdateModal')
        @endif