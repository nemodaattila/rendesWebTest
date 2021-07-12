<?php

namespace euler;

/**
 * Class EulerModel model for counting the solution for Euler problem 753
 * @package euler
 */
class EulerModel
{

    private int $a = 1;
    private int $b = 1;
    private int $c = 0;

    /**
     * @var int the maximum of a,b and c
     */
    private int $maxOfTree;

    /**
     * @var array primes up to maxPrime
     */
    private array $primes;

    /**
     * @var array power (of 3) of numbers up to maxPrime
     */
    private array $powers;

    /**
     * @var int the sum of a^3 + b^3
     */
    private int $ABPower = 2;

    /**
     * @var int the max of an interval what a prime can be
     */
    private int $maxPrime;

    /**
     * @param array $powers
     */
    public function setPowers(array $powers): void
    {
        $this->powers = $powers;
    }

    /**
     * @param array $primes
     */
    public function setPrimes(array $primes): void
    {
        $this->primes = $primes;
    }

    /**
     * @param int $maxPrime
     */
    public function setMaxPrime(int $maxPrime): void
    {
        $this->maxPrime = $maxPrime;
    }

    /**
     * increases the numbers (a,b,c) with 1
     * first c , then if c is maxPrime , increases b (and sets c to 1)
     * finally, if b === maxPrime, increases a
     * @return bool if (a === maxPrime) false
     */
    public function increaseIntegers(): bool
    {
        $this->c++;
        if ($this->c === $this->maxPrime) {
            $this->c = 1;
            $this->b++;
            if ($this->b === $this->maxPrime) {
                $this->b = 1;
                $this->a++;
            }
            $this->ABPower = $this->powers[$this->a] + $this->powers[$this->b];
            if ($this->a === $this->maxPrime)
                return false;
        }
        $this->maxOfTree = max($this->a, $this->b, $this->c);
        return true;
    }

    /**
     * iterates primes up to maxValue (and maxOfTree)
     * count all solutions that matches the formula
     * @return int count of matching solutions
     */
    public function checkAllCongruent(): int
    {
        $c = $this->powers[$this->c];
        $count = 0;
        foreach ($this->primes as $prime) {
            if ($prime <= $this->maxOfTree) {
                $true = $this->ABPower % $prime === $c;
                $count += $true;
            } else
                break;
        }
        return $count;
    }
}
