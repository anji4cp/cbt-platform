@extends('layouts.school_admin')

@section('content')

<div class="max-w-3xl mx-auto mt-12">

    <!-- CARD -->
    <div class="bg-white rounded-2xl shadow-md">

        <!-- HEADER -->
        <div class="border-b px-8 py-6">
            <h1 class="text-xl font-semibold text-gray-800 text-center">
                Buat Ujian Baru
            </h1>
            <p class="text-sm text-gray-500 text-center mt-1">
                Atur mata pelajaran, kelas peserta, dan paket soal
            </p>
        </div>

        <!-- BODY -->
        <form method="POST" action="{{ route('school.exams.store') }}" class="px-8 py-6 space-y-6">
            @csrf

            <!-- INFORMASI UMUM -->
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-3 uppercase">
                    Informasi Ujian
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm mb-1">Mata Pelajaran</label>
                        <input name="subject" required
                               class="w-full rounded-lg border px-3 py-2 focus:ring-indigo-200"
                               placeholder="Contoh: Matematika">
                    </div>

                    <div>
                        <label class="block text-sm mb-1">Token / Kode</label>
                        <input name="token" required
                               class="w-full rounded-lg border px-3 py-2 focus:ring-indigo-200"
                               placeholder="Contoh: MTK2026">
                    </div>
                </div>
            </div>

            <!-- JUMLAH & DURASI -->
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-3 uppercase">
                    Waktu & Aturan
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm mb-1">Jumlah Soal</label>
                        <input type="number" name="total_questions" placeholder="Berapa banyak soal" min="1" required
                               class="w-full rounded-lg border px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm mb-1">Durasi (menit)</label>
                        <input type="number" name="duration_minutes" placeholder="Durasi Ujian"min="1" required
                               class="w-full rounded-lg border px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm mb-1">Minimal Kirim</label>
                        <input type="number" name="min_submit_minutes" placeholder="Minimal Bisa Kirim Jawaban" min="1" required
                               class="w-full rounded-lg border px-3 py-2">
                    </div>
                </div>
            </div>

            <!-- KELAS PESERTA -->
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-3 uppercase">
                    Kelas Peserta
                </h3>

                @if(isset($classes) && $classes->count())
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                        @foreach($classes as $class)
                            <label class="flex items-center gap-2 text-sm bg-slate-50 px-3 py-2 rounded-lg border">
                                <input type="checkbox" name="classes[]" value="{{ $class }}">
                                {{ $class }}
                            </label>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-red-600">
                        Belum ada data kelas. Tambahkan siswa terlebih dahulu.
                    </p>
                @endif
            </div>

            <!-- PAKET SOAL -->
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-3 uppercase">
                    Paket Soal
                </h3>

                <div id="packages" class="space-y-4">

                    <!-- PAKET A -->
                    <div class="border rounded-xl p-4 bg-slate-50">
                        <input type="hidden" name="packages[0][code]" value="A">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm mb-1 block">Link Soal Paket A</label>
                                <input name="packages[0][pdf_url]" placeholder="Link google drive soal" required
                                       class="w-full rounded-lg border px-3 py-2">
                            </div>

                            <div>
                                <label class="text-sm mb-1 block">Kunci Jawaban Paket A</label>
                                <textarea name="packages[0][answer_key]" placeholder="Harus sama dengan Jumlah soal" rows="2" required
                                          class="w-full rounded-lg border px-3 py-2"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button"
                        onclick="addPackage()"
                        class="text-sm text-indigo-600 hover:underline mt-2">
                    + Tambah Paket Soal
                </button>
            </div>

            <!-- OPSI -->
            <div class="flex items-center gap-2">
                <input type="checkbox" name="show_result" value="1">
                <span class="text-sm">Tampilkan nilai ke siswa</span>
            </div>

            <!-- ACTION -->
            <div class="flex justify-center gap-4 pt-6 border-t">
                <button
                    class="bg-indigo-600 text-white px-10 py-2 rounded-lg hover:bg-indigo-700">
                    Simpan Ujian
                </button>

                <a href="{{ route('school.exams.index') }}"
                   class="px-10 py-2 rounded-lg border hover:bg-slate-100">
                    Batal
                </a>
            </div>

        </form>
    </div>
</div>

<script>
let packageIndex = 1;
const packageCodes = ['A','B','C','D','E','F','G'];

function addPackage() {
    if (packageIndex >= packageCodes.length) return;

    const code = packageCodes[packageIndex];
    document.getElementById('packages').insertAdjacentHTML('beforeend', `
        <div class="border rounded-xl p-4 bg-slate-50">
            <input type="hidden" name="packages[${packageIndex}][code]" value="${code}">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm mb-1 block">Link Soal Paket ${code}</label>
                    <input name="packages[${packageIndex}][pdf_url]"
                           class="w-full rounded-lg border px-3 py-2" required>
                </div>
                <div>
                    <label class="text-sm mb-1 block">Kunci Jawaban Paket ${code}</label>
                    <textarea name="packages[${packageIndex}][answer_key]"
                              rows="2"
                              class="w-full rounded-lg border px-3 py-2" required></textarea>
                </div>
            </div>
        </div>
    `);
    packageIndex++;
}
</script>

@endsection
