<?php

namespace euler;

class EulerModel
{

    private int $a = 1;
    private int $b = 1;
    private int $c = 0;

    private int $maxOfTree;
    private array $primes;

    private int $ABPower=2;


    /**
     * @param array $primes
     */
    public function setPrimes(array $primes): void
    {
        $this->primes = $primes;
        foreach ($this->primes as $value)
        {
            $this->counts[$value]=0;
        }
    }

    CONST POWER = 3;

    private int $maxPrime;

    /**
     * @param int $maxPrime
     */
    public function setMaxPrime(int $maxPrime): void
    {
        $this->maxPrime = $maxPrime;
    }




    /**
     * @return int
     */
    public function getA(): int
    {
        return $this->a;
    }

    /**
     * @return int
     */
    public function getB(): int
    {
        return $this->b;
    }

    /**
     * @return int
     */
    public function getC(): int
    {
        return $this->c;
    }

    public function writeOut()
    {
        echo $this->actualPrime." ".$this->getA() . ' ' . $this->getB() . ' ' . $this->getC() . " ".($this->a**self::POWER + $this->b**self::POWER) % $this->actualPrime." ".$this->c**self::POWER." <br/>";
    }

    public function increaseIntegers()
    {
        $this->c++;
        if ($this->c === $this->maxPrime) {
            $this->c = 1;
            $this->b++;
            $this->recountABPover();
            if ($this->b === $this->maxPrime)
            {
                $this->b = 1;
                $this->a++;
            }
            if ($this->a === $this->maxPrime)
                return false;
        }
        $this->maxOfTree = max($this->a,$this->b,$this->c);
        return 1;
    }

    private function recountABPover()
    {
        $this->ABPower=$this->a**self::POWER + $this->b**self::POWER;
    }

    public function isCongruent(int $c, int $prime): bool
    {
        return $this->ABPower % $prime === $c;
    }

    public function checkAllCongruent()
    {
        $c= $this->c**self::POWER;
        $count = 0;
        foreach( $this->primes as $value)
        {
            if ($value> $this->maxOfTree) {
                $true = $this->isCongruent($c, $value);
                $count += $true;
            }
        }
        return $count;
    }
}
