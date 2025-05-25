<?php

$timeZone = $_ENV["TIMEZONE"] ?? "Europe/Paris";
date_default_timezone_set($timeZone);

$isSecure = isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on";
error_reporting(E_ALL);

$currentURL = "http" . ($isSecure ? "s" : "") . "://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

$baseUrlParts = parse_url("http" . ($isSecure ? "s" : "") . "://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
$baseUrl = $baseUrlParts["scheme"] . "://" . $baseUrlParts["host"];
$rootURL = $baseUrl;

if (isset($_ENV["APP_ENV"]) && strtolower($_ENV["APP_ENV"]) === "development") {
    $appName = $_ENV["APP_NAME"] ?? "Nova";
    $baseUrl .= "/" . $appName . "/public";
    $rootURL .= "/" . $appName;
    ini_set("display_errors", 1);
    error_reporting(E_ALL);
} else {
    ini_set("display_errors", 0);
    error_reporting(0);
}

define("CURRENT_URL", $currentURL);

$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? "";
define("IS_MOBILE", preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $userAgent));
define("PUBLIC_URL", $baseUrl);
define("PREVIOUS_URL", $_SERVER["HTTP_REFERER"] ?? PUBLIC_URL);
define("SRC_DIR", realpath(__DIR__ . "/.."));
define("VENDOR_DIR", realpath(__DIR__ . "/../../vendor"));
define("VIEW_DIR", realpath(__DIR__ . "/../../resources/views"));
define("MAILER_DIR", realpath(__DIR__ . "/../../resources/mailer"));
