<?php

namespace App\Livewire\Ppdb;

use Livewire\Component;
use App\Models\OrangTua;
use Illuminate\Support\Facades\Hash;
use Log;

class Register extends Component
{
    public $nama_lengkap;
    public $email;
    public $password;
    public $password_confirmation;

    protected $rules = [
        'nama_lengkap' => 'required|string|max:255',
        'email' => 'required|email|unique:orang_tua,email',
        'password' => 'required|string|min:8|confirmed',
    ];

    public function register()
    {
        $this->validate();

        // Create OrangTua
        $orangTua = OrangTua::create([
            'nama_lengkap' => $this->nama_lengkap,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);
        
        Log::info('OrangTua registered: ' . $this->email . ' password: ' . $this->password);

        // Optionally, you can redirect or show a success message
        session()->flash('message', 'Pendaftaran berhasil! Silakan login.');

        return redirect()->route('login');
    }

    public function render()
    {
        return view('livewire.ppdb.register');
    }
}
