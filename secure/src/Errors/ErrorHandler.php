<?php

namespace src\Errors;

use src\Abstracts\Error;

class ErrorHandler
{
    /**
     * Handles the error based on the provided error code.
     *
     * Determines the error class name from the error code and attempts to load the corresponding error class.
     * If the class does not exist, it loads a default error class "Err000".
     *
     * @param int $errorCode The error code to handle.
     * @return void
     */
    public function handle(int $errorCode): void
    {
        $errorClass = $this->getErrorClassName($errorCode);

        if (class_exists($errorClass)) {
            $this->loadError($errorClass);
        } else {
            $this->loadError("Err000");
        }
    }

    /**
     * Generates the error class name based on the provided error code.
     *
     * Constructs the fully qualified class name for the error type by padding the error code with leading zeros
     * and appending it to the namespace `src\Errors\ErrorTypes`.
     *
     * @param int $errorCode The error code to generate the class name for.
     * @return string The fully qualified error class name.
     */
    private function getErrorClassName(int $errorCode): string
    {
        return "src\\Errors\\ErrorTypes\\Err" . str_pad($errorCode, 3, "0", STR_PAD_LEFT);
    }

    /**
     * Loads and renders the error class based on the provided class name.
     *
     * Checks if the error class exists and is an instance of the `Error` class.
     * If so, it renders the error. If the class does not exist or is not an instance of `Error`,
     * it sends a 500 Internal Server Error header.
     *
     * @param string $errorClass The fully qualified name of the error class to load.
     * @return void
     */
    private function loadError(string $errorClass): void
    {
        if (class_exists($errorClass)) {
            $class = new $errorClass();
            if ($class instanceof Error) {
                $class->render();
                return;
            }
        }

        header("HTTP/1.1 500 Internal Server Error");
    }
}
