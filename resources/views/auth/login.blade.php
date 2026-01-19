@extends('layouts.public')

@php
    $title = 'Login';
    $heading = 'Login';
    $subheading = 'Masuk ke sistem CBT';
@endphp

@section('content')
<form method="POST" action="{{ route('login') }}" class="space-y-6">
    @csrf

    <div>
        <label class="block text-sm font-medium mb-1">Email</label>
        <input type="email" name="email"
               class="w-full rounded-lg border px-3 py-2"
               required autofocus>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Password</label>
        <input type="password" name="password"
               class="w-full rounded-lg border px-3 py-2"
               required>
    </div>

    <button class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700">
        Login
    </button>
</form>
@endsection
