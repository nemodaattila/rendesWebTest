<?php

namespace euro;

/**
 * class for virtualising the entire euro 2020 championship
 * Class EuroCalculator
 * @package euro
 */
class EuroCalculator
{
    /**
     * @var AllTeamRosters model class for the roster of all team
     */
    private AllTeamRosters $rosters;

    /**
     * @var Groups model class for the groups in the group stage
     */
    private Groups $groups;

    /**
     * @var array array<GroupStats> statistics for all groups in group stage
     */
    private array $groupStatistics = [];

    /**
     * @var array|\int[][][] fixtures in matchdays (with team numbers)
     */
    private array $matchDays = [
        [[0, 1], [2, 3]],
        [[0, 2], [1, 3]],
        [[0, 3], [1, 2]],
    ];

    /**
     * @var array teams advanced to knockout stage (changes woth every level)
     */
    private array $succededTeams = [];

    /**
     * @var array mathes in the knockout stage (with team numnbers) (changes with every level)
     */
    private array $knockoutMatches = [];

    /**
     * @var int winner of the tornament
     */
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
    }

    /**
     * generates every team's players
     */
    private function generateTeams()
    {
        $this->rosters = AllTeamRosters::getInstance();
    }

    /**
     * generates starting statistics for every group in group stage
     */
    private function generateGroupDefaultStats()
    {
        foreach ($this->groups->getGroups() as $key => $value) {
            $this->groupStatistics[$key] = new GroupStats($value);
        }
    }

    /**
     * simulates every match in group stage
     */
    private function playGroupStage()
    {
        foreach ($this->matchDays as $key => $matches) {
            LogEvents::log("GR," . ($key + 1));
            [$first, $second] = $matches;
            foreach ($this->groupStatistics as $key => $value) {
                LogEvents::log("G," . $key);
                $match1 = new FootballMatch($this->groups->getGroups()[$key][$first[0]], $this->groups->getGroups()[$key][$first[1]]);
                $match2 = new FootballMatch($this->groups->getGroups()[$key][$second[0]], $this->groups->getGroups()[$key][$second[1]]);
                $this->changeGroupStats($key, [$this->groups->getGroups()[$key][$first[0]], $this->groups->getGroups()[$key][$first[1]]], $match1->getGoals());
                $this->changeGroupStats($key, [$this->groups->getGroups()[$key][$second[0]], $this->groups->getGroups()[$key][$second[1]]], $match2->getGoals());
                $this->groupStatistics[$key]->logResults();
            }

        }
    }

    /**
     * updates the group statistics based on match result
     * @param string $group group index
     * @param array $teams the two teams that played
     * @param array $results match result
     */
    private function changeGroupStats(string $group, array $teams, array $results)
    {
        $this->groupStatistics[$group]->refreshGroupStats($teams, $results);

    }

    /**
     * gets the 4 best teams of the group thirds
     */
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
        LogEvents::log("AHGT");
        $thirdGroup->logResults();
        $third = array_keys($thirdGroup->getStats());
        unset($third[4]);
        unset($third[5]);
        $this->succededTeams = array_merge($succeded, $third);
        asort($this->succededTeams);
        $this->logSuccededTeams();
    }

    /**
     * logs succeded teams for round of the knockout stage
     */
    private function logSuccededTeams(int $num = 0)
    {
        if ($num === 0) {
            LogEvents::log("TQKT");
        } else
            LogEvents::log("TWKT," . $num);
        $teams = (new TeamData())->getTeams();
        foreach ($this->succededTeams as $value) {
            LogEvents::log("AT,".$teams[$value][0]);
        }
    }

    /**
     * simulates matches of the konckout stage
     */
    private function playEliminationRound()
    {
        LogEvents::log("KSS");
        for ($i = 1; $i < 5; $i++) {
            $this->succededTeams = [];
            LogEvents::log("KSR," . $i);
            for ($k = 0; $k < count($this->knockoutMatches); $k++) {
                [$t1, $t2] = $this->knockoutMatches[$k];
                $match = new FootballMatch($t1, $t2, true);
                [$g1, $g2] = ($match->getGoals());
                $this->succededTeams[] = ($g1 <=> $g2) === 1 ? $t1 : $t2;
            }
            if ($i < 4) {
                $this->logSuccededTeams($i);
                $this->drawKnockoutMatches(false);
            }
            $this->winner = $this->succededTeams[0];
        }
    }

    /**
     * draws matches for the first knockout round
     * @param bool $shuffle shuffles(true) in the the first round
     */
    private function drawKnockoutMatches(bool $shuffle = true)
    {
        $this->knockoutMatches = [];
        if ($shuffle)
            shuffle($this->succededTeams);
        for ($i = 0; $i < count($this->succededTeams); $i += 2) {
            $this->knockoutMatches[] = [$this->succededTeams[$i], $this->succededTeams[$i + 1]];
        }
    }

    /**
     * returns and logs the winner of the tornament
     * @return string name of the winner team
     */
    public function getWinner(): string
    {
        $teams = (new TeamData())->getTeams();
        LogEvents::log("WT," . $this->winner);
        return $teams[$this->winner][0];
    }

}
