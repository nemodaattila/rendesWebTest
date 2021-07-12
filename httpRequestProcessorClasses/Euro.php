<?php

namespace HttpRequestProcessorClasses;

use coreServices\DataForView;
use euro\EuroCalculator;
use euro\FormatLogDisplay;
use euro\LogEvents;

/**
 * Class Euro http request processor class - euro 2020
 * @package HttpRequestProcessorClasses
 */
class Euro
{
    /**
     * simulates the entire euro 2020 championship
     */
    public function playChampionShip()
    {
        $ec = new EuroCalculator();
        $this->displayWinner($ec->getWinner());
    }

    /**
     * displays the winner of the championship
     * @param string $winner
     */
    private function displayWinner(string $winner)
    {
        (DataForView::getInstance())->setValue("winner", $winner);
        require_once "view/euro.php";
    }

    /**
     * displays formatted log
     */
    public function displayLog()
    {
        $log = LogEvents::readAllLog();
        FormatLogDisplay::format($log);
        require_once "view/euroLog.php";
    }

}
