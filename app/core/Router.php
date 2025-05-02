<?php

namespace App\Core;

class Router
{
    /**
     * Array to store the defined routes.
     * Each route is an associative array containing 'method', 'path', and 'callback'.
     *
     * @var array
     */
    private $routes = [];

    /**
     * Registers a new GET route.
     *
     * @param string $path The URI path to match (e.g., '/users', '/products/:id').
     * @param callable|array|string $callback The function, method array, or controller@method string to execute.
     * @return void
     */
    public function get(string $path, $callback): void
    {
        $this->addRoute('GET', $path, $callback);
    }

    /**
     * Registers a new POST route.
     *
     * @param string $path The URI path to match (e.g., '/submit', '/update/:id').
     * @param callable|array|string $callback The function, method array, or controller@method string to execute.
     * @return void
     */
    public function post(string $path, $callback): void
    {
        $this->addRoute('POST', $path, $callback);
    }

    /**
     * Adds a new route to the $routes array.
     *
     * @param string $method The HTTP method (e.g., 'GET', 'POST').
     * @param string $path The URI path to match.
     * @param callable|array|string $callback The function, method array, or controller@method string to execute.
     * @return void
     */
    private function addRoute(string $method, string $path, $callback): void
    {
        $this->routes[] = [
            'method' => strtoupper($method), // Store method in uppercase for consistency
            'path' => $path,
            'callback' => $callback,
        ];
    }

    /**
     * Attempts to find a matching route for the current request and execute its callback.
     *
     * @return mixed The output of the matched route's callback or a 404 response.
     */
    public function resolve()
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD']); // Get request method in uppercase
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = rtrim($uri, '/'); // Remove trailing slash for consistent matching
        if ($uri === '') {
            $uri = '/'; // Handle root path
        }

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $this->matchPath($route['path'], $uri, $params)) {
                return call_user_func_array($route['callback'], $params);
            }
        }

        // If no route is found, return a 404 response
        http_response_code(404);
        return $this->renderView('404');
    }

    /**
     * Matches a given route path with the current URI.
     * It also extracts any dynamic parameters from the URI.
     *
     * @param string $routePath The defined route path (e.g., '/users/:id').
     * @param string $uri The current URI path.
     * @param array $params An array to store the extracted parameters (passed by reference).
     * @return bool True if the path matches, false otherwise.
     */
    private function matchPath(string $routePath, string $uri, &$params = []): bool
    {
        $routeParts = explode('/', trim($routePath, '/'));
        $uriParts = explode('/', trim($uri, '/'));

        if (count($routeParts) !== count($uriParts)) {
            return false;
        }

        $params = [];

        foreach ($routeParts as $index => $routePart) {
            if (strpos($routePart, ':') === 0) {
                $paramName = substr($routePart, 1); // Get the parameter name without the colon
                $params[$paramName] = $uriParts[$index];
            } elseif ($routePart !== $uriParts[$index]) {
                return false;
            }
        }

        return true;
    }

    /**
     * Renders a view file, injecting data into it and the main layout.
     *
     * @param string $view The name of the view file (without the .php extension).
     * @param array $data An associative array of data to pass to the view.
     * @return string The rendered HTML content.
     */
    public function renderView(string $view, array $data = []): string
    {
        extract($data); // Extract data array into variables

        $layoutContent = $this->layoutContent();
        $viewContent = $this->viewContent($view, $data);

        if ($viewContent === false) {
            http_response_code(500);
            return 'Internal Server Error: View not found.';
        }

        return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    /**
     * Includes the main layout file and returns its content.
     *
     * @return string The content of the layout file.
     */
    private function layoutContent(): string
    {
        ob_start();
        include_once __DIR__ . '/../Views/layouts/main.php';
        return ob_get_clean();
    }

    /**
     * Includes a specific view file and returns its content.
     *
     * @param string $view The name of the view file.
     * @param array $data An associative array of data to pass to the view.
     * @return string|bool The content of the view file or false if the file doesn't exist.
     */
    private function viewContent(string $view, array $data): string|bool
    {
        $viewPath = __DIR__ . "/../Views/$view.php";
        if (!file_exists($viewPath)) {
            return false;
        }
        extract($data);
        ob_start();
        include_once $viewPath;
        return ob_get_clean();
    }
}