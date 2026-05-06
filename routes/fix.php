<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Route;

Route::get('/fix-database', function () {
    try {
        $messages = [];
        
        // 1. Cek midtrans_id
        if (!Schema::hasColumn('orders', 'midtrans_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('midtrans_id')->nullable()->after('snap_token');
            });
            $messages[] = "Kolom 'midtrans_id' berhasil ditambahkan.";
        }

        // 2. Cek ongkir
        if (!Schema::hasColumn('orders', 'ongkir')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->integer('ongkir')->default(0)->after('total_harga');
            });
            $messages[] = "Kolom 'ongkir' berhasil ditambahkan.";
        }

        // 3. Cek kabupaten
        if (!Schema::hasColumn('orders', 'kabupaten')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('kabupaten')->nullable()->after('alamat_pengiriman');
            });
            $messages[] = "Kolom 'kabupaten' berhasil ditambahkan.";
        }
        
        if (empty($messages)) {
            return "Database sudah dalam kondisi terbaru.";
        }
        
        return implode("<br>", $messages);
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

Route::get('/fix-pengirim', function () {
    try {
        // 1. Hapus akun 'pengirim' baru yang mungkin tadi sempat terbuat (agar tidak bentrok)
        \App\Models\User::where('username', 'pengirim')->where('name', '!=', 'damar')->delete();

        // 2. Cari akun 'damar' dan ubah identitasnya
        $user = \App\Models\User::where('username', 'damar')->first();
        
        if ($user) {
            $user->update([
                'name' => 'Pengirim Kurir',
                'username' => 'pengirim',
                'password' => 'pengirim123', 
                'role' => 'pengirim'
            ]);
            $status = "Akun 'damar' BERHASIL diubah menjadi 'pengirim'!";
        } else {
            \App\Models\User::updateOrCreate(
                ['username' => 'pengirim'],
                [
                    'name' => 'Pengirim Kurir',
                    'phone' => '081234567890',
                    'city' => 'Kupang',
                    'password' => 'pengirim123',
                    'role' => 'pengirim'
                ]
            );
            $status = "Akun 'pengirim' baru telah dibuat!";
        }

        return "<h3>$status</h3>
                Username: <b style='color:green'>pengirim</b><br>
                Password: <b style='color:green'>pengirim123</b><br><br>
                <a href='/login' style='padding:10px 20px; background:brown; color:white; text-decoration:none; border-radius:5px;'>KLIK DISINI UNTUK LOGIN</a>";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

Route::get('/debug-db', function () {
    try {
        $db = \Illuminate\Support\Facades\DB::connection();
        $host = config('database.connections.mysql.host');
        $dbName = $db->getDatabaseName();
        $count = \App\Models\Order::count();
        $latest = \App\Models\Order::latest()->first();

        return "<h3>HASIL DIAGNOSA DATABASE:</h3>
                <b>Host:</b> $host <br>
                <b>Database:</b> $dbName <br>
                <b>Total Pesanan:</b> $count <br>
                <b>ID Pesanan Terakhir:</b> " . ($latest->id ?? 'Tidak Ada') . " <br><br>
                <i>Jika Host bukan 127.0.0.1, berarti data Anda masuk ke Hosting (Online).</i>";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

Route::get('/debug-columns', function () {
    try {
        $columns = \Illuminate\Support\Facades\Schema::getColumnListing('orders');
        return "<h3>DAFTAR KOLOM TABEL ORDERS:</h3><ol><li>" . implode("</li><li>", $columns) . "</li></ol>";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

Route::get('/cleanup-database', function () {
    try {
        if (\Illuminate\Support\Facades\Schema::hasColumn('orders', 'bukti_pembayaran')) {
            \Illuminate\Support\Facades\Schema::table('orders', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->dropColumn('bukti_pembayaran');
            });
            return "Kolom 'bukti_pembayaran' BERHASIL dihapus. Database sekarang 100% bersih!";
        }
        return "Kolom 'bukti_pembayaran' sudah tidak ada.";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});