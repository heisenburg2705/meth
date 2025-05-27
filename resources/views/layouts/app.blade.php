<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'PPDB Online' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @livewireStyles
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('landing') }}">PPDB Online</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                @auth('orangtua')
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard.orangtua') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <form action="{{ route('logout.orangtua') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-light ms-3">Logout</button>
                            </form>
                        </li>
                    </ul>
                @else
                    <div class="d-flex">
                        <a class="btn btn-outline-light me-2" href="{{ route('login') }}">Login</a>
                        <a class="btn btn-light" href="{{ route('register') }}">Daftar</a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <main class="py-5">
        <div class="container">
            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @livewireScripts
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('show-toast', (data) => {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: data.message,
                    showConfirmButton: false,
                    timer: 3000
                });
            });
        });
    </script>
    @stack('scripts')
</body>
</html>