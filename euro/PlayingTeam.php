<?php

namespace euro;

class PlayingTeam
{
    private string $name;
    private array $primary=[];
    private array $reserves=[];
    private array $substituted=[];
    private int $substituesUsed=0;
    CONST MAX_RESERVE_STAT = 500;

    public function __construct(TeamRosters $fullteam)
    {
        $fullteam->sortPlayers();
//        var_dump($fullteam);
        $this->name = $fullteam->getName();
        $this->getActivePlayers($fullteam->getTeam());
    }

    private function getActivePlayers(array $fullTeam)
    {
        $i=0;
        foreach ($fullTeam as $num=>$player) {
            if ($player[0]>0) {
                if ($i < 11) $this->primary[$num] = $player[0];
                if ($i > 11 && $i < 15) $this->reserves[$num] = $player[0];
                $i++;
                if ($i === 15) break;
            }
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

    public function checkReservesAndInjuries(int $num): int
    {
        $this->checkInjuries($num);
        $this->checkReserveNeeds($num);
        $count = count($this->primary);
       return $count;
    }

    private function checkInjuries(int $num)
    {
        $tired = $this->findStatUnder500();
        foreach ($tired as $key=>$value)
        {
            $injuryChance = 0;
            if ($value<500 && $value>0) $injuryChance = 1;
            if ($value<0) $injuryChance = 4;
            if ($injuryChance>0)
            {
                $rand = mt_rand(0,100);
                if ($rand<$injuryChance)
                {
//                    var_dump($tired);
//                    var_dump([$rand, $injuryChance]);
//                    var_dump($key);
                    LogEvents::log($num.". minute: - Injury " . $this->name." player: ".$key);
                    $this->playerBecameInjured($key);
                }
            }
        }
    }

    private function checkReserveNeeds(int $num)
    {
        if ($this->substituesUsed < 3)
        {
            $tired = $this->findStatUnder500();
            $reserveChance = floor((count($tired)*$num)/self::MAX_RESERVE_STAT*100);
//            var_dump($tired);
//            var_dump($reserveChance);
            $rand=mt_rand(0,100);
            if ($rand<$reserveChance)
            {
                LogEvents::log($num.". minute: Substitute: ". $this->name);
                $this->replaceAPlayer(array_key_first($tired));
            }
        }
    }

    private function playerBecameInjured(int $num)
    {
        $this->primary[$num]-=1500;
        if ($this->substituesUsed < 3)
        {
            $this->replaceAPlayer($num);
        }
        else
        {
            $this->substituted[$num]=$this->primary[$num];
            unset($this->primary[$num]);
        }
        if (count($this->primary)<11)
        {
            LogEvents::log($this->name.' plays with '.count($this->primary)." players");
        }

    }

    private function replaceAPlayer(int $player)
    {
        $this->substituted[$player]=$this->primary[$player];
        unset($this->primary[$player]);
        $newPlayer = array_key_first($this->reserves);
        $this->primary[$newPlayer]=$this->reserves[$newPlayer];
        unset($this->reserves[$newPlayer]);
        $this->substituesUsed++;
        LogEvents::log("replace ".$player. " with ". $newPlayer);

    }

    private function findStatUnder500()
    {
        $tired = array_filter($this->primary, function ($value){
            return $value<500;
        });
        asort($tired);
        return $tired;
    }

    public function refreshRoster(TeamRosters $team)
    {
        foreach ($this->primary as $key=>$value)
        {
            $team->setPLayerStat($key,$value);
        }
        foreach ($this->reserves as $key=>$value)
        {
            $team->setPLayerStat($key,$value);
        }
        foreach ($this->substituted as $key=>$value)
        {
            $team->setPLayerStat($key,$value);
        }
        return $team;
    }

}
