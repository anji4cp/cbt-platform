@extends('layouts.school_admin')

@section('content')

{{-- ================= HEADER ================= --}}
<div class="mb-6 text-center">
    <h1 class="text-xl font-semibold">Edit Profil Sekolah</h1>
    <p class="text-sm text-slate-500">
        Perbarui informasi sekolah dan keamanan akun.
    </p>
</div>

<div class="max-w-3xl mx-auto space-y-10">

    {{-- ================= FORM PROFIL ================= --}}
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <h2 class="text-lg font-semibold mb-6">Profil Sekolah</h2>

        <form method="POST"
              action="{{ route('school.profile.update') }}"
              enctype="multipart/form-data"
              class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @csrf
            @method('PUT')

            {{-- NAMA --}}
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Nama Sekolah
                </label>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name', $school->name) }}"
                    required
                    class="w-full border rounded-lg px-3 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            {{-- KONTAK --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Kontak
                </label>
                <input
                    type="text"
                    name="contact"
                    value="{{ old('contact', $school->contact) }}"
                    class="w-full border rounded-lg px-3 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            {{-- WARNA TEMA --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Warna Tema
                </label>
                <input
                    type="color"
                    name="theme_color"
                    value="{{ old('theme_color', $school->theme_color) }}"
                    class="w-24 h-10 border rounded-lg cursor-pointer">
            </div>

            {{-- LOGO --}}
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Logo Sekolah
                </label>
                <input
                    type="file"
                    name="logo"
                    class="text-sm">
            </div>

            {{-- ACTION --}}
            <div class="sm:col-span-2 flex flex-col sm:flex-row gap-3 justify-end pt-4">
                <a href="{{ route('school.profile') }}"
                   class="px-5 py-2 rounded-lg border text-center text-sm
                          text-slate-600 hover:bg-slate-100">
                    Batal
                </a>

                <button
                    type="submit"
                    class="bg-indigo-600 text-white px-6 py-2 rounded-lg text-sm
                           hover:bg-indigo-700">
                    Simpan Profil
                </button>
            </div>
        </form>
    </div>

    {{-- ================= FORM PASSWORD ================= --}}
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <h2 class="text-lg font-semibold mb-6">Ubah Password</h2>

        <form method="POST"
              action="{{ route('school.profile.password') }}"
              class="space-y-4">
            @csrf
            @method('PUT')

            {{-- PASSWORD LAMA --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Password Lama
                </label>
                <input
                    type="password"
                    name="current_password"
                    required
                    class="w-full border rounded-lg px-3 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            {{-- PASSWORD BARU --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Password Baru
                </label>
                <input
                    type="password"
                    name="password"
                    required
                    class="w-full border rounded-lg px-3 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            {{-- KONFIRMASI --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Konfirmasi Password Baru
                </label>
                <input
                    type="password"
                    name="password_confirmation"
                    required
                    class="w-full border rounded-lg px-3 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            {{-- ACTION --}}
            <div class="flex flex-col sm:flex-row gap-3 justify-end pt-4">
                <a href="{{ route('school.profile') }}"
                   class="px-5 py-2 rounded-lg border text-center text-sm
                          text-slate-600 hover:bg-slate-100">
                    Batal
                </a>

                <button
                    type="submit"
                    class="bg-indigo-600 text-white px-6 py-2 rounded-lg text-sm
                           hover:bg-indigo-700">
                    Simpan Password
                </button>
            </div>

        </form>
    </div>

</div>

@endsection
