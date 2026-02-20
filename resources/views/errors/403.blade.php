@extends('errors.layout')

@section('title', __('Forbidden'))
@section('code', '403')

@section('header')
    <div class="gradient-bg px-8 py-12 text-center">
        <div class="error-illustration mb-6">
            <svg class="w-32 h-32 mx-auto text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>
        <h1 class="text-7xl font-bold text-white mb-4">403</h1>
        <h2 class="text-2xl font-semibold text-white mb-2">{{ __('Forbidden') }}</h2>
        <p class="text-orange-100 text-lg">{{ __('Anda tidak memiliki izin untuk mengakses halaman ini') }}</p>
    </div>
@endsection

@section('content')
    <div class="bg-orange-50 border-l-4 border-orange-400 p-4 mb-6">
        <div class="flex">
            <div class="shrink-0">
                <svg class="h-5 w-5 text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-orange-800 font-medium">
                    Halaman ini memerlukan hak akses khusus yang tidak Anda miliki saat ini.
                </p>
            </div>
        </div>
    </div>

    <p class="text-gray-600 text-center mb-8">
        Kemungkinan penyebab:
    </p>

    <ul class="space-y-3 mb-8">
        <li class="flex items-start">
            <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                    clip-rule="evenodd" />
            </svg>

            <span class="text-gray-700">
                {{ $exception->getMessage() ?: 'Anda belum login atau sesi Anda telah berakhir.' }}
            </span>
        </li>
    </ul>
@endsection


@section('action-buttons')
    <a href="#"
        class="button-hover block w-full bg-orange-600 text-white text-center px-6 py-3.5 rounded-lg font-semibold hover:bg-orange-700 transition-colors">
        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Kembali ke Halaman Sebelumnya
    </a>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <a href="#"
            class="button-hover block bg-gray-100 text-gray-700 text-center px-6 py-3 rounded-lg font-medium hover:bg-gray-200 transition-colors">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            Dashboard
        </a>

        <a href="#"
            class="button-hover block bg-gray-100 text-gray-700 text-center px-6 py-3 rounded-lg font-medium hover:bg-gray-200 transition-colors">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Hubungi Admin
        </a>
    </div>
@endsection
