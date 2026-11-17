<?php foreach (($scripts ?? []) as $s): ?><script src="<?= e(u($s)) ?>?v=<?= e($asset) ?>" defer></script>
<?php endforeach; ?></body>
</html>
