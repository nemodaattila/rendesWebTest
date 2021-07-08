<?php

namespace euro;
require_once "PlayingTeam.php";

class FootballMatch
{
    private TeamRosters $teamA;
    private TeamRosters $teamB;
    private PlayingTeam $playingTeamA;
    private PlayingTeam $playingTeamB;
    private int $ballPosition=0;
    private array $goals=[0,0];

    public function __construct( int $teamANum, int $teamBNum,AllTeamRosters $rosters)
    {

        $this->teamA = $rosters->getATeamRoster($teamANum);
        $this->playingTeamA = new PlayingTeam($this->teamA);
        $this->teamB = $rosters->getATeamRoster($teamBNum);
        $this->playingTeamB = new PlayingTeam($this->teamB);
        echo "Match: ".$this->teamA->getName()." - ".$this->teamB->getName();
        $this->play();
    }

    private function play(): void
    {
        var_dump($this->playingTeamA, $this->playingTeamB);
        for ($i=1;$i<91;$i++)
        {
            $scoreA = $this->playingTeamA->getOverallStat()*(mt_rand(9,11)/10);
            $scoreB = $this->playingTeamB->getOverallStat()*(mt_rand(9,11)/10);
//            var_dump([$scoreA,$scoreB]);
            $equalLimit = min($scoreA,$scoreB)/5;
            $clashValue = abs($scoreA-$scoreB);
//            var_dump($clashValue);
            if ($clashValue>$equalLimit)
            {
                $this->ballPosition+=($scoreA>$scoreB)?1:-1;
                if ($this->ballPosition === 5)
                {
                    var_dump("goal");
                    $this->ballPosition = 0;
                    $this->goals[0]++;
                    $this->playingTeamA->decreaseStatsByPercent(10);
                }
                if ($this->ballPosition === -5)
                {
                    var_dump("goal");
                    $this->ballPosition = 0;
                    $this->goals[1]++;
                    $this->playingTeamB->decreaseStatsByPercent(10);

                }
            }
//            var_dump($i);
            $fatique =3*(floor($i/10)+1);
//                        var_dump($fatique);

            $this->playingTeamA->decreaseStats($fatique);
            $this->playingTeamB->decreaseStats($fatique);
//                var_dump($this->ballPosition);
        }
        var_dump($this->goals);
        var_dump($this->playingTeamA);
        var_dump($this->playingTeamB);

        //substitues/ injury
    }
}
