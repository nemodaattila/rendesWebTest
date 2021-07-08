<?php

namespace euro;

class TeamRosters
{

    private array $playerStats;

    public function addPlayers(array $players)
    {
        $this->playerStats = $players;
    }
}
