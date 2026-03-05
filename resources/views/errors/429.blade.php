@extends('errors::minimal')

@section('title', __('Too Many Requests'))
@section('code', '429')
@section('message', __('Too Many Requests'))



{{-- @extends('errors.layout')

@section('title', 'Terlalu Banyak Permintaan')
@section('code', '429')
@section('message', 'Terlalu Banyak Permintaan')
@section('description', 'Maaf, Anda melakukan terlalu banyak permintaan ke server kami.')

@section('content')
    <div class="text-center">
        <p class="text-gray-600 mb-8">
            Silakan tunggu beberapa saat sebelum mencoba lagi.
        </p>
    </div>
@endsection
 --}}
