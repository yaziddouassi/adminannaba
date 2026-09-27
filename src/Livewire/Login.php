<?php
namespace Annaba\Admin\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            return redirect()->intended('/dashboard');
        }

        $this->addError('email', 'Identifiants incorrects.');
    }

    public function render()
    {
        return view('adminannaba::livewire.login')
                   ->layout('adminannaba::layouts.app');
    }
}