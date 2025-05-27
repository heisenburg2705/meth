<div class="card shadow-sm mt-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Formulir Pendaftaran Anak</h5>
    </div>

    <div class="card-body">
        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form wire:submit.prevent="simpan">
            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" wire:model="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror">
                @error('nama_lengkap') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">NISN</label>
                <input type="text" wire:model="nisn" class="form-control @error('nisn') is-invalid @enderror">
                @error('nisn') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">No Telepon</label>
                <input type="text" wire:model="no_telepon" class="form-control @error('no_telepon') is-invalid @enderror">
                @error('no_telepon') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Asal Sekolah</label>
                <input type="text" wire:model="asal_sekolah" class="form-control @error('asal_sekolah') is-invalid @enderror">
                @error('asal_sekolah') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Jenis Kelamin</label>
                <select wire:model="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror">
                    <option value="">-- Pilih Jenis Kelamin --</option>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>
                @error('jenis_kelamin') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Tanggal Lahir</label>
                <input type="date" wire:model="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror">
                @error('tanggal_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="text-end">
                <a class="btn btn-danger" href="{{ route(name: 'dashboard.orangtua') }}">Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan Pendaftar</button>
            </div>
        </form>
    </div>
</div>
