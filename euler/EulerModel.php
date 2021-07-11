<?php

namespace euler;

class EulerModel
{

    private int $a = 1;
    private int $b = 1;
    private int $c = 0;

    private int $maxOfTree;
    private array $primes;
    private array $power;

    private int $ABPower=2;

    private int $maxPrime;

    /**
     * @param array $power
     */
    public function setPower(array $power): void
    {
        $this->power = $power;
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

            if ($this->b === $this->maxPrime)
            {
                $this->b = 1;
                $this->a++;
            }
            $this->recountABPover();
            if ($this->a === $this->maxPrime)
                return false;
        }
        $this->maxOfTree = max($this->a,$this->b,$this->c);
        return 1;
    }

    private function recountABPover()
    {
        $this->ABPower=$this->power[$this->a] +
            $this->power[$this->b];

    }

    public function isCongruent(int $c, int $prime): bool
    {
        return $this->ABPower % $prime === $this->power[$this->c];
    }

    public function checkAllCongruent()
    {
        $c= $this->power[$this->c];
        $count = 0;
        foreach( $this->primes as $value)
        {
            if ($value<= $this->maxOfTree) {
                $true = $this->isCongruent($c, $value);



                $count += $true;
            }
        }
        return $count;
    }
}
