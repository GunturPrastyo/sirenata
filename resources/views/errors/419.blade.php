@extends('errors::minimal')

@section('title', __('Page Expired'))
@section('code', '419')
@section('message', __('Page Expired'))



{{-- @extends('errors.layout')

@section('title', 'Halaman Kadaluarsa')
@section('code', '419')
@section('message', 'Halaman Kadaluarsa')
@section('description', 'Maaf, sesi Anda telah berakhir. Silakan muat ulang halaman dan coba lagi.')

@section('content')
    <div class="text-center">
        <a href="javascript:window.location.reload()"
            class="button-hover inline-block bg-orange-600 text-white text-center px-6 py-3.5 rounded-lg font-semibold hover:bg-orange-700 transition-colors">
            Muat Ulang Halaman
        </a>
    </div>
@endsection
 --}}
