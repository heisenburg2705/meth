@extends('layouts.app')

@section('content')
    <h1>Dashboard Orang Tua</h1>
    <p>Halo, {{ Auth::guard('orangtua')->user()->nama_lengkap }}</p>

    <form id="logout-form" method="POST" action="{{ route('logout.orangtua') }}">
        @csrf
    </form>

    <hr>

    <livewire:ppdb.list-pendaftar />
@endsection
