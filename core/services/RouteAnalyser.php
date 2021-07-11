<?php

namespace coreServices;

use coreModel\RequestParameters;
use routes\Routes;

/**
 * analyses the routeBase taken from url, and searches for a corresponding route
 * Class RouteAnalyser
 * @package service
 */
class RouteAnalyser
{
    /**
     * possible routes
     * @var array|string[][]
     */
    private array $routes;


    private array $routeBase = [];

    /**
     * @var RequestParameters http parameters from request
     */
    private RequestParameters $parameters;

    /**
     * @var array data of request processor class
     */
    private array $restData;

    public function getParameters(): RequestParameters
    {
        return $this->parameters;
    }

    public function getRestData(): array
    {
        return $this->restData;
    }

    public function __construct($routeBase)
    {
        $this->routeBase = $this->createNonEmptyArrayFromUrl($routeBase);
        $this->routes = (new Routes())->getRoutes();
        $this->parameters = new RequestParameters();
    }

    private function createNonEmptyArrayFromUrl(string $route): array
    {
        $url = explode('\\', $route);
        $url = array_filter($url);
        if ($url === []) $url = [''];
        return $url;
    }

    /**
     * @return bool iterates all routes and searches for the appropriate route for routeBase
     */
    public function processGivenRoute(): bool
    {
        foreach ($this->routes as $route) {
            $real = $this->identifyRoute($route[0], $route[1]);
            if ($real) {
                $this->restData = ['className' => $route[2], 'functionName' => $route[3], 'authentication' => $route[4]];
                return true;
            }
        }
        return false;
    }

    /**
     * checks if the routes is appropriate for route base
     * @param string $httpMethod http method i.e: GET
     * @param string $path url path
     * @return bool true if appropriate false if not
     */
    private function identifyRoute(string $httpMethod, string $path): bool
    {
        if ($httpMethod !== $_SERVER['REQUEST_METHOD'])
            return false;

        $path = str_replace(['//', '/'], "\\", $path);
        $path = explode('\\', $path);
        if (count($path) !== count($this->routeBase)) {
            return false;
        }
        $length = count($path);
        for ($i = 0; $i < $length; $i++) {

            if ($path[$i] !== $this->routeBase[$i]) {

                if (preg_match('/\$([0-9]+?)/', $path[$i]) !== 1) {
                    $this->parameters->reset();
                    return false;
                }
                $this->parameters->addUrlParameter(filter_var($this->routeBase[$i], FILTER_SANITIZE_STRING));
            }
        }
        return true;
    }

}
