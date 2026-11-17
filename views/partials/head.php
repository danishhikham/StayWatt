<!DOCTYPE html>
<html lang="id"<?= $tema === 'terang' ? ' class="light"' : '' ?> data-base="<?= e(App::$base) ?>">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="<?= $tema === 'terang' ? '#F4F2ED' : '#15171A' ?>">
<title><?= e($judul) ?> · StayWatt</title>
<link rel="stylesheet" href="<?= e(u('/css/app.css')) ?>?v=<?= e($asset) ?>">
<style>
  /* Lebar konten mengikuti layar (dengan batas wajar untuk layar ultra-lebar) */
  .wadah-app { width: 100%; max-width: none; }
  @media (min-width: 1280px) { main.main-app { padding-left: 48px; padding-right: 48px; } }
  @media (min-width: 1700px) { main.main-app { padding-left: 64px; padding-right: 64px; } }
</style>
</head>
<body>