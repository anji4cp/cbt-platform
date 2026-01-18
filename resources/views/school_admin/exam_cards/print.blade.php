<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Kartu Ujian</title>

<style>
@page {
    size: A4;
    margin: 10mm;
}

body {
    font-family: Arial, sans-serif;
    font-size: 11px;
}

.page {
    display: grid;
    grid-template-columns: 1fr 1fr;
    grid-template-rows: repeat(5, 1fr);
    gap: 8mm;
}

.card {
    border: 1px solid #000;
    padding: 8mm;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

/* ===== KOP ===== */
.kop {
    display: flex;
    align-items: center;
    gap: 8px;
}

.kop img {
    width: 40px;
}

.kop-text {
    flex: 1;
    text-align: center;
}

.kop-text .instansi {
    font-weight: bold;
    text-transform: uppercase;
}

.kop-text .school {
    font-weight: bold;
    margin-top: 2px;
}

.line {
    border-top: 1px solid #000;
    margin: 4px 0 6px;
}

.title {
    text-align: center;
    font-weight: bold;
    margin-bottom: 6px;
}

/* ===== ISI ===== */
.row {
    margin-bottom: 3px;
}

/* ===== TTD KANAN ===== */
.footer {
    display: flex;
    justify-content: space-between;
    margin-top: 8px;
}

.ttd {
    text-align: right;
}
</style>
</head>
<body>

<div class="page">
@foreach($students as $student)
    <div class="card">

        <!-- KOP -->
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

            <div class="row">Nama : {{ $student->name }}</div>
            <div class="row">Kelas : {{ $student->class }}</div>
            <div class="row">Username : {{ $student->username }}</div>

            @if($config['showPass'])
                <div class="row">
                    Password : {{ $student->exam_password ?? '-' }}
                </div>
            @endif
        </div>

        <!-- FOOTER KANAN -->
        <div class="footer">
            <div></div>

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
window.onload = () => window.print();
</script>

</body>
</html>
