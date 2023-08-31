<?php

namespace libs;
use App\Controllers\ResponseController;

class Route{    

    private static $routes = [];

    public static function get($uri, $callback){
        self::$routes['GET'][$uri] = $callback;
    }

    public static function post($uri, $callback){
        self::$routes['POST'][$uri] = $callback;
    }

    public static function delete($uri, $callback){
        self::$routes['DELETE'][$uri] = $callback;
    }

    public static function put($uri, $callback){
        self::$routes['PUT'][$uri] = $callback;
    }
    
    public static function dispatch($uri, $method){

        $targetRoutes = self::$routes[$method] ?? [];

        $GLOBALS['matches'] = [];
        $matchRoutes = self::getRoutesMatches($uri, $method);

        if(count($matchRoutes) === 0){

            $httpMethods = array_keys(self::$routes);
            $otherMethodMatches = [];
            foreach($httpMethods as $httpMethod){

                if($httpMethod === $method) continue;

                $matchesRoutes = self::getRoutesMatches($uri, $httpMethod);
                if(count($matchesRoutes) > 0) $otherMethodMatches[$httpMethod] = self::getRoutesMatches($uri, $httpMethod);
                
            }

            $responseController = new ResponseController();

            if(count($otherMethodMatches) > 0){
                $responseController->httpResponse(HTTP_CODE_METHOD_NOT_ALLOWED, 'Method not allowed', ['allowedMethods' => array_keys($otherMethodMatches)]);
            }else{
                $responseController->httpResponse(HTTP_CODE_NOT_FOUND, 'Not found', []);
            }

        }else{
            $callback = end($matchRoutes);
            $controller = $callback[0];
            $method = $callback[1];
            $controller = new $controller;
            $controller->$method(...array_slice($GLOBALS['matches'], 1));
        }


    }

    private static function getRoutesMatches($uri, $method){
        $targetRoutes = self::$routes[$method] ?? [];

        $GLOBALS['matches'] = [];
        $matchRoutes = array_filter($targetRoutes, function($route) use ($uri){
            if(strpos($route, ':')) $route = preg_replace('#:([a-zA-Z0-9]+)#', '([a-zA-Z0-9]+)', $route);
            $res = preg_match("#^$route$#", $uri, $matches);
            if($res) $GLOBALS['matches'] = $matches;
            return $res;
        }, ARRAY_FILTER_USE_KEY);

        return $matchRoutes;
    }


}