@extends('layouts.super_admin')

@section('content')
<h2>Monitoring Global</h2>

<ul>
    <li>Total Sekolah: <strong>{{ $totalSchools }}</strong></li>
    <li>Total Siswa: <strong>{{ $totalStudents }}</strong></li>
    <li>Ujian Sedang Berlangsung: <strong>{{ $activeSessions }}</strong></li>
</ul>
@endsection
