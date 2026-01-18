@extends('layouts.school_admin')

@section('content')
<h1 class="text-xl font-semibold mb-6 text-center">Cetak Kartu Ujian</h1>
<div class="flex justify-center">
<form method="POST"
      action="{{ route('school.exam-cards.print') }}"
      target="_blank"
      class="bg-white rounded-xl shadow p-6 w-full max-w-xl">
    @csrf

    <div class="mb-4">
        <select name="class"
                required
                class="w-full border rounded px-3 py-2">
            @foreach($classes as $class)
                <option value="{{ $class }}">{{ $class }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-4">
        <label class="block text-sm mb-1">Judul Kartu</label>
        <input name="title"
               value="KARTU PESERTA UJIAN"
               class="w-full border rounded px-3 py-2">
    </div>

    <div class="mb-4">
        <label class="block text-sm mb-1">Kop Instansi</label>
        <input name="kop"
               value="DINAS PENDIDIKAN......"
               class="w-full border rounded px-3 py-2">
    </div>

    <div class="mb-4">
        <label class="block text-sm mb-1">Tanggal Kartu</label>
        <input type="date"
               name="card_date"
               value="{{ date('Y-m-d') }}"
               class="border rounded px-3 py-2 text-sm">
    </div>

    <div class="mb-4">
        <label class="flex items-center gap-2">
            <input type="checkbox" name="show_password" value="1">
            Tampilkan Password Siswa
        </label>
    </div>

    <hr class="my-4">

    <div class="mb-3">
        <label class="block text-sm">Jabatan Penandatangan</label>
        <input name="position"
               placeholder="Contoh : Kepala Sekolah"
               class="w-full border rounded px-3 py-2">
    </div>

    <div class="mb-3">
        <label class="block text-sm">Nama Penandatangan</label>
        <input name="sign_name"
               class="w-full border rounded px-3 py-2">
    </div>

    <div class="mb-4">
        <label class="block text-sm">NIP</label>
        <input name="nip"
               class="w-full border rounded px-3 py-2">
    </div>

    <button type="submit"
            class="bg-indigo-600 text-white px-5 py-2 rounded">
        Cetak Kartu
    </button>
</form>
</div>
@endsection
