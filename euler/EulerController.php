<?php
namespace euler;
require_once "EulerModel.php";

/**
 * Class EulerController class for handling data for euler problem 753
 * iterates and checks combinations that matches the formula
 * @package euler
 */
class EulerController
{
    /**
     * @var EulerModel model for euler
     */
    private EulerModel $model;

    /**
     * @var int count of combination that matches the formula
     */
    private int $congruentCount = 0;

    public function __construct()
    {
        $this->model = new EulerModel();
    }

    /**
     * checks all variations if it matches the formula, up to given number
     * @param int $maxNumber the max number to which prime are analysed
     * @return int count of matching combinations
     */
    public function countSolutions(int $maxNumber): int
    {
        $this->model->setMaxPrime($maxNumber);
        while ($this->model->increaseIntegers())
        {
            $this->congruentCount+=$this->model->checkAllCongruent();
        }
        return $this->congruentCount;
    }

    /**
     * @param array $primes sets the model's primes property with primes to be used
     */
    public function setPrimes(array $primes)
    {
        $this->model->setPrimes($primes);
    }

    /**
     * @param array $power sets the model's power property with the power numbers to be used
     *  i.e [2=>8]
     */
    public function setPower(array $power)
    {
        $this->model->setPowers($power);
    }

}
