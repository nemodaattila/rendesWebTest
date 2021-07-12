<?php

namespace routes;

/**
 * Class Routes possible routes for Http request Router
 * @package routes
 */
class Routes
{

    /**
     * possible routes, parameters: type [GET, POST, PUT, DELETE] | url path | called class | called function
     * | authenticated users
     * @var array|string[][]
     */

    private array $routes = [

        ['POST', "euler\count", "Euler", "countSolutions", "all"],
        ['GET', "euler", "Euler", "displayInputNUmber", "all"],
        ['POST', "date\sunday", "Date", "countSundays", "all"],
        ['GET', "date", "Date", "displayCalendar", "all"],
        ['GET', 'euro\readlog', "Euro", "displayLog", 'all'],
        ['GET', "euro", "Euro", "playChampionship", 'all'],
        ['GET', "", "Main", "loadMenu", 'all']
    ];

    /**
     * returns all routes
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }
}
