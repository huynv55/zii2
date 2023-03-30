<?php
/**
 * @var League\Plates\Template\Template $this
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Framework PHP</title>
    <link rel="stylesheet" href="<?= $this->asset('/css/bootstrap.min.css') ?>">
</head>
<body>
    <div class="container-fluid">
        <?= $this->section('content') ?>
    </div>
    <script src="<?= $this->asset('/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>