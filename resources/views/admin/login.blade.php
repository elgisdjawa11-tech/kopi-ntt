<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Panel | Kopi NTT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --coffee-dark: #2c1810;
            --coffee-primary: #3e2723;
            --accent-gold: #d4a373;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), 
                        url('https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?q=80&w=1500');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 30px;
            padding: 50px 40px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.3);
            border: 1px solid rgba(255,255,255,0.2);
            position: relative;
            overflow: hidden;
        }

        .login-card::before {
            content: "";
            position: absolute;
            top: -50px;
            right: -50px;
            width: 100px;
            height: 100px;
            background: var(--accent-gold);
            opacity: 0.1;
            border-radius: 50%;
        }

        .brand-logo {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            color: var(--coffee-dark);
            letter-spacing: 2px;
            margin-bottom: 5px;
        }

        .brand-subtitle {
            font-size: 0.8rem;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 40px;
        }

        .form-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--coffee-primary);
            margin-left: 5px;
        }

        .input-group-text {
            background: transparent;
            border-right: none;
            border-radius: 15px 0 0 15px;
            color: #aaa;
        }

        .form-control {
            border-radius: 0 15px 15px 0;
            padding: 12px;
            border-left: none;
            font-size: 0.9rem;
            background: transparent;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #dee2e6;
        }

        .input-group:focus-within .input-group-text,
        .input-group:focus-within .form-control {
            border-color: var(--accent-gold);
            color: var(--coffee-dark);
        }

        .btn-coffee {
            background: var(--coffee-primary);
            color: white;
            border-radius: 15px;
            width: 100%;
            padding: 14px;
            font-weight: 600;
            font-size: 0.9rem;
            border: none;
            transition: all 0.3s ease;
            margin-top: 20px;
            letter-spacing: 1px;
        }

        .btn-coffee:hover {
            background: var(--coffee-dark);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(62, 39, 35, 0.3);
            color: white;
        }

        .alert {
            border-radius: 15px;
            font-size: 0.8rem;
            border: none;
            background: #fff5f5;
            color: #e53e3e;
        }

        .footer-text {
            text-align: center;
            margin-top: 30px;
            font-size: 0.8rem;
            color: rgba(255,255,255,0.7);
        }

        .back-home {
            color: var(--accent-gold);
            text-decoration: none;
            font-weight: 600;
            transition: 0.2s;
        }

        .back-home:hover {
            color: white;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="login-card text-center">
            <div class="brand-logo">KOPI <span style="color: var(--accent-gold)">NTT</span></div>
            <div class="brand-subtitle">Internal Panel</div>
            
            @if(session('error'))
                <div class="alert alert-danger mb-4 shadow-sm">
                    <i class="bi bi-exclamation-circle-fill me-2"></i> {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                
                {{-- UPDATE: Username Input --}}
                <div class="text-start mb-3">
                    <label class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="username" class="form-control" placeholder="Masukkan username" required autofocus>
                    </div>
                </div>

                <div class="text-start mb-4">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-coffee">
                    LOG IN <i class="bi bi-arrow-right-short ms-1"></i>
                </button>
            </form>
        </div>

        <div class="footer-text">
            &copy; {{ date('Y') }} Kopi NTT Premium. 
            <br>
            <a href="{{ route('home') }}" class="back-home mt-2 d-inline-block">
                <i class="bi bi-house-door me-1"></i> Kembali ke Katalog
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>