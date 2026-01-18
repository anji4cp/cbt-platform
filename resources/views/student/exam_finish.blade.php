<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Ujian Selesai</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {
    margin: 0;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: linear-gradient(135deg,#2563eb,#1e3a8a);
    font-family: system-ui, sans-serif;
}
.card {
    background: #fff;
    width: 100%;
    max-width: 420px;
    padding: 32px;
    border-radius: 18px;
    box-shadow: 0 30px 60px rgba(0,0,0,.25);
    text-align: center;
}
.title {
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 8px;
}
.subtitle {
    font-size: 14px;
    color: #6b7280;
    margin-bottom: 20px;
}
.info {
    text-align: left;
    background: #f8fafc;
    padding: 16px;
    border-radius: 12px;
    font-size: 14px;
    margin-bottom: 20px;
}
.info div {
    margin-bottom: 6px;
}
.score {
    margin: 24px 0;
}
.score-number {
    font-size: 64px;
    font-weight: 800;
    color: #2563eb;
}
.score-detail {
    font-size: 14px;
    color: #374151;
}
.footer {
    margin-top: 24px;
    font-size: 12px;
    color: #9ca3af;
}
</style>
</head>
<body>

<div class="card">

    <div class="title">Ujian Selesai 🎉</div>
    <div class="subtitle">
        Terima kasih telah menyelesaikan ujian <strong>{{ $exam->subject }}</strong>
    </div>

    <div class="info">
        <div><strong>Nama</strong> : {{ $student->name }}</div>
        <div><strong>Kelas</strong> : {{ $student->class }}</div>
        <div><strong>Username</strong> : {{ $student->username }}</div>
    </div>

    @if($exam->show_score)
        <div class="score">
            <div class="score-number">{{ $session->score }}</div>
            <div class="score-detail">
                Benar {{ $correct }} soal · Salah {{ $wrong }} soal
            </div>
        </div>
    @else
        <div class="subtitle">
            Nilai akan diumumkan oleh pihak sekolah
        </div>
    @endif
    
    <div style="margin-top:24px">
        <a href="{{ route('student.exams') }}"
        style="
            display:inline-block;
            padding:12px 22px;
            border-radius:12px;
            background:#2563eb;
            color:#fff;
            font-weight:600;
            text-decoration:none;
            box-shadow:0 8px 20px rgba(37,99,235,.35);
        ">
            ⬅ Kembali ke Daftar Ujian
        </a>
    </div>


</div>

</body>
</html>
