<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Kartu Peserta Ujian</title>

<style>
/* ================= PAGE SETUP ================= */
@page {
    size: A4;
    margin: 15mm 15mm 18mm 15mm; /* 🔥 TAMBAH JARAK ATAS & SAMPING */
}

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    padding: 2mm; /* 🔥 BUFFER TAMBAHAN BIAR AMAN */
    font-family: Arial, Helvetica, sans-serif;
    font-size: 11px;
    color: #000;
}

/* ================= GRID HALAMAN ================= */
.page {
    display: grid;
    grid-template-columns: 1fr 1fr;
    grid-template-rows: repeat(5, 1fr);
    gap: 9mm; /* 🔥 JARAK ANTAR KARTU LEBIH LEGA */
}

/* ================= CARD ================= */
.card {
    border: 1px solid #000;
    padding: 7mm; /* 🔥 ISI TIDAK MEPET */
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

/* ================= KOP ================= */
.kop {
    display: flex;
    align-items: center;
    gap: 8px;
}

.kop img {
    width: 38px;
    height: 38px;
    object-fit: contain;
}

.kop-text {
    flex: 1;
    text-align: center;
}

.kop-text .instansi {
    font-weight: bold;
    text-transform: uppercase;
    font-size: 11px;
}

.kop-text .school {
    font-weight: bold;
    font-size: 12px;
    margin-top: 2px;
}

/* GARIS */
.line {
    border-top: 1px solid #000;
    margin: 5px 0 7px;
}

/* JUDUL */
.title {
    text-align: center;
    font-weight: bold;
    margin-bottom: 7px;
    text-transform: uppercase;
}

/* ================= ISI ================= */
.row {
    margin-bottom: 3px;
    line-height: 1.35;
}

/* ================= FOOTER ================= */
.footer {
    display: flex;
    justify-content: flex-end;
    margin-top: 10px;
}

.ttd {
    text-align: right;
    font-size: 10px;
}

/* ================= PRINT FIX ================= */
@media print {
    body {
        margin: 0;
    }
}
</style>
</head>
<body>

<div class="page">
@foreach($students as $student)
    <div class="card">

        <!-- ===== HEADER ===== -->
        <div>
            <div class="kop">
                @if($config['logo'])
                    <img src="{{ asset('storage/'.$config['logo']) }}">
                @endif

                <div class="kop-text">
                    <div class="instansi">{{ $config['kop'] }}</div>
                    <div class="school">{{ $config['schoolName'] }}</div>
                </div>
            </div>

            <div class="line"></div>

            <div class="title">{{ $config['title'] }}</div>

            <div class="row"><strong>Nama</strong> : {{ $student->name }}</div>
            <div class="row"><strong>Kelas</strong> : {{ $student->class }}</div>
            <div class="row"><strong>Username</strong> : {{ $student->username }}</div>

            @if($config['showPass'])
                <div class="row">
                    <strong>Password</strong> : {{ $student->exam_password ?? '-' }}
                </div>
            @endif
        </div>

        <!-- ===== TTD ===== -->
        <div class="footer">
            <div class="ttd">
                {{ $config['date'] }}<br>
                {{ $config['position'] }}<br><br><br>
                <strong>{{ $config['signName'] }}</strong><br>
                NIP. {{ $config['nip'] }}
            </div>
        </div>

    </div>
@endforeach
</div>

<script>
window.onload = function () {
    window.print();
};
</script>

</body>
</html>
