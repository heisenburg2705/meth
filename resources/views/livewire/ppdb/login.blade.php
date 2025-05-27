<div class="row justify-content-center">
    <div class="col-md-6">
        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Login Orang Tua Pendaftar</h5>
            </div>
            <div class="card-body">
                <form wire:submit.prevent="login">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" wire:model.lazy="email"
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="Masukkan email aktif">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Kata Sandi</label>
                        <input type="password" id="password" wire:model.lazy="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Masukkan kata sandi">
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Masuk</button>
                    </div>
                </form>
            </div>

            <div class="card-footer text-center">
                Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a>
            </div>
        </div>
    </div>
</div>