@extends('layouts.super_admin')

@section('content')

<h1 class="text-2xl font-semibold mb-6">Tambah Sekolah</h1>

<div class="flex justify-center">
<div class="w-full max-w-xl bg-white rounded-xl shadow p-6">

    <form action="{{ route('superadmin.schools.store') }}" method="POST" class="space-y-5">
        @csrf

        {{-- NAMA SEKOLAH --}}
        <div>
            <label class="block text-sm font-medium mb-1">
                Nama Sekolah
            </label>
            <input
                type="text"
                name="name"
                value="{{ old('name') }}"
                required
                class="w-full border rounded-lg px-4 py-2 focus:ring focus:ring-indigo-200"
                placeholder="Contoh: SMA Negeri 1 Lampung">
        </div>

        {{-- JENIS LANGGANAN --}}
        <div>
            <label class="block text-sm font-medium mb-1">
                Jenis Langganan
            </label>
            <select
                name="plan_type"
                class="w-full border rounded-lg px-4 py-2">
                <option value="trial">Trial (7 Hari)</option>
                <option value="active">Aktif (Berlangganan)</option>
            </select>
        </div>

        {{-- DURASI LANGGANAN --}}
        <div class="grid grid-cols-3 gap-3">
            <div class="col-span-1">
                <label class="block text-sm font-medium mb-1">
                    Satuan
                </label>
                <select
                    name="duration_unit"
                    class="w-full border rounded-lg px-3 py-2">
                    <option value="days">Hari</option>
                    <option value="months">Bulan</option>
                    <option value="years">Tahun</option>
                </select>
            </div>

            <div class="col-span-2">
                <label class="block text-sm font-medium mb-1">
                    Jumlah
                </label>
                <input
                    type="number"
                    name="duration_value"
                    min="1"
                    placeholder="Contoh: 1, 6, 12"
                    class="w-full border rounded-lg px-4 py-2">
            </div>
        </div>

        {{-- INFO --}}
        <div class="text-sm text-gray-500 bg-slate-50 p-3 rounded">
            • Trial otomatis aktif 7 hari<br>
            • Paket aktif akan dihitung dari durasi yang dipilih
        </div>

        {{-- ACTION --}}
        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('superadmin.schools.index') }}"
               class="px-4 py-2 rounded-lg border text-gray-600 hover:bg-gray-100">
                Batal
            </a>

            <button
                type="submit"
                class="px-6 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
                Simpan Sekolah
            </button>
        </div>

    </form>
</div>
</div>

@endsection
