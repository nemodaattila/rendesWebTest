<?php

namespace euro;

class GroupStats
{
    private array $stats;

    public function __construct($group)
    {
        foreach ($group as $value)
        {
            $this->stats[$value]=["p"=>0,
                "w"=>0,
                "D"=>0,
                "L"=>0,
                "gf"=>0,
                "ga"=>0,
                "gd"=>0,
                "pts"=>0
                ];
        }
    }

    /**
     * @return array
     */
    public function getStats(): array
    {
        return $this->stats;
    }

}
