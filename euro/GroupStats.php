<?php

namespace euro;

/**
 * Class GroupStats statistic for every group in group stage + all third team in groups
 * @package euro
 */
class GroupStats
{
    /**
     * @var array statistics for every team [name, played, won, draw, lost, goal for, goal away, goal difference, point]
     */
    private array $stats;

    public function __construct($group)
    {
        $teams = (new TeamData())->getTeams();
        foreach ($group as $value) {
            $this->stats[$value] = [
                "name" => $teams[$value][0],
                "p" => 0,
                "w" => 0,
                "d" => 0,
                "l" => 0,
                "gf" => 0,
                "ga" => 0,
                "gd" => 0,
                "pts" => 0
            ];
        }
    }

    /**
     * @return array
     */
    public function getStats(): array
    {
        return $this->stats;
    }

    /**
     * refreshes group stats based on match results
     * @param array $teams number(id) of 2 teams
     * @param array $results result of the match
     */
    public function refreshGroupStats(array $teams, array $results)
    {
        [$t1, $t2] = $teams;
        [$g1, $g2] = $results;

        $this->stats[$t1]["p"]++;
        $this->stats[$t2]["p"]++;

        $this->stats[$t1]["gf"] += $g1;
        $this->stats[$t2]["gf"] += $g2;
        $this->stats[$t1]["ga"] += $g2;
        $this->stats[$t2]["ga"] += $g1;
        $this->stats[$t1]["gd"] = $this->stats[$t1]["gf"] - $this->stats[$t1]["ga"];
        $this->stats[$t2]["gd"] = $this->stats[$t2]["gf"] - $this->stats[$t2]["ga"];

        if ($g1 === $g2) {
            $this->stats[$t1]["d"]++;
            $this->stats[$t2]["d"]++;
        }
        if ($g1 > $g2) {
            $this->stats[$t1]["w"]++;
            $this->stats[$t2]["l"]++;
        }
        if ($g1 < $g2) {
            $this->stats[$t2]["w"]++;
            $this->stats[$t1]["l"]++;
        }
        $this->stats[$t1]["pts"] = 3 * $this->stats[$t1]['w'] + $this->stats[$t1]['d'];
        $this->stats[$t2]["pts"] = 3 * $this->stats[$t2]['w'] + $this->stats[$t2]['d'];

        uasort($this->stats, [$this, "sortData"]);

    }

    /**
     * sorting function for complex sorting of the group stats
     * @param array $a team A
     * @param array $b team B
     * @return int sorting value
     */
    function sortData(array $a, array $b): int
    {
        $c = ($b['pts'] <=> $a['pts']);
        if ($c !== 0) {
            return $c;
        }

        $c = ($b['gd'] <=> $a['gd']);
        if ($c !== 0) {
            return $c;
        }

        return ($b['gf'] <=> $a['gf']);
    }

    /**
     * logs the result of the group
     */
    public function logResults()
    {
        LogEvents::log("GRES");
        foreach ($this->stats as $value) {
            LogEvents::log("GS,".$value['name'] . "," . $value['w'] . "," . $value['d'] . "," . $value['l'] . "," . $value['gf'] . "," . $value['ga'] . "," . $value['gd'] . "," . $value['pts']);
        }
    }

    /**
     * fills a group with values (for the group of thirds)
     * @param array $stats statistics
     */
    public function setExactValues(array $stats)
    {
        foreach ($stats as $key => $value) {
            $this->stats[$key] = $value;
        }
        uasort($this->stats, [$this, "sortData"]);
    }

}
