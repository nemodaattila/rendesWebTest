<?php

namespace euler;

class EulerModel
{

    private int $a = 1;
    private int $b = 1;
    private int $c = 0;

    private int $prime;
    CONST POWER = 3;


    public function __construct(int $prime)
    {
        $this->prime = $prime;
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
        echo $this->prime." ".$this->getA() . ' ' . $this->getB() . ' ' . $this->getC() . " ".($this->a**self::POWER + $this->b**self::POWER) % $this->prime." ".$this->c**self::POWER." <br/>";
    }

    public function increaseIntegers()
    {
        $this->c++;
        if ($this->c === $this->prime) {
            $this->c = 1;
            $this->b++;
            if ($this->b === $this->prime)
            {
                $this->b = 1;
                $this->a++;
            }
            if ($this->a === $this->prime)
                return false;
        }
        return 1;
    }

    public function isCongruent(): bool
    {
        return ($this->a**self::POWER + $this->b**self::POWER) % $this->prime === $this->c**self::POWER;
    }
}
