# StayWatt

StayWatt adalah aplikasi web untuk membantu penghuni kos memahami estimasi konsumsi listrik, biaya, dan emisi karbon dioksida ekuivalen (CO₂e) dari peralatan yang digunakan.

Pengguna dapat mencatat peralatan, daya, jumlah unit, dan durasi pemakaian. Informasi tersebut ditampilkan dalam dashboard dan riwayat penggunaan, serta digunakan untuk menyusun rekomendasi penghematan dan mencoba simulasi perubahan kebiasaan.

## Fitur utama

- Mencatat dan mengelola peralatan listrik.
- Menampilkan estimasi konsumsi, biaya, dan emisi peralatan.
- Melihat ringkasan dan riwayat penggunaan listrik.
- Mendapatkan rekomendasi penghematan berbasis aturan.
- Mencoba simulasi perubahan durasi pemakaian tanpa mengubah data utama.

## Cara perhitungan

- Konsumsi listrik (kWh) = daya (W) × durasi (jam/hari) × jumlah hari × jumlah unit ÷ 1.000.
- Biaya listrik = konsumsi (kWh) × tarif listrik per kWh.
- Emisi CO₂e = konsumsi (kWh) × faktor emisi listrik.

Nilai yang ditampilkan merupakan estimasi berdasarkan data yang dimasukkan pengguna, bukan hasil pengukuran listrik secara langsung.

## Teknologi

StayWatt menggunakan PHP 8, MySQL/MariaDB, antarmuka server-rendered, Tailwind CSS, dan Chart.js. Aplikasi tersedia melalui [staywatt-production.up.railway.app](https://staywatt-production.up.railway.app/).
