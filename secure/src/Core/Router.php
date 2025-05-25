<?php

namespace src\Core;

use src\Abstracts\Controller;
use src\Components\ComponentMapper;
use src\Interfaces\IMiddleware;

class Router
{
    protected array $routes = [];
    protected array $componentRoutes = [];

    public function __construct()
    {
        $this->loadComponentRoutes();
    }

    /**
     * Add a new route to the router.
     *
     * This method adds a new route to the router with the specified HTTP method(s), route, action, and middleware.
     *
     * @param mixed $method The HTTP method(s) for the route. Can be a string or an array of strings.
     * @param string $route The URL pattern for the route.
     * @param string $action The controller action to be executed for the route.
     * @param mixed $middleware The middleware(s) to be applied to the route.
     * @return void
     */
    public function addRoute(mixed $method, string $route, string $action, mixed $middleware): void
    {
        $methods = is_array($method) ? $method : [$method];

        $middleware = $middleware ?? [];
        $middleware = is_array($middleware) ? $middleware : [$middleware];
        $this->routes[] = [
            "methods" => $methods,
            "route" => $route,
            "action" => $action,
            "middleware" => $middleware
        ];
    }

    /**
     * Load routes from a file.
     *
     * This method loads routes from a specified file and adds them to the router.
     * It returns a status code indicating the result of the operation.
     *
     * @param string $file The path to the file containing the routes.
     * @return int Returns 200 if the routes were successfully loaded, or 500 if the file does not exist.
     */
    public function loadRoutes(string $file): int
    {
        if (file_exists($file)) {
            $routes = require_once $file;
            foreach ($routes as $route => $details) {
                $this->addRoute($details["method"], $route, $details["action"], $details["middleware"] ?? []);
            }
            return 200;
        }
        return 500;
    }

    /**
     * Load component routes.
     *
     * This method retrieves all components, checks if they can act as controllers,
     * and if so, loads their routes from their respective web.php files.
     * The routes are then added to the componentRoutes array.
     *
     * @return void
     */
    private function loadComponentRoutes(): void
    {
        $components = ComponentMapper::getAll();

        foreach ($components as $component) {
            if ($component->canActAsController()) {
                $componentWebPath = $component->getWebPath();
                $routes = require_once $componentWebPath;

                foreach ($routes as $route => $details) {
                    $method = $details["method"];
                    $methods = is_array($method) ? $method : [$method];

                    $middleware = $details["middleware"] ?? [];
                    $middleware = is_array($middleware) ? $middleware : [$middleware];
                    $this->componentRoutes[] = [
                        "methods" => $methods,
                        "route" => $route,
                        "action" => $details["action"],
                        "middleware" => $middleware,
                        "class" => $component::class
                    ];
                }
            }
        }
    }

    /**
     * Matches the given URL and HTTP method to the registered routes.
     *
     * This method iterates over the registered routes and component routes to find the longest matching route
     * for the given URL and HTTP method. It returns the matched route with the longest length.
     *
     * @param string $url The URL to match against the registered routes.
     * @param string $method The HTTP method to match against the registered routes.
     * @return array The matched route with the longest length, or an empty array if no match is found.
     */
    public function match(string $url, string $method): array
    {
        $longestMatch = null;
        $longestMatchLength = 0;

        foreach ($this->routes as $route) {
            $matchedRoute = $this->matchesRoute($url, $method, $route);
            if ($matchedRoute) {
                $routeLength = strlen($route['route']);
                if ($routeLength > $longestMatchLength) {
                    $longestMatch = $matchedRoute;
                    $longestMatchLength = $routeLength;
                }
            }
        }

        foreach ($this->componentRoutes as $route) {
            $matchedRoute = $this->matchesRoute($url, $method, $route);
            if ($matchedRoute) {
                $routeLength = strlen($route['route']);
                if ($routeLength > $longestMatchLength) {
                    $longestMatch = $matchedRoute;
                    $longestMatchLength = $routeLength;
                }
            }
        }

        return $longestMatch ?: [];
    }

    /**
     * Matches the given URL and HTTP method to a specific route.
     *
     * This method checks if the provided URL and HTTP method match the given route.
     * It handles root URL matching, checks if the method is allowed, and extracts
     * the remaining path segments if the URL matches the route.
     *
     * @param string $url The URL to match against the route.
     * @param string $method The HTTP method to match against the route.
     * @param array $route The route definition to match against.
     * @return array|null The matched route with extracted segments, or null if no match is found.
     */
    private function matchesRoute(string $url, string $method, array $route): ?array
    {
        if ($route['route'] === '/' && $url === '/') {
            $route['segments'] = [];
            return $route;
        }

        if ($route['route'] === '/' && strpos($url, '/') !== strlen($route['route'])) {
            return null;
        }

        if ((in_array($method, $route["methods"]) || in_array("*", $route["methods"])) && str_starts_with($url, $route["route"])) {
            $remainingPath = substr($url, strlen($route['route']));

            $segments = array_filter(explode('/', trim($remainingPath, '/')), fn($s) => $s !== '');
            $segments = array_values($segments);
            $route['segments'] = $segments;
            return $route;

        }

        return null;
    }

    /**
     * Resolves the request to a route.
     *
     * This method takes a Request object, extracts the URL and HTTP method,
     * matches them to a registered route, and then executes the corresponding action.
     * If the route is a component, it runs the component as a controller.
     * If middleware is defined for the route, it handles the middleware before calling the action.
     *
     * @param Request $request The request object containing the URL and HTTP method.
     * @return int Returns 200 if the route is successfully resolved, or 404 if no matching route is found.
     */
    public function resolve(Request $request): int
    {
        $url = $request->getUrl();
        $method = $request->getMethod();

        $matchedRoute = $this->match($url, $method);

        if (empty($matchedRoute)) {
            return 404;
        }

        if (isset($matchedRoute["class"])) {
            $component = new $matchedRoute["class"]();
            return $component->runAsController($matchedRoute);
        }

        $code = $this->handleMiddleware($matchedRoute["middleware"]);
        if ($code !== 200) {
            return $code;
        }

        return $this->callAction($matchedRoute["action"], $matchedRoute["segments"]);
    }

    /**
     * Handles the middleware for a route.
     *
     * This method iterates over the provided middleware array, checks if the middleware class exists,
     * instantiates it, and calls its handle method. If the middleware class does not exist or is not
     * an instance of Middleware, it returns a 500 status code.
     *
     * @param array $middleware The list of middleware to be applied to the route.
     * @return int Returns 200 if all middleware are successfully handled, or 500 if any middleware fails.
     */
    private function handleMiddleware(array $middleware): int
    {
        foreach ($middleware as $mw) {
            $middlewareClass = "src\\Middleware\\$mw";
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
     * Calls the specified action on the controller.
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
        $controllerClass = "src\\Controllers\\$controller";

        if (class_exists($controllerClass) && method_exists($controllerClass, $method)) {
            $controllerInstance = new $controllerClass();

            if ($controllerInstance instanceof Controller) {
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
