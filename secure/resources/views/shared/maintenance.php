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

    <title><?= ucwords(str_replace(['-', '_'], ' ', $_ENV["APP_NAME"])) ?> | Maintenance</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,100..900;1,100..900&display=swap');
        body,h1{font-weight:100;text-align:center}a,body{color:#fff}body,html{padding:0;margin:0;width:100%;height:100%}*{box-sizing:border-box}h1{font-size:50px}body{padding:0;background:#d6433b;font-family:"Public Sans", sans-serif;font-size:20px;display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-pack:center;-ms-flex-pack:center;justify-content:center;-webkit-box-align:center;-ms-flex-align:center;align-items:center}article{display:block;width:100%;max-width:800px;margin-left:10%;margin-right:10%}a{font-weight:700}a:hover{text-decoration:none}svg{width:75px;margin-top:1em}
    </style>
</head>
<body>
    <article>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 202.24 202.24">
            <defs>
                <style>.cls-1 {
                        fill: #fff;
                    }</style>
            </defs>
            <title>Asset 3</title>
            <g id="Layer_2" data-name="Layer 2">
                <g id="Capa_1" data-name="Capa 1">
                    <path class="cls-1"
                          d="M101.12,0A101.12,101.12,0,1,0,202.24,101.12,101.12,101.12,0,0,0,101.12,0ZM159,148.76H43.28a11.57,11.57,0,0,1-10-17.34L91.09,31.16a11.57,11.57,0,0,1,20.06,0L169,131.43a11.57,11.57,0,0,1-10,17.34Z"/>
                    <path class="cls-1"
                          d="M101.12,36.93h0L43.27,137.21H159L101.13,36.94Zm0,88.7a7.71,7.71,0,1,1,7.71-7.71A7.71,7.71,0,0,1,101.12,125.63Zm7.71-50.13a7.56,7.56,0,0,1-.11,1.3l-3.8,22.49a3.86,3.86,0,0,1-7.61,0l-3.8-22.49a8,8,0,0,1-.11-1.3,7.71,7.71,0,1,1,15.43,0Z"/>
                </g>
            </g>
        </svg>
        <h1>We'll be back soon!</h1>
        <div>
            <p>Sorry for the inconvenience. We're performing some maintenance at the moment. we'll be back up
                shortly!</p>
            <p>- <?= ucwords(str_replace(['-', '_'], ' ', $_ENV["APP_NAME"])) ?></p>
        </div>
    </article>
</body>
</html>
