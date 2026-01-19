@extends('layouts.super_admin')

@section('content')
<div class="max-w-xl mx-auto">

    <h1 class="text-2xl font-semibold text-gray-800 mb-6">
        Edit Sekolah
    </h1>

    <div class="bg-white rounded-xl shadow p-6">

        <form method="POST" action="{{ route('superadmin.schools.update', $school) }}">
            @csrf
            @method('PUT')

            {{-- NAMA SEKOLAH --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Nama Sekolah
                </label>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name', $school->name) }}"
                    required
                    class="w-full border rounded-lg px-4 py-2 text-sm
                           focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            {{-- STATUS / PLAN --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Status / Paket
                </label>
                <select name="plan_type"
                        class="w-full border rounded-lg px-4 py-2 text-sm">
                    <option value="trial" {{ $school->status === 'trial' ? 'selected' : '' }}>
                        Trial (7 Hari)
                    </option>
                    <option value="active" {{ $school->status === 'active' ? 'selected' : '' }}>
                        Active (Berlangganan)
                    </option>
                    <option value="expired" {{ $school->status === 'expired' ? 'selected' : '' }}>
                        Expired
                    </option>
                    <option value="suspend" {{ $school->status === 'suspend' ? 'selected' : '' }}>
                        Suspend
                    </option>
                </select>
            </div>

            {{-- DURASI --}}
            <div class="grid grid-cols-3 gap-3 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Durasi
                    </label>
                    <input
                        type="number"
                        name="duration_value"
                        value="{{ old('duration_value') }}"
                        placeholder="Contoh: 1 / 6 / 12"
                        class="w-full border rounded-lg px-3 py-2 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Satuan
                    </label>
                    <select name="duration_unit"
                            class="w-full border rounded-lg px-3 py-2 text-sm">
                        <option value="days">Hari</option>
                        <option value="months">Bulan</option>
                        <option value="years">Tahun</option>
                    </select>
                </div>

                <div class="flex items-end text-xs text-gray-400">
                    * Kosongkan jika tidak ingin mengubah masa aktif
                </div>
            </div>

            {{-- INFO SAAT INI --}}
            <div class="bg-slate-50 rounded-lg p-4 mb-6 text-sm">
                <div class="mb-1">
                    <strong>Status:</strong> {{ strtoupper($school->status) }}
                </div>

                @if($school->trial_ends_at)
                    <div>
                        <strong>Trial Berakhir:</strong>
                        {{ \Carbon\Carbon::parse($school->trial_ends_at)->format('d/m/Y') }}
                    </div>
                @endif

                @if($school->subscription_ends_at)
                    <div>
                        <strong>Langganan Berakhir:</strong>
                        {{ \Carbon\Carbon::parse($school->subscription_ends_at)->format('d/m/Y') }}
                    </div>
                @endif
            </div>

            {{-- ACTION --}}
            <div class="flex justify-between items-center">
                <a href="{{ route('superadmin.schools.index') }}"
                   class="text-sm text-gray-500 hover:text-gray-700">
                    ← Kembali
                </a>

                <button
                    type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white
                           px-6 py-2 rounded-lg text-sm font-medium shadow">
                    Update Sekolah
                </button>
            </div>

        </form>

    </div>
</div>
@endsection
