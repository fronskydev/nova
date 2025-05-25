<?php
$number = $number ?? "000";
$title = $title ?? "Error Not Found";
$description = $description ?? ["The requested error code has not been found."];
?>

<!DOCTYPE html>
<html lang="<?= $_ENV["LOCALE"] ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#ffffff">

    <link rel="manifest" href="<?= PUBLIC_URL . "/assets/manifest.json" ?>">

    <link rel="shortcut icon" href="<?= PUBLIC_URL . "/favicon.ico" ?>" type="image/x-icon">
    <link rel="icon" href="<?= PUBLIC_URL . "/assets/images/icons/icon.png" ?>">
    <link rel="apple-touch-icon" href="<?= PUBLIC_URL . "/assets/images/icons/icon.png" ?>">

    <link rel="stylesheet" type="text/css" href="<?= PUBLIC_URL . "/assets/css/core/root.css" ?>">
    <link rel="stylesheet" type="text/css" href="<?= PUBLIC_URL . "/assets/css/core/bootstrap.css" ?>">
    <link rel="stylesheet" type="text/css" href="<?= PUBLIC_URL . "/assets/css/core/bootstrap-icons.min.css" ?>">
    <link rel="stylesheet" type="text/css" href="<?= PUBLIC_URL . "/assets/css/core/error.min.css" ?>">
    <link rel="stylesheet" type="text/css" href="<?= PUBLIC_URL . "/assets/css/global.css" ?>">

    <script src="<?= PUBLIC_URL . "/assets/js/core/theme.min.js" ?>"></script>

    <title><?= ucwords(str_replace(['-', '_'], ' ', $_ENV["APP_NAME"])) . " | " . $number ?></title>
</head>
<body>
    <div class="error-container bg-body-tertiary" style="margin-right: 1rem; margin-left: 1rem;">
        <div class="error-number text-danger"><?= $number ?></div>
        <div class="error-title"><?= $title ?></div>
        <div class="error-description">
            <?php foreach ($description as $line) { ?>
                <p><?= $line ?></p>
            <?php } ?>
        </div>
        <hr>
        <p>Go back to the <a href=<?= PUBLIC_URL ?>>homepage</a></p>
    </div>

    <script src="<?= PUBLIC_URL . "/assets/js/core/jquery.min.js" ?>"></script>
    <script src="<?= PUBLIC_URL . "/assets/js/core/bootstrap.bundle.min.js" ?>"></script>
    <script src="<?= PUBLIC_URL . "/assets/js/global.js" ?>"></script>
</body>
</html>
