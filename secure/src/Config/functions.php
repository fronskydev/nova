<?php

use Random\RandomException;

/**
 * Starts a new session if one doesn't already exist.
 *
 * @return void
 */
function startSession(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

/**
 * Checks if a specific session variable is active and has a value.
 *
 * @param string $name The name of the session variable to check.
 * @return bool True if the session variable is active and has a value, otherwise false.
 */
function isSessionActive(string $name): bool
{
    startSession();
    return isset($_SESSION[$name]) && $_SESSION[$name] !== "";
}

/**
 * Sets a value for a specific session variable.
 *
 * This function starts a session if one doesn't already exist and sets the value of a specified session variable.
 *
 * @param string $name The name of the session variable to set.
 * @param string $value The value to assign to the session variable.
 * @return void
 */
function setSessionValue(string $name, string $value): void
{
    startSession();
    $_SESSION[$name] = $value;
}

/**
 * Deletes a specific session variable and associated data.
 *
 * This function starts a session if one doesn't already exist, checks if the specified session variable is active,
 * and then deletes the session variable and associated data.
 *
 * @param string $name The name of the session variable to delete.
 * @return void
 */
function deleteSession(string $name): void
{
    startSession();
    if (isSessionActive($name)) {
        $_SESSION[$name] = "";
        unset($_SESSION["data"]);
    }
}

/**
 * Retrieves the value of a specific session variable.
 *
 * This function starts a session if the session variable is active and returns its value.
 *
 * @param string $name The name of the session variable to retrieve.
 * @return mixed|null The value of the session variable, or null if the session variable is not active.
 */
function getSessionValue(string $name): mixed
{
    if (isSessionActive($name)) {
        startSession();
        return $_SESSION[$name];
    }

    return null;
}

/**
 * Checks if a specific cookie is active.
 *
 * @param string $name The name of the cookie to check.
 * @return bool True if the cookie is active, otherwise false.
 */
function isCookieActive(string $name): bool
{
    if (isset($_COOKIE[$name])) {
        return true;
    }

    return false;
}

/**
 * Sets a cookie with the provided values and options.
 *
 * @param string $name The name of the cookie.
 * @param string $value The value to be stored in the cookie.
 * @param int $expiration The expiration time of the cookie in Unix timestamp format. Default is one year from the current time.
 * @param string $path The path on the server where the cookie will be available. Default is "/".
 * @param string $domain The domain for which the cookie is valid. Default is an empty string.
 * @param bool $secure If true, the cookie will only be sent over secure HTTPS connections. Default is false.
 * @param bool $httpOnly If true, the cookie will be accessible only through HTTP(S) and not JavaScript. Default is false.
 * @return void
 */
function setCookieValue(string $name, string $value, int $expiration = 0, string $path = "/", string $domain = "", bool $secure = true, bool $httpOnly = true): void
{
    if ($expiration === 0) {
        $expiration = time() + 31536000;
    }

    setcookie($name, $value, $expiration, $path, $domain, $secure, $httpOnly);
}

/**
 * Deletes a specific cookie.
 *
 * @param string $name The name of the cookie to delete.
 * @param string $path The path on the server where the cookie is available. Default is "/".
 * @param string $domain The domain for which the cookie is valid. Default is an empty string.
 * @return void
 */
function deleteCookie(string $name, string $path = "/", string $domain = ""): void
{
    if (isCookieActive($name)) {
        setcookie($name, "", time() - 3600, $path, $domain);
    }
}

/**
 * Retrieves the value of a specific cookie.
 *
 * @param string $name The name of the cookie to retrieve.
 * @return mixed|null The value of the cookie, or null if the cookie is not active.
 */
function getCookieValue(string $name): mixed
{
    if (isCookieActive($name)) {
        return $_COOKIE[$name];
    }

    return null;
}

/**
 * Encrypts a given plaintext string using AES-256-CBC encryption.
 *
 * @param string $text The plaintext string to be encrypted.
 * @param string $fieldKey An optional field-specific key to derive the encryption and HMAC keys.
 *                         Defaults to an empty string.
 * @return string The base64-encoded encrypted string, including the IV and HMAC.
 */
function encryptText(string $text, string $fieldKey = ""): string
{
    $baseKey = $_ENV["TXT_ENCRYPTION_KEY"];
    $encryptionKey = hash("sha256", $baseKey . $fieldKey, true);
    $hmacKey = hash("sha256", $baseKey . $fieldKey . "_hmac", true);

    $iv = random_bytes(16);
    $ciphertext = openssl_encrypt($text, "aes-256-cbc", $encryptionKey, OPENSSL_RAW_DATA, $iv);

    if ($ciphertext === false) {
        return "Encryption failed";
    }

    $hmac = hash_hmac("sha256", $iv . $ciphertext, $hmacKey, true);

    return base64_encode($iv . $ciphertext . $hmac);
}

/**
 * Decrypts a base64-encoded encrypted string using AES-256-CBC decryption.
 *
 * @param string $encodedData The base64-encoded encrypted string to decrypt.
 * @param string $fieldKey An optional field-specific key to derive the encryption and HMAC keys.
 *                         Defaults to an empty string.
 * @return string The decrypted plaintext string. Returns an error message if decryption fails
 *                or if the data integrity check fails.
 */
function decryptText(string $encodedData, string $fieldKey = ""): string
{
    $baseKey = $_ENV["TXT_ENCRYPTION_KEY"];
    $encryptionKey = hash("sha256", $baseKey . $fieldKey, true);
    $hmacKey = hash("sha256", $baseKey . $fieldKey . "_hmac", true);

    $data = base64_decode($encodedData, true);
    if ($data === false || strlen($data) < 48) {
        return "Incorrectly formatted or too short encrypted data";
    }

    $iv = substr($data, 0, 16);
    $hmac = substr($data, -32);
    $ciphertext = substr($data, 16, -32);

    $calculatedHmac = hash_hmac("sha256", $iv . $ciphertext, $hmacKey, true);
    if (!hash_equals($hmac, $calculatedHmac)) {
        return "Data integrity check failed – data may have been tampered with";
    }

    $plaintext = openssl_decrypt($ciphertext, "aes-256-cbc", $encryptionKey, OPENSSL_RAW_DATA, $iv);
    if ($plaintext === false) {
        return "Decryption failed";
    }

    return $plaintext;
}

/**
 * Encrypts an array of data using AES-256-CBC encryption.
 *
 * @param array $data The data to be encrypted.
 * @param string $fieldKey An optional field-specific key to derive the encryption and HMAC keys.
 *                         Defaults to an empty string.
 * @return string The base64-encoded encrypted string, including the IV and HMAC.
 */
function encryptData(array $data, string $fieldKey = ""): string
{
    $baseKey = $_ENV["ARR_ENCRYPTION_KEY"];
    $encryptionKey = hash('sha256', $baseKey . $fieldKey, true);
    $hmacKey = hash('sha256', $baseKey . $fieldKey . '_hmac', true);

    $ivLength = openssl_cipher_iv_length("aes-256-cbc");
    $iv = random_bytes($ivLength);

    $serialized = serialize($data);
    $ciphertext = openssl_encrypt($serialized, "aes-256-cbc", $encryptionKey, OPENSSL_RAW_DATA, $iv);

    if ($ciphertext === false) {
        return "Encryption failed";
    }

    $hmac = hash_hmac('sha256', $iv . $ciphertext, $hmacKey, true);

    return base64_encode($iv . $ciphertext . $hmac);
}

/**
 * Decrypts a base64-encoded encrypted string using AES-256-CBC decryption.
 *
 * @param string $input The base64-encoded encrypted string to decrypt.
 * @param string $fieldKey An optional field-specific key to derive the encryption and HMAC keys.
 *                         Defaults to an empty string.
 * @return mixed The decrypted and unserialized data. Returns an error message if decryption fails
 *               or if the data integrity check fails.
 */
function decryptData(string $input, string $fieldKey = ""): mixed
{
    $baseKey = $_ENV["ARR_ENCRYPTION_KEY"];
    $encryptionKey = hash('sha256', $baseKey . $fieldKey, true);
    $hmacKey = hash('sha256', $baseKey . $fieldKey . '_hmac', true);

    $data = base64_decode($input, true);
    if ($data === false) {
        return "Incorrectly formatted encrypted data";
    }

    $ivLength = openssl_cipher_iv_length("aes-256-cbc");
    if (strlen($data) <= $ivLength + 32) {
        return "Incorrectly formatted or too short encrypted data";
    }

    $iv = substr($data, 0, $ivLength);
    $hmac = substr($data, -32);
    $ciphertext = substr($data, $ivLength, -32);

    $calculatedHmac = hash_hmac('sha256', $iv . $ciphertext, $hmacKey, true);
    if (!hash_equals($hmac, $calculatedHmac)) {
        return "Data integrity check failed – data may have been tampered with";
    }

    $decrypted = openssl_decrypt($ciphertext, "aes-256-cbc", $encryptionKey, OPENSSL_RAW_DATA, $iv);
    if ($decrypted === false) {
        return "Decryption failed";
    }

    return unserialize($decrypted, ["allowed_classes" => false]);
}

/**
 * Generates a random string of a specified length.
 *
 * @param int $length The length of the random string to generate.
 * @return string The generated random string.
 */
function generateRandomString(int $length): string
{
    $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $charactersLength = strlen($characters);
    $randomString = "";

    for ($i = 0; $i < $length; $i++) {
        try {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        } catch (Exception) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
    }

    return $randomString;
}

/**
 * Formats a given timestamp into a human-readable date string.
 *
 * @param string $timestamp The timestamp to format, in a string format that can be parsed by `strtotime`.
 * @return string The formatted date string in "dd-MM-yyyy" format.
 */
function getFormattedDate(string $timestamp): string
{
    $formatter = new IntlDateFormatter(
        $_ENV["LOCALE"],
        IntlDateFormatter::FULL,
        IntlDateFormatter::NONE,
        $_ENV["TIMEZONE"],
        IntlDateFormatter::GREGORIAN,
        "dd-MM-yyyy"
    );
    return mb_convert_case($formatter->format(strtotime($timestamp)), MB_CASE_TITLE, "UTF-8");
}

/**
 * Formats a given timestamp into a human-readable text date string.
 *
 * @param string $timestamp The timestamp to format, in a string format that can be parsed by `strtotime`.
 * @return string The formatted date string in "EEEE dd MMMM yyyy" format.
 */
function getFormattedTextLongDate(string $timestamp): string
{
    $formatter = new IntlDateFormatter(
        $_ENV["LOCALE"],
        IntlDateFormatter::FULL,
        IntlDateFormatter::NONE,
        $_ENV["TIMEZONE"],
        IntlDateFormatter::GREGORIAN,
        "EEEE dd MMMM yyyy"
    );
    return mb_convert_case($formatter->format(strtotime($timestamp)), MB_CASE_TITLE, "UTF-8");
}

/**
 * Formats a given timestamp into a human-readable text date string.
 *
 * @param string $timestamp The timestamp to format, in a string format that can be parsed by `strtotime`.
 * @return string The formatted date string in "EEEE dd MMMM yyyy" format.
 */
function getFormattedTextShortDate(string $timestamp): string
{
    $formatter = new IntlDateFormatter(
        $_ENV["LOCALE"],
        IntlDateFormatter::FULL,
        IntlDateFormatter::NONE,
        $_ENV["TIMEZONE"],
        IntlDateFormatter::GREGORIAN,
        "dd MMMM yyyy"
    );
    return mb_convert_case($formatter->format(strtotime($timestamp)), MB_CASE_TITLE, "UTF-8");
}
