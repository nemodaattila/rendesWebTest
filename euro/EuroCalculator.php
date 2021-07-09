<?php

namespace euro;
require_once "AllTeamRosters.php";
require_once "Groups.php";
require_once "GroupStats.php";
require_once "FootballMatch.php";
require_once "LogEvents.php";

class EuroCalculator
{
    private AllTeamRosters $rosters;
    private Groups $groups;
    private array $groupStatistics = [];
    private array $matchDays = [
        [[0, 1], [2, 3]],
        [[0, 2], [1, 3]],
        [[0, 3], [1, 2]],
    ];
    private array $succededTeams = [];
    private array $knockoutMatches = [];
    private int $winner;

    public function __construct()
    {
        LogEvents::emptyLog();
        $this->groups = new Groups();
        $this->generateTeams();
        $this->generateGroupDefaultStats();
        $this->playGroupStage();
        $this->getTeamsForEliminationRound();
        $this->drawKnockoutMatches();
        $this->playEliminationRound();
        $this->displayWinner();
    }

    private function generateTeams()
    {
        $this->rosters = AllTeamRosters::getInstance();
    }

    private function generateGroupDefaultStats()
    {
        foreach ($this->groups->getGroups() as $key => $value) {
            $this->groupStatistics[$key] = new GroupStats($value);
        }
    }

    private function playGroupStage()
    {
        foreach ($this->matchDays as $key => $matches) {
            LogEvents::log("Round " . ($key + 1) . ":<br/>");
            [$first, $second] = $matches;
            foreach ($this->groupStatistics as $key => $value) {
                LogEvents::log("Group " . $key . ":<br>");
                $match1 = new FootballMatch($this->groups->getGroups()[$key][$first[0]], $this->groups->getGroups()[$key][$first[1]]);
                $match2 = new FootballMatch($this->groups->getGroups()[$key][$second[0]], $this->groups->getGroups()[$key][$second[1]]);
                $this->changeGroupStats($key, [$this->groups->getGroups()[$key][$first[0]], $this->groups->getGroups()[$key][$first[1]]], $match1->getGoals());
                $this->changeGroupStats($key, [$this->groups->getGroups()[$key][$second[0]], $this->groups->getGroups()[$key][$second[1]]], $match2->getGoals());
                $this->groupStatistics[$key]->logResults();
            }

        }
    }

    private function changeGroupStats(string $group, array $teams, array $results)
    {
//        var_dump($this->groupStatistics);
        $this->groupStatistics[$group]->changeGroupStats($teams, $results);

    }

    private function getTeamsForEliminationRound()
    {
        $succeded = [];
        $third = [];
        foreach ($this->groupStatistics as $key => $value) {
            $keys = (array_keys($value->getStats()));
            $succeded[] = $keys[0];
            $succeded[] = $keys[1];
            $third[$keys[2]] = $value->getStats()[$keys[2]];
        }
        $thirdGroup = new GroupStats(array_keys($third));

        $thirdGroup->setExactValues($third);
        LogEvents::log("Harmadik helyezettek:");
        $thirdGroup->logResults();
        $third = array_keys($thirdGroup->getStats());
        unset($third[4]);
        unset($third[5]);
        $this->succededTeams = array_merge($succeded, $third);
        asort($this->succededTeams);
        $this->logSuccededTeams();
    }

    private function logSuccededTeams(int $num = 0)
    {
        if ($num === 0) {
            LogEvents::log("Teams qualified in knockout stage:");
        } else
            LogEvents::log("Teams won in knockout stage " . $num . ':');
        $teams = (new TeamData())->getTeams();
        foreach ($this->succededTeams as $value) {
            LogEvents::log($teams[$value][0]);
        }
    }

    private function playEliminationRound()
    {
        LogEvents::log("<br/>Knockout stage starts:<br>");
        for ($i = 1; $i < 5; $i++) {
            $this->succededTeams = [];
            LogEvents::log("Round " . ($i) . ":<br/>");
            for ($k = 0; $k < count($this->knockoutMatches); $k++) {
                [$t1, $t2] = $this->knockoutMatches[$k];
                $match = new FootballMatch($t1, $t2, true);
                [$g1, $g2] = ($match->getGoals());
                $this->succededTeams[] = ($g1 <=> $g2) === 1 ? $t1 : $t2;

//                $match2=new FootballMatch($this->groups->getGroups()[$key][$second[0]], $this->groups->getGroups()[$key][$second[1]]);
//                $this->changeGroupStats($key,[$this->groups->getGroups()[$key][$first[0]],$this->groups->getGroups()[$key][$first[1]]], $match1->getGoals());
//                $this->changeGroupStats($key,[$this->groups->getGroups()[$key][$second[0]],$this->groups->getGroups()[$key][$second[1]]], $match2->getGoals());
//                $this->groupStatistics[$key]->logResults();
            }

            if ($i < 4) {
                $this->logSuccededTeams($i);
                $this->drawKnockoutMatches(false);
            }
            $this->winner=$this->succededTeams[0];

        }
//        foreach ($this->matchDays as $key=>$matches) {
//            LogEvents::log("Round ".($key+1).":<br/>");
//            [$first, $second] = $matches;
//            foreach ($this->groupStatistics as $key => $value) {
//                LogEvents::log("Group ".$key.":<br>");
//
//            }
//
//        }
    }

    private function drawKnockoutMatches(bool $shuffle = true)
    {
        $this->knockoutMatches=[];
        if ($shuffle)
            shuffle($this->succededTeams);
        for ($i = 0; $i < count($this->succededTeams); $i += 2) {
            $this->knockoutMatches[] = [$this->succededTeams[$i], $this->succededTeams[$i + 1]];
        }
    }

    private function displayWinner()
    {
        $teams = (new TeamData())->getTeams();
        LogEvents::log("The Winner OF THE EURO 2020 Football Championship is:<br/>");
        LogEvents::log("!!!!!!!!!!!!!!! ".$teams[$this->winner][0].' !!!!!!!!!!!!!!!!!!!!!!');
        echo "The Winner OF THE EURO 2020 Football Championship is:<br/>";
        echo "!!!!!!!!!!!!!!! ".$teams[$this->winner][0].' !!!!!!!!!!!!!!!!!!!!!!';
    }

}
