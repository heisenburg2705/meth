@extends('layouts.app')

@section('content')
<div class="container text-center">
    <h1 class="mb-4">Selamat Datang di PPDB Online</h1>
    <p class="lead">Daftar sekarang untuk menjadi bagian dari sekolah kami!</p>
    <a href="{{ route('register') }}" class="btn btn-primary mt-3">Daftar Sekarang</a>
</div>
@endsection
