<?php
$bootstrapEnabled = $bootstrapEnabled ?? true;
$container = $container ?? true;
$cookiesCheckEnabled = $cookiesCheckEnabled ?? true;
$styles = $styles ?? [];
$title = $title ?? ucwords(str_replace(['-', '_'], ' ', $_ENV["APP_NAME"]));
$content = $content ?? "";
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache")
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
    <?php if ($bootstrapEnabled) { ?>
        <link rel="stylesheet" type="text/css" href="<?= PUBLIC_URL . "/assets/css/core/bootstrap.css" ?>">
        <link rel="stylesheet" type="text/css" href="<?= PUBLIC_URL . "/assets/css/core/bootstrap-icons.min.css" ?>">
    <?php } ?>
    <link rel="stylesheet" type="text/css" href="<?= PUBLIC_URL . "/assets/css/core/lightbox.min.css" ?>">
    <link rel="stylesheet" type="text/css" href="<?= PUBLIC_URL . "/assets/css/global.css" ?>">

    <?php foreach ($styles as $style) { ?>
        <link rel="stylesheet" href="<?= PUBLIC_URL . "/assets/css/$style" ?>">
    <?php } ?>

    <script src="<?= PUBLIC_URL . "/assets/js/core/theme.min.js" ?>"></script>

    <title><?= $title ?></title>
</head>
<body>
    <?php if(isset($header)) { ?>
        <header>
            <?= $header ?>
        </header>
    <?php } ?>

    <?php if ($container) { ?>
    <main class="container">
    <?php } else { ?>
    <main>
    <?php } ?>
        <?= $content ?>

        <?php if ($cookiesCheckEnabled && !isCookieActive("cookies_accepted")) { ?>
            <div class="cookies lightbox-container lightbox-active">
                <div class="lightbox-dialog">
                    <div class="lightbox-content bg-body-secondary">
                        <div class="mb-3">
                            <img class="rounded mb-3" src="<?= PUBLIC_URL . "/assets/images/cookies.png" ?>" width="75" alt="cookies-img">
                            <p>
                                By using cookies, we aim to provide the best website experience. If you continue without changing your settings, we assume that you consent to receive all cookies from us.
                            </p>
                        </div>

                        <button type="button" class="btn btn-secondary btn me-2 default-rounded" onclick="setCookie('cookies_accepted', 'no', 365); dismissLightbox();">Deny</button>
                        <button type="button" class="btn btn-primary btn default-rounded" onclick="setCookie('cookies_accepted', 'yes', 365); dismissLightbox();">Accept</button>
                    </div>
                </div>
            </div>
        <?php } ?>
    </main>

    <?php if(isset($footer)) { ?>
        <footer>
            <?= $footer ?>
        </footer>
    <?php } ?>

    <script src="<?= PUBLIC_URL . "/assets/js/core/jquery.min.js" ?>"></script>
    <?php if ($bootstrapEnabled) { ?>
        <script src="<?= PUBLIC_URL . "/assets/js/core/bootstrap.bundle.min.js" ?>"></script>
    <?php } ?>
    <script src="<?= PUBLIC_URL . "/assets/js/core/cookies.min.js"; ?>"></script>
    <script src="<?= PUBLIC_URL . "/assets/js/global.js" ?>"></script>

    <?php foreach ($scripts ?? [] as $script) { ?>
        <script src="<?= PUBLIC_URL . "/assets/js/$script" ?>"></script>
    <?php } ?>
</body>
</html>
