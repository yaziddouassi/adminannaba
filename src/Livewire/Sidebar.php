<?php

namespace Annaba\Admin\Livewire;
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Sidebar extends Component
{
    public function logout()
    {
        Auth::guard('web')->logout();

        session()->invalidate();
        session()->regenerateToken();

        return $this->redirect('/', navigate: true);
    }
     public function render()
    {
        return view('adminannaba::livewire.sidebar');
    } 
   
}