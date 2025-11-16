<?php
// router básico para manejar rutas
class Router {
    private $routes = [];

    // registra ruta GET
    public function get($path, $controller, $action) {
        $this->routes['GET'][$path] = ['controller' => $controller, 'action' => $action];
    }

    // registra ruta POST
    public function post($path, $controller, $action) {
        $this->routes['POST'][$path] = ['controller' => $controller, 'action' => $action];
    }

    // busca y ejecuta la ruta
    public function dispatch($request, $method) {
        // exacta
        if (isset($this->routes[$method][$request])) {
            $route = $this->routes[$method][$request];
            $this->callController($route['controller'], $route['action']);
            return;
        }

        // con parámetros
        foreach ($this->routes[$method] as $path => $route) {
            if ($this->matchRoute($path, $request, $params)) {
                $_GET = array_merge($_GET, $params);
                $this->callController($route['controller'], $route['action']);
                return;
            }
        }

        // si no existe
        $this->callController('ErrorController', 'notFound');
    }

    // revisa si ruta con params coincide
    private function matchRoute($pattern, $path, &$params) {
        $patternRegex = '#^' . preg_replace('#:[^/]+#', '([^/]+)', $pattern) . '$#';

        if (preg_match($patternRegex, $path, $matches)) {
            array_shift($matches);
            $paramNames = [];
            if (preg_match_all('#:([^/]+)#', $pattern, $paramMatches)) {
                $paramNames = $paramMatches[1];
            }

            foreach ($paramNames as $i => $name) {
                $params[$name] = $matches[$i] ?? null;
            }
            return true;
        }
        return false;
    }

    // llama al controlador y acción
    private function callController($controller, $action) {
        require_once CONTROLLERS_PATH . $controller . '.php';
        $controllerClass = new $controller();
        $controllerClass->$action();
    }
}
