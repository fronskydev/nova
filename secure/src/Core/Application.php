<?php

namespace src\Core;

use src\Controllers\MaintenanceController;
use src\Errors\ErrorHandler;

class Application
{
    protected Router $router;

    public function __construct()
    {
        $this->router = new Router();
    }

    /**
     * Load the routes from the specified file and check for errors.
     *
     * @return void
     */
    public function loadRoutes(): void
    {
        $statusCode = $this->router->loadRoutes(SECURE_DIR . "/routes/web.php");
        if ($statusCode !== 200) {
            $this->checkForErrors($statusCode);
            exit();
        }
    }

    /**
     * Run the application.
     *
     * This method checks if the application is in maintenance mode and loads the maintenance controller if true.
     * It then creates a new request, resolves it using the router, and checks for any errors based on the status code and HTTP response code.
     *
     * @return void
     */
    public function run(): void
    {
        if ($_ENV["APP_MAINTENANCE"] === "true") {
            $maintenanceInstance = new MaintenanceController();
            $maintenanceInstance->load();
            exit();
        }

        $request = new Request();

        $statusCode = $this->router->resolve($request);
        $httpCode = http_response_code();

        $this->checkForErrors($statusCode);
        $this->checkForErrors($httpCode);
    }

    /**
     * Check for errors based on the provided error code.
     *
     * This method checks if the provided error code is 200. If it is, the method returns immediately.
     * Otherwise, it clears the output buffer, creates an instance of the ErrorHandler class, and handles the error.
     *
     * @param int $error The error code to check.
     * @return void
     */
    private function checkForErrors(int $error): void
    {
        if ($error === 200) {
            return;
        }

        ob_clean();
        $errorHandler = new ErrorHandler();
        $errorHandler->handle($error);
    }
}
