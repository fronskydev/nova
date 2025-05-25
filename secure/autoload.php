<?php

require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/src/Config/functions.php";

define("SECURE_DIR", realpath(__DIR__));

spl_autoload_register(static function ($class) {
    $baseDir = SECURE_DIR;
    $fileName = str_replace("\\", "/", $class) . ".php";
    $filePath = $baseDir . "/" . $fileName;

    if (file_exists($filePath)) {
        require_once $filePath;
    } else {
        $dirIterator = new RecursiveDirectoryIterator($baseDir);
        $iterator = new RecursiveIteratorIterator(
            $dirIterator,
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $file) {
            if (str_contains($file, $fileName)) {
                require_once $file;
                break;
            }
        }
    }
});

$dotenv = Dotenv\Dotenv::createImmutable(SECURE_DIR);
$dotenv->safeLoad();

require_once __DIR__ . "/src/Config/app.php";
