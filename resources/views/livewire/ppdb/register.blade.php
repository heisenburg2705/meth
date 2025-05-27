<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Daftar Akun PPDB</h5>
            </div>
            <div class="card-body">
                <form wire:submit.prevent="register">
                    <div class="mb-3">
                        <label for="nama_lengkap">Nama Lengkap</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="nama_lengkap" 
                            wire:model="nama_lengkap"
                            placeholder="Masukkan nama lengkap Anda">
                        @error('nama_lengkap') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email">Email</label>
                        <input 
                            type="email"
                            class="form-control"
                            id="email"
                            wire:model="email"
                            placeholder="Masukkan email aktif Anda">
                        @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password">Password</label>
                        <input 
                            type="password" 
                            class="form-control" 
                            id="password" 
                            wire:model="password"
                            placeholder="Masukkan kata sandi Anda">
                        @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation">Konfirmasi Password</label>
                        <input
                            type="password"
                            class="form-control"
                            id="password_confirmation"
                            wire:model="password_confirmation"
                            placeholder="Konfirmasi kata sandi Anda">
                        @error('password_confirmation') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Daftar</button>
                    </div>
                </form>
            </div>

            <div class="card-footer text-center">
                Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a>
            </div>
        </div>
    </div>
</div>