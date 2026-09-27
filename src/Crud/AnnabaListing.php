<?php

namespace Annaba\Admin\Crud;

header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination; 
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AnnabaListing extends Component
{

   use WithFileUploads; 
   use WithPagination;

   public $settings = [];
   public $tabIds = [];
   public string $customActionUrlTemoin = '';
   public string $currentFormOpen = '';
   public $showModal = false;
   public array $annabaFormList = [];
   public array $annabaFormListStart = [];
   public array $filters = [];
   public array $filterActifs = [];
   public array $bulks = [];
   public $record ;
   public $urlStorage ;


    public function addForm(array $settings): self
    {
       
        $url = $settings['action'];

        $this->annabaFormList[$url]['info'] = $settings;
        $this->annabaFormList[$url]['info']['ide'] = 0;
        $this->annabaFormList[$url]['info']['formType'] = 'creator';
        $this->annabaFormList[$url]['info']['modalWidth'] = '580px';
        $this->annabaFormList[$url]['info']['grid'] = ['sm' =>  1 , 'md' => 1 , 'lg' => 1 , 'xl' => 1];
        $this->annabaFormList[$url]['info']['wizardActive'] = 'no';
        $this->annabaFormList[$url]['info']['wizard'] = [];
        $this->annabaFormList[$url]['info']['wizardCurrent'] = 1;
        $this->annabaFormList[$url]['info']['wizardAction'] = '';

        $this->annabaFormList[$url]['info']['btnFermer'] = 'no';
        $this->annabaFormList[$url]['info']['createLabel'] = 'Créer';
        $this->annabaFormList[$url]['info']['updateLabel'] = 'Editer';

      
        $this->customActionUrlTemoin = $url ;
        
        return $this;
    }

      public function form(array $fields): self
    {
        foreach ($fields as $field) {
            $field->registerToCustomAction($this);
        }
        
        return $this;
    }

   
   public function addBulk(array  $settings) {

        $this->bulks[$settings['action']] = $settings;
    }

    public function addFilter($filter,$label) {

        $this->filters[$filter] = $label;
    }

    public function deleteFilterActif($key) {
        unset($this->filterActifs[$key]);
    }

     public function ajouterFilterActif($key,$a) {
        $this->filterActifs[$key] = $a;
    }

      public function openModal1($currentForm)
    {
      $this->resetValidation();
      $this->currentFormOpen = $currentForm;
      $this->resetForm($currentForm);
      $this->showModal = true;
    }

      public function openModal2($currentForm,$record)
    {
      
      $this->initRecord($currentForm,$record);
      $this->resetValidation();
      $this->currentFormOpen = $currentForm;
      $this->showModal = true;
    }

     public function closeModal()
    {
      $currentFormOpen = '';
      $this->showModal = false;
    }

    public function initRecord($formName,$record) {

       $this->resetForm($formName);

        $this->setIde($formName,$record['id']);

       foreach ($this->annabaFormList[$formName]['fields'] as $key => $field) {
          
           if($field['type'] == 'Text' || $field['type'] == 'Date' || $field['type'] == 'Number'
                || $field['type'] == 'Quill' || $field['type'] == 'Checkbox' ||
                $field['type'] == 'Select' ||  $field['type'] == 'Radio' || 
                 $field['type'] == 'CheckboxList') {
              if($field['options']['noDatabase'] == 'no') {
                $this->record[$field['field']] = $field['value'];

                $this->annabaFormList[$formName]['fields'][$key]['value'] = $record[$key] ;
              }
             
           }

           if($field['type'] == 'file') {
              if($field['options']['noDatabase'] == 'no') {           
               $this->annabaFormList[$formName]['fields'][$key]['options']['urlRecord']  = $this->urlStorage . $record[$key] ;
                
              }
             
           }

          

      }

    }

    ///////////////////////////////////////////////////
    ///////////////////////////////////////////////////

    public function getIde($form) {
       return  $this->annabaFormList[$form]['info']['ide'];
    }

    public function setIde($form,$ide) {
       $this->annabaFormList[$form]['info']['ide'] = $ide;
    }

    public function onUpdate() {
     
       $this->annabaFormList[$this->customActionUrlTemoin]['info']['formType'] = 'updator';
       return $this;
    }

    public function onWizard(array $wizard) {
     $this->annabaFormList[$this->customActionUrlTemoin]['info']['wizard'] = $wizard;
     $this->annabaFormList[$this->customActionUrlTemoin]['info']['wizardActive'] = 'yes';
    
     $this->annabaFormList[$this->customActionUrlTemoin]['info']['formType'] = 'wizardCreator';
     return $this;
    }

    public function onWizardUpdate(array $wizard) {
     $this->annabaFormList[$this->customActionUrlTemoin]['info']['wizard'] = $wizard;
     $this->annabaFormList[$this->customActionUrlTemoin]['info']['wizardActive'] = 'yes';
     
     $this->annabaFormList[$this->customActionUrlTemoin]['info']['formType'] = 'wizardUpdator';
       return $this;
    }

    public function input($form , $field) {

       return $this->annabaFormList[$form]['fields'][$field]['value'];
    }

    public function inputName($form , $field) {

       return 'annabaFormList.' .$form . '.fields.' . $field . '.value';
    }

    
    public function backUp() {

        $this->annabaFormListStart = $this->annabaFormList ;
    }

     public function resetForm($form) {

        $this->annabaFormList[$form] = $this->annabaFormListStart[$form] ;
    }

     public function resetInput($form,$field) {

        $this->annabaFormList[$form]['fields'][$field]['value'] =
        $this->annabaFormListStart[$form]['fields'][$field]['value'];
    }


      public function insert($formName)
    {

      foreach ($this->annabaFormList[$formName]['fields'] as $key => $field) {
          
           if($field['type'] == 'Text' || $field['type'] == 'Date' || $field['type'] == 'Number'
               || $field['type'] == 'Quill' || $field['type'] == 'Checkbox' ||
                $field['type'] == 'Select' ||  $field['type'] == 'Radio' || 
                 $field['type'] == 'CheckboxList') {
              if($field['options']['noDatabase'] == 'no') {
                $this->record[$field['field']] = $field['value'];
              }
             
           }

            if($field['type'] == 'file') {
              if($field['options']['noDatabase'] == 'no') {
                 if($field['value'] != '') {

                    $randomString = Str::random(10);
                    $ext = $field['value']->getClientOriginalExtension();
                    $name1 = time(). '-'. $randomString .'.'.$ext;
                    $folder = $field['options']['folder'] ;
                    $name2 = $folder. '/' . $name1;
                    $field['value']->storeAs($folder,$name1, 'public');
                    $this->record[$field['field']] =  $name2;



                 }
               
              }
             
           }



           if($field['type'] == 'Password') {
              if($field['options']['noDatabase'] == 'no') {
                 if($field['value'] != '') {

               $this->record[$key] = Hash::make($field['value']) ;

                 }
              }
           }



          
      }
      
    }


      public function update($formName)
    {
      $this->insert($formName);
    }

   
       public function createLabel($label): self
    {
        $this->annabaFormList[$this->customActionUrlTemoin]['info']['createLabel'] = $label;
        return $this;
    }

         public function updateLabel($label): self
    {
        $this->annabaFormList[$this->customActionUrlTemoin]['info']['updateLabel'] = $label;
       
        return $this;
    }

         public function btnFermer(): self
    {
        $this->annabaFormList[$this->customActionUrlTemoin]['info']['btnFermer'] = 'yes';
       
        return $this;
    }


       public function nextStep($formName)
    {
        
       $this->annabaFormList[$formName]['info']['wizardCurrent'] =

       $this->annabaFormList[$formName]['info']['wizardCurrent'] + 1;
        
    }

        public function prexiousStep($formName)
    {
        
       $this->annabaFormList[$formName]['info']['wizardCurrent'] =
       $this->annabaFormList[$formName]['info']['wizardCurrent'] - 1;
        
    }

       public function getWizardCurrent($formName)
    {    
        return $this->annabaFormList[$formName]['info']['wizardCurrent'];
    }

      public function getWizardAction($formName)
    {    
        return $this->annabaFormList[$formName]['info']['wizardAction'];
    }


    ///////////////////////////////////////////////////
    /////////////////////////////////////////////////// 


}