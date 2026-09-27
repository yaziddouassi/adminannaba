<?php

namespace Annaba\Admin;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Illuminate\Support\Facades\Blade;

class AnnabaServiceProvider extends ServiceProvider
{
   
    public function register(): void
    {
      $this->mergeConfigFrom(
            __DIR__.'/../config/annabadmin.php', 'annabadmin'
        );
    }
   
    public function boot(): void
    {
 
      $this->publishes([
            __DIR__.'/../config/annabadmin.php' => config_path('annabadmin.php'),
        ], 'config');
  

         $this->loadRoutesFrom(__DIR__.'/../routes/web.php'); 

          $this->loadViewsFrom(__DIR__.'/../resources/views','adminannaba');

    
        Livewire::component('AnnabaForm', \Annaba\Admin\Crud\Livewire\AnnabaForm::class);
        Livewire::component('AnnabaListing', \Annaba\Admin\Crud\Livewire\AnnabaListing::class);
        Livewire::component('adminannaba1', \Annaba\Admin\Livewire\Adminannaba1::class); 
        Livewire::component('adminannaba2', \Annaba\Admin\Livewire\Adminannaba2::class); 
        Livewire::component('adminannaba3', \Annaba\Admin\Livewire\Adminannaba3::class);  
        Livewire::component('adminannaba.liste1', \Annaba\Admin\Livewire\Liste1::class);
        Livewire::component('adminannaba.liste2', \Annaba\Admin\Livewire\Liste2::class);
        Livewire::component('adminannaba.form1', \Annaba\Admin\Livewire\Form1::class);
        Livewire::component('adminannaba.form2', \Annaba\Admin\Livewire\Form2::class);
        Livewire::component('adminannaba.form3', \Annaba\Admin\Livewire\Form3::class);
        Livewire::component('adminannaba.form4', \Annaba\Admin\Livewire\Form4::class);
        Livewire::component('adminannaba.sidebar', \Annaba\Admin\Livewire\Sidebar::class); 
        Livewire::component('adminannaba.navbar', \Annaba\Admin\Livewire\Navbar::class);
        Livewire::component('adminannaba.chart1', \Annaba\Admin\Livewire\Chart1::class);
        Livewire::component('adminannaba.widget1', \Annaba\Admin\Livewire\Widget1::class);
    }
}