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
        
        throw new Exception("Route not found: {$method} {$path}");
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
        
        return $path ?: '/';
    }
    
    private function matchPath($routePath, $requestPath) {
        return $routePath === $requestPath;
    }
}
?>