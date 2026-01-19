@extends('layouts.school_admin')

@section('content')

{{-- ================= HEADER ================= --}}
<div class="max-w-4xl mx-auto mb-6 text-center">
    <h1 class="text-xl font-semibold">Edit Ujian</h1>
    <p class="text-sm text-slate-500">
        Perbarui data ujian dan paket soal
    </p>
</div>

<div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-sm overflow-hidden">

    {{-- ERROR GLOBAL --}}
    @if ($errors->any())
        <div class="m-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            <ul class="list-disc pl-5 text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ================= FORM ================= --}}
    <form id="examForm"
          method="POST"
          action="{{ route('school.exams.update', $exam) }}"
          class="p-6 space-y-10">
        @csrf
        @method('PUT')

        {{-- ================= INFORMASI ================= --}}
        <section>
            <h3 class="text-sm font-semibold text-slate-700 mb-4 uppercase">
                Informasi Ujian
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">
                        Mata Pelajaran
                    </label>
                    <input
                        name="subject"
                        value="{{ old('subject', $exam->subject) }}"
                        required
                        class="w-full rounded-lg border px-3 py-2 text-sm
                               focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">
                        Token
                    </label>
                    <input
                        id="token"
                        name="token"
                        value="{{ old('token', $exam->token) }}"
                        required
                        class="w-full rounded-lg border px-3 py-2 text-sm
                               focus:ring-2 focus:ring-indigo-500">
                    <p id="tokenError"
                       class="text-xs text-red-600 mt-1 hidden">
                        Token sudah digunakan
                    </p>
                </div>
            </div>
        </section>

        {{-- ================= WAKTU ================= --}}
        <section>
            <h3 class="text-sm font-semibold text-slate-700 mb-4 uppercase">
                Waktu & Aturan
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="text-sm">Jumlah Soal</label>
                    <input id="total_questions"
                           type="number"
                           name="total_questions"
                           min="1"
                           value="{{ old('total_questions', $exam->total_questions) }}"
                           required
                           class="w-full rounded-lg border px-3 py-2">
                </div>

                <div>
                    <label class="text-sm">Durasi (menit)</label>
                    <input type="number"
                           name="duration_minutes"
                           min="1"
                           value="{{ old('duration_minutes', $exam->duration_minutes) }}"
                           required
                           class="w-full rounded-lg border px-3 py-2">
                </div>

                <div>
                    <label class="text-sm">Minimal Submit</label>
                    <input type="number"
                           name="min_submit_minutes"
                           min="0"
                           value="{{ old('min_submit_minutes', $exam->min_submit_minutes) }}"
                           required
                           class="w-full rounded-lg border px-3 py-2">
                </div>
            </div>
        </section>

        {{-- ================= KELAS ================= --}}
        <section>
            <h3 class="text-sm font-semibold text-slate-700 mb-4 uppercase">
                Kelas Peserta
            </h3>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                @foreach($classes as $class)
                    <label
                        class="flex items-center gap-2 text-sm
                               bg-slate-50 border rounded-lg px-3 py-2">
                        <input type="checkbox"
                               name="classes[]"
                               value="{{ $class }}"
                               {{ in_array($class, old('classes', $selectedClasses)) ? 'checked' : '' }}>
                        {{ $class }}
                    </label>
                @endforeach
            </div>
        </section>

        {{-- ================= PAKET ================= --}}
        <section>
            <h3 class="text-sm font-semibold text-slate-700 mb-4 uppercase">
                Paket Soal
            </h3>

            <p id="answerError"
               class="text-xs text-red-600 mb-3 hidden">
                Jumlah kunci jawaban harus sama dengan jumlah soal
            </p>

            <div id="packages" class="space-y-4">

                @foreach($packages as $i => $pkg)
                    @php
                        $keys = json_decode($pkg->answer_key, true) ?? [];
                    @endphp

                    <div class="border rounded-xl p-4 bg-slate-50">
                        <input type="hidden"
                               name="packages[{{ $i }}][code]"
                               value="{{ $pkg->package_code }}">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm">
                                    Link Soal Paket {{ $pkg->package_code }}
                                </label>
                                <input
                                    name="packages[{{ $i }}][pdf_url]"
                                    value="{{ $pkg->pdf_url }}"
                                    required
                                    class="w-full rounded-lg border px-3 py-2">
                            </div>

                            <div>
                                <label class="text-sm">
                                    Kunci Jawaban Paket {{ $pkg->package_code }}
                                </label>
                                <textarea
                                    name="packages[{{ $i }}][answer_key]"
                                    rows="2"
                                    required
                                    class="answer-input w-full rounded-lg border px-3 py-2">{{ implode(',', $keys) }}</textarea>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>

            <button type="button"
                    onclick="addPackage()"
                    class="mt-3 text-sm text-indigo-600 hover:underline">
                + Tambah Paket Soal
            </button>
        </section>

        {{-- ================= OPSI ================= --}}
        <div class="flex items-center gap-2">
            <input type="checkbox"
                   name="show_result"
                   value="1"
                   {{ $exam->show_score ? 'checked' : '' }}>
            <span class="text-sm">Tampilkan nilai ke siswa</span>
        </div>

        {{-- ================= ACTION ================= --}}
        <div class="flex flex-col sm:flex-row justify-center gap-4 pt-6 border-t">
            <button id="submitBtn"
                    class="bg-indigo-600 text-white px-10 py-2 rounded-lg hover:bg-indigo-700">
                Update
            </button>

            <a href="{{ route('school.exams.index') }}"
               class="px-10 py-2 rounded-lg border text-center hover:bg-slate-100">
                Batal
            </a>
        </div>

    </form>
