@extends('errors::minimal')

@section('title', __('Unauthorized'))
@section('code', '401')
@section('message', __('Unauthorized'))


{{-- @extends('errors.layout')

@section('title', 'Tidak Diizinkan')
@section('code', '401')
@section('message', 'Tidak Diizinkan')
@section('description', 'Maaf, Anda tidak memiliki izin untuk mengakses halaman ini. Silakan login terlebih dahulu.')

@section('content')
    <div class="text-center">
        <a href="{{ route('login') }}"
            class="button-hover inline-block bg-orange-600 text-white text-center px-6 py-3.5 rounded-lg font-semibold hover:bg-orange-700 transition-colors">
            Login Sekarang
        </a>
    </div>
@endsection --}}
