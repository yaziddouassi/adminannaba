<?php
namespace Annaba\Admin\Fields;

class MultiSelectCollection
{
    protected string $field;
    protected string $model;
    protected string $type = 'MultiSelectCollection';
    protected string $colonneContent = 'id';
    protected string $colonneLabel = 'id';
    protected bool $lazyLoad = false;
    protected $fillables ;
    protected $queryList = [];
    protected $querySearch = '';
    public $limit = 5;
    protected $records ;
    protected $contents = [];
    protected $labels = [];
    protected $defaultValue = [];
    protected $label = '';
    protected $noDatabase = 'no';
    protected $readOnly = 'no';
    protected $colSpan = ['sm' =>  1 , 'md' => 1 , 'lg' => 1 , 'xl' => 1];

    public static function make(array $settings): self
    {
        $instance = new self();
        $instance->field = $settings['field'];
        $instance->model = $settings['model'];
        $model = new $instance->model;
        $instance->fillables = $model->getFillable();
     
        $instance->label = ucfirst($settings['field']); 
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


        public function lazy(): self
    {
        $this->lazyLoad = true;
        return $this;
    }

      public function perPage(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }


       public function where($champ,$valeur): self
    {
        $this->queryList[$champ] = $valeur;

         $query = $this->model::query();

          foreach ($this->queryList as $field => $value) {
             $query->where($field, $value);
          }

         $query->limit($this->limit) ;
         $this->records = $query->get() ; 

         $this->contents = $this->records->pluck('id')->toArray() ;
         $this->labels =   $this->records->pluck($this->field)->toArray() ;
     
        return $this;
    }


        public function pluck($colonneContent,$colonneLabel)
    {
        $this->colonneContent = $colonneContent;
        $this->colonneLabel = $colonneLabel ;
         return $this;

    }

       public function searchValue(string $querySearch)
    {
         $this->querySearch = $querySearch ;
         return $this;
    }  

     public function searchLikeColumns(array $fillables)
    {
         $this->fillables = $fillables ;
         return $this;
    }  


    public function getRecords()
{
    $query = $this->model::query();

    if ($this->querySearch != '') {
        $query->where(function ($q) {
            foreach ($this->fillables as $key => $fillable) {
                $q->orWhere($fillable, 'LIKE', '%' . $this->querySearch . '%');
            }
        });
    }

    foreach ($this->queryList as $field => $value) {
        $query->where($field, $value);
    }

    $query->limit($this->limit);
    $this->records = $query->get();

    $this->contents = $this->records->pluck($this->colonneContent)->toArray();
    $this->labels = $this->records->pluck($this->colonneLabel)->toArray();
}
      


     public function registerToCustomAction($generator): void
    {
        $this->getRecords();

        
        $nameSession = 'annaba-' . $generator->customActionUrlTemoin . '-optionselect-' . $this->field ;  ;
        $contentSession = [];
        $contentSession['permissions'] = $generator->permissionTemoin;
        $contentSession['customActionUrlTemoin'] = $generator->customActionUrlTemoin;
        $contentSession['model'] = $this->model ;
        $contentSession['fillables'] = $this->fillables ;
        $contentSession['colonneContent'] = $this->colonneContent ;
        $contentSession['colonneLabel'] = $this->colonneLabel ;
        $contentSession['contents'] = $this->contents ;
        $contentSession['labels'] = $this->labels ;
        $contentSession['queryList'] = $this->queryList ;
        $contentSession['querySearch'] = $this->querySearch ;
        $contentSession['lazyLoad'] = $this->lazyLoad ;
        $contentSession['limit'] = $this->limit ;
        $contentSession['limitStart'] = $this->limit ;
        $contentSession['avanceOnLimit'] = 'oui' ;
        $contentSession['numberToskip'] = 0 ;

        // dd($contentSession);
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['field'] = $this->field;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['type'] = $this->type;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['value'] = $this->defaultValue;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['label'] = $this->label;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['defaultValue'] = $this->defaultValue;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['noDatabase'] = $this->noDatabase;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['model'] = $this->model;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['fillables'] = $this->fillables;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['colonneContent'] = $this->colonneContent;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['colonneLabel'] = $this->colonneLabel;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['contents'] = $this->contents;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['labels'] = $this->labels;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['queryList'] = $this->queryList;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['querySearch'] = $this->querySearch;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['lazyLoad'] = $this->lazyLoad;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['readOnly'] = $this->readOnly;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['limit'] = $this->limit;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['limitStart'] = $this->limit;
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['avanceOnLimit'] = 'oui';
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['numberToskip'] = 0;  
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['page'] = 1;  
        $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$this->field]['options']['colSpan'] = $this->colSpan;
      }   


     public function repeteurToCustomAction($generator , $field) {

    $this->getRecords();
    $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$field]['fields'][$this->field]['field'] = $this->field;
    $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$field]['fields'][$this->field]['type'] = $this->type;
    $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$field]['fields'][$this->field]['value'] = $this->defaultValue;
    $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$field]['fields'][$this->field]['options']['label'] = $this->label ;
    $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$field]['fields'][$this->field]['options']['defaultValue'] = $this->defaultValue ;
    $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$field]['fields'][$this->field]['options']['noDatabase'] = $this->noDatabase ;
    $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$field]['fields'][$this->field]['options']['fillables'] = $this->fillables ;
    $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$field]['fields'][$this->field]['options']['colonneContent'] = $this->colonneContent ;
    $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$field]['fields'][$this->field]['options']['colonneLabel'] = $this-> colonneLabel ;
    $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$field]['fields'][$this->field]['options']['contents'] = $this->contents ;
    $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$field]['fields'][$this->field]['options']['labels'] = $this->labels ;
    $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$field]['fields'][$this->field]['options']['queryList'] = $this->queryList ;
    $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$field]['fields'][$this->field]['options']['querySearch'] = $this->querySearch ;
    $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$field]['fields'][$this->field]['options']['lazyLoad'] = $this->lazyLoad ;
    $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$field]['fields'][$this->field]['options']['readOnly'] = $this->readOnly ;
    $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$field]['fields'][$this->field]['options']['limit'] = $this->limit ;
    $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$field]['fields'][$this->field]['options']['limitStart'] = $this->limit ;
    $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$field]['fields'][$this->field]['options']['avanceOnLimit'] = 'oui' ;
    $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$field]['fields'][$this->field]['options']['numberToskip'] = 0 ;
    $generator->annabaFormList[$generator->customActionUrlTemoin]['fields'][$field]['fields'][$this->field]['options']['page'] = 1 ;
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