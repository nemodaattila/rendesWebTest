<?php

namespace euler;

require_once "EulerController.php";

/**
 * Class EulerCounter class for initializing data for Euler problem 753
 * @package euler
 */
class EulerCounter
{
    /**
     * maximum number of the interval (from 0) that the maximum prime can be
     */
    const MAX_PRIME = 1000000;

    /**
     * @var int maximum prime to be checked in the formula
     */
    private int $maxPrimeToCheck;

    /**
     * @var array array of possible primes (up to maxPrimeToCheck or MAX_PRIME)
     */
    private array $primes = [];

    /**
     * @var array array of possible power of numbers ( of 3) (up to maxPrimeToCheck or MAX_PRIME)
     * i.e: [2=>3]
     */
    private array $power = [];

    /**
     * @var string hash of the file that contains the primes
     */
    private string $primeFileSha1 = "d93e630802e375a228d57767b4ead46ef988e317";

    /**
     * @var string hash of the file that contains the power of numbers
     */
    private string $powerFileSha1 = "d931c8ee166af26faab8df890a94819cf96a9874";

    private EulerController $euController;

    public function __construct(int $prime)
    {
        $this->maxPrimeToCheck = $prime;
        $this->euController = new EulerController();
        $this->checkPrimesInFile();
        $this->checkPowerFile();
    }

    /**
     * checks if the file for primes exists, and it's hash is ok
     * if ok reads primes up to maxPrimeToCheck
     * else it calls prime search function searchAndSavePrimes
     */
    private function checkPrimesInFile()
    {
        $file = "euler\primes.txt";

        if (file_exists($file) && sha1_file($file) === $this->primeFileSha1) {

            $openFile = fopen($file, "r");
            while (!feof($openFile)) {
                $num = fgets($openFile);
                if (intval($num) <= $this->maxPrimeToCheck) {
                    $this->primes[] = intval($num);
                } else {
                    break;
                }
            }
        } else
            $this->searchAndSavePrimes();

    }

    /**
     * checks if the file for power of numbers exists, and it's hash is ok
     * if ok reads numbers up to maxPrimeToCheck
     * else it calls search function searchAndSavePower
     */
    private function checkPowerFile()
    {
        $file = "euler\power.txt";
        if (file_exists($file) && sha1_file($file) === $this->powerFileSha1) {
            $openFile = fopen($file, "r");
            while (!feof($openFile)) {
                $nums = fgets($openFile);
                [$num, $power] = explode(',', $nums);
                if (intval($num) <= $this->maxPrimeToCheck) {
                    $this->power[intval($num)] = intval($power);
                } else {
                    break;
                }
            }
        } else
            $this->searchAndSavePower();
    }

    /**
     * searches for primes up to 1 000 000
     * saves primes into variable up to maxPrimeToCheck
     */
    private function searchAndSavePrimes()
    {
        $this->primes = [];
        $file = fopen("euler\primes.txt", "w") or die("Unable to open file!");
        for ($i = 1; $i <= self::MAX_PRIME; $i++) {
            if ($this->isPrime($i)) {
                if ($i <= $this->maxPrimeToCheck)
                    $this->primes[] = $i;
                fwrite($file, $i . "\n");
            }
        }
    }

    /**
     * calculates power (of 3) of numbers up to 1 000 000
     * saves into variable up to maxPrimeToCheck
     */
    private function searchAndSavePower()
    {
        $this->power = [];
        $file = fopen("euler\power.txt", "w") or die("Unable to open file!");
        for ($i = 1; $i <= 1000000; $i++) {

            if ($i <= $this->maxPrimeToCheck) {
                $this->power[$i] = $i ** 3;
            }
            fwrite($file, $i . "," . $i ** 3 . "\n");
        }

    }

    /**
     * @param int $num checks if a number is prime
     * @return bool true -> prime
     */
    function isPrime(int $num): bool
    {
        for ($i = 2; $i <= sqrt($num); $i++) {
            if ($num % $i == 0) {
                return 0;
            }
        }
        return 1;
    }

    /**
     * sends to primes and powers of numbers to controller
     * calls function for matching combinations
     * @return int count of matching combinations for euler problem 753
     */
    public function countSolutions(): int
    {
        $this->euController->setPrimes($this->primes);
        $this->euController->setPower($this->power);
        return $this->euController->countSolutions($this->maxPrimeToCheck);
    }

}
