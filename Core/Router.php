<?php

namespace Core;

class Router
{
    protected $routes = [];
    protected $params = [];

    public function dispatch($url)
    {
        $queryParams = [];
        list($url, $queryParams) = $this->removeQueryStringVariables($url);

        if ($this->match($url)) {
            $controller = $this->params['controller'];
            $controller = $this->convertToStudlyCaps($controller);
            $controller = "App\\Controllers\\{$controller}Controller";

            if (class_exists($controller)) {
                $controller_object = new $controller();
                
                $action = $this->params['action'];
                $action = $this->convertToCamelCase($action);
                if (is_callable([$controller_object, $action])) {
                    // Extract route parameters (excluding controller and action)
                    $routeParams = array_filter($this->params, function($key) {
                        return $key !== 'controller' && $key !== 'action';
                    }, ARRAY_FILTER_USE_KEY);
                    
                    // Merge route parameters with query parameters
                    $allParams = array_merge($routeParams, $queryParams);
                    
                    $controller_object->$action(...array_values($allParams));
                    } else {
                        throw new \Exception("Method $action in controller $controller cannot be called directly");
                    }
            } else {
                throw new \Exception("Controller class $controller not found");
            }
        } else {
            throw new \Exception('No route matched.', 404);
        }
    }

    protected function convertToStudlyCaps($string)
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

    protected function convertToCamelCase($string)
    {
        return lcfirst($this->convertToStudlyCaps($string));
    }

    protected function match($url)
    {
        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                foreach ($matches as $key => $match) {
                    if (is_string($key)) {
                        $params[$key] = $match;
                    }
                }

                $this->params = $params;
                return true;
            }
        }

        return false;
    }

    protected function removeQueryStringVariables($url)
    {
        $queryParams = [];
        if ($url != '') {
            $parts = explode('&', $url, 2);
            $parts[0] = trim($parts[0], '/?');
            foreach ($parts as $part) {
                if (strpos($part, '=') !== false) {
                    list($key, $value) = explode('=', $part, 2);
                    $queryParams[$key] = $value;
                }
            }
            if (strpos($parts[0], '=') === false) {
                $url = $parts[0];
            } else {
                $url = '';
            }
        }

        return [$url, $queryParams];
    }

    public function add($route, $params = [])
    {
        $route = preg_replace('/\//', '\\/', $route);
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-zA-Z0-9-]+)', $route);
        $route = '/^' . $route . '$/i';
        $this->routes[$route] = $params;
    }
}