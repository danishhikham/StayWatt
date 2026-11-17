# StayWatt

Estimasi konsumsi listrik, biaya, dan emisi CO₂e untuk penghuni kos.
Stack: **PHP 8 (7.4+)** native (tanpa framework/Composer), PDO MySQL/MariaDB (XAMPP), view PHP (SSR), Tailwind (CSS sudah dibangun), Chart.js (lokal, hanya di Dashboard).
Fitur, tampilan, dan isi sama dengan versi Node.js sebelumnya.

## Menjalankan (XAMPP)
1. Buka XAMPP Control Panel, **Start** **MySQL**. (Apache hanya perlu bila memakai cara B.)
2. Salin `.env.example` menjadi `.env` (default XAMPP: user `root`, password kosong, port 3306).
3. Buat database + data contoh: `php scripts/init-db.php` lalu `php seed.php`
   (atau `npm run setup`). Alternatif: impor `schema.sql` lewat phpMyAdmin, lalu `php seed.php`.
4. Jalankan, pilih salah satu:
   - **A. Server bawaan PHP**: `php scripts/serve.php` (atau `npm start`), buka http://localhost:3000
   - **B. Apache XAMPP**: taruh folder ini di `htdocs/staywatt`, buka http://localhost/staywatt/
     (butuh `mod_rewrite`, aktif secara bawaan di XAMPP). Lokasi aplikasi dideteksi otomatis; bila keliru, isi `BASE_URL` di `.env`.

Akun contoh: `rani@staywatt.local` / `rani12345` (pengguna), `admin@staywatt.local` / `admin12345` (admin).
`php seed.php` mengosongkan semua tabel lalu mengisi ulang.

Ekstensi PHP yang dipakai: `pdo_mysql`, `mbstring` (keduanya aktif di XAMPP).

## Struktur singkat
- `public/index.php`: pintu masuk web; `server.php`: sesi, tema, CSRF, routing, galat (pengganti `server.js`).
- `routes/`: `auth.php`, `app.php`, `admin.php`.
- `lib/hitung.php`: rumus tunggal (kWh, biaya, CO₂e); dipakai form peralatan (`/api/estimasi`), dashboard, rekomendasi, dan simulasi (`/api/simulasi`).
- `lib/data.php`: agregasi + cache selama satu permintaan (dibersihkan saat peralatan/pemakaian/konfigurasi berubah).
- `lib/rekomendasi.php`: aturan dari tabel `rekomendasi_aturan`. Format kolom `kondisi`: `nama~AC;porsi>=30;kurang=1` (`kurang` = jam yang dikurangi). Teks `saran` boleh memakai `{nama} {porsi} {jam} {kurang}`.
- `views/partials`: komponen yang dipakai ulang; token desain di `src/input.css` + `tailwind.config.js`.
- Tema gelap/terang disimpan di cookie `tema` (tanpa JS).

## Mengubah tampilan (opsional)
`public/css/app.css` sudah jadi. Bila mengubah kelas Tailwind di `views/`, jalankan `npm install` lalu `npm run build`.

## Menjalankan lewat VS Code (Windows + XAMPP)
1. Install **XAMPP** (PHP 8.x) dan **VS Code**. Buka XAMPP Control Panel, **Start** modul **MySQL**.
2. Di VS Code: **File → Open Folder…** pilih folder `staywatt`. Klik **Install** pada pop-up ekstensi yang direkomendasikan (PHP Intelephense, PHP Debug, Tailwind).
3. Bila XAMPP tidak di `C:\xampp`, ubah path di `.vscode/settings.json` (dua baris `php...executablePath` dan `PATH`).
4. Tutup terminal lama, buka terminal baru (**Ctrl+`**), ketik `php -v`. Harus muncul versi PHP.
5. **Ctrl+Shift+P → Tasks: Run Task → Setup lengkap (.env + database + seed)**. Tunggu sampai muncul "Seed selesai".
6. Jalankan server: **Ctrl+Shift+B** (task *Jalankan StayWatt*), atau tekan **F5** (server + debug, butuh Xdebug). Buka http://localhost:3000.
7. Login: `rani@staywatt.local` / `rani12345`, atau admin `admin@staywatt.local` / `admin12345`.
8. Berhenti: klik di terminal lalu **Ctrl+C**.
