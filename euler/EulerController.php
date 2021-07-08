<?php
namespace euler;
require_once "EulerModel.php";
class EulerController
{
    private EulerModel $model;
    private $congruentCount = 0;

    public function __construct(int $prime)
    {
        $this->model = new EulerModel($prime);
    }

    public function countSolutions()
    {
        while ($this->model->increaseIntegers())
        {
            if ( $this->model->isCongruent()) $this->congruentCount++;
        }
        return $this->congruentCount;
    }

}
