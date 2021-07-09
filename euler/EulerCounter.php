<?php

namespace euler;

use Exception;

require_once "EulerController.php";

class EulerCounter
{

    //dont edit
    const PRIME_COUNT_UP_TO_1M = 78499;
    const MAX_PRIME = 1000000;

    const MAX_PRIME_TO_CHECK = 1000;
    private int $allSolutions = 0;
    private array $primes = [];



    private EulerController $euController;

    public function __construct()
    {
        $this->euController = new EulerController();
        $this->checkPrimesInFile();
    }

    private function checkPrimesInFile()
    {
        if (file_exists("primes.txt")) {
            $myfile = fopen("primes.txt", "r");
            while (!feof($myfile)) {
                $num = fgets($myfile);
                if (intval($num)!==0)
                    $this->primes[] = intval($num);
            }
            if (count($this->primes) !== self::PRIME_COUNT_UP_TO_1M)
            {
                $this->searchAndSavePrimes();
            }

        }
        else
            $this->searchAndSavePrimes();
    }

    private function searchAndSavePrimes()
    {
        $this->primes=[];
        $myfile = fopen("primes.txt", "w") or die("Unable to open file!");
        for ($i = 1; $i <= self::MAX_PRIME; $i++) {
            if ($this->isPrime($i))
            {
                $this->primes[]=$i;
                fwrite($myfile, $i."\n");
            }
        }

        var_dump($this->primes);
    }

    public function countSolutions(): int
    {
        $this->getUsedPrimes();
        $this->euController->setPrimes($this->primes);
        $this->allSolutions=$this->euController->countSolutions(self::MAX_PRIME_TO_CHECK);
//        $sum = 0;
//        for ($i = 1; $i <= self::MAXPRIME; $i++) {
//            if ($this->isPrime($i))
//                $sum++;
//        }
//        var_dump($sum);

//        for ($i = 1; $i <= self::MAXPRIME; $i++) {
//            if ($this->isPrime($i)) $this->countWithPrime($i);
//        }

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

    private function getUsedPrimes()
    {
        if (self::MAX_PRIME_TO_CHECK !== self::MAX_PRIME)
        {
            $i=0;
            do
            {
                $i++;
            }while($this->primes[$i]<=self::MAX_PRIME_TO_CHECK);
            array_splice($this->primes, $i);
        }
    }

}
