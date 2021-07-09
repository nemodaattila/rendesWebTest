<?php

namespace euro;

class GroupStats
{
    private array $stats;

    public function __construct($group)
    {
        $teams = (new TeamData())->getTeams();
        foreach ($group as $value) {
            $this->stats[$value] = [
                "name"=>$teams[$value][0],
                "p" => 0,
                "w" => 0,
                "d" => 0,
                "l" => 0,
                "gf" => 0,
                "ga" => 0,
                "gd" => 0,
                "pts" => 0
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

    public function changeGroupStats( array $teams, array $results)
    {
        [$t1,$t2]=$teams;
        [$g1,$g2]=$results;

        $this->stats[$t1]["p"]++;
        $this->stats[$t2]["p"]++;

        $this->stats[$t1]["gf"]+=$g1;
        $this->stats[$t2]["gf"]+=$g2;
        $this->stats[$t1]["ga"]+=$g2;
        $this->stats[$t2]["ga"]+=$g1;
        $this->stats[$t1]["gd"]=$this->stats[$t1]["gf"]-$this->stats[$t1]["ga"];
        $this->stats[$t2]["gd"]=$this->stats[$t2]["gf"]-$this->stats[$t2][  "ga"];


        if ($g1===$g2)
        {
            $this->stats[$t1]["d"]++;
            $this->stats[$t2]["d"]++;
        }
        if ($g1>$g2)
        {
            $this->stats[$t1]["w"]++;
            $this->stats[$t2]["l"]++;
        }
        if ($g1<$g2)
        {
            $this->stats[$t2]["w"]++;
            $this->stats[$t1]["l"]++;
        }
        $this->stats[$t1]["pts"]=3*$this->stats[$t1]['w']+$this->stats[$t1]['d'];
        $this->stats[$t2]["pts"]=3*$this->stats[$t2]['w']+$this->stats[$t2]['d'];

        uasort($this->stats, [$this,"sortData"]);

    }

    function sortData($a, $b) {
        $c = ($b['pts']<=> $a['pts']);
        if($c !== 0) {
            return $c;
        }

        $c = ($b['gd']<=> $a['gd']);
        if($c !== 0) {
            return $c;
        }

        return ($b['gf'] <=> $a['gf']);
    }

    public function logResults()
    {
        LogEvents::log("Group Results:");
        LogEvents::log("Name | Win | Draw | Lost | Goal For | Goal Away | Goal Diff | Points");
        foreach ($this->stats as $value)
        {
            LogEvents::log($value['name']." | ".$value['w']." | ".$value['d']." | ".$value['l']." | ".$value['gf']." | ".$value['ga']." | ".$value['gd']." | ".$value['pts']);
        }
        LogEvents::log("");
    }

    public function setExactValues(array $data)
    {
        foreach ($data as $key=>$value)
        {
            $this->stats[$key]=$value;
        }
        uasort($this->stats, [$this,"sortData"]);
    }

}
