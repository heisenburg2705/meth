<?php

namespace App\Livewire\Ppdb;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CreatePendaftar extends Component
{
    public $nama_lengkap, 
           $nisn, 
           $no_telepon, 
           $asal_sekolah, 
           $jenis_kelamin, 
           $tanggal_lahir;

    public function simpan()
    {
        $this->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nisn' => 'nullable|string|max:20',
            'no_telepon' => 'nullable|string|max:15',
            'asal_sekolah' => 'nullable|string|max:255',
            'jenis_kelamin' => 'nullable|in:L,P',
            'tanggal_lahir' => 'nullable|date',
        ]);

        // Simpan data pendaftar ke database
        \App\Models\Pendaftar::create([
            'nama_lengkap' => $this->nama_lengkap,
            'nisn' => $this->nisn,
            'no_telepon' => $this->no_telepon,
            'asal_sekolah' => $this->asal_sekolah,
            'jenis_kelamin' => $this->jenis_kelamin,
            'tanggal_lahir' => $this->tanggal_lahir,
            'orang_tua_id' => Auth::guard('orangtua')->id(),
        ]);

        session()->flash('message', 'Pendaftar berhasil disimpan.');

        // Reset input fields
        $this->reset();
        return redirect()->route('dashboard.orangtua');
    }
    
    public function render()
    {
        return view('livewire.ppdb.create-pendaftar');
    }
}
