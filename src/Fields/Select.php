<?php
namespace Annaba\Admin\Fields;

class Select
{
    protected string $field;
    protected string $type = 'Select';
    protected $defaultValue = '';
    protected $label = '';
    protected $noDatabase = 'no';
    protected $contents = [];
    protected $labels = [];
    protected $readOnly = 'no';
    protected $colSpan = ['sm' =>  1 , 'md' => 1 , 'lg' => 1 , 'xl' => 1];

    public static function make(string $field): self
    {
        $instance = new self();
        $instance->field = $field;
        $instance->label = ucfirst($field);
        return $instance;
    }

    public function label(string $label): self
    {
        $this->label = $label;
        return $this;
    }

    public function value($value): self
    {
        $this->defaultValue = $value;
        return $this;
    }


    public function contents(array $value): self
    {
        $this->contents = $value;
        return $this;
    }

    public function labels(array $value): self
    {
        $this->labels = $value;
        return $this;
    }

    
    public function notInDatabase(): self
    {
        $this->noDatabase = 'yes';
        return $this;
    }

      public function readOnly(): self
    {
        $this->readOnly = 'yes';
        return $this;
    }

       public function colSpan(array $colSpan): self
    {
        $this->colSpan = $colSpan;
        return $this;
    }

     public function registerToCustomAction($generator): void
    {

          foreach ($this->contents as $key => $value) {
          $this->contents[$key] = (string) $this->contents[$key] ;
        }

         foreach ($this->labels as $key2 => $value2) {
          $this->labels[$key2] = (string) $this->labels[$key2] ;
        }

          $this->defaultValue = (string) $this->defaultValue ; 


        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['field'] = $this->field;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['type'] = $this->type;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['value'] = $this->defaultValue;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['label'] = $this->label;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['defaultValue'] = $this->defaultValue;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['noDatabase'] = $this->noDatabase;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['contents'] = $this->contents;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['labels'] = $this->labels;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['readOnly'] = $this->readOnly;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['colSpan'] = $this->colSpan;
      }   


     public function repeteurToCustomAction($generator , $field) {


    foreach ($this->contents as $key => $value) {
          $this->contents[$key] = (string) $this->contents[$key] ;
        }

         foreach ($this->labels as $key2 => $value2) {
          $this->labels[$key2] = (string) $this->labels[$key2] ;
        }

          $this->defaultValue = (string) $this->defaultValue ;


    $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$field]['fields'][$this->field]['field'] = $this->field;
    $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$field]['fields'][$this->field]['type'] = $this->type;
    $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$field]['fields'][$this->field]['value'] = $this->defaultValue;
    $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$field]['fields'][$this->field]['options']['label'] = $this->label ;
    $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$field]['fields'][$this->field]['options']['defaultValue'] = $this->defaultValue ;
    $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$field]['fields'][$this->field]['options']['noDatabase'] = $this->noDatabase ;
    $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$field]['fields'][$this->field]['options']['contents'] = $this->contents ;
    $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$field]['fields'][$this->field]['options']['labels'] = $this->labels ;
    $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$field]['fields'][$this->field]['options']['readOnly'] = $this->readOnly ;
    $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$field]['fields'][$this->field]['options']['colSpan'] = $this->colSpan ;
    if($generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$field]['options']['readOnly'] === 'yes') {
      $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$field]['fields'][$this->field]['options']['readOnly'] = 'yes' ;
    }
    
    $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$field]['schemaFields'][$this->field] = $this->defaultValue;


    $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$field]['value'] = [] ;

                  for ($i=0; $i < $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$field]['numberOflines'] ; $i++) { 
             
                    array_push($generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$field]['value'],
                     $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$field]['schemaFields']);
  
                }


   }

}