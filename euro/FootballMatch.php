<?php

namespace euro;

/**
 * simulates a football match
 * Class FootballMatch
 * @package euro
 */
class FootballMatch
{
    /**
     * @var TeamRosters full roster of team A
     */
    private TeamRosters $teamA;

    /**
     * @var TeamRosters full roster of Team B
     */
    private TeamRosters $teamB;

    /**
     * @var PlayingTeam playing team of team A
     */
    private PlayingTeam $playingTeamA;

    /**
     * @var PlayingTeam playing team of team B
     */
    private PlayingTeam $playingTeamB;

    /**
     * @var int position of the ball on the field (-5<=x<=5)
     */
    private int $ballPosition = 0;

    /**
     * @var array|int[] score of the game
     */
    private array $goals = [0, 0];

    /**
     * @return array
     */
    public function getGoals(): array
    {
        return $this->goals;
    }

    /**
     * FootballMatch constructor.
     * @param int $teamANum number of team A
     * @param int $teamBNum number of team B
     * @param bool $isKnockout match is in knockout stage
     */
    public function __construct(int $teamANum, int $teamBNum, bool $isKnockout = false)
    {
        $rosters = AllTeamRosters::getInstance();
        $this->teamA = $rosters->getATeamRoster($teamANum);
        $this->playingTeamA = new PlayingTeam($this->teamA, $teamANum);
        $this->teamB = $rosters->getATeamRoster($teamBNum);
        $this->playingTeamB = new PlayingTeam($this->teamB, $teamBNum);
        LogEvents::log("MD," . $this->teamA->getName() . "," . $this->teamB->getName());
        LogEvents::log("MOS," . $this->playingTeamA->getOverallStat() . "," . $this->playingTeamB->getOverallStat());
        $this->play();
        if ($isKnockout && $this->goals[0] === $this->goals[1])
            $this->extraTime();
        $this->refreshRosters();
    }

    /**
     * iterates the minutes of the match
     */
    private function play(): void
    {
        for ($i = 1; $i < 91; $i++) {
            if (!$this->playAMinute($i))
                return;
        }
    }

    /**
     * if the match is in knockout stage and the result is draw at the and of the normal time
     * plays extra time until one of the team goals (barbaric I know :))
     */
    private function extraTime()
    {
        LogEvents::log("SNT," . $this->goals[0] . ',' . $this->goals[1]);
        LogEvents::log("ET");
        LogEvents::log("MOS," . $this->playingTeamA->getOverallStat() . "," . $this->playingTeamB->getOverallStat());
        $i = 91;
        while ($this->goals[0] === $this->goals[1]) {
            $this->playAMinute($i);
            $i++;
        }
    }

    /**
     * simulates a minute of the match
     * based on the two teams randomised score, the ball moves in the field, can be goal
     * the players experience fatigue
     * checks for injuries
     * @param int $i minute
     * @return bool false if a team doesn't have enough players
     */
    private function playAMinute(int $i): bool
    {
        $scoreA = $this->playingTeamA->getOverallStat() * (mt_rand(9, 11) / 10);
        $scoreB = $this->playingTeamB->getOverallStat() * (mt_rand(9, 11) / 10);
        $equalLimit = min($scoreA, $scoreB) / 5;
        $clashValue = abs($scoreA - $scoreB);
        if ($clashValue > $equalLimit) {
            $this->ballPosition += ($scoreA > $scoreB) ? 1 : -1;
            $this->isGoal($i);
        }
        $this->decreaseStatsWithFatigue($i);
        $playerNums = $this->checkReservesAndInjuries($i);
        if (min($playerNums) < 7) {
            LogEvents::log("MENEP");
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

    /**
     * checks if its goal, ball position is 5 or -5
     * @param int $minute
     */
    private function isGoal(int $minute)
    {
        if ($this->ballPosition === 5) {
            LogEvents::log("GL," . $minute . "," . $this->playingTeamA->getId());
            $this->ballPosition = 0;
            $this->goals[0]++;
            $this->playingTeamA->decreaseStatsByPercent(10);
        }
        if ($this->ballPosition === -5) {
            LogEvents::log("GL," . $minute . "," . $this->playingTeamB->getId());
            $this->ballPosition = 0;
            $this->goals[1]++;
            $this->playingTeamB->decreaseStatsByPercent(10);
        }
    }

    /**
     * decreases the stats of the teams with fatigue
     * @param int $minutes minute of the match
     */
    private function decreaseStatsWithFatigue(int $minutes)
    {
        $fatigue = 3 * (floor($minutes / 10) + 1);
        $this->playingTeamA->decreaseStats($fatigue);
        $this->playingTeamB->decreaseStats($fatigue);
    }

    /**
     * checks for injuries and the need for reserves
     * @param int $minute
     * @return array player count for both team
     */
    private function checkReservesAndInjuries(int $minute): array
    {
        $countA = $this->playingTeamA->checkReservesAndInjuries($minute);
        $countB = $this->playingTeamB->checkReservesAndInjuries($minute);
        return [$countA, $countB];
    }

    /**
     * refreshes every team (TeamRoster) with data from playing team (PLayingTeam)
     * rests the players
     */
    private function refreshRosters()
    {
        $this->teamA = $this->playingTeamA->refreshRoster($this->teamA);
        $this->teamB = $this->playingTeamB->refreshRoster($this->teamB);

        $this->teamA->restFor3Days();
        $this->teamB->restFor3Days();
        LogEvents::log("FS," . $this->goals[0] . ',' . $this->goals[1]);
    }

}
