<?php
namespace Annaba\Admin\Fields;

class MultipleFile
{
    protected string $field;
    protected string $type = 'MultipleFile';
    protected string $typeBis = 'file';
    protected $defaultValue = [];
    protected $label = '';
    protected $noDatabase = 'no';
    protected $nullable = 'no';
    protected $noTouchable = 'no';
    protected $readOnly = 'no';
    protected $maxNumberFiles = 1000000000000;
    protected string $folder;
    protected $colSpan = ['sm' =>  1 , 'md' => 1 , 'lg' => 1 , 'xl' => 1];

    public static function make(string $field): self
    {
        $instance = new self();
        $instance->field = $field;
        $instance->folder = config('annabadmin.storage_folder');
        $instance->label = ucfirst($field);
        return $instance;
    }

    public function label(string $label): self
    {
        $this->label = $label;
        return $this;
    }

     public function folder(string $folder): self
    {
        $this->folder = $folder;
        return $this;
    }

      public function readOnly(): self
    {
        $this->readOnly = 'yes';
        return $this;
    }
   
    public function notInDatabase(): self
    {
        $this->noDatabase = 'yes';
        return $this;
    }

    public function fieldAndRecordNotNull(): self
    {
        $this->nullable = 'yes';
        return $this;
    }

     public function keepExistingFiles(): self
    {
        $this->noTouchable = 'yes';
        return $this;
    }

    public function maxNumberFiles(int $maxNumberFiles): self
    {
        $this->maxNumberFiles = $maxNumberFiles;
        return $this;
    }

       public function colSpan(array $colSpan): self
    {
        $this->colSpan = $colSpan;
        return $this;
    }

     public function image(): self
    {
        $this->typeBis = 'image';
        return $this;
    }

     public function video(): self
    {
        $this->typeBis = 'video';
        return $this;
    }

    public function audio(): self
    {
        $this->typeBis = 'audio';
        return $this;
    }

    public function registerToCustomAction($generator): void
    {

        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['field'] = $this->field;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['type'] = 'MultipleFile';
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['typeBis'] = $this->typeBis;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['value'] = $this->defaultValue;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['label'] = $this->label;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['defaultValue'] = $this->defaultValue;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['noDatabase'] = $this->noDatabase;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['tempUrlTabs'] = [];
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['existingFiles'] = [];
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['nullable'] = $this->nullable;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['noTouchable'] = $this->noTouchable;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['readOnly'] = $this->readOnly;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['maxNumberFiles'] = $this->maxNumberFiles;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['folder'] = $this->folder;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['colSpan'] = $this->colSpan;
    }   


}