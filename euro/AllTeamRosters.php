<?php

namespace euro;
require_once "TeamData.php";
require_once "TeamRosterCalculator.php";
require_once "TeamRosters.php";

class AllTeamRosters
{
    private TeamData $teamData;
    private TeamRosterCalculator $calculator;

    private array $rosters = [];

    public function __construct()
    {
        $this->teamData = new TeamData();
        $this->generateTeams();
    }

    private function generateTeams()
    {
        $minMax=$this->teamData->getMinMax();
        $this->calculator = new TeamRosterCalculator($minMax);
        foreach ($this->teamData->getTeams() as $index => $team)
        {

            $this->rosters[$index]=new TeamRosters();
            $this->addRoster($this->calculator->calculateTeam($this->rosters[$index], $team));
        }
    }

    private function addRoster(TeamRosters $team)
    {
        $this->rosters[]=$team;
    }
}
