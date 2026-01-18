@extends('layouts.student')

@section('content')
<style>
    .login-wrap {
        min-height: 70vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .login-card {
        width: 100%;
        max-width: 420px;
        background: #ffffff;
        border-radius: 18px;
        box-shadow: 0 20px 40px rgba(0,0,0,.08);
        padding: 36px 32px;
    }

    .login-title {
        text-align: center;
        font-size: 22px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 6px;
    }

    .login-sub {
        text-align: center;
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 28px;
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-group label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 6px;
    }

    .form-group input {
        width: 100%;
        padding: 12px 14px;
        border-radius: 10px;
        border: 1px solid #d1d5db;
        font-size: 14px;
        transition: .2s;
    }

    .form-group input:focus {
        outline: none;
        border-color: {{ session('school_brand.theme') ?? '#2563eb' }};
        box-shadow: 0 0 0 3px rgba(37,99,235,.15);
    }

    .login-btn {
        width: 100%;
        padding: 12px;
        border-radius: 12px;
        border: none;
        font-size: 15px;
        font-weight: 700;
        color: #fff;
        background: {{ session('school_brand.theme') ?? '#2563eb' }};
        cursor: pointer;
        transition: .2s;
    }

    .login-btn:hover {
        opacity: .9;
        transform: translateY(-1px);
    }

    .login-error {
        background: #fee2e2;
        border: 1px solid #fecaca;
        color: #991b1b;
        font-size: 13px;
        padding: 10px 12px;
        border-radius: 10px;
        margin-bottom: 16px;
    }

    .login-footer {
        text-align: center;
        font-size: 11px;
        color: #9ca3af;
        margin-top: 24px;
    }
</style>

<div class="login-wrap">
    <div class="login-card">

        <div class="login-title">Login Siswa</div>
        <div class="login-sub">
            {{ session('school_brand.name') }}
        </div>

        @if($errors->any())
            <div class="login-error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('student.login') }}">
            @csrf

            <div class="form-group">
                <label>Username</label>
                <input
                    type="text"
                    name="username"
                    value="{{ old('username') }}"
                    placeholder="Masukkan username"
                    required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input
                    type="password"
                    name="password"
                    placeholder="Masukkan password"
                    required>
            </div>

            <button type="submit" class="login-btn">
                Masuk Ujian
            </button>
        </form>

        <div class="login-footer">
            CBT Sekolah • Sistem Ujian Berbasis Komputer
        </div>

    </div>
</div>
@endsection
