<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Token Ujian</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #2563eb, #1e3a8a);
            font-family: system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .card {
            background: #fff;
            width: 100%;
            max-width: 380px;
            padding: 28px;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0,0,0,.2);
        }

        .title {
            text-align: center;
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .subtitle {
            text-align: center;
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 24px;
        }

        label {
            font-size: 13px;
            font-weight: 500;
            color: #374151;
        }

        input {
            width: 100%;
            padding: 12px 1px;
            border-radius: 10px;
            border: 1px solid #d1d5db;
            font-size: 15px;
            margin-top: 6px;
            outline: none;
            letter-spacing: 2px;
            text-align: center;
        }

        input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,.15);
        }

        button {
            width: 100%;
            margin-top: 20px;
            padding: 12px;
            border-radius: 10px;
            border: none;
            background: #2563eb;
            color: #fff;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
        }

        button:hover {
            background: #1d4ed8;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
            font-size: 13px;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 14px;
            text-align: center;
        }

        .footer {
            text-align: center;
            font-size: 11px;
            color: #9ca3af;
            margin-top: 18px;
        }
    </style>
</head>
<body>

<div class="card">

    <div class="title">Token Ujian</div>
    <div class="subtitle">
        Masukkan token untuk mulai mengerjakan ujian
    </div>

    @error('token')
        <div class="error">
            {{ $message }}
        </div>
    @enderror

    <form method="POST" action="{{ route('student.exam.token', $exam->id) }}">
        @csrf

        <label>Token Ujian</label>
        <input
            type="text"
            name="token"
            placeholder="••••••"
            required
            autofocus>

        <button type="submit">
            Mulai Ujian
        </button>
    </form>

    <div class="footer">
        © {{ date('Y') }} CBT Platform
    </div>

</div>

</body>
</html>
