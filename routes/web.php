<?php

use Illuminate\Support\Facades\Route;

/*$directory = base_path('routes'); // Specify the directory
$fileName = 'saphir.php'; // Specify the file name

$filePath = $directory . DIRECTORY_SEPARATOR . $fileName;

if (file_exists($filePath)) {
    require_once $filePath;
} */


Route::middleware('web')->group(function () {
   
 // Route::get('/annaba',\Annaba\Admin\Livewire\Adminannaba1::class);
 // Route::get('/annaba2',\Annaba\Admin\Livewire\Adminannaba2::class);
//  Route::get('/annaba3',\Annaba\Admin\Livewire\Adminannaba3::class);
});