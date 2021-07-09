<?php

namespace euro;

class TeamRosters
{
    private string $name;



    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    private array $playerStats;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function addPlayers(array $players)
    {
        $this->playerStats = $players;
    }

    public function sortPlayers()
    {
        arsort($this->playerStats);
    }

    public function getTeam(): array
    {
        return $this->playerStats;
    }

    public function setPLayerStat(int $num, int $value)
    {
        $this->playerStats[$num][0]=$value;
    }

    public function restFor3Days()
    {
        foreach ($this->playerStats as $key=>$value)
        {
            $this->playerStats[$key][0]=min($value[0]+1500, $value[1]);
        }
    }
}
