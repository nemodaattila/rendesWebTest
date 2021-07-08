<?php

namespace euro;

class Groups
{
    private array $groups =[
        "A"=>[11,24,21,22],
        "B"=>[2,5,7,16],
        "C"=>[12,1,23,13],
        "D"=>[6,3,4,17],
        "E"=>[20,19,18,14],
        "F"=>[8,9,15,10]
    ];

    /**
     * @return array|\int[][]
     */
    public function getGroups(): array
    {
        return $this->groups;
    }
}
