# 1. Langkah-Langkah Pengerjaan

## 1.1 Menyiapkan Project dan Database

Project Laravel disiapkan di folder `laporbanjir`. Database MySQL dibuat melalui phpMyAdmin dengan nama `db_laporbanjir`. Koneksi database diatur melalui file `.env`.

```env
APP_NAME=LaporBanjir
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_laporbanjir
DB_USERNAME=root
DB_PASSWORD=
```

Nilai `DB_USERNAME` dan `DB_PASSWORD` disesuaikan dengan konfigurasi MySQL lokal.

## 1.2 Membuat Model dan Migration Laporan

Model dan migration dapat dibuat menggunakan perintah berikut.

```bash
php artisan make:model Laporan -m
```

Migration `database/migrations/2026_10_26_065948_create_laporans_table.php` mendefinisikan struktur tabel `laporans`.

```php
Schema::create('laporans', function (Blueprint $table) {
    $table->id();
    $table->string('nama_pelapor');
    $table->string('no_hp', 20);
    $table->string('lokasi');
    $table->string('kecamatan');
    $table->unsignedInteger('tinggi_air');
    $table->text('keterangan');
    $table->string('foto');
    $table->string('status')->default('Menunggu');
    $table->timestamps();
});
```

Migration dijalankan agar tabel dibuat di database.

```bash
php artisan migrate
```

Model `app/Models/Laporan.php` mengatur kolom yang boleh disimpan secara massal.

```php
class Laporan extends Model
{
    protected $fillable = [
        'nama_pelapor', 'no_hp', 'lokasi', 'kecamatan',
        'tinggi_air', 'keterangan', 'foto', 'status',
    ];
}
```

## 1.3 Membuat Controller dan Route Resource

Resource controller dibuat dengan perintah berikut.

```bash
php artisan make:controller LaporanController --resource
```

Route warga untuk melihat form dan mengirim laporan dibiarkan publik. Halaman pengelolaan petugas dimasukkan ke middleware `auth`.

```php
Route::get('/', [LaporanController::class, 'create'])->name('laporan.create');
Route::post('/laporan', [LaporanController::class, 'store'])->name('laporan.store');

Route::middleware('auth')->group(function () {
    Route::resource('laporan', LaporanController::class)
        ->except(['create', 'store']);
});
```

## 1.4 Membuat Form Warga dan Menyimpan Foto

Form pada `resources/views/laporan/create.blade.php` memakai `POST` dan `multipart/form-data` agar berkas dapat dikirim. Field lainnya mengikuti pola input di bawah ini dan menampilkan error validasi di bawah input.

```blade
<form action="{{ route('laporan.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <label for="nama_pelapor">Nama pelapor</label>
    <input id="nama_pelapor" name="nama_pelapor" value="{{ old('nama_pelapor') }}">
    @error('nama_pelapor') <p class="error">{{ $message }}</p> @enderror

    <label for="foto">Foto bukti</label>
    <input id="foto" type="file" name="foto" accept=".jpg,.jpeg,.png">
    @error('foto') <p class="error">{{ $message }}</p> @enderror

    <button type="submit">Kirim laporan</button>
</form>
```

Controller memvalidasi data, membatasi foto ke JPG/JPEG/PNG maksimal 2 MB, menyimpan file pada disk `public`, lalu membuat record laporan.

```php
$validated = $request->validate([
    'nama_pelapor' => 'required|string|max:255',
    'no_hp' => 'required|string|max:20',
    'lokasi' => 'required|string|max:255',
    'kecamatan' => 'required|string|max:255',
    'tinggi_air' => 'required|integer|min:0',
    'keterangan' => 'required|string',
    'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
]);

$validated['foto'] = $request->file('foto')->store('foto-banjir', 'public');
$validated['status'] = 'Menunggu';
Laporan::create($validated);
```

Folder penyimpanan publik dihubungkan ke direktori web dengan perintah berikut.

```bash
php artisan storage:link
```

## 1.5 Membuat Fitur Petugas: Daftar, Detail, Ubah, Hapus

Method `index()` mengambil laporan terbaru dengan pagination. Halaman `index.blade.php` menampilkan thumbnail foto, lokasi, tinggi air, status, dan tombol aksi. Method `show()` menampilkan detail laporan pada `show.blade.php`.

Pada method `update()`, foto bersifat opsional. Jika petugas mengunggah foto baru, aplikasi menyimpan foto tersebut dan menghapus file lama. Status dibatasi ke tiga pilihan.

```php
'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
'status' => 'required|in:Menunggu,Diproses,Selesai',
```

```php
if ($request->hasFile('foto')) {
    $fotoLama = $laporan->foto;
    $validated['foto'] = $request->file('foto')->store('foto-banjir', 'public');
    Storage::disk('public')->delete($fotoLama);
}

$laporan->update($validated);
```

Method `destroy()` menghapus foto dari disk `public` dan menghapus record laporan dari database. Halaman edit menampilkan foto saat ini dan pilihan status `Menunggu`, `Diproses`, atau `Selesai`.