</div>

{{-- ================= SCRIPT ================= --}}
<script>
let timer;
const tokenInput = document.getElementById('token');
const tokenError = document.getElementById('tokenError');
const submitBtn = document.getElementById('submitBtn');

/* TOKEN CHECK */
tokenInput.addEventListener('input', () => {
    clearTimeout(timer);
    tokenError.classList.add('hidden');
    submitBtn.disabled = false;

    timer = setTimeout(() => {
        fetch(`/school-admin/exams/check-token?token=${tokenInput.value}&ignore={{ $exam->id }}`)
            .then(res => res.json())
            .then(data => {
                if (data.exists) {
                    tokenError.classList.remove('hidden');
                    submitBtn.disabled = true;
                }
            });
    }, 400);
});

/* ANSWER COUNT */
document.getElementById('examForm').addEventListener('submit', function (e) {
    const total = parseInt(document.getElementById('total_questions').value);
    let valid = true;

    document.querySelectorAll('.answer-input').forEach(el => {
        const count = el.value.split(',')
            .map(v => v.trim())
            .filter(v => v !== '').length;

        if (count !== total) valid = false;
    });

    if (!valid) {
        e.preventDefault();
        document.getElementById('answerError').classList.remove('hidden');
        document.getElementById('answerError').scrollIntoView({ behavior: 'smooth' });
    }
});

/* ADD PACKAGE */
let packageIndex = {{ count($packages) }};
const packageCodes = ['A','B','C','D','E','F','G'];

function addPackage() {
    if (packageIndex >= packageCodes.length) return;

    const code = packageCodes[packageIndex];
    document.getElementById('packages').insertAdjacentHTML('beforeend', `
        <div class="border rounded-xl p-4 bg-slate-50">
            <input type="hidden" name="packages[${packageIndex}][code]" value="${code}">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm">Link Soal Paket ${code}</label>
                    <input name="packages[${packageIndex}][pdf_url]"
                           class="w-full rounded-lg border px-3 py-2" required>
                </div>
                <div>
                    <label class="text-sm">Kunci Jawaban Paket ${code}</label>
                    <textarea name="packages[${packageIndex}][answer_key]"
                              rows="2"
                              class="answer-input w-full rounded-lg border px-3 py-2"
                              required></textarea>
                </div>
            </div>
        </div>
    `);
    packageIndex++;
}
</script>

@endsection
