<?php

namespace date;

use DateInterval;
use DateTime;
use Exception;

/**
 * Class SundayCounter searches for the count of sundays , that are the first day of a month, starting from given date
 * @package date
 */
class SundayCounter
{
    /**
     * @var DateTime chosen date by the user for display
     */
    private DateTime $chosenDate;
    /**
     * @var DateTime chosen date by user, for counting
     */
    private DateTime $date;

    /**
     * @var DateTime today's date
     */
    private DateTime $today;

    /**
     * @var int|null number of sundays that are the first day of a month
     */
    private ?int $numOfSundays;

    /**
     * SundayCounter constructor.
     * @param string $date chosen date by user
     * @throws Exception DateTime creation error
     */
    public function __construct(string $date)
    {
        $this->today = new DateTime();
        $this->date = $this->chosenDate = new DateTime(filter_var($date, FILTER_SANITIZE_STRING));
        $this->numOfSundays = 0;
    }

    /**
     * function calls for counting sundays
     * @return array [formatted chosen date, count of sundays]
     * @throws Exception DateInterval duration parsing
     */
    public function countSundays(): array
    {
        $this->setDateToNearestSunday();
        $this->iterateSundays();
        return [$this->chosenDate->format('Y-m-d'), $this->numOfSundays];
    }

    /**
     * sets the day to sunday AFTER the chosen date
     * @throws Exception DateInterval duration parsing
     */
    private function setDateToNearestSunday()
    {
        $day_of_week = intval($this->date->format('w'));
        $this->date->add(new DateInterval('P' . (($day_of_week !== 0) ? 7 - $day_of_week : 0) . 'D'));
    }

    /**
     *  increases the date with 1 week until today
     * checks every date if its the first of a month
     */
    private function iterateSundays()
    {
        while ($this->date < $this->today) {
            if ($this->isFirstDayOfMonth()) $this->numOfSundays++;
            $this->date->add(new DateInterval('P7D'));
        }
    }

    /**
     * checks if the actual data is the first day of a month
     * @return bool true : first day
     */
    private function isFirstDayOfMonth(): bool
    {
        return intval($this->date->format('d')) === 1;
    }
}
