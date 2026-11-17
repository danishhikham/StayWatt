<?php
// Cache agregasi selama satu permintaan; dibersihkan saat data berubah.
class Cache
{
    private static $m = [];

    public static function remember(string $k, callable $fn)
    {
        if (array_key_exists($k, self::$m)) return self::$m[$k];
        return self::$m[$k] = $fn();
    }

    public static function clearUser($id): void
    {
        $awal = "u{$id}:";
        foreach (array_keys(self::$m) as $k) {
            if (strpos($k, $awal) === 0) unset(self::$m[$k]);
        }
    }

    public static function clearAll(): void
    {
        self::$m = [];
    }
}
