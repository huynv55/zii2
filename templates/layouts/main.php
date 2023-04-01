<?php
/**
 * @var League\Plates\Template\Template $this
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf_token" content="<?= csrf_token() ?>" />
    <title>Framework PHP</title>
    <link rel="stylesheet" href="<?= $this->asset('/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= $this->asset('/css/styles.css') ?>">
</head>
<body>
    <div class="container-fluid">
        <?= $this->fetch("../elements/flash") ?>
        <?= $this->section('content') ?>
    </div>
    <script src="<?= $this->asset('/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>