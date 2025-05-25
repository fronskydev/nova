<?php

namespace src\Core;

class Request
{
    /**
     * Get the HTTP request method.
     *
     * @return string The HTTP request method.
     */
    public function getMethod(): string
    {
        return $_SERVER["REQUEST_METHOD"];
    }

    /**
     * Get the URL from the HTTP request.
     *
     * This method parses the URL from the `REQUEST_URI` server variable and returns the path component.
     * If the application environment is set to development, it removes the application name and public directory from the URL.
     *
     * @return string The parsed URL path.
     */
    public function getUrl(): string
    {
        $url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

        if ($_ENV["APP_ENV"] === "development") {
            $url = str_replace("/" . $_ENV["APP_NAME"] . "/public", "", $url);
        }

        return $url;
    }
    /**
     * Get the query parameters from the URL.
     *
     * This method parses the query string from the `REQUEST_URI` server variable and returns it as an associative array.
     *
     * @return array The query parameters as an associative array.
     */
    public function getQueryParams(): array
    {
        parse_str(parse_url($_SERVER["REQUEST_URI"], PHP_URL_QUERY), $params);
        return $params;
    }

    /**
     * Get the POST data from the HTTP request.
     *
     * This method returns the contents of the `$_POST` superglobal array, which contains the POST data sent with the HTTP request.
     *
     * @return array The POST data as an associative array.
     */
    public function getPostData(): array
    {
        return $_POST;
    }

    /**
     * Get the GET data from the HTTP request.
     *
     * This method returns the contents of the `$_GET` superglobal array, which contains the GET data sent with the HTTP request.
     *
     * @return array The GET data as an associative array.
     */
    public function getGetData(): array
    {
        return $_GET;
    }

    /**
     * Get the HTTP request headers.
     *
     * This method returns all the HTTP request headers as an associative array.
     *
     * @return array The HTTP request headers.
     */
    public function getHeaders(): array
    {
        return getallheaders();
    }

    /**
     * Get the raw body of the HTTP request.
     *
     * This method reads the raw data from the HTTP request body using the `php://input` stream.
     *
     * @return string|null The raw body of the HTTP request, or null if the body is empty.
     */
    public function getBody(): ?string
    {
        return file_get_contents("php://input");
    }

    /**
     * Get a specific query parameter from the URL.
     *
     * This method retrieves the value of a specific query parameter from the URL's query string.
     *
     * @param string $key The key of the query parameter to retrieve.
     * @return string|null The value of the query parameter, or null if the parameter does not exist.
     */
    public function getQueryParam(string $key): ?string
    {
        return $this->getQueryParams()[$key] ?? null;
    }

    /**
     * Get a specific POST parameter from the HTTP request.
     *
     * This method retrieves the value of a specific POST parameter from the POST data.
     *
     * @param string $key The key of the POST parameter to retrieve.
     * @return string|null The value of the POST parameter, or null if the parameter does not exist.
     */
    public function getPostParam(string $key): ?string
    {
        return $this->getPostData()[$key] ?? null;
    }

    /**
     * Get a specific GET parameter from the HTTP request.
     *
     * This method retrieves the value of a specific GET parameter from the GET data.
     *
     * @param string $key The key of the GET parameter to retrieve.
     * @return string|null The value of the GET parameter, or null if the parameter does not exist.
     */
    public function getGetParam(string $key): ?string
    {
        return $this->getGetData()[$key] ?? null;
    }

    /**
     * Get a specific HTTP request header.
     *
     * This method retrieves the value of a specific HTTP request header.
     *
     * @param string $key The key of the header to retrieve.
     * @return string|null The value of the header, or null if the header does not exist.
     */
    public function getHeader(string $key): ?string
    {
        $headers = $this->getHeaders();
        return $headers[$key] ?? null;
    }
}
