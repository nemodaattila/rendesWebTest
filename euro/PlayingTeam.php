<?php

namespace euro;

/**
 * the actual team that id playing in a match (11 + 3 player)
 * Class PlayingTeam
 * @package euro
 */
class PlayingTeam
{
    /**
     * @var int id (number) of the team
     */
    private int $id;

    /**
     * @var array starter player of the team
     */
    private array $starters = [];

    /**
     * @var array reserves/substitutes
     */
    private array $reserves = [];

    /**
     * @var array substituted players
     */
    private array $substituted = [];

    /**
     * @var int count of substitutes used
     */
    private int $substitutesUsed = 0;

    /**
     * if a player is under that value, the player can be substituted or injured
     */
    const MAX_RESERVE_STAT = 500;

    public function __construct(TeamRosters $fullTeam, int $teamNumber)
    {
        $fullTeam->sortPlayers();
        $this->id = $teamNumber;
        $this->getActivePlayers($fullTeam->getTeam());
    }

    /**
     * fills the starter and reserve players form full roster (best players)
     * @param array $fullTeam full roster
     */
    private function getActivePlayers(array $fullTeam)
    {
        $i = 0;
        foreach ($fullTeam as $num => $player) {
            if ($player[0] > 0) {
                if ($i < 11) $this->starters[$num] = $player[0];
                if ($i > 11 && $i < 15) $this->reserves[$num] = $player[0];
                $i++;
                if ($i === 15) break;
            }
        }
    }

    /**
     * returns the overall stat of the playing team
     * @return int overall stat
     */
    public function getOverallStat(): int
    {
        return array_sum($this->starters);
    }

    /**
     * decreases the stats of all primary players with a fixed value
     * @param int $value value to be decreased with
     */
    public function decreaseStats(int $value)
    {
        foreach ($this->starters as $key => $stat) {
            $this->starters[$key] = $stat - $value;
        }
    }

    /**
     * decreases the stats of all players with a percentage
     * @param int $value percentage to decrease with
     */
    public function decreaseStatsByPercent(int $value)
    {
        foreach ($this->starters as $key => $stat) {
            $this->starters[$key] = floor($stat * ((100 - $value) / 100));
        }
    }

    /**
     * checks after reserves and injuries
     * @param int $minute minute of play
     * @return int count of starter players
     */
    public function checkReservesAndInjuries(int $minute): int
    {
        $this->checkInjuries($minute);
        $this->checkReserveNeeds($minute);
        return count($this->starters);
    }

    /**
     * checks starter players for injury, based on player stat
     * @param int $minute
     */
    private function checkInjuries(int $minute)
    {
        $tired = $this->findStatUnder500();
        foreach ($tired as $key => $value) {
            $injuryChance = 0;
            if ($value < 500 && $value > 0) $injuryChance = 1;
            if ($value < 0) $injuryChance = 4;
            if ($injuryChance > 0) {
                $rand = mt_rand(0, 100);
                if ($rand < $injuryChance) {
                    LogEvents::log("PI," . $minute . "," . $this->id . "," . $key);
                    $this->playerBecameInjured($key);
                }
            }
        }
    }

    /**
     * checks if substitute is needed
     * @param int $minute minute of play
     */
    private function checkReserveNeeds(int $minute)
    {
        if ($this->substitutesUsed < 3) {
            $tired = $this->findStatUnder500();
            $reserveChance = floor((count($tired) * $minute) / self::MAX_RESERVE_STAT * 100);
            $rand = mt_rand(0, 100);
            if ($rand < $reserveChance) {
                LogEvents::log("PS," . $minute . "," . $this->id);
                $this->replaceAPlayer(array_key_first($tired));
            }
        }
    }

    /**
     * if a player is injured and there is a substitute
     *  player is substituted
     * if there is no substitute, player simply comes off
     * player stats significantly decrease
     * @param int $minute
     */
    private function playerBecameInjured(int $minute)
    {
        $this->starters[$minute] -= 1500;
        if ($this->substitutesUsed < 3) {
            $this->replaceAPlayer($minute);
        } else {
            $this->substituted[$minute] = $this->starters[$minute];
            unset($this->starters[$minute]);
        }
        if (count($this->starters) < 11) {
            LogEvents::log("TPC," . $this->id . ',' . count($this->starters));
        }
    }

    /**
     * replaces a player with a substitute
     * @param int $player number of the player to be substituted
     */
    private function replaceAPlayer(int $player)
    {
        $this->substituted[$player] = $this->starters[$player];
        unset($this->starters[$player]);
        $newPlayer = array_key_first($this->reserves);
        $this->starters[$newPlayer] = $this->reserves[$newPlayer];
        unset($this->reserves[$newPlayer]);
        $this->substitutesUsed++;
        LogEvents::log("PR," . $player . "," . $newPlayer);

    }

    /**
     * searches for players wirth stat under 500
     * @return array
     */
    private function findStatUnder500(): array
    {
        $tired = array_filter($this->starters, function ($value) {
            return $value < 500;
        });
        asort($tired);
        return $tired;
    }

    /**
     * refreshes the statistic of the full roster with stats of the playing team at the end of the match
     * @param TeamRosters $team FUll roster of the team
     * @return TeamRosters refreshed team roster
     */
    public function refreshRoster(TeamRosters $team): TeamRosters
    {
        foreach ($this->starters as $key => $value) {
            $team->setPLayerStat($key, $value);
        }
        foreach ($this->reserves as $key => $value) {
            $team->setPLayerStat($key, $value);
        }
        foreach ($this->substituted as $key => $value) {
            $team->setPLayerStat($key, $value);
        }
        return $team;
    }

    /**
     * returns the teams id
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

}
