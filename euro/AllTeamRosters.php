<?php

namespace euro;

/**
 * Class AllTeamRosters collection of all team rosters
 * @package euro
 */
class AllTeamRosters
{
    /**
     * @var TeamData team names and coefficients
     */
    private TeamData $teamData;

    /**
     * @var array array of TeamRosters
     */
    private array $rosters = [];

    /**
     * @var AllTeamRosters|null singleton instance
     */
    private static ?AllTeamRosters $instance = null;

    public static function getInstance(): AllTeamRosters
    {
        if (self::$instance === null) {
            self::$instance = new AllTeamRosters();
        }

        return self::$instance;
    }

    public function __construct()
    {
        $this->teamData = new TeamData();
        $this->generateTeams();
    }

    /**
     * return's a team roster by team number
     * @param int $teamNumber number of the team
     * @return TeamRosters players of a team
     */
    public function getATeamRoster(int $teamNumber): TeamRosters
    {
        return $this->rosters[$teamNumber];
    }

    /**
     * generates all team rosters
     */
    private function generateTeams()
    {
        $minMax = $this->teamData->getMinMax();
        $calculator = new TeamRosterCalculator($minMax);
        foreach ($this->teamData->getTeams() as $index => $team) {
            $this->rosters[$index] = new TeamRosters($team[0]);
            $this->rosters[] = $calculator->calculateTeam($this->rosters[$index], $team);
        }
    }
}
