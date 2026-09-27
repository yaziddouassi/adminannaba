<?php

namespace Annaba\Admin\Livewire;
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
use Livewire\Component;
use Illuminate\Support\Facades\File;


class Chart1 extends Component
{
    public $data = [] ;
    public $labels = [] ;
    public $label ;
    public $chartId ;
    public $chartType ;
    public $backgroundColor = array() ;

    public function mount()
    {
        $this->chartId = 'Chartexample' ;
        $this->label = 'Best Sailings'  ;
        $this->data = [1,2,3,8]  ;
        $this->labels = ['first','second','third','four']  ;
        $this->chartType = 'bar';
        $this->backgroundColor = [
            'blue',
            'red',
            'black',
            'lime',
            
        ];
       
    }


    public function chartchange()
    {
        $this->data= [1,2,3]  ;
        $this->labels = ['first','second','third']  ;
       
    }


    

    
    public function render()
    {
        return view('adminannaba::livewire.chart1');
    }
}
