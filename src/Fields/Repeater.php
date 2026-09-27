<?php
namespace Annaba\Admin\Fields;

class Repeater
{
    protected string $field;
    protected $noDatabase = 'no';
    protected int $numberOflines = 1;
    protected int $grille = 1;
    protected int $minLine = 0;
    protected  $label ;
    protected string|int $maxLine = 'infinite';
    protected string $dragger = 'yes';
    protected string $ajoutLine = 'yes';
    protected string $suppLine = 'yes';
    protected $readOnly = 'no';
    protected $generation = null;
    protected array $nestedFields = [];
    protected $colSpan = ['sm' =>  1 , 'md' => 1 , 'lg' => 1 , 'xl' => 1];
    protected $grid = ['sm' =>  1 , 'md' => 1 , 'lg' => 2 , 'xl' => 2];

    public static function make(string $field): self
    {
        $instance = new self();
        $instance->field = $field;
        $instance->label = ucfirst($field);
        return $instance;
    }

    public function lines(int $line): self
    {
        $this->numberOflines = $line;
        return $this;
    }

  
    public function min(int $minLine): self
    {
        $this->minLine = $minLine;
        return $this;
    }

    public function max(int $maxLine): self
    {
        $this->maxLine = $maxLine;
        return $this;
    }

    public function draggable(bool $dragger): self
    {
        $this->dragger = $dragger ? 'yes' : 'no';
        return $this;
    }

    public function addLine(bool $ajoutLine): self
    {
        $this->ajoutLine = $ajoutLine ? 'yes' : 'no';
        return $this;
    }

    public function removeLine(bool $suppLine): self
    {
        $this->suppLine = $suppLine ? 'yes' : 'no';
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

    public function grid(array $grid): self
    {
        $this->grid = $grid;
        return $this;
    }

    // ✅ méthode `form()` qui stocke simplement les champs internes
    public function form(array $fields): self
    {
        $this->nestedFields = $fields;
        return $this;
    }

    public function registerToCustomAction($generator): void
    {

        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['fields'] = [] ;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['schemaFields'] = [] ;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['numberOflines'] = $this->numberOflines ;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['grid'] = $this->grille ;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['minLine'] = $this->minLine ;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['maxLine'] = $this->maxLine ;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['draggable'] = $this->dragger ;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['addLine'] = $this->ajoutLine ;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['removeLine'] = $this->suppLine ;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['field'] = $this->field;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['type'] = 'Repeater';
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['value'] = [];
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['label'] = $this->label;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['defaultValue'] = [];
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['noDatabase'] = $this->noDatabase;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['readOnly'] = $this->readOnly;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['colSpan'] = $this->colSpan;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['grid'] = $this->grid;

        foreach ($this->nestedFields as $field) {
            $field->repeteurToCustomAction($generator ,$this->field);
        }

    }    

}