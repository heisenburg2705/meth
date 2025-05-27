<?php

namespace App\Livewire\Ppdb;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Pendaftar;

class ListPendaftar extends Component
{
    public $pendaftar = [];
    public $editMode = false;
    public $editId;
    public $form = [];
    public int $pendingDeleteId;

    public function mount()
    {
        // Load the list of pendaftar from the database
        $this->pendaftar = Auth::guard('orangtua')->user()?->pendaftar ?? [];
    }

    public function render()
    {
        return view('livewire.ppdb.list-pendaftar');
    }

    public function edit($id)
    {
        $this->editMode = true;
        $this->editId = $id;

        $anak = Pendaftar::findOrFail($id);

        $this->form = [
            'nama_lengkap' => $anak->nama_lengkap,
            'nisn' => $anak->nisn,
            'no_telepon' => $anak->no_telepon,
            'asal_sekolah' => $anak->asal_sekolah,
            'jenis_kelamin' => $anak->jenis_kelamin,
            'tanggal_lahir' => $anak->tanggal_lahir,
        ];
    }

    public function update()
    {
        $this->validate([
            'form.nama_lengkap' => 'required|string|max:255',
            'form.nisn' => 'nullable|string|max:20',
            'form.no_telepon' => 'nullable|string|max:15',
            'form.asal_sekolah' => 'nullable|string|max:255',
            'form.jenis_kelamin' => 'nullable|in:L,P',
            'form.tanggal_lahir' => 'nullable|date',
        ]);

        $pendaftar = Pendaftar::findOrFail($this->editId);
        $pendaftar->update($this->form);

        session()->flash('message', 'Data pendaftar berhasil diperbarui.');

        $this->reset(['editMode', 'editId', 'form']);
        $this->mount();
        $this->dispatch('show-toast', [
            'message' => 'Data anak berhasil diperbarui!',
        ]);
    }

    public function konfirmasiHapus($id)
    {
        $this->js("
            Livewire.dispatch('show-delete-confirmation', { id: {$id} });
        ");
    }

    #[\Livewire\Attributes\On('delete')]
    public function delete(int $id)
    {
        $pendaftar = Pendaftar::where('orang_tua_id', auth('orangtua')->id())
            ->findOrFail($id);

        $pendaftar->delete();

        // session()->flash('message', 'Data pendaftar berhasil dihapus.');

        $this->mount();

        $this->js("
            Livewire.dispatch('show-toast', {
                message: 'Data anak berhasil dihapus.'
            });
        ");
    }
}
