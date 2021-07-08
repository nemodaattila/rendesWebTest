<?php

namespace euro;

class TeamData
{
    private array $teams = [
        1 => ["Austria", 1523],
        2 => ["Belgium", 1783],
        3 => ["Croatia", 1606],
        4 => ["Chech Republic", 1459],
        5 => ["Denmark", 1632],
        6 => ["England", 1687],
        7 => ["Finland", 1411],
        8 => ["France", 1757],
        9 => ["Germany", 1609],
        10 => ["Hungary", 1469],
        11 => ["Italy", 1642],
        12 => ["Netherlands", 1598],
        13 => ["North Macedonia", 1375],
        14 => ["Poland", 1550],
        15 => ["Portugal", 1666],
        16 => ["Russia", 1463],
        17 => ["Scotland", 1441],
        18 => ["Slovakia", 1475],
        19 => ["Spain", 1648],
        20 => ["Sweden", 1570],
        21 => ["SwitzerLand", 1606],
        22 => ["Turkey", 1505],
        23 => ["Ukraine", 1515],
        24 => ["Wales", 1570]
    ];

    /**
     * @return array
     */
    public function getTeams(): array
    {
        return $this->teams;
    }

    public function getMinMax()
    {
        $min = INF;
        $max = 0;
        foreach ($this->teams as $value)
        {
            if ($value[1]<$min) $min = $value[1];
            if ($value[1]>$max) $max = $value[1];
        }
        return [$min,$max];
    }
}
