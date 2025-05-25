<?php

namespace src\Abstracts;

use src\Interfaces\IMiddleware;

abstract class Component
{
    protected bool $actAsController = false;

    /**
     * Gets the name of the component.
     *
     * This abstract method should be implemented by subclasses to return the name of the component.
     *
     * @return string The name of the component.
     */
    abstract protected function getComponentName(): string;

    /**
     * Determines if the component can act as a controller.
     *
     * This method checks if the component is set to act as a controller and
     * verifies the existence of the web.php file in the component's directory.
     *
     * @return bool True if the component can act as a controller, false otherwise.
     */
    public function canActAsController(): bool
    {
        if (!$this->actAsController) {
            return false;
        }

        return file_exists($this->getWebPath());
    }

    /**
     * Gets the web path for the component.
     *
     * This method constructs the path to the web.php file for the component
     * based on the component's name.
     *
     * @return string The path to the web.php file for the component.
     */
    public function getWebPath(): string
    {
        return SECURE_DIR . "/src/Components/{$this->getComponentName()}/web.php";
    }

    /**
     * Runs the component as a controller.
     *
     * This method handles the middleware for the route and then calls the specified action
     * on the controller. It returns the status code from the middleware or the action.
     *
     * @param array $route The route definition containing middleware and action.
     * @return int Returns the status code from the middleware or action.
     */
    public function runAsController(array $route): int
    {
        $code = $this->handleMiddleware($route["middleware"]);
        if ($code !== 200) {
            return $code;
        }

        return $this->callAction($route["action"], $route["segments"]);
    }

    /**
     * Handles the middleware for the component.
     *
     * This method iterates over the provided middleware array, checks if the middleware class exists,
     * instantiates it, and calls its handle method. If the middleware class does not exist or is not
     * an instance of Middleware, it returns a 500 status code.
     *
     * @param array $middleware The list of middleware to be applied to the component.
     * @return int Returns 200 if all middleware are successfully handled, or 500 if any middleware fails.
     */
    private function handleMiddleware(array $middleware): int
    {
        foreach ($middleware as $mw) {
            $middlewareClass = "src\\Components\\{$this->getComponentName()}\\Middleware\\$mw";
            if (class_exists($middlewareClass)) {
                $middlewareInstance = new $middlewareClass();

                if ($middlewareInstance instanceof IMiddleware) {
                    return $middlewareInstance->handle();
                }
                return 500;
            }
        }
        return 200;
    }

    /**
     * Calls the specified action on the component's controller.
     *
     * This method takes an action string in the format "Controller@method",
     * splits it into the controller and method parts, and then attempts to
     * instantiate the controller and call the specified method with the provided segments.
     *
     * @param string $action The action string in the format "Controller@method".
     * @param array $segments The segments to be passed to the controller method.
     * @return int Returns the status code from the controller method, 200 if the method does not return an integer, or 500 if the controller or method does not exist or is invalid.
     */
    private function callAction(string $action, array $segments): int
    {
        [$controller, $method] = explode("@", $action);
        $controllerClass = "src\\Components\\{$this->getComponentName()}\\Controllers\\$controller";

        if (class_exists($controllerClass) && method_exists($controllerClass, $method)) {
            $controllerInstance = new $controllerClass();

            if ($controllerInstance instanceof ComponentController) {
                $code = $controllerInstance->$method($segments);
                if (is_int($code)) {
                    return $code;
                }
                return 200;
            }
            return 500;
        }
        return 500;
    }
}
