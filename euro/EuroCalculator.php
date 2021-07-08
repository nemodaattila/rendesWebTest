<?php

namespace euro;
require_once "AllTeamRosters.php";
require_once "Groups.php";
require_once "GroupStats.php";

class EuroCalculator
{
    private AllTeamRosters $rosters;
    private Groups $groups;
    private array $groupStatistics=[];

    public function __construct()
    {
        $this->groups = new Groups();
        $this->generateTeams();
        $this->generateGroupDefaultStats();
        $this->playGroupStage();
    }

    private function generateTeams()
    {
        $this->rosters = new AllTeamRosters();
    }

    private function generateGroupDefaultStats()
    {
        foreach ($this->groups->getGroups() as $key=>$value) {
            $this->groupStatistics[$key]=new GroupStats($value);
        }
    }

    private function playGroupStage()
    {
        for ()
    }

}
