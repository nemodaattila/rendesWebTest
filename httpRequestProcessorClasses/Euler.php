<?php

namespace HttpRequestProcessorClasses;

use coreModel\RequestParameters;
use coreServices\DataForView;
use DateTime;
use euler\EulerCounter;

/**
 * Class Euler http request processor class - euler problem 753
 * @package HttpRequestProcessorClasses
 */
class Euler
{
    /**
     * display input number for euler
     */
    public function displayInputNumber()
    {
        require_once "view\maxPrimeChooser.html";
    }

    /**
     * count and displays the solutions of euler problem 753
     * @param RequestParameters $requestParameters http request parameters
     */
    public function countSolutions(RequestParameters $requestParameters)
    {
        $oNum = $requestParameters->getRequestData()["euler"];
        $num = intval($oNum);
        $dv = DataForView::getInstance();
        if ($num !== 0) {
            require_once "euler/infResources.php";
            $startTime = (new DateTime())->getTimestamp();
            $euc = new EulerCounter($num);
            $dv->setValue("result", $euc->countSolutions());
            $endTime = (new DateTime())->getTimestamp();
            $elapsedTime = $endTime - $startTime;
            $minute = floor($elapsedTime / 60);
            $second = $elapsedTime % 60;
            $dv->setValue("success", true);
            $dv->setValue("prime", $num);
            $dv->setValue("time", [$minute, $second]);
        } else {
            $dv->setValue("success", false);
            $dv->setValue("result", "chosen prime value is not a number: " . $oNum);
        }
        require_once "view/eulerResult.php";
    }
}
