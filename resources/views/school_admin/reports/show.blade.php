@extends('layouts.school_admin')

@section('content')
<h2>Hasil Ujian: {{ $exam->subject }}</h2>

<p>
Rata-rata: {{ number_format($avg, 2) }} |
Tertinggi: {{ $max }} |
Terendah: {{ $min }}
</p>

<table border="1" cellpadding="5">
<tr>
    <th>Nama Siswa</th>
    <th>Nilai</th>
    <th>Mulai</th>
    <th>Submit</th>
</tr>

@foreach($sessions as $s)
<tr>
    <td>{{ $s->student->name ?? '-' }}</td>
    <td>{{ $s->score ?? '-' }}</td>
    <td>{{ $s->started_at }}</td>
    <td>{{ $s->submitted_at }}</td>
</tr>
@endforeach
</table>
@endsection

