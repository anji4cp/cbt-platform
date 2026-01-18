@extends('layouts.school_admin')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow p-6">
    <h2 class="text-lg font-semibold mb-6 text-center">Edit Profil Sekolah</h2>

    <form method="POST"
          action="{{ route('school.profile.update') }}"
          enctype="multipart/form-data"
          class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="text-sm">Nama Sekolah</label>
            <input name="name"
                   value="{{ old('name', $school->name) }}"
                   class="w-full border rounded-lg px-3 py-2">
        </div>

        <div>
            <label class="text-sm">Kontak</label>
            <input name="contact"
                   value="{{ old('contact', $school->contact) }}"
                   class="w-full border rounded-lg px-3 py-2">
        </div>

        <div>
            <label class="text-sm">Warna Tema</label>
            <input type="color"
                   name="theme_color"
                   value="{{ old('theme_color', $school->theme_color) }}"
                   class="w-20 h-10 border rounded">
        </div>

        <div>
            <label class="text-sm">Logo Sekolah</label>
            <input type="file" name="logo">
        </div>

        <div class="flex justify-between pt-4">
            <a href="{{ route('school.profile') }}"
               class="px-4 py-2 border rounded-lg">
                Batal
            </a>

            <button class="bg-indigo-600 text-white px-6 py-2 rounded-lg">
                Simpan
            </button>
        </div>
    </form>
    <br></br>
    <h2 class="text-lg font-semibold mb-6 text-center">Ubah Password</h2>

    <form method="POST" action="{{ route('school.profile.password') }}"
      class="w-full border rounded-lg px-3 py-2">
    @csrf
    @method('PUT')

    <div>
        <label>Password Lama</label>
        <input type="password" name="current_password"
               class="w-full border rounded px-3 py-2" required>
    </div>

    <div>
        <label>Password Baru</label>
        <input type="password" name="password"
               class="w-full border rounded px-3 py-2" required>
    </div>

    <div>
        <label>Konfirmasi Password Baru</label>
        <input type="password" name="password_confirmation"
               class="w-full border rounded px-3 py-2" required>
    </div>
    <br></br>
    <div class="flex justify-between pt-4">
    <a href="{{ route('school.profile') }}"
               class="px-4 py-2 border rounded-lg">
                Batal
            </a>
    <button class="bg-indigo-600 text-white px-6 py-2 rounded-lg">
        Simpan Password
    </button>
    </div>
</form>
</div>
@endsection
