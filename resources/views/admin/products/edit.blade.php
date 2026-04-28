<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk | Kopi NTT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Poppins', sans-serif; }
        .card { border-radius: 15px; border: none; }
        .img-preview { width: 100px; height: 100px; object-fit: cover; border-radius: 10px; border: 2px solid #ddd; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold text-dark">Edit Data Kopi NTT</h2>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>

                <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT') {{-- WAJIB: Memberitahu Laravel ini adalah proses Update --}}
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Kopi</label>
                        <input type="text" name="nama_kopi" class="form-control" value="{{ $product->nama_kopi }}" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Daerah Asal</label>
                            <input type="text" name="daerah_asal" class="form-control" value="{{ $product->daerah_asal }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Harga (Rp)</label>
                            <input type="number" name="harga" class="form-control" value="{{ $product->harga }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Stok Tersedia</label>
                            <input type="number" name="stok" class="form-control" value="{{ $product->stok }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Tingkat Sangrai</label>
                            <input type="text" name="tingkat_sangrai" class="form-control" value="{{ $product->tingkat_sangrai }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="4">{{ $product->deskripsi }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold d-block">Foto Produk Saat Ini</label>
                        <img src="{{ asset('storage/'.$product->foto) }}" class="img-preview mb-2" alt="Foto Kopi">
                        <input type="file" name="foto" class="form-control">
                        <div class="form-text text-danger">*Kosongkan jika tidak ingin mengganti foto.</div>
                    </div>

                    <button type="submit" class="btn btn-warning w-100 py-2 fw-bold text-dark shadow-sm">
                        <i class="bi bi-arrow-repeat"></i> Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>