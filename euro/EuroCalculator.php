<?php

namespace euro;
require_once "AllTeamRosters.php";
require_once "Groups.php";
require_once "GroupStats.php";
require_once "FootballMatch.php";

class EuroCalculator
{
    private AllTeamRosters $rosters;
    private Groups $groups;
    private array $groupStatistics = [];
    private array $matchDays = [
        [[0, 1], [2, 3]],
//        [[0, 2], [1, 3]],
//        [[0, 3], [1, 2]],

    ];

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
        foreach ($this->groups->getGroups() as $key => $value) {
            $this->groupStatistics[$key] = new GroupStats($value);
        }
    }

    private function playGroupStage()
    {
        foreach ($this->matchDays as $matches) {
            [$first, $second] = $matches;
            var_dump($first);
            var_dump($second);
            foreach ($this->groupStatistics as $key => $value) {
                new FootballMatch($this->groups->getGroups()[$key][$first[0]], $this->groups->getGroups()[$key][$first[1]], $this->rosters);
                new FootballMatch($this->groups->getGroups()[$key][$second[0]], $this->groups->getGroups()[$key][$second[1]], $this->rosters);
            }
        }
    }

}
