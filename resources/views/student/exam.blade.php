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

/* ================= LAYOUT ================= */
.exam-wrapper {
    display: flex;
    height: 100vh;
    overflow: hidden;
}

/* ================= SOAL ================= */
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

/* OVERLAY 15% ATAS */
.pdf-overlay {
    position: absolute;
    top: 0;
    left: 0;
    height: 15%;
    width: 100%;
    background: rgba(0,0,0,0.01);
    z-index: 10;
}

/* ================= JAWABAN ================= */
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

/* SCROLL JAWABAN */
.answer-scroll {
    flex: 1;
    overflow-y: auto;
    padding: 16px;
    padding-bottom: 90px;
}

/* BARIS JAWABAN */
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

/* TOMBOL FIX */
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

/* ================= MOBILE ================= */
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

    .answer-scroll {
        padding: 12px;
    }

    /* CENTER JAWABAN */
    .answer-row {
        justify-content: center;
    }

    .number {
        width: auto;
        margin-right: 8px;
    }

    .options {
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
            Sisa Waktu: <span id="timer">--:--</span>
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
            <button onclick="submitExam()">Kirim Jawaban</button>
        </div>

    </div>
</div>

<script>
/* ================= DEVICE ================= */
if (!localStorage.getItem('cbt_device_id')) {
    localStorage.setItem('cbt_device_id', crypto.randomUUID());
}
const DEVICE_ID = localStorage.getItem('cbt_device_id');

/* ================= TIMER ================= */
let remaining = {{ (int) $remaining }};

function tick() {
    if (remaining <= 0) {
        submitExam(true);
        return;
    }
    let m = Math.floor(remaining / 60);
    let s = remaining % 60;
    document.getElementById('timer').innerText =
        String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
    remaining--;
}
setInterval(tick, 1000);

/* ================= AUTOSAVE ================= */
setInterval(() => {
    let data = new FormData(document.getElementById('answerForm'));
    data.append('device_id', DEVICE_ID);

    fetch('{{ route("student.exam.save", $exam) }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: data
    });
}, 5000);

/* ================= SUBMIT ================= */
function submitExam(force = false) {

    const now = Math.floor(Date.now() / 1000);
    const passed = now - STARTED_AT;

    if (!force && passed < MIN_SUBMIT_SECONDS) {
        const remain = Math.ceil((MIN_SUBMIT_SECONDS - passed) / 60);
        alert(`Jawaban baru bisa dikirim setelah ${remain} menit.`);
        return;
    }

    if (!force && !confirm('Yakin kirim jawaban?')) return;

    let form = document.getElementById('answerForm');
    let data = new FormData(form);
    data.append('device_id', DEVICE_ID);

    fetch('{{ route("student.exam.submit", $exam) }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: data
    })
    .then(res => res.json())
    .then(r => {
        window.location.href = r.redirect;
    });

}

</script>

<script>
/* ===== ANTI BACK TOTAL ===== */
(function () {
    history.pushState(null, null, location.href);
    window.addEventListener('popstate', function () {
        history.pushState(null, null, location.href);
    });

    // Android gesture back
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Backspace') {
            e.preventDefault();
        }
    });
})();
</script>

<script>
/* =============================
   ANTI BACK / HISTORY LOCK
   ============================= */

// paksa browser bikin banyak history palsu
(function lockHistory() {
    history.pushState(null, null, location.href);
    history.pushState(null, null, location.href);
    history.pushState(null, null, location.href);

    window.onpopstate = function () {
        history.go(1);
    };
})();

// cegah gesture back di mobile (swipe)
document.addEventListener('touchstart', function (e) {
    if (e.touches.length > 1) {
        e.preventDefault();
    }
}, { passive: false });

// cegah reload + back via keyboard
window.addEventListener('keydown', function (e) {
    if (
        e.key === 'Backspace' ||
        e.key === 'F5' ||
        (e.ctrlKey && e.key === 'r')
    ) {
        e.preventDefault();
    }
});

// peringatan kalau coba keluar halaman
window.addEventListener('beforeunload', function (e) {
    e.preventDefault();
    e.returnValue = '';
});
</script>

<script>
const MIN_SUBMIT_SECONDS = {{ $exam->min_submit_minutes * 60 }};
const STARTED_AT = {{ \Carbon\Carbon::parse($session->started_at)->timestamp }};
</script>



</body>
</html>