## 1.6 Membuat Login Petugas Manual

Autentikasi dibuat secara manual menggunakan fasilitas autentikasi Laravel. Pilihan ini diambil karena kebutuhan project hanya login, logout, dan pembatasan halaman pengelolaan; aplikasi tidak membutuhkan fitur registrasi maupun komponen UI Breeze. Controller login memeriksa kredensial dan meregenerasi sesi setelah login berhasil.

```php
if (! Auth::attempt($credentials, $request->boolean('remember'))) {
    return back()->withErrors([
        'email' => 'Email atau kata sandi tidak cocok.',
    ])->onlyInput('email');
}

$request->session()->regenerate();
return redirect()->intended(route('laporan.index'));
```

Akun petugas awal dibuat oleh seeder dan dapat dipakai untuk demonstrasi.

```text
Email       : petugas@laporbanjir.test
Kata sandi  : password
```

## 1.7 Menjalankan dan Memeriksa Aplikasi

Migration, seeder akun petugas, symbolic link storage, dan server aplikasi dijalankan dengan perintah berikut.

```bash
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Warga membuka halaman utama untuk mengirim laporan. Petugas membuka `/login`, lalu mengelola data di `/laporan`. Pemeriksaan route dilakukan dengan `php artisan route:list`.

# 2. Analisis dan Pembahasan

## 2.1 Migration dan Model

Migration membentuk tabel `laporans` yang menyimpan identitas pelapor, lokasi, tinggi air, keterangan, path foto, dan status penanganan. Nilai bawaan status adalah `Menunggu`, sehingga laporan baru langsung memiliki status awal yang jelas. Model `Laporan` menggunakan `$fillable` agar hanya kolom yang ditentukan dapat diisi melalui mass assignment.

## 2.2 Controller dan Validasi Upload

`LaporanController` menjalankan seluruh proses CRUD. Pada proses tambah, `Request::validate()` memastikan semua informasi wajib diisi dan foto memenuhi tipe serta ukuran yang ditetapkan. Ukuran `2048` pada rule Laravel berarti maksimal 2048 KB atau 2 MB. Jika validasi gagal, Laravel mengembalikan pengguna ke form dengan error untuk ditampilkan di bawah input.

File disimpan dengan `store('foto-banjir', 'public')`. Database menyimpan path relatifnya, bukan isi binary foto. Pemisahan ini memungkinkan aplikasi menampilkan file menggunakan symbolic link `public/storage`.

Pada update, foto baru bersifat opsional. File lama dihapus hanya saat ada foto pengganti. Pada delete, file terkait ikut dibersihkan supaya tidak tertinggal di storage setelah record dihapus.

## 2.3 Route dan Perlindungan Halaman

Route `create` dan `store` dapat diakses publik karena digunakan warga untuk mengirim laporan. Route resource untuk daftar, detail, edit, update, dan delete ditempatkan di dalam middleware `auth`, sehingga hanya petugas yang masuk yang dapat mengelola data laporan. Pembagian akses ini sesuai dengan peran warga dan petugas.

## 2.4 View dan Pengalaman Pengguna

View `create` menyediakan form pelaporan dan pesan validasi. View `index` menampilkan thumbnail dan pagination. View `show` menampilkan detail dan foto bukti, sementara view `edit` menyediakan penggantian foto dan perubahan status. Pesan berhasil ditampilkan setelah tambah, ubah, dan hapus. Seluruh halaman menggunakan stylesheet `public/css/laporbanjir.css`.

## 2.5 Hasil Penyimpanan

Database yang digunakan adalah `db_laporbanjir`, dengan tabel `laporans`. Saat pemeriksaan project, terdapat satu laporan tersimpan. Kolom `foto` berisi path `foto-banjir/P32oz0fOKUKvpW1vxKVRv4DrsYuCbWIb0mJ.jpg`; file tersebut ditemukan di `storage/app/public/foto-banjir`.

Untuk dokumentasi hasil di Word, sisipkan screenshot phpMyAdmin yang memperlihatkan tabel `laporans` beserta kolom `foto`, dan screenshot folder `storage/app/public/foto-banjir` yang memperlihatkan file bukti. Tambahkan juga screenshot form warga dan dashboard petugas jika diminta dosen.

# 3. Kesimpulan

Aplikasi LaporBanjir telah menerapkan CRUD laporan banjir dengan upload foto bukti. Warga dapat mengirim laporan melalui form publik, sedangkan petugas harus login untuk melihat, memperbarui status, mengubah foto, dan menghapus laporan. Foto divalidasi sebagai JPG/JPEG/PNG dengan ukuran maksimal 2 MB, disimpan pada disk `public`, dapat ditampilkan melalui symbolic link, dan dihapus dari storage ketika foto diganti atau laporan dihapus. Data laporan dan path file sudah tersimpan pada database `db_laporbanjir`. Autentikasi manual dipilih karena sesuai dengan kebutuhan project yang sederhana dan tetap menggunakan mekanisme autentikasi Laravel.
