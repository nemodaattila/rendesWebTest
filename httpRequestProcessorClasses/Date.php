<?php

namespace HttpRequestProcessorClasses;

use coreModel\RequestParameters;
use coreServices\DataForView;
use date\SundayCounter;
use Exception;

/**
 * Class Date http request processor class - counting sundays
 * @package HttpRequestProcessorClasses
 */
class Date
{
    /**
     * displays calendar
     */
    public function displayCalendar()
    {
        require_once "view\calendar.html";
    }

    /**
     * counts and displays sundays that are first days of a month
     * @param RequestParameters $requestParameters parameter from http request
     */
    public function countSundays(RequestParameters $requestParameters)
    {
        $dv = DataForView::getInstance();
        try {
            $sc = new SundayCounter($requestParameters->getRequestData()["date"]);
            $dv->setValue("success", true);
            $dv->setValue("result", $sc->countSundays());
        } catch (Exception $e) {
            $dv->setValue("success", false);
            $dv->setValue("result", $e->getMessage());
        }
        require_once "view/sundays.php";
    }
}
