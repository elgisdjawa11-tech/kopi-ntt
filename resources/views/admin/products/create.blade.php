<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk Premium | Kopi NTT Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --coffee-dark: #3e2723;
            --coffee-brown: #5d4037;
            --coffee-light: #fdfaf7; /* Krem Muda/Latte */
            --accent-gold: #d4a373;
            --text-muted: #8d6e63;
        }

        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: var(--coffee-light); 
            color: var(--coffee-dark);
            background-image: url('https://www.transparenttextures.com/patterns/pinstriped-suit.png'); /* Subtle texture */
        }

        .heading-font { font-family: 'Playfair Display', serif; }

        .main-container { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem 0; }

        .card-estetik { 
            border-radius: 30px; 
            border: 1px solid rgba(141, 110, 99, 0.2); 
            box-shadow: 0 20px 60px rgba(62, 39, 35, 0.1); 
            background: #fff;
            overflow: hidden;
        }

        .card-header-coffee {
            background-color: var(--coffee-dark);
            color: #fff;
            padding: 2rem 3rem;
            border-bottom: 4px solid var(--accent-gold);
        }

        .card-body-estetik { padding: 3rem; }

        /* Styling Input Form */
        .form-label-coffee { color: var(--coffee-brown); font-weight: 500; font-size: 0.9rem; margin-bottom: 0.5rem; }
        
        .form-control-coffee { 
            border-radius: 15px; 
            border: 2px solid #e0e0e0; 
            padding: 0.8rem 1.2rem; 
            transition: all 0.3s ease;
            color: var(--coffee-dark);
        }
        .form-control-coffee:focus { 
            border-color: var(--accent-gold); 
            box-shadow: 0 0 0 4px rgba(212, 163, 115, 0.15); 
        }

        .input-group-text-coffee {
            background-color: #eee;
            border: 2px solid #e0e0e0;
            border-right: none;
            color: var(--coffee-brown);
            border-radius: 15px 0 0 15px;
            font-weight: 600;
        }
        .form-control-coffee.has-prefix { border-radius: 0 15px 15px 0; }

        /* Styling Select */
        .form-select-coffee { 
            border-radius: 15px; 
            border: 2px solid #e0e0e0; 
            padding: 0.8rem 1.2rem; 
            color: var(--coffee-dark);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%235d4037' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
        }
        .form-select-coffee:focus { border-color: var(--accent-gold); box-shadow: 0 0 0 4px rgba(212, 163, 115, 0.15); }

        /* Styling File Input */
        .file-input-wrapper {
            position: relative;
            border: 2px dashed #d0d0d0;
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            background-color: #fafafa;
            transition: 0.3s;
            cursor: pointer;
        }
        .file-input-wrapper:hover { border-color: var(--accent-gold); background-color: rgba(212, 163, 115, 0.05); }
        .file-input-icon { font-size: 2rem; color: var(--accent-gold); mb-2; d-block; }
        .file-input-text { color: var(--text-muted); font-size: 0.85rem; }
        .real-file-input { position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; }

        /* Tombol */
        .btn-gold { 
            background-color: var(--accent-gold); 
            color: var(--coffee-dark); 
            border-radius: 50px; 
            padding: 1rem 2rem; 
            font-weight: 600; 
            border: none; 
            transition: 0.4s; 
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .btn-gold:hover { background-color: var(--coffee-dark); color: #fff; transform: translateY(-3px); box-shadow: 0 10px 20px rgba(62, 39, 35, 0.2); }

        .btn-outline-coffee {
            color: var(--accent-gold);
            border: 2px solid var(--accent-gold);
            border-radius: 50px;
            font-weight: 500;
            transition: 0.3s;
        }
        .btn-outline-coffee:hover {
            background-color: var(--accent-gold);
            color: var(--coffee-dark);
        }

        /* Alert Estetik */
        .alert-coffee {
            background-color: #fff3e0;
            border: none;
            border-left: 5px solid #ff9800;
            color: #e65100;
            border-radius: 15px;
        }
    </style>
</head>
<body>

<div class="main-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9 col-xl-8">
                <div class="card-estetik">
                    
                    <div class="card-header-coffee d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-uppercase small" style="color: var(--accent-gold); letter-spacing: 3px;">Administrator Panel</span>
                            <h1 class="heading-font fw-bold m-0 mt-1">Tambah Koleksi <span style="color: var(--accent-gold)">Premium</span></h1>
                        </div>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-coffee btn-sm px-4">
                            <i class="bi bi-arrow-left me-2"></i>Katalog
                        </a>
                    </div>

                    <div class="card-body-estetik bg-white">
                        @if ($errors->any())
                            <div class="alert alert-coffee alert-dismissible fade show shadow-sm mb-5" role="alert">
                                <h6 class="fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i>Mohon Periksa Kembali:</h6>
                                <ul class="mb-0 small">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="mb-4">
                                <label class="form-label-coffee">Nama Varian Kopi</label>
                                <input type="text" name="nama_kopi" class="form-control-coffee form-control" placeholder="Contoh: Arabika Bajawa 'Blue Flores'" value="{{ old('nama_kopi') }}" required>
                            </div>

                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label-coffee">Daerah Asal (Terroir)</label>
                                    <input type="text" name="daerah_asal" class="form-control-coffee form-control" placeholder="Contoh: Ngada, Flores NTT" value="{{ old('daerah_asal') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-coffee">Tingkat Sangrai (Roast Level)</label>
                                    <select name="tingkat_sangrai" class="form-select-coffee form-select" required>
                                        <option value="" disabled selected>Pilih Tingkat Sangrai</option>
                                        <option value="Light Roast" {{ old('tingkat_sangrai') == 'Light Roast' ? 'selected' : '' }}>Light Roast (Asam, Fruit)</option>
                                        <option value="Medium Roast" {{ old('tingkat_sangrai') == 'Medium Roast' ? 'selected' : '' }}>Medium Roast (Balanced)</option>
                                        <option value="Dark Roast" {{ old('tingkat_sangrai') == 'Dark Roast' ? 'selected' : '' }}>Dark Roast (Pahit, Bold)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label-coffee">Harga Jual (per 250gr)</label>
                                    <div class="input-group">
                                        <span class="input-group-text-coffee">Rp</span>
                                        <input type="number" name="harga" class="form-control-coffee form-control has-prefix" placeholder="95000" value="{{ old('harga') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-coffee">Stok Awal di Gudang</label>
                                    <input type="number" name="stok" class="form-control-coffee form-control" placeholder="100" value="{{ old('stok') }}" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label-coffee">Deskripsi Produk & Notes Rasa</label>
                                <textarea name="deskripsi" class="form-control-coffee form-control" rows="5" placeholder="Jelaskan aroma, body, acidity, dan aftertaste yang unik dari kopi ini..." required>{{ old('deskripsi') }}</textarea>
                            </div>

                            <div class="mb-5">
                                <label class="form-label-coffee">Foto Produk (Estetik)</label>
                                <div class="file-input-wrapper">
                                    <i class="bi bi-camera-fill file-input-icon"></i>
                                    <span class="fw-bold d-block text-dark mb-1">Klik atau Seret Gambar ke Sini</span>
                                    <span class="file-input-text">Format: JPG, PNG (Max. 2MB)</span>
                                    <input type="file" name="foto" class="real-file-input" required id="fotoInput">
                                </div>
                                <div id="file-name-preview" class="text-center mt-2 small text-success fw-bold"></div>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn-gold btn w-100 shadow">
                                    <i class="bi bi-cup-hot-fill me-2"></i> Liris Produk ke Katalog
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <p class="text-center small mt-5" style="color: var(--text-muted);">
                    &copy; 2026 Crafted with Passion for TA - Eulogius Jawa (22120068)
                </p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Script sederhana untuk menampilkan nama file yang dipilih
    document.getElementById('fotoInput').addEventListener('change', function(e){
        var fileName = e.target.files[0].name;
        document.getElementById('file-name-preview').innerHTML = '<i class="bi bi-file-earmark-check me-1"></i> File terpilih: ' + fileName;
    });
</script>
</body>
</html>