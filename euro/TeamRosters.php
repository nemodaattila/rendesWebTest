<?php

namespace euro;

/**
 * Class TeamRosters full roster of on team (id + overall score)
 * @package euro
 */
class TeamRosters
{
    /**
     * @var string name of the team
     */
    private string $name;

    /**
     * @var array statistics of player [id=>score]
     */
    private array $playerStats;

    public function getName(): string
    {
        return $this->name;
    }

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @param array $players adds a new player
     */
    public function addPlayers(array $players)
    {
        $this->playerStats = $players;
    }

    /**
     * sorts players by score, descending
     */
    public function sortPlayers()
    {
        arsort($this->playerStats);
    }

    /**
     * returns all players stats
     * @return array stats
     */
    public function getTeam(): array
    {
        return $this->playerStats;
    }

    /**
     * sets a players score to a value
     * @param int $num id of the player
     * @param int $value score to be set
     */
    public function setPLayerStat(int $num, int $value)
    {
        $this->playerStats[$num][0] = $value;
    }

    /**
     * increases the score of all players, capped to original score
     * (rest between 2 match)
     */
    public function restFor3Days()
    {
        foreach ($this->playerStats as $key => $value) {
            $this->playerStats[$key][0] = min($value[0] + 1500, $value[1]);
        }
    }
}
