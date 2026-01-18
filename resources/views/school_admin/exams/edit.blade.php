@extends('layouts.school_admin')

@section('content')

<div class="max-w-2xl mx-auto mt-12">
    <div class="bg-white rounded-2xl shadow p-6">

        <h1 class="text-xl font-semibold mb-6 text-center">
            Edit Ujian
        </h1>

        <form method="POST" action="{{ route('school.exams.update', $exam) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- MAPEL -->
            <div>
                <label class="block text-sm font-medium mb-1">Mata Pelajaran</label>
                <input name="subject" value="{{ $exam->subject }}" required
                       class="w-full border rounded-lg px-3 py-2">
            </div>

            <!-- TOKEN -->
            <div>
                <label class="block text-sm font-medium mb-1">Token</label>
                <input name="token" value="{{ $exam->token }}" required
                       class="w-full border rounded-lg px-3 py-2">
            </div>

            <!-- JUMLAH SOAL -->
            <div>
                <label class="block text-sm font-medium mb-1">Jumlah Soal</label>
                <input type="number" name="total_questions"
                       value="{{ $exam->total_questions }}" required
                       class="w-full border rounded-lg px-3 py-2">
            </div>

            <!-- DURASI -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Durasi</label>
                    <input type="number" name="duration_minutes"
                           value="{{ $exam->duration_minutes }}" required
                           class="w-full border rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Minimal Kirim</label>
                    <input type="number" name="min_submit_minutes"
                           value="{{ $exam->min_submit_minutes }}" required
                           class="w-full border rounded-lg px-3 py-2">
                </div>
            </div>

            <!-- KELAS -->
            <div>
                <label class="block text-sm font-medium mb-2">Kelas Peserta</label>
                <div class="grid grid-cols-2 gap-2">
                    @foreach($classes as $class)
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox"
                                   name="classes[]"
                                   value="{{ $class }}"
                                   {{ in_array($class, $selectedClasses) ? 'checked' : '' }}>
                            {{ $class }}
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- PAKET SOAL -->
            <div>
                <label class="block text-sm font-medium mb-2">Paket Soal</label>

                @foreach($packages as $i => $pkg)
                <div class="border rounded-lg p-4 mb-4">

                    <input type="hidden" name="packages[{{ $i }}][code]"
                        value="{{ $pkg->package_code }}">

                    <label class="text-sm">
                        Link Soal Paket {{ $pkg->package_code }}
                    </label>
                    <input name="packages[{{ $i }}][pdf_url]"
                        value="{{ $pkg->pdf_url }}"
                        class="w-full border rounded px-3 py-2 mb-2">

                    @php
                        $keys = is_array($pkg->answer_key)
                            ? $pkg->answer_key
                            : json_decode($pkg->answer_key, true);
                    @endphp

                    <label class="text-sm">
                        Kunci Jawaban Paket {{ $pkg->package_code }}
                    </label>
                    <textarea name="packages[{{ $i }}][answer_key]"
                            rows="2"
                            class="w-full border rounded px-3 py-2">{{ implode(',', $keys ?? []) }}</textarea>
                </div>
                @endforeach
            </div>

            <!-- OPSI -->
            <div class="flex items-center gap-2">
                <input type="checkbox" name="show_result" value="1"
                       {{ $exam->show_score ? 'checked' : '' }}>
                <label class="text-sm">Tampilkan hasil ke siswa</label>
            </div>

            <!-- ACTION -->
            <div class="flex justify-center gap-4 pt-6">
                <button class="bg-indigo-600 text-white px-8 py-2 rounded-lg">
                    Update
                </button>
                <a href="{{ route('school.exams.index') }}"
                   class="px-8 py-2 rounded-lg border">
                    Batal
                </a>
            </div>

        </form>
    </div>
</div>

@endsection
