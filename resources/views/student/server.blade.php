<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>CBT Sekolah</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg,#2563eb,#1e3a8a);
            font-family: system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .card {
            background: #fff;
            width: 100%;
            max-width: 420px;
            padding: 36px 34px;
            border-radius: 22px;
            box-shadow: 0 30px 60px rgba(0,0,0,.2);
            text-align: center;
        }

        .title {
            font-size: 24px;
            font-weight: 800;
            margin-bottom: 6px;
            color: #111827;
        }

        .subtitle {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 26px;
        }

        .form-group {
            text-align: center;
            margin-bottom: 18px;
        }

        label {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            display: block;
            margin-bottom: 6px;
        }

        input {
            width: 100%;
            padding: 13px 1px;
            border-radius: 12px;
            border: 1px solid #d1d5db;
            font-size: 14px;
            text-align: center;
        }

        input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,.15);
        }

        button {
            width: 100%;
            padding: 14px;
            border-radius: 14px;
            border: none;
            background: #2563eb;
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
        }

        button:hover {
            background: #1d4ed8;
        }

        .error {
            background: #fee2e2;
            border: 1px solid #fecaca;
            color: #991b1b;
            font-size: 13px;
            padding: 10px 12px;
            border-radius: 10px;
            margin-bottom: 16px;
            text-align: left;
        }

        .footer {
            font-size: 11px;
            color: #9ca3af;
            margin-top: 26px;
        }
    </style>
</head>
<body>

<div class="card">
    <div class="title">CBT Sekolah</div>
    <div class="subtitle">
        Masukkan <strong>ID Sekolah</strong> untuk melanjutkan
    </div>

    @if ($errors->any())
        <div class="error">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('student.server.set') }}">
        @csrf

        <div class="form-group">
            <label>ID Sekolah / Server ID</label>
            <input
                type="text"
                name="server_id"
                placeholder="Minta ke Pengawas atau Admin"
                required
                autofocus>
        </div>

        <button>
            Lanjutkan
        </button>
    </form>

    <div class="footer">
        © {{ date('Y') }} CBT Platform
    </div>
</div>

</body>
</html>
