<?php

namespace src\Core;

use JsonException;

class PageUtil
{
    private static string $sharedDir = VIEW_DIR . "/shared/";

    /**
     * Renders a view file within a layout, including optional header and footer files.
     *
     * This method extracts the provided data array into variables, sets up the page information,
     * and includes the specified view file within a layout. If the layout file is not found,
     * it directly includes the view file. If the view file is not found, it sends a 500 HTTP response.
     *
     * @param string $viewFile The path to the view file to be rendered.
     * @param array $data An associative array of data to be extracted and made available to the view.
     * @param PageInfo $pageInfo An optional PageInfo object containing additional page settings.
     * @return void
     */
    public static function render(string $viewFile, array $data = [], PageInfo $pageInfo = new PageInfo()): void
    {
        $headerFile =  self::$sharedDir . "header.php";
        $footerFile =  self::$sharedDir . "footer.php";
        $layoutFile =  self::$sharedDir . "layout.php";

        if (!file_exists($viewFile)) {
            http_response_code(500);
            return;
        }

        extract($data);

        $title = empty($pageInfo->title) ? ucfirst(strtolower($viewName)) : $pageInfo->title;
        $styles = $pageInfo->styles;
        $scripts = $pageInfo->scripts;
        $bootstrapEnabled = $pageInfo->bootstrapEnabled;
        $cookiesCheckEnabled = $pageInfo->cookiesCheckEnabled;
        $container = $pageInfo->container;

        if (file_exists($layoutFile)) {
            ob_start();
            require_once $viewFile;
            $content = ob_get_clean();

            if ($pageInfo->headerEnabled && file_exists($headerFile)) {
                ob_start();
                require_once $headerFile;
                $header = ob_get_clean();
            }

            if ($pageInfo->footerEnabled && file_exists($footerFile)) {
                ob_start();
                require_once $footerFile;
                $footer = ob_get_clean();
            }

            require_once $layoutFile;
            return;
        }

        require_once $viewFile;
    }

    /**
     * Sends a JSON response with the given data.
     *
     * @param mixed $data The data to be encoded as JSON and sent in the response.
     * @return void
     */
    public static function jsonResponse(mixed $data): void
    {
        try {
            $json = json_encode($data, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
            header("Content-Type: application/json");
            echo $json;
        } catch (JsonException) {
            http_response_code(500);
        }
    }

    /**
     * Checks if a given URL exists by making a HEAD request.
     *
     * This method initializes a cURL session to the specified URL, sets the necessary options
     * to perform a HEAD request, and checks the HTTP response code to determine if the URL exists.
     *
     * @param string $url The URL to check.
     * @return bool Returns true if the URL exists (HTTP status code 200-399), false otherwise.
     */
    public static function doesUrlExist(string $url): bool
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "User-Agent: " . $_ENV["APP_NAME"] ."-" . $_ENV["APP_VERSION"]
        ]);

        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        return $httpCode >= 200 && $httpCode < 400;
    }

    /**
     * Redirects the user to a specified URL, optionally with POST data.
     *
     * If `postData` is provided, a JavaScript function is generated to create a form
     * and submit it with the POST data to the specified URL. If `postData` is not provided,
     * a standard HTTP redirect is performed.
     *
     * @param string $url The URL to redirect to.
     * @param array|null $postData Optional. An associative array of POST data to send with the redirect.
     * @param bool $specificUrl Optional. If true, the URL is treated as an absolute path. Default is false.
     * @return void
     */
    public static function redirect(string $url, ?array $postData = null, bool $specificUrl = false): void
    {
        if (!$specificUrl) {
            $url = PUBLIC_URL . $url;
        }

        if ($postData !== null) {
            try {
                echo "
                    <script>
                    function redirectWithPost() {
                        const form = document.createElement('form');
                        form.method = 'post';
                        form.action = '$url';
    
                        Object.entries(" . json_encode($postData, JSON_THROW_ON_ERROR) . ").forEach(([key, value]) => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = key;
                            input.value = value;
                            form.appendChild(input);
                        });
    
                        document.body.appendChild(form);
                        form.submit();
                    }
                    window.onload = redirectWithPost;
                    </script>
                ";
            } catch (JsonException) {
                http_response_code(500);
            }
            return;
        }

        header("Location: $url");
        exit();
    }
}
