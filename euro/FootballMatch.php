<?php

namespace euro;
require_once "PlayingTeam.php";

class FootballMatch
{
    private TeamRosters $teamA;
    private TeamRosters $teamB;
    private PlayingTeam $playingTeamA;
    private PlayingTeam $playingTeamB;
    private int $ballPosition = 0;
    private array $goals = [0, 0];

    /**
     * @return array|int[]
     */
    public function getGoals(): array
    {
        return $this->goals;
    }

    public function __construct(int $teamANum, int $teamBNum, bool $isKnockout=false)
    {
        $rosters = AllTeamRosters::getInstance();
        $this->teamA = $rosters->getATeamRoster($teamANum);
        $this->playingTeamA = new PlayingTeam($this->teamA);
        $this->teamB = $rosters->getATeamRoster($teamBNum);
        $this->playingTeamB = new PlayingTeam($this->teamB);
        LogEvents::log("Match: " . $this->teamA->getName() . " - " . $this->teamB->getName());
        LogEvents::log("Overall scores: ". $this->playingTeamA->getOverallStat() ." - ". $this->playingTeamB->getOverallStat().'<br>');
        $this->play();
        if ($isKnockout && $this->goals[0]===$this->goals[1])
            $this->extraTime();
        $this->refreshRosters();
    }

    private function play(): void
    {

        for ($i = 1; $i < 91; $i++) {

            if (!$this->playAMinute($i))
                return;
//            var_dump($i);

//                var_dump($this->ballPosition);
        }
//
    }

    private function extraTime()
    {
        LogEvents::log("<br>Score: ". $this->goals[0]. ' - '. $this->goals[1]."<br>");
        LogEvents::log("Extra Time:");
        LogEvents::log("Overall scores: ". $this->playingTeamA->getOverallStat() ." - ". $this->playingTeamB->getOverallStat().'<br>');
        $i=91;
        while($this->goals[0]===$this->goals[1])
        {
            $this->playAMinute($i);
            $i++;
        }
    }

    private function playAMinute($i): bool
    {
        $scoreA = $this->playingTeamA->getOverallStat() * (mt_rand(9, 11) / 10);
        $scoreB = $this->playingTeamB->getOverallStat() * (mt_rand(9, 11) / 10);
//            LogEvents::log($scoreA.' - '. $scoreB);
        $equalLimit = min($scoreA, $scoreB) / 5;
        $clashValue = abs($scoreA - $scoreB);
//            var_dump($clashValue);
        if ($clashValue > $equalLimit) {
            $this->ballPosition += ($scoreA > $scoreB) ? 1 : -1;
            $this->isGoal($i);

        }
        $this->decreaseStatsWithFatigue($i);
        $playerNums = $this->checkReservesAndInjuries($i);
        if (min($playerNums) < 7) {
            LogEvents::log("Match is Ended because one team does'nt have enugh players");
            if ($playerNums[0] < 7) {
                $this->goals = [0, 3];
            } else
                if ($playerNums[1] < 7) {
                    $this->goals = [3, 0];
                }
            return false;
        }
        return true;
    }

    private function isGoal(int $minute)
    {
        if ($this->ballPosition === 5) {
            LogEvents::log($minute. ". minute: Goal: ". $this->teamA->getName());
            $this->ballPosition = 0;
            $this->goals[0]++;
            $this->playingTeamA->decreaseStatsByPercent(10);
        }
        if ($this->ballPosition === -5) {
            LogEvents::log($minute. ". minute: Goal: ". $this->teamB->getName());
            $this->ballPosition = 0;
            $this->goals[1]++;
            $this->playingTeamB->decreaseStatsByPercent(10);
        }
    }

    private function decreaseStatsWithFatigue(int $num)
    {
        $fatique = 3 * (floor($num / 10) + 1);
//                        var_dump($fatique);

        $this->playingTeamA->decreaseStats($fatique);
        $this->playingTeamB->decreaseStats($fatique);
    }

    private function checkReservesAndInjuries(int $i): array
    {
        $countA = $this->playingTeamA->checkReservesAndInjuries($i);
        $countB = $this->playingTeamB->checkReservesAndInjuries($i);
        return [$countA, $countB];
    }

    private function refreshRosters()
    {
        $this->teamA=$this->playingTeamA->refreshRoster($this->teamA);
        $this->teamB=$this->playingTeamB->refreshRoster($this->teamB);

        $this->teamA->restFor3Days();
        $this->teamB->restFor3Days();
        LogEvents::log("<br>Final Score: ". $this->goals[0]. ' - '. $this->goals[1]."<br>");
    }

}
