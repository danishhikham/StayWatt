<?php
// Tarif & faktor emisi dibaca dari tabel konfigurasi (versi terbaru yang sudah berlaku).
function getKonfig(): array
{
    return Cache::remember('konfig', function () {
        $r = q('SELECT faktor_emisi, tarif_per_kwh, sumber, berlaku_mulai FROM konfigurasi_emisi_tarif ORDER BY (berlaku_mulai <= CURDATE()) DESC, berlaku_mulai DESC, id DESC LIMIT 1')[0] ?? null;
        if (!$r) throw new RuntimeException('Tabel konfigurasi_emisi_tarif kosong. Jalankan: php seed.php');
        return ['faktor' => (float)$r['faktor_emisi'], 'tarif' => (float)$r['tarif_per_kwh'], 'sumber' => $r['sumber'], 'berlaku' => $r['berlaku_mulai']];
    });
}

// Rekap per bulan (urut naik) lengkap dengan rincian peralatan.
function rekap(int $userId): array
{
    return Cache::remember("u{$userId}:rekap", function () use ($userId) {
        $cfg = getKonfig();
        $rows = q(
            "SELECT DATE_FORMAT(u.tanggal,'%Y-%m') AS bulan, p.id, p.nama, p.daya_w, p.jumlah, p.kategori_id, k.nama AS kategori, SUM(u.durasi_jam) AS total_jam
             FROM pemakaian u JOIN peralatan p ON p.id = u.peralatan_id JOIN kategori_peralatan k ON k.id = p.kategori_id
             WHERE p.user_id = ? GROUP BY bulan, p.id, p.nama, p.daya_w, p.jumlah, p.kategori_id, k.nama ORDER BY bulan ASC, p.id ASC",
            [$userId]
        );
        $peta = [];
        foreach ($rows as $r) {
            $bln = $r['bulan'];
            if (!isset($peta[$bln])) $peta[$bln] = ['periode' => $bln, 'hari' => hariDalamBulan($bln), 'items' => []];
            $hari = $peta[$bln]['hari'];
            $jamPerHari = (float)$r['total_jam'] / $hari;
            $k = kwh(['dayaW' => (int)$r['daya_w'], 'jamPerHari' => $jamPerHari, 'hari' => $hari, 'jumlah' => (int)$r['jumlah']]);
            $peta[$bln]['items'][] = [
                'id' => (int)$r['id'], 'nama' => $r['nama'], 'kategori' => $r['kategori'], 'kategoriId' => (int)$r['kategori_id'],
                'dayaW' => (int)$r['daya_w'], 'jumlah' => (int)$r['jumlah'], 'jamPerHari' => $jamPerHari,
                'kwh' => $k, 'biaya' => $k * $cfg['tarif'], 'emisi' => $k * $cfg['faktor'],
            ];
        }
        $out = [];
        foreach ($peta as $b) {
            usort($b['items'], function ($x, $y) {
                return ($y['kwh'] <=> $x['kwh']) ?: ($x['id'] <=> $y['id']);
            });
            $kw = 0.0;
            foreach ($b['items'] as $i) $kw += $i['kwh'];
            $b['total'] = ['kwh' => $kw, 'biaya' => $kw * $cfg['tarif'], 'emisi' => $kw * $cfg['faktor']];
            foreach ($b['items'] as $idx => $i) {
                $b['items'][$idx]['porsi'] = $kw ? ($i['kwh'] / $kw) * 100 : 0;
            }
            $out[] = $b;
        }
        usort($out, function ($a, $b) {
            return strcmp($a['periode'], $b['periode']);
        });
        return $out;
    });
}

function periodeSekarang(): string
{
    return date('Y-m');
}

function periodeAktif(array $r): string
{
    return count($r) ? $r[count($r) - 1]['periode'] : periodeSekarang();
}

function rentangTanggal(string $periode): array
{
    return ["$periode-01", $periode . '-' . str_pad((string)hariDalamBulan($periode), 2, '0', STR_PAD_LEFT)];
}

// Mengisi durasi harian untuk satu bulan (mengganti baris lama pada bulan itu).
function isiPemakaianBulan(PDO $conn, int $peralatanId, string $periode, float $jam): void
{
    [$awal, $akhir] = rentangTanggal($periode);
    $conn->prepare('DELETE FROM pemakaian WHERE peralatan_id = ? AND tanggal BETWEEN ? AND ?')->execute([$peralatanId, $awal, $akhir]);
    $n = hariDalamBulan($periode);
    $vals = [];
    $ph = [];
    $jamTeks = number_format($jam, 6, '.', '');
    for ($d = 1; $d <= $n; $d++) {
        $ph[] = '(?,?,?)';
        array_push($vals, $peralatanId, sprintf('%s-%02d', $periode, $d), $jamTeks);
    }
    $conn->prepare('INSERT INTO pemakaian (peralatan_id, tanggal, durasi_jam) VALUES ' . implode(',', $ph))->execute($vals);
}
