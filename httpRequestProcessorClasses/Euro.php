<?php

namespace HttpRequestProcessorClasses;

use coreServices\DataForView;
use euro\EuroCalculator;

class Euro
{
    public function playChampionShip()
    {
        $ec = new EuroCalculator();
        $this->displayWinner($ec->getWinner());

    }

    private function displayWinner(string $winner)
    {
        (DataForView::getInstance())->setValue("winner", $winner);
        require_once "view/euro.php";
    }

    public function displayLog()
    {
        require_once "view/euroLog.php";
    }


}
