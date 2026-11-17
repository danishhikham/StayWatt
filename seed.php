<?php
// php seed.php  -> mengosongkan semua tabel lalu mengisi data contoh (sesuai mockup).
require __DIR__ . '/lib/boot.php';

$BASE = [ // nama, kategori, daya W, unit, jam/hari
    ['AC ½ PK', 'Pendingin', 350, 1, 8], ['Kulkas mini', 'Elektronik', 50, 1, 24], ['Dispenser', 'Elektronik', 350, 1, 3],
    ['Laptop', 'Elektronik', 65, 1, 6], ['Kipas angin', 'Pendingin', 45, 1, 6], ['Lampu LED', 'Penerangan', 10, 2, 10],
];
$RIWAYAT = ['2026-06' => 168, '2026-07' => 181, '2026-08' => 176, '2026-09' => 189, '2026-10' => 183, '2026-11' => 177.3];
$REFERENSI = [['AC ½ PK', 'Pendingin', 350], ['Kipas angin', 'Pendingin', 45], ['Laptop', 'Elektronik', 65], ['Kulkas mini', 'Elektronik', 50],
    ['Lampu LED', 'Penerangan', 10], ['Dispenser', 'Elektronik', 350], ['Rice cooker', 'Elektronik', 400], ['Setrika', 'Elektronik', 300]];
$ATURAN = [
    ['Pendingin', 'nama~AC;porsi>=30;kurang=1', 'AC menyumbang {porsi}% konsumsimu ({jam} jam/hari). Kurangi {kurang} jam per hari.'],
    ['Elektronik', 'nama~Dispenser;jam>=2;kurang=1', '{nama} menyala {jam} jam/hari. Matikan saat tidak dipakai, kurangi {kurang} jam.'],
    ['Penerangan', 'jam>=8;kurang=2', 'Dinyalakan {jam} jam/hari. Matikan {kurang} jam saat tidak di kamar.'],
];

try {
    $c = db();
    $c->exec('SET FOREIGN_KEY_CHECKS = 0');
    foreach (['pemakaian', 'peralatan', 'rekomendasi_aturan', 'referensi_peralatan', 'kategori_peralatan', 'konfigurasi_emisi_tarif', 'users'] as $t) $c->exec("TRUNCATE TABLE $t");
    $c->exec('SET FOREIGN_KEY_CHECKS = 1');

    $ins = function (string $sql, array $p) use ($c): int {
        $c->prepare($sql)->execute($p);
        return (int)$c->lastInsertId();
    };

    $kat = [];
    foreach (['Pendingin', 'Elektronik', 'Penerangan'] as $n) $kat[$n] = $ins('INSERT INTO kategori_peralatan (nama) VALUES (?)', [$n]);
    foreach ($REFERENSI as [$n, $k, $w]) $ins('INSERT INTO referensi_peralatan (kategori_id, nama, daya_default_w) VALUES (?,?,?)', [$kat[$k], $n, $w]);
    foreach ($ATURAN as [$k, $kond, $saran]) $ins('INSERT INTO rekomendasi_aturan (kategori_id, kondisi, saran) VALUES (?,?,?)', [$kat[$k], $kond, $saran]);
    $ins('INSERT INTO konfigurasi_emisi_tarif (faktor_emisi, tarif_per_kwh, sumber, berlaku_mulai) VALUES (?,?,?,?)', ['0.87', '1444.70', 'Kepmen ESDM 163.K/HK.02/MEM.S/2021', '2026-11-01']);

    $user = function (string $nama, string $email, string $pw, string $role) use ($ins): int {
        return $ins('INSERT INTO users (nama, email, password_hash, role) VALUES (?,?,?,?)', [$nama, $email, password_hash($pw, PASSWORD_BCRYPT, ['cost' => 10]), $role]);
    };
    $rani = $user('Rani', 'rani@staywatt.local', 'rani12345', 'pengguna');
    $user('Admin', 'admin@staywatt.local', 'admin12345', 'admin');

    $ids = [];
    foreach ($BASE as [$n, $k, $w, $u]) $ids[] = $ins('INSERT INTO peralatan (user_id, kategori_id, nama, daya_w, jumlah) VALUES (?,?,?,?,?)', [$rani, $kat[$k], $n, $w, $u]);

    // Kulkas selalu 24 jam; peralatan lain diskalakan agar total kWh bulanan sama dengan riwayat.
    $kulkasPerHari = 0.05 * 24; // kWh
    $lainPerHari = 0.0;
    foreach ($BASE as $b) $lainPerHari += $b[0] === 'Kulkas mini' ? 0 : ($b[2] * $b[4] * $b[3]) / 1000;
    $rows = [];
    foreach ($RIWAYAT as $p => $target) {
        $n = hariDalamBulan($p);
        $skala = ($target - $kulkasPerHari * $n) / ($lainPerHari * $n);
        for ($d = 1; $d <= $n; $d++) {
            foreach ($BASE as $i => $b) {
                $jam = $b[0] === 'Kulkas mini' ? $b[4] : $b[4] * $skala;
                $rows[] = [$ids[$i], sprintf('%s-%02d', $p, $d), number_format($jam, 6, '.', '')];
            }
        }
    }
    foreach (array_chunk($rows, 400) as $part) {
        $flat = [];
        foreach ($part as $r) array_push($flat, $r[0], $r[1], $r[2]);
        $c->prepare('INSERT INTO pemakaian (peralatan_id, tanggal, durasi_jam) VALUES ' . implode(',', array_fill(0, count($part), '(?,?,?)')))->execute($flat);
    }
    echo 'Seed selesai: ' . count($rows) . " baris pemakaian.\n";
    echo "Pengguna: rani@staywatt.local / rani12345\n";
    echo "Admin   : admin@staywatt.local / admin12345\n";
} catch (Throwable $e) {
    fwrite(STDERR, 'Seed gagal: ' . $e->getMessage() . "\n");
    exit(1);
}
