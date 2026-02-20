@extends('errors.layout')

@section('title', __('Not Found'))
@section('code', '404')
{{-- @extends('errors::minimal')

@section('title', __('Not Found'))
@section('code', '404')
@section('message', __('Not Found')) --}}

@section('header')
    <div class="gradient-bg-blue px-8 py-12 text-center">
        <div class="error-illustration mb-6">
            <svg class="w-32 h-32 mx-auto text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <h1 class="text-7xl font-bold text-white mb-4">404</h1>
        <h2 class="text-2xl font-semibold text-white mb-2">Halaman Tidak Ditemukan</h2>
        <p class="text-indigo-100 text-lg">Oops! Sepertinya Anda tersesat di ruang digital</p>
    </div>
@endsection

@section('content')
    <p class="text-gray-600 text-center mb-8">
        Maaf, halaman yang Anda cari tidak dapat ditemukan di sistem SIRENATA.
        Halaman mungkin telah dipindahkan, dihapus, atau tidak pernah ada.
    </p>
@endsection

@section('action-buttons')
    <a href="{{ route('landingpage.index') }}"
        class="button-hover block w-full bg-indigo-600 text-white text-center px-6 py-3.5 rounded-lg font-semibold hover:bg-indigo-700 transition-colors">
        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
        </svg>
        Kembali ke Beranda
    </a>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <a href="#"
            class="button-hover block bg-gray-100 text-gray-700 text-center px-6 py-3 rounded-lg font-medium hover:bg-gray-200 transition-colors">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            Katalog Kursus
        </a>

        <a href="#"
            class="button-hover block bg-gray-100 text-gray-700 text-center px-6 py-3 rounded-lg font-medium hover:bg-gray-200 transition-colors">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            Pusat Bantuan
        </a>
    </div>
@endsection
