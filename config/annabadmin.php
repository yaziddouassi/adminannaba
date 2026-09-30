<?php

return  [
    'company' =>'My Company',
    'middlewareList' => ['auth'],
    'middlewareDev' =>  'auth',
    'storage_disk' => env('ANNABA_STORAGE_DISK', 'public'),
    'storage_url' => env('ANNABA_STORAGE_URL', 'http://127.0.0.1:8000/storage/'),
    'storage_folder' => env('ANNABA_STORAGE_DEFAULT_FOLDER', 'files'),
   
];