<?php

namespace App\Livewire\Ppdb;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;

class Login extends Component
{
    public $email;
    public $password;

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        if (Auth::guard('orangtua')->attempt(['email' => $this->email, 'password' => $this->password])) {
            session()->regenerate();
            \Log::info('Email: ' . $this->email . ' Password: ' . $this->password . ' - Login successful');

            return redirect()->route('dashboard.orangtua'); 
        } else {
            \Log::info('Email: ' . $this->email . ' Password: ' . $this->password . ' - Login failed');
            session()->flash('error', 'Email atau password salah.');
        }
    }

    public function render()
    {
        return view('livewire.ppdb.login');
    }
}
