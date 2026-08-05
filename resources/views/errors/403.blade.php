<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak | E-Layanan APTIKA Jombang</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@700;900&family=Plus+Jakarta+Sans:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #0f2c59 0%, #1e3a8a 60%, #0369a1 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .card {
            background: #fff;
            border-radius: 24px;
            padding: 3rem 2.5rem;
            max-width: 480px;
            width: 100%;
            text-align: center;
            box-shadow: 0 25px 60px rgba(0,0,0,0.3);
        }
        .icon-wrap {
            width: 90px; height: 90px;
            border-radius: 24px;
            background: linear-gradient(135deg, #fee2e2, #fca5a5);
            display: flex; align-items: center; justify-content: center;
            font-size: 2.8rem; color: #dc2626;
            margin: 0 auto 1.5rem;
        }
        h1 { font-family: 'Outfit', sans-serif; font-size: 3rem; font-weight: 900; color: #dc2626; margin-bottom: 0.25rem; }
        h2 { font-family: 'Outfit', sans-serif; font-size: 1.4rem; font-weight: 700; color: #1e293b; margin-bottom: 0.75rem; }
        p  { color: #64748b; font-size: 0.9rem; line-height: 1.6; margin-bottom: 1.5rem; }
        .badge {
            display: inline-block;
            background: #fef3c7; color: #92400e;
            padding: 6px 16px; border-radius: 99px;
            font-size: 0.78rem; font-weight: 700;
            margin-bottom: 2rem;
        }
        .btn {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 10px 24px; border-radius: 10px;
            font-size: 0.875rem; font-weight: 700;
            text-decoration: none; margin: 0 4px;
            transition: opacity .2s;
        }
        .btn:hover { opacity: 0.85; }
        .btn-primary { background: #0f2c59; color: #fff; }
        .btn-light   { background: #f1f5f9; color: #475569; }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon-wrap"><i class="fa-solid fa-shield-halved"></i></div>
        <h1>403</h1>
        <h2>Akses Ditolak!</h2>
        <span class="badge"><i class="fa-solid fa-triangle-exclamation me-1"></i> Hak Akses Tidak Mencukupi</span>
        <p>
            Anda tidak memiliki izin untuk mengakses halaman ini.<br>
            Halaman tersebut hanya dapat diakses oleh role tertentu dalam sistem E-Layanan APTIKA.
        </p>
        @auth
            <p style="font-size:0.8rem; color:#94a3b8;">
                Anda login sebagai: <strong style="color:#0f2c59;">{{ Auth::user()->name }}</strong><br>
                Role aktif: <strong style="color:#059669; text-transform:uppercase;">{{ Auth::user()->role }}</strong>
            </p>
        @endauth
        <div style="margin-top: 1.5rem;">
            <a href="{{ url()->previous() }}" class="btn btn-light">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('home') }}" class="btn btn-primary">
                <i class="fa-solid fa-house"></i> Beranda
            </a>
        </div>
    </div>
</body>
</html>
