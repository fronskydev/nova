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
 * Encrypts a text using a simple XOR-based encryption with a given key.
 *
 * @param string $text The plaintext text to be encrypted.
 * @return string The encrypted and base64-encoded result.
 */
function encryptText(string $text): string
{
    $key = $_ENV["TXT_ENCRYPTION_KEY"];
    $encryptedText = "";
    $textLength = strlen($text);

    for ($i = 0; $i < $textLength; $i++) {
        $encryptedText .= chr(ord($text[$i]) ^ ord($key[$i % strlen($key)]));
    }

    return base64_encode($encryptedText);
}

/**
 * Decrypts an encrypted text using a simple XOR-based decryption with a given key.
 *
 * @param string $encryptedText The encrypted text to be decrypted (base64-encoded).
 * @return string The decrypted plaintext result.
 */
function decryptText(string $encryptedText): string
{
    $key = $_ENV["TXT_ENCRYPTION_KEY"];
    $encryptedText = base64_decode($encryptedText);
    $decryptedText = "";
    $textLength = strlen($encryptedText);

    for ($i = 0; $i < $textLength; $i++) {
        $decryptedText .= chr(ord($encryptedText[$i]) ^ ord($key[$i % strlen($key)]));
    }

    return $decryptedText;
}

/**
 * Encrypts data using AES-256-CBC encryption.
 *
 * @param array $data The data to be encrypted.
 * @return string The encrypted result.
 * @throws RandomException If generating random bytes for the IV fails.
 */
function encryptData(array $data): string
{
    $key = $_ENV["ARR_ENCRYPTION_KEY"];
    $iv = random_bytes(openssl_cipher_iv_length("aes-256-cbc"));
    $serialized_data = serialize($data);
    $encrypted_data = openssl_encrypt($serialized_data, "aes-256-cbc", $key, 0, $iv);
    if ($encrypted_data === false) {
        $error_data = ['error' => 'Encryption failed'];
        return base64_encode(random_bytes(openssl_cipher_iv_length('aes-256-cbc')) . openssl_encrypt(serialize($error_data), "aes-256-cbc", $key, 0, $iv));
    }
    return base64_encode($iv . $encrypted_data);
}

/**
 * Decrypts previously encrypted data using AES-256-CBC decryption.
 *
 * @param string $encrypted_data The encrypted data to be decrypted.
 * @return mixed The decrypted and serialized data.
 */
function decryptData(string $encrypted_data): mixed
{
    $key = $_ENV["ARR_ENCRYPTION_KEY"];
    $data = base64_decode($encrypted_data);
    $iv_length = openssl_cipher_iv_length("aes-256-cbc");
    $iv = substr($data, 0, $iv_length);
    $encrypted_data = substr($data, $iv_length);
    $decrypted_data = openssl_decrypt($encrypted_data, "aes-256-cbc", $key, 0, $iv);
    return unserialize($decrypted_data, ["allowed_classes" => false]);
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
