<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Ujian CBT</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

<div class="exam-wrapper">

    <!-- ================= SOAL ================= -->
    <div class="question-panel">
        <div class="pdf-overlay"></div>
        @if($package && $package->pdf_url)
            <iframe
                src="{{ str_replace('/view?usp=drive_link', '/preview', $package->pdf_url) }}"
                class="pdf-frame"
                sandbox="allow-scripts allow-same-origin"
                onerror="document.querySelector('.pdf-frame-error') && document.querySelector('.pdf-frame-error').style.display = 'flex'">
            </iframe>
            <div class="pdf-frame-error" style="display:none;">
                <div class="error-content">
                    <p>⚠️ File soal tidak dapat dimuat</p>
                    <p class="text-sm">Pastikan URL PDF valid atau hubungi admin</p>
                </div>
            </div>
        @else
            <div class="pdf-frame-error">
                <div class="error-content">
                    <p>❌ File soal tidak ditemukan</p>
                    <p class="text-sm">Hubungi administrator sekolah</p>
                </div>
            </div>
        @endif
    </div>

    <!-- ================= JAWABAN ================= -->
    <div class="answer-panel">

        <div class="timer">
            Sisa Waktu: <span id="timer">--:--</span><br>
            <small id="autosaveStatus" style="color:#16a34a">⏳ Menyimpan...</small>
        </div>

        <div class="answer-scroll">
            <form id="answerForm">
                @csrf

                @for($i=1; $i<=$exam->total_questions; $i++)
                    <div class="answer-row">
                        <div class="number">{{ $i }}</div>
                        <div class="options">
                            @foreach(['A','B','C','D','E'] as $opt)
                                <label>
                                    <input type="radio"
                                           name="answers[{{ $i }}]"
                                           value="{{ $opt }}"
                                           {{ ($session->answers[$i] ?? '') === $opt ? 'checked' : '' }}>
                                    {{ $opt }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endfor
            </form>
        </div>

        <div class="submit-fixed">
            <button id="submitBtn" onclick="submitExam()" disabled>
                Kirim Jawaban
            </button>

        </div>

    </div>
</div>

<script>

    /* ================= TIMER (UX ONLY) ================= */
document.addEventListener('DOMContentLoaded', function () {

    /* ================= DEVICE (SAFE) ================= */
    function generateUUID() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            const r = Math.random()*16|0, v = c === 'x' ? r : (r&0x3|0x8);
            return v.toString(16);
        });
    }

    if (!localStorage.getItem('cbt_device_id')) {
        localStorage.setItem('cbt_device_id', generateUUID());
    }

    const DEVICE_ID = localStorage.getItem('cbt_device_id');

    /* ================= TIMER ================= */
    const timerEl = document.getElementById('timer');
    if (!timerEl) return;

    let remaining = Number('{{ (int) ($remaining ?? 0) }}');

    function renderTimer() {
        const m = Math.floor(remaining / 60);
        const s = remaining % 60;
        timerEl.innerText =
            String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
    }

    renderTimer();

    const timerInterval = setInterval(() => {
        if (remaining <= 0) {
            clearInterval(timerInterval);
            renderTimer();
            submitExam(); // 🔥 AUTO SUBMIT
            return;
        }

        remaining--;
        renderTimer();
    }, 1000);

    /* ================= AUTOSAVE ================= */
    setInterval(() => {
        const form = document.getElementById('answerForm');
        if (!form) return;

        const data = new FormData(form);
        data.append('device_id', DEVICE_ID);

        fetch('{{ route("student.exam.save", $exam) }}', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: data
        })
        .then(res => res.json())
        .then(result => {

            if (result.status === 'saved') {
                document.getElementById('autosaveStatus').innerText =
                    '💾 Tersimpan ' + result.time;
            }

            if (result.status === 'blocked') {
                alert('Ujian dibuka di perangkat lain.');
                window.location.href = '{{ route("student.exams") }}';
            }

            if (result.status === 'locked') {
                // waktu habis → arahkan ke finish
                window.location.href = result.redirect ?? '{{ route("student.exam.finish", $exam) }}';
            }
        })
        .catch(err => console.warn('Autosave error', err));

    }, 5000);

    /* ================= SUBMIT ================= */
    window.submitExam = function () {

        if (!confirm('Yakin kirim jawaban?')) return;

        const data = new FormData(document.getElementById('answerForm'));
        data.append('device_id', DEVICE_ID);

        fetch('{{ route("student.exam.submit", $exam) }}', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: data
        })
        .then(res => res.json())
        .then(r => {
            if (r.redirect) {
                window.location.href = r.redirect;
                return;
            }
            if (r.message) {
                alert(r.message);
            }
        })
        .catch(() => alert('Server error.'));
    };

    /* ================= DATA ================= */
        const submitBtn = document.getElementById('submitBtn');
        const minSubmitSeconds = Number({{ $minSubmit ?? 0 }});
        const startedAt = Number({{ \Carbon\Carbon::parse($session->started_at)->timestamp }});

        /* ================= MIN SUBMIT LOCK ================= */
        function checkMinSubmit() {
            if (minSubmitSeconds <= 0) {
                submitBtn.disabled = false;
                return;
            }

            const now = Math.floor(Date.now() / 1000);
            const elapsed = now - startedAt;

            if (elapsed >= minSubmitSeconds) {
                submitBtn.disabled = false;
                submitBtn.innerText = 'Kirim Jawaban';
            } else {
                submitBtn.disabled = true;
                const remain = Math.ceil((minSubmitSeconds - elapsed) / 60);
                submitBtn.innerText = `Kirim (${remain} menit lagi)`;
            }
        }

        // cek tiap detik
        setInterval(checkMinSubmit, 1000);

        // cek awal
        checkMinSubmit();

});
</script>

<style>
    html, body {
        margin: 0;
        height: 100%;
        font-family: system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
        background: #000;
    }
    
    :root {
        --theme: {{ session('school_brand.theme') ?? '#2563eb' }};
    }
</style>

</body>
</html>
