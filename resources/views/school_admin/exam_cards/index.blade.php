@extends('layouts.school_admin')

@section('content')

{{-- ================= HEADER ================= --}}
<div class="mb-6 text-center">
    <h1 class="text-xl font-semibold">Cetak Kartu Ujian</h1>
    <p class="text-sm text-slate-500">
        Cetak kartu peserta ujian berdasarkan kelas
    </p>
</div>

<div class="flex justify-center">
<form method="POST"
      action="{{ route('school.exam-cards.print') }}"
      target="_blank"
      class="bg-white rounded-2xl shadow-sm p-6 w-full max-w-xl space-y-6">
    @csrf

    {{-- ================= DATA KARTU ================= --}}
    <section class="space-y-4">
        <h3 class="text-sm font-semibold text-slate-700 uppercase">
            Informasi Kartu
        </h3>

        <div>
            <label class="block text-sm mb-1">Kelas</label>
            <select name="class"
                    required
                    class="w-full border rounded-lg px-3 py-2 text-sm">
                @foreach($classes as $class)
                    <option value="{{ $class }}">{{ $class }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm mb-1">Judul Kartu</label>
            <input name="title"
                   value="KARTU PESERTA UJIAN"
                   class="w-full border rounded-lg px-3 py-2 text-sm">
        </div>

        <div>
            <label class="block text-sm mb-1">Kop Instansi</label>
            <input name="kop"
                   value="DINAS PENDIDIKAN......"
                   class="w-full border rounded-lg px-3 py-2 text-sm">
        </div>

        <div>
            <label class="block text-sm mb-1">Tanggal Kartu</label>
            <input type="date"
                   name="card_date"
                   value="{{ date('Y-m-d') }}"
                   class="border rounded-lg px-3 py-2 text-sm">
        </div>

        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="show_password" value="1">
            Tampilkan password siswa
        </label>
    </section>

    <hr>

    {{-- ================= PENANDATANGAN ================= --}}
    <section class="space-y-4">
        <h3 class="text-sm font-semibold text-slate-700 uppercase">
            Penandatangan
        </h3>

        <div>
            <label class="block text-sm mb-1">Jabatan</label>
            <input name="position"
                   placeholder="Contoh: Kepala Sekolah"
                   class="w-full border rounded-lg px-3 py-2 text-sm">
        </div>

        <div>
            <label class="block text-sm mb-1">Nama</label>
            <input name="sign_name"
                   class="w-full border rounded-lg px-3 py-2 text-sm">
        </div>

        <div>
            <label class="block text-sm mb-1">NIP</label>
            <input name="nip"
                   class="w-full border rounded-lg px-3 py-2 text-sm">
        </div>
    </section>

    {{-- ================= ACTION ================= --}}
    <div class="pt-4">
        <button type="submit"
                class="w-full bg-indigo-600 text-white py-2.5 rounded-lg
                       hover:bg-indigo-700 font-medium">
            Cetak Kartu
        </button>
    </div>

</form>
</div>

@endsection
