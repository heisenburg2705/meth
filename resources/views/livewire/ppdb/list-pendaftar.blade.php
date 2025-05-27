<div class="card shadow-sm mt-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Daftar Anak yang Didaftarkan</h5>
    </div>

    <div class="card-body">
        <div class="mb-4">
            <a class="btn btn-outline-primary me-2" href="{{ route('create.pendaftar') }}">Tambah Pendaftar</a>
        </div>

        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (count($pendaftar) > 0)
            <ul class="list-group">
                @foreach($pendaftar as $anak)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $anak->nama_lengkap }}</strong><br>
                            <small class="text-muted">Asal Sekolah: {{ $anak->asal_sekolah ?? '-' }},
                                JK: {{ $anak->jenis_kelamin ?? '-' }}</small>
                        </div>
                        <div>
                            <button class="btn btn-sm btn-warning" wire:click="edit({{ $anak->id }})"
                                wire:loading.attr="disabled" wire:target="edit">
                                Edit
                            </button>

                            <button class="btn btn-sm btn-danger" wire:click="konfirmasiHapus({{ $anak->id }})"
                                wire:loading.attr="disabled" wire:target="konfirmasiHapus">
                                Hapus
                            </button>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="alert alert-warning mb-0">
                Belum ada anak yang didaftarkan.
            </div>
        @endif

        @if ($editMode)
            <div class="modal fade show d-block" style="background: rgba(0,0,0,0.5)">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5>Edit Data Anak</h5>
                            <button type="button" class="btn-close" wire:click="$set('editMode', false)"></button>
                        </div>
                        <div class="modal-body">
                            <form wire:submit.prevent="update">
                                <div class="mb-2">
                                    <label>Nama Lengkap</label>
                                    <input type="text" wire:model.defer="form.nama_lengkap" class="form-control">
                                </div>
                                <div class="mb-2">
                                    <label>Asal Sekolah</label>
                                    <input type="text" wire:model.defer="form.asal_sekolah" class="form-control">
                                </div>
                                <div class="mb-2">
                                    <label>Jenis Kelamin</label>
                                    <select wire:model.defer="form.jenis_kelamin" class="form-select">
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label>Tanggal Lahir</label>
                                    <input type="date" wire:model.defer="form.tanggal_lahir" class="form-control">
                                </div>
                                <div class="text-end">
                                    <button class="btn btn-secondary" wire:click="$set('editMode', false)">Batal</button>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('show-delete-confirmation', (data) => {
            Swal.fire({
                title: 'Hapus Data Anak?',
                text: "Data tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('delete', {id : data.id});
                }
            })
        });
    });
</script>
@endpush
