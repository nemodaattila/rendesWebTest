<?php
namespace euler;
require_once "EulerModel.php";
class EulerController
{
    private EulerModel $model;

    private $congruentCount = 0;

    public function __construct()
    {
        $this->model = new EulerModel();
    }

    public function countSolutions(int $prime)
    {
        $this->model->setMaxPrime($prime);
        while ($this->model->increaseIntegers())
        {
            $this->congruentCount+=$this->model->checkAllCongruent();
        }
        return $this->congruentCount;
    }

    public function setPrimes(array $primes)
    {
        $this->model->setPrimes($primes);
    }

}
