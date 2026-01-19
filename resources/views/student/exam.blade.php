<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Ujian CBT</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
html, body {
    margin: 0;
    height: 100%;
    font-family: system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
    background: #000;
}

/* LAYOUT */
.exam-wrapper {
    display: flex;
    height: 100vh;
    overflow: hidden;
}

/* SOAL */
.question-panel {
    position: relative;
    flex: 1;
    background: #000;
}

.pdf-frame {
    width: 100%;
    height: 100%;
    border: none;
}

.pdf-overlay {
    position: absolute;
    top: 0;
    left: 0;
    height: 15%;
    width: 100%;
    background: rgba(0,0,0,0.01);
    z-index: 10;
}

/* JAWABAN */
.answer-panel {
    width: 26%;
    display: flex;
    flex-direction: column;
    background: #fff;
    border-left: 1px solid #e5e7eb;
}

.timer {
    font-weight: 700;
    font-size: 14px;
    text-align: center;
    padding: 12px;
    border-bottom: 1px solid #e5e7eb;
}

.answer-scroll {
    flex: 1;
    overflow-y: auto;
    padding: 16px;
    padding-bottom: 90px;
}

.answer-row {
    display: flex;
    align-items: center;
    margin-bottom: 12px;
}

.number {
    width: 28px;
    font-weight: 600;
}

.options {
    display: flex;
    gap: 10px;
}

.options label {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 14px;
}

.submit-fixed {
    position: sticky;
    bottom: 0;
    background: #fff;
    padding: 12px;
    border-top: 1px solid #e5e7eb;
    text-align: center;
}

.submit-fixed button {
    width: 90%;
    max-width: 320px;
    padding: 12px;
    border-radius: 12px;
    border: none;
    background: #2563eb;
    color: #fff;
    font-weight: 600;
    font-size: 15px;
    cursor: pointer;
}

/* MOBILE */
@media (max-width: 768px) {
    .exam-wrapper {
        flex-direction: column;
    }
    .question-panel {
        height: 68vh;
    }
    .answer-panel {
        width: 100%;
        height: 35vh;
    }
    .answer-row {
        justify-content: center;
    }
}
</style>
</head>

<body>

<div class="exam-wrapper">

    <!-- ================= SOAL ================= -->
    <div class="question-panel">
        <div class="pdf-overlay"></div>
        <iframe
            src="{{ str_replace('/view?usp=drive_link', '/preview', $package->pdf_url) }}"
            class="pdf-frame"
            sandbox="allow-scripts allow-same-origin">
        </iframe>
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
document.addEventListener('DOMContentLoaded', function () {

    /* ================= DEVICE ================= */
    if (!localStorage.getItem('cbt_device_id')) {
        localStorage.setItem('cbt_device_id', crypto.randomUUID());
    }
    const DEVICE_ID = localStorage.getItem('cbt_device_id');

    /* ================= TIMER (UX ONLY) ================= */
    const timerEl = document.getElementById('timer');

    if (!timerEl) {
        alert('TIMER ELEMENT TIDAK DITEMUKAN');
        return;
    }

    let remaining = Number({{ (int) $remaining }});

    function renderTimer() {
        const m = Math.floor(remaining / 60);
        const s = remaining % 60;
        timerEl.innerText =
            String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
    }

    renderTimer(); // render awal

    setInterval(() => {
        if (remaining > 0) remaining--;
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

            if (result.status === 'blocked' || result.status === 'locked') {
                alert('Sesi ujian berakhir.');
                window.location.href = '{{ route("student.exams") }}';
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
</body>
</html>
