<?php

namespace HttpRequestProcessorClasses;

use coreModel\RequestParameters;
use coreServices\DataForView;
use date\SundayCounter;

class Date
{
    public function displayCalendar()
    {
        require_once "view\calendar.html";
    }

    public function countSundays(RequestParameters $requestParameters)
    {
            $dv = DataForView::getInstance();
        try {

            $sc = new SundayCounter($requestParameters->getRequestData()["date"]);
            $dv->setValue("success", true);
            $dv->setValue("result", $sc->countSundays());
        }
        catch (\Exception $e)
        {
            $dv->setValue("success", false);
            $dv->setValue("result", $e->getMessage());
        }
        require_once "view/sundays.php";
    }
}
