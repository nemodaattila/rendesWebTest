<?php

namespace euro;

/**
 * Class TeamRosterCalculator generates a roster for a team
 * @package euro
 */
class TeamRosterCalculator
{
    /**
     * chances of getting better players i.e.: [50,85,98]=>common,uncommon, rare, legendary
     * @var array|int[][]
     */
    private array $playerStatIndicator = [
        [50, 85, 98],
        [42, 82, 97],
        [32, 77, 95],
        [16, 67, 90]
    ];
    /**
     * @var int|mixed value for counting the maximum player bonus indicator
     */
    private int $maxStatBonus;

    /**
     * @var int|mixed minimum player value
     */
    private int $min;

    public function __construct($minmax)
    {
        $this->maxStatBonus = $minmax[1] - $minmax[0];
        $this->min = $minmax[0];
    }

    /**
     * generates players based on coefficient
     * @param TeamRosters $team Full roster (empty)
     * @param array $teamData team name + coefficient
     * @return TeamRosters filled team roster
     */
    public function calculateTeam(TeamRosters $team, array $teamData): TeamRosters
    {
        $teamLevel = $teamData[1];
        $statLevel = $this->calculateStatIndicator($teamLevel);
        $team->addPlayers($this->generatePlayers($teamLevel, $statLevel));
        return $team;
    }

    /**
     * calculates player stat indicator category (playerStatIndicator)
     * @param int $stat team coefficient
     * @return int  indicator category
     */
    private function calculateStatIndicator(int $stat): int
    {
        $stat = (($stat - $this->min) / $this->maxStatBonus) * 4;
        return ($stat === 4) ? 3 : floor($stat);
    }

    /**
     * generates players based on coefficient and stat level indicator
     * @param int $teamLevel coefficient
     * @param int $statLevel stat level indicator
     * @return array generated players
     */
    private function generatePlayers(int $teamLevel, int $statLevel): array
    {
        $players = [];
        $statIndicator = $this->playerStatIndicator[$statLevel];
        for ($i = 0; $i < 26; $i++) {
            $stat = $teamLevel;
            $rand = mt_rand(0, 100) + 1;

            if ($rand < $statIndicator[0]) $stat *= (0.9 * mt_rand(5, 15) / 10);
            if ($rand < $statIndicator[1] && $rand > $statIndicator[0]) $stat *= (1.1 * mt_rand(5, 15) / 10);
            if ($rand < $statIndicator[2] && $rand > $statIndicator[1]) $stat *= (1.2 * mt_rand(5, 15) / 10);
            if ($rand > $statIndicator[2]) $stat *= (1.4 * mt_rand(5, 15) / 10);

            $players[] = [floor($stat), floor($stat)];
        }
        return $players;
    }

}
