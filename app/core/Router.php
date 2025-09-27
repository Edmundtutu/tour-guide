<?php

class Router {
    private $routes = [];
    
    public function addRoute($method, $path, $controller, $action) {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'controller' => $controller,
            'action' => $action
        ];
    }
    
    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = $this->getPath();
        
        // Debug information (remove in production)
        if (isset($_GET['debug']) && $_GET['debug'] === 'routes') {
            $this->debugRoutes($method, $path);
            return;
        }
        
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $this->matchPath($route['path'], $path)) {
                $controllerName = $route['controller'];
                $actionName = $route['action'];
                
                if (!class_exists($controllerName)) {
                    throw new Exception("Controller {$controllerName} not found");
                }
                
                $controller = new $controllerName();
                
                if (!method_exists($controller, $actionName)) {
                    throw new Exception("Action {$actionName} not found in {$controllerName}");
                }
                
                return $controller->$actionName();
            }
        }
        
        // Try to find similar routes for better error messages
        $similarRoutes = $this->findSimilarRoutes($method, $path);
        $errorMessage = "Route not found: {$method} {$path}";
        if (!empty($similarRoutes)) {
            $errorMessage .= "\nSimilar routes found:\n" . implode("\n", $similarRoutes);
        }
        
        throw new Exception($errorMessage);
    }
    
    private function debugRoutes($method, $path) {
        echo "<h2>Router Debug Information</h2>";
        echo "<p><strong>Request Method:</strong> {$method}</p>";
        echo "<p><strong>Request Path:</strong> {$path}</p>";
        echo "<p><strong>Total Routes:</strong> " . count($this->routes) . "</p>";
        
        echo "<h3>Available Routes:</h3>";
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>Method</th><th>Path</th><th>Controller</th><th>Action</th></tr>";
        
        foreach ($this->routes as $route) {
            $highlight = ($route['method'] === $method && $route['path'] === $path) ? 'style="background-color: yellow;"' : '';
            echo "<tr {$highlight}>";
            echo "<td>{$route['method']}</td>";
            echo "<td>{$route['path']}</td>";
            echo "<td>{$route['controller']}</td>";
            echo "<td>{$route['action']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    private function findSimilarRoutes($method, $path) {
        $similar = [];
        foreach ($this->routes as $route) {
            if ($route['method'] === $method) {
                $similarity = similar_text($route['path'], $path, $percent);
                if ($percent > 50) {
                    $similar[] = "  - {$route['path']} ({$percent}% similar)";
                }
            }
        }
        return $similar;
    }
    
    private function getPath() {
        $path = $_SERVER['REQUEST_URI'];
        
        // Remove query string
        if (($pos = strpos($path, '?')) !== false) {
            $path = substr($path, 0, $pos);
        }
        
        // Remove base path if running in subdirectory
        $basePath = str_replace('/public', '', dirname($_SERVER['SCRIPT_NAME']));
        if ($basePath !== '/' && strpos($path, $basePath) === 0) {
            $path = substr($path, strlen($basePath));
        }
        
        // Remove /public/ from path if present
        if (strpos($path, '/public/') === 0) {
            $path = substr($path, 8);
        }
        
        // Ensure path starts with /
        if (empty($path) || $path[0] !== '/') {
            $path = '/' . $path;
        }
        
        return $path;
    }
    
    private function matchPath($routePath, $requestPath) {
        return $routePath === $requestPath;
    }
}
