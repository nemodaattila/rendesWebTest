<?php

namespace euler;

use Exception;

require_once "EulerController.php";

class EulerCounter
{

    //dont edit
    const PRIME_COUNT_UP_TO_1M = 78499;
    const MAX_PRIME = 1000000;

    private int $maxPrimeToCheck=0;
    private int $allSolutions = 0;
    private array $primes = [];
    private array $power = [];

    private string $primeFileSha1 = "d93e630802e375a228d57767b4ead46ef988e317";
    private string $powerFileSha1 = "d931c8ee166af26faab8df890a94819cf96a9874";



    private EulerController $euController;

    public function __construct(int $prime)
    {
        $this->maxPrimeToCheck = $prime;
        $this->euController = new EulerController();
        $this->checkPrimesInFile();
        $this->checkPowerFile();
    }

    private function checkPrimesInFile()
    {
        $file = "euler\primes.txt";

        if (file_exists($file) && sha1_file($file) === $this->primeFileSha1){

            $openFile = fopen($file, "r");
            while (!feof($openFile)) {
                $num = fgets($openFile);
                if (intval($num)<=$this->maxPrimeToCheck)  {
                    $this->primes[] = intval($num);
                }
                else
                {
                    break;
                }
            }
        }
        else
            $this->searchAndSavePrimes();

    }

        private function checkPowerFile()
    {
        $file="euler\power.txt";
        if (file_exists($file) && sha1_file($file) === $this->powerFileSha1){
            $openFile = fopen($file, "r");
            while (!feof($openFile)) {
                $nums = fgets($openFile);
                [$num,$power] = explode(',',$nums);
                if (intval($num)<=$this->maxPrimeToCheck)  {
                    $this->power[intval($num)] = intval($power);
                }
                else
                {
                    break;
                }
            }
        }
        else
            $this->searchAndSavePower();
    }

      private function searchAndSavePrimes()
    {
        $this->primes=[];
        $myfile = fopen("euler\primes.txt", "w") or die("Unable to open file!");
        for ($i = 1; $i <= self::MAX_PRIME; $i++) {
            if ($this->isPrime($i))
            {
                if ($i<=$this->maxPrimeToCheck)
                    $this->primes[]=$i;
                fwrite($myfile, $i."\n");
            }
        }
    }

        private function searchAndSavePower()
    {
        $this->power=[];
        $myfile = fopen("euler\power.txt", "w") or die("Unable to open file!");
        for ($i = 1; $i <= 1000000; $i++) {

            if ($i<=$this->maxPrimeToCheck)  {
                $this->power[$i]=$i**3;
            }
            fwrite($myfile, $i.",".$i**3 ."\n");
        }

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


//
//
//

//
    public function countSolutions(): int
    {
        $this->euController->setPrimes($this->primes);
        $this->euController->setPower($this->power);
        $this->allSolutions=$this->euController->countSolutions($this->maxPrimeToCheck);
////        $sum = 0;
////        for ($i = 1; $i <= self::MAXPRIME; $i++) {
////            if ($this->isPrime($i))
////                $sum++;
////        }
////        var_dump($sum);
//
////        for ($i = 1; $i <= self::MAXPRIME; $i++) {
////            if ($this->isPrime($i)) $this->countWithPrime($i);
////        }
//
        return $this->allSolutions;
    }
//
//
//
//    private function countWithPrime($prime)
//    {
//        $this->euController = new EulerController($prime);
//        $this->allSolutions += $this->euController->countSolutions();
//    }
//

//
//    private function getUsedPrimes()
//    {
//        if ($this->maxPrimeToCheck !== self::MAX_PRIME)
//        {
//            $i=0;
//            do
//            {
//                $i++;
//            }while($this->primes[$i]<=$this->maxPrimeToCheck);
//            array_splice($this->primes, $i);
//        }
//    }
//


}
