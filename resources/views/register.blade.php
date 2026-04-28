<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pelanggan | Kopi NTT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?q=80&w=1500');
            background-size: cover; background-position: center;
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            padding: 20px 0;
        }
        .register-card {
            background: white; border-radius: 20px; padding: 40px;
            width: 100%; max-width: 450px; box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }
        .btn-coffee { background: #3e2723; color: white; border-radius: 50px; padding: 10px; transition: 0.3s; border: none; }
        .btn-coffee:hover { background: #d4a373; color: white; }
        .form-control { border-radius: 10px; padding: 10px; font-size: 0.9rem; }
        .form-label { margin-bottom: 0.3rem; }
    </style>
</head>
<body>

    <div class="register-card">
        <div class="text-center mb-4">
            <h3 class="fw-bold">Buat Akun <span style="color: #d4a373">Pelanggan</span></h3>
            <p class="text-muted small">Daftar untuk mulai belanja kopi terbaik NTT</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger small py-2">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register.post') }}" method="POST">
            @csrf
            
            {{-- Nama Lengkap --}}
            <div class="mb-2">
                <label class="form-label small fw-bold">Nama Lengkap</label>
                <input type="text" name="name" class="form-control" placeholder="Masukkan nama Anda" value="{{ old('name') }}" required>
            </div>

            {{-- Username --}}
            <div class="mb-2">
                <label class="form-label small fw-bold">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Buat username unik" value="{{ old('username') }}" required>
            </div>

            {{-- UPDATE REVISI: Nomor WhatsApp --}}
            <div class="mb-2">
                <label class="form-label small fw-bold">Nomor WhatsApp</label>
                <input type="number" name="phone" class="form-control" placeholder="Contoh: 08123456789" value="{{ old('phone') }}" required>
            </div>

            {{-- UPDATE REVISI: Kota Asal --}}
            <div class="mb-3">
                <label class="form-label small fw-bold">Kota / Alamat</label>
                <input type="text" name="city" class="form-control" placeholder="Contoh: Kupang, NTT" value="{{ old('city') }}" required>
            </div>

            <hr>

            {{-- Password --}}
            <div class="mb-2">
                <label class="form-label small fw-bold">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" required>
            </div>

            {{-- Konfirmasi Password --}}
            <div class="mb-4">
                <label class="form-label small fw-bold">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
            </div>

            <button type="submit" class="btn btn-coffee w-100 fw-bold shadow-sm">DAFTAR SEKARANG</button>
            
            <div class="text-center mt-3">
                <p class="small">Sudah punya akun? <a href="{{ route('login') }}" class="text-decoration-none fw-bold" style="color: #3e2723;">Masuk di sini</a></p>
            </div>
        </form>
    </div>

</body>
</html>