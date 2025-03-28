<?php

namespace App;

class Router
{
    // Array to store all registered routes
    private array $routes = [];

    /**
     * Add a new route to the router.
     *
     * @param string $method The HTTP method (e.g., GET, POST).
     * @param string $pattern The regex pattern to match the URI.
     * @param callable $callback The callback function to handle the route.
     * @return void
     */
    public function addRoute(string $method, string $pattern, callable $callback): void
    {
        $this->routes[] = [
            'method' => $method,      // HTTP method for the route
            'pattern' => $pattern,    // URI pattern to match
            'callback' => $callback   // Function to execute when the route matches
        ];
    }

    /**
     * Dispatch the request to the appropriate route.
     *
     * @param string $uri The requested URI.
     * @param string $method The HTTP method of the request.
     * @return void
     */
    public function dispatch(string $uri, string $method): void
    {
        foreach ($this->routes as $route) {
            // Check if the HTTP method and URI pattern match the current route
            if ($method === $route['method'] && preg_match($route['pattern'], $uri, $matches)) {
                array_shift($matches); // Remove the full match from the matches array
                call_user_func_array($route['callback'], $matches); // Call the callback with any captured parameters
                return;
            }
        }

        // If no route matches, return a 404 response
        http_response_code(404);
        echo json_encode(['error' => 'Not Found']); // Return a JSON error message
    }
}
