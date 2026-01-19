@extends('layouts.school_admin')

@section('content')

{{-- ================= HEADER ================= --}}
<div class="mb-6">
    <h1 class="text-xl font-semibold">Data Siswa</h1>
    <p class="text-sm text-slate-500">
        Gunakan template resmi. Jangan ubah nama kolom.
    </p>
</div>

{{-- ================= ACTION BAR ================= --}}
<div class="flex flex-col lg:flex-row lg:items-center gap-4 mb-6">

    {{-- LEFT ACTIONS --}}
    <div class="flex flex-wrap gap-3">
        <a href="{{ route('school.students.create') }}"
           class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">
            + Tambah Siswa
        </a>

        <a href="{{ asset('templates/template_import_siswa.xlsx') }}"
           class="bg-slate-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-slate-700"
           download>
            ⬇ Template Import
        </a>
    </div>

    {{-- RIGHT ACTIONS --}}
    <div class="flex flex-wrap gap-3 lg:ml-auto">

        {{-- IMPORT --}}
        <form method="POST"
              action="{{ route('school.students.import') }}"
              enctype="multipart/form-data"
              class="flex items-center gap-2">
            @csrf
            <input type="file"
                   name="file"
                   required
                   class="text-sm border rounded px-2 py-1 bg-white">
            <button class="bg-slate-700 text-white px-4 py-2 rounded-lg text-sm">
                Import
            </button>
        </form>

        {{-- HAPUS PER KELAS --}}
        <form method="POST"
              action="{{ route('school.students.deleteByClass') }}"
              onsubmit="return confirm('Yakin hapus semua siswa di kelas ini?')"
              class="flex items-center gap-2">
            @csrf
            <select name="class" required class="border rounded px-3 py-2 text-sm">
                <option value="">Pilih Kelas</option>
                @foreach($classes as $class)
                    <option value="{{ $class }}">{{ $class }}</option>
                @endforeach
            </select>
            <button class="bg-orange-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-orange-700">
                Hapus Kelas
            </button>
        </form>

        {{-- HAPUS SEMUA --}}
        <form method="POST"
              action="{{ route('school.students.deleteAll') }}"
              onsubmit="return confirm('⚠️ SEMUA siswa akan dihapus. Lanjutkan?')">
            @csrf
            @method('DELETE')
            <button class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700">
                Hapus Semua
            </button>
        </form>

    </div>
</div>

{{-- ================= FILTER KELAS ================= --}}
<div class="flex gap-2 mb-5 overflow-x-auto">
    <a href="{{ route('school.students.index') }}"
       class="px-4 py-1.5 rounded-full text-sm border whitespace-nowrap
       {{ empty($selectedClass)
            ? 'bg-indigo-600 text-white border-indigo-600'
            : 'bg-white text-slate-600 hover:bg-slate-100' }}">
        Semua
    </a>

    @foreach($classes as $class)
        <a href="{{ route('school.students.index', ['class' => $class]) }}"
           class="px-4 py-1.5 rounded-full text-sm border whitespace-nowrap
           {{ $selectedClass === $class
                ? 'bg-indigo-600 text-white border-indigo-600'
                : 'bg-white text-slate-600 hover:bg-slate-100' }}">
            {{ $class }}
        </a>
    @endforeach
</div>

{{-- ================= FLASH / IMPORT RESULT ================= --}}
@if (session('success'))
    <div class="mb-4 rounded-lg bg-green-50 border border-green-200 p-4 text-sm text-green-700">
        {{ session('success') }}
    </div>
@endif

@if(session('import_success'))
    <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
        <h3 class="font-semibold text-green-700 mb-2">
            ✅ Import Berhasil ({{ count(session('import_success')) }})
        </h3>
        <div class="overflow-x-auto">
            <table class="text-sm w-full">
                <thead class="bg-green-100">
                    <tr>
                        <th class="px-3 py-2 text-left">Baris</th>
                        <th class="px-3 py-2 text-left">Nama</th>
                        <th class="px-3 py-2 text-left">Username</th>
                        <th class="px-3 py-2 text-left">Kelas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(session('import_success') as $row)
                        <tr class="border-t">
                            <td class="px-3 py-2">{{ $row['row'] }}</td>
                            <td class="px-3 py-2">{{ $row['name'] }}</td>
                            <td class="px-3 py-2">{{ $row['username'] }}</td>
                            <td class="px-3 py-2">{{ $row['class'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

@if(session('import_failed'))
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
        <h3 class="font-semibold text-red-700 mb-2">
            ❌ Gagal Import ({{ count(session('import_failed')) }})
        </h3>
        <div class="overflow-x-auto">
            <table class="text-sm w-full">
                <thead class="bg-red-100">
                    <tr>
                        <th class="px-3 py-2 text-left">Baris</th>
                        <th class="px-3 py-2 text-left">Alasan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(session('import_failed') as $row)
                        <tr class="border-t">
                            <td class="px-3 py-2">{{ $row['row'] }}</td>
                            <td class="px-3 py-2">{{ $row['reason'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

{{-- ================= TABLE ================= --}}
<div class="bg-white rounded-xl shadow overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-slate-100 text-slate-600">
            <tr>
                <th class="px-4 py-3 text-left">Nama</th>
                <th class="px-4 py-3 text-left">Username</th>
                <th class="px-4 py-3 text-left hidden sm:table-cell">Password</th>
                <th class="px-4 py-3 text-left">Kelas</th>
                <th class="px-4 py-3 text-center">Status</th>
                <th class="px-4 py-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $student)
                <tr class="border-t hover:bg-slate-50">
                    <td class="px-4 py-3">{{ $student->name }}</td>
                    <td class="px-4 py-3">{{ $student->username }}</td>
                    <td class="px-4 py-3 hidden sm:table-cell">
                        {{ $student->exam_password ?? '-' }}
                    </td>
                    <td class="px-4 py-3">{{ $student->class }}</td>
                    <td class="px-4 py-3 text-center">
                        @if($student->is_active)
                            <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-700">
                                Aktif
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-700">
                                Nonaktif
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex flex-wrap justify-center gap-2">
                            <a href="{{ route('school.students.edit', $student) }}"
                               class="px-3 py-1 text-xs rounded bg-blue-100 text-blue-700 hover:bg-blue-200">
                                Edit
                            </a>

                            <form method="POST" action="{{ route('school.students.toggle', $student) }}">
                                @csrf
                                @method('PATCH')
                                <button
                                    class="px-3 py-1 text-xs rounded
                                    {{ $student->is_active
                                        ? 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200'
                                        : 'bg-green-100 text-green-700 hover:bg-green-200' }}">
                                    {{ $student->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>

                            <form method="POST"
                                  action="{{ route('school.students.destroy', $student) }}"
                                  onsubmit="return confirm('Hapus siswa ini?')">
                                @csrf
                                @method('DELETE')
                                <button
                                    class="px-3 py-1 text-xs rounded bg-red-100 text-red-700 hover:bg-red-200">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-6 text-slate-400">
                        Tidak ada data siswa
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
