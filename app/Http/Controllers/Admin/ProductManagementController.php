<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductManagementController extends Controller
{
    /**
     * Tampilkan semua daftar kopi
     */
    public function index() {
        $products = Product::all();
        return view('admin.products.index', compact('products'));
    }

    /**
     * Form tambah kopi baru
     */
    public function create() {
        return view('admin.products.create');
    }

    /**
     * Simpan kopi baru ke database
     */
    public function store(Request $request) {
        // 1. Validasi: Pastikan semua data yang wajib di database terisi di sini
        $data = $request->validate([
            'nama_kopi'       => 'required|string|max:255',
            'daerah_asal'     => 'required|string|max:255',
            'harga'           => 'required|numeric',
            'stok'            => 'required|numeric',
            'deskripsi'       => 'required',
            'tingkat_sangrai' => 'nullable|string', // Sesuai kolom di HeidiSQL kamu
            'foto'            => 'required|image|mimes:jpg,png,jpeg|max:2048'
        ]);

        // 2. Proses upload foto ke folder storage/app/public/products
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('products', 'public');
        }

        // 3. Simpan ke database (Pastikan $fillable di Model Product.php sudah lengkap)
        Product::create($data);
        
        return redirect()->route('admin.products.index')->with('success', 'Kopi baru berhasil ditambahkan!');
    }

    /**
     * Form edit kopi
     */
    public function edit($id) {
        $product = Product::findOrFail($id);
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update data kopi yang sudah ada
     */
    public function update(Request $request, $id) {
        $product = Product::findOrFail($id);

        $data = $request->validate([
            'nama_kopi'       => 'required|string|max:255',
            'daerah_asal'     => 'required|string|max:255',
            'harga'           => 'required|numeric',
            'stok'            => 'required|numeric',
            'deskripsi'       => 'required',
            'tingkat_sangrai' => 'nullable|string',
            'foto'            => 'nullable|image|mimes:jpg,png,jpeg|max:2048'
        ]);

        // Proses ganti foto jika ada file baru yang diunggah
        if ($request->hasFile('foto')) {
            // Hapus foto lama agar tidak memenuhi memori laptop/server
            if($product->foto && Storage::disk('public')->exists($product->foto)) {
                Storage::disk('public')->delete($product->foto);
            }
            $data['foto'] = $request->file('foto')->store('products', 'public');
        }

        $product->update($data);
        
        return redirect()->route('admin.products.index')->with('success', 'Data kopi berhasil diupdate!');
    }

    /**
     * Hapus kopi (Hanya jika tidak ada riwayat transaksi)
     */
    public function destroy($id) {
        $product = Product::findOrFail($id);

        try {
            // Hapus foto dari folder storage sebelum data di DB dihapus
            if($product->foto && Storage::disk('public')->exists($product->foto)) {
                Storage::disk('public')->delete($product->foto);
            }
            
            $product->delete();
            return back()->with('success', 'Kopi berhasil dihapus!');
        } catch (\Exception $e) {
            // Jika gagal hapus karena ada di riwayat pesanan
            return back()->with('error', 'Gagal menghapus! Kopi ini sudah pernah dipesan oleh pelanggan.');
        }
    }
}