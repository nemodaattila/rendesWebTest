<?php

namespace euler;
require_once "EulerController.php";

class EulerCounter
{

    const MAXPRIME = 200;
    private int $allSolutions = 0;

    private EulerController $euController;

    public function countSolutions(): int
    {
        for ($i = 1; $i <= self::MAXPRIME; $i++) {
            if ($this->isPrime($i)) $this->countWithPrime($i);
        }

        return $this->allSolutions;
    }

    private function countWithPrime($prime)
    {
        $this->euController = new EulerController($prime);
        $this->allSolutions += $this->euController->countSolutions();
    }


    function isPrime($num): bool
    {
        for ($i = 2; $i <= sqrt($num); $i++) {
            if ($num % $i == 0) {
                return 0;
            }
        }
        return 1;
    }



}
