<?php

namespace euro;

class PlayingTeam
{
    private array $primary=[];
    private array $reserve=[];
    private int $substituesUsed=0;

    public function __construct(TeamRosters $fullteam)
    {
        $fullteam->sortPlayers();
        $this->getActivePlayers($fullteam->getTeam());
    }

    private function getActivePlayers(array $fullTeam)
    {
        $i=0;
        foreach ($fullTeam as $num=>$player) {
            if ($i<11) $this->primary[$num]=$player;
            if ($i>11 && $i<15 ) $this->reserve[$num]=$player;
            $i++;
            if ($i===15) break;
        }
    }

    public function getOverallStat(): int
    {
        return array_sum($this->primary);
    }

    public function decreaseStats(int $value)
    {
        foreach ($this->primary as $key=>$stat)
        {
            $this->primary[$key]=$stat-$value;
        }
//        var_dump($this->primary);
    }

    public function decreaseStatsByPercent(int $value)
    {
        foreach ($this->primary as $key=>$stat)
        {
            $this->primary[$key]=floor($stat*((100-$value)/100));
        }
//        var_dump($this->primary);
    }


}
