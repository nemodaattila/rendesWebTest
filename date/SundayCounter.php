<?php
namespace date;
use DateInterval;
use DateTime;

class SundayCounter
{
    private DateTime $chosenDate;
    private DateTime $today;
    private DateTime $date;
    private ?int $numOfSundays=null;

    public function __construct(string $date)
    {
        $this->today = new DateTime();
        $this->date = new DateTime(filter_var( $date, FILTER_SANITIZE_STRING));
        $this->chosenDate = new DateTime(filter_var($date, FILTER_SANITIZE_STRING));
        $this->numOfSundays = 0;
        }

    public function countSundays():array
    {

        $this->setDateToNearestSunday();
        $this->iteratateSundays();
        return [$this->chosenDate->format('Y-m-d'), $this->numOfSundays];
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
}



