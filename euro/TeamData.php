<?php

namespace euro;

/**
 * Class TeamData name and coefficient of all teams
 * @package euro
 */
class TeamData
{
    /**
     * @var array|array[] name and coefficient of all teams
     */
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
     * returns all teams
     * @return array team names and coefficient
     */
    public function getTeams(): array
    {
        return $this->teams;
    }

    /**
     * returns the name of a team based on id
     * @param int $id id of the team
     * @return string name of the team
     */
    public function getTeamName(int $id):string
    {
        return $this->teams[$id][0];
    }

    /**
     * returns the maximum and minimum of coefficients
     * @return array [min,max]
     */
    public function getMinMax(): array
    {
        $min = INF;
        $max = 0;
        foreach ($this->teams as $value) {
            if ($value[1] < $min) $min = $value[1];
            if ($value[1] > $max) $max = $value[1];
        }
        return [$min, $max];
    }
}
