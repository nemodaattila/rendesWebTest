<?php

class SundayCounter
{
    private DateTime $chosenDate;
    private DateTime $today;
    private DateTime $date;
    private int $numOfSundays=0;

    public function __construct()
    {
        $this->today = new DateTime();
        $this->date = new DateTime(filter_input(INPUT_POST, "date", FILTER_SANITIZE_STRING));
        $this->chosenDate = new DateTime(filter_input(INPUT_POST, "date", FILTER_SANITIZE_STRING));
        var_dump($this);
    }

    public function countSundays()
    {
        $this->setDateToNearestSunday();
        $this->iteratateSundays();
        $this->displayResult();
    }

    private function setDateToNearestSunday()
    {
        $day_of_week = intval($this->date->format('w'));
        $offset = 0;
        $offset = ($day_of_week !== 0) ? 7 - $day_of_week : 0;
        $this->date->add(new DateInterval('P' . $offset . 'D'));
    }

    private function iteratateSundays()
    {
        while ($this->date < $this->today) {
            if ($this->isFirstDayofMonth()) $this->numOfSundays++;
            $this->date->add(new DateInterval('P7D'));

        }
    }

    private function isFirstDayofMonth()
    {
        return intval($this->date->format('d')) === 1;
    }

    private function displayResult()
    {
        var_dump($this->numOfSundays);
        echo "The count of Sundays since ".$this->chosenDate->format('Y-m-d')." is: ".$this->numOfSundays;
    }
}

$sc = new SundayCounter();
$sc->countSundays();


