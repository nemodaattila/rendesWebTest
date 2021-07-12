<?php

namespace euro;

use coreServices\DataForView;

/***
 * Class FormatLogDisplay format log data into a readable form
 * @package euro
 */
class FormatLogDisplay
{
    /**
     * @var TeamData name and score of teams
     */
    private static TeamData $teams;

    /**
     * iterates the log data, sends it to format, than displays it
     * @param array $log log data
     */
    public static function format(array $log)
    {
        self::$teams = new TeamData();
        foreach ($log as $key => $value) {
            $type = array_shift($value);
            if ($type !== '') {

                $log[$key] = self::formatString(trim($type), $value);
            } else
                unset($log[$key]);
        }
        (DataForView::getInstance())->setValue("log", $log);
    }

    /**
     * formats log data according to log type
     * @param string $type type of log
     * @param array $value value of log
     * @return string formatted log data
     */
    private static function formatString(string $type, array $value): string
    {
        if ($type === "GR") {
            return "<br>Group Stage round " . intval($value[0]) . ":";
        }
        if ($type === "G") {
            return "<br/>Group " . trim($value[0]) . ":";
        }
        if ($type === "MD") {
            return "<br/>Match: " . $value[0] . ' - ' . trim($value[1]);
        }
        if ($type === "MOS") {
            return "Overall score: " . $value[0] . ' - ' . trim($value[1]) . '<br/>';
        }
        if ($type === "GL") {
            return $value[0] . ". minute: Goal: " . self::$teams->getTeamName(trim($value[1]));
        }
        if ($type === "PS") {
            return $value[0] . ". minute: Substitute: " . self::$teams->getTeamName(trim($value[1]));
        }
        if ($type === "PR") {
            return $value[0] . " -> " . trim($value[1]);
        }
        if ($type === "FS") {
            return "<br/>Final score: " . $value[0] . " - " . trim($value[1]);
        }

        if ($type === "PI") {
            return $value[0] . ".minute: Injury: " . self::$teams->getTeamName($value[1]) . " player " . trim($value[2]);
        }

        if ($type === "GRES") {
            return "<br/>Group Results<br>Name | Win | Draw | Lost | Goal For | Goal Away | Goal Diff | Points";
        }
        if ($type === "GS") {
            return trim(implode(' | ', $value));
        }
        if ($type === "TPC") {
            return self::$teams->getTeamName($value[0]) . ' plays with ' . trim($value[1]) . " players";
        }
        if ($type === "AHGT") {
            return "<br/>Ranking of Group Thirds";
        }
        if ($type === "TQKT") {
            return "<br>Advanced to the Knockout Stage: ";
        }
        if ($type === "AT") {
            return trim($value[0]);
        }

        if ($type === "KSS") {
            return "<br>Knockout Stage starts ";
        }

        if ($type === "KSR") {
            return "<br>Round " . trim($value[0]);
        }

        if ($type === "SNT") {
            return "<br>Score: " . $value[0] . " - " . trim($value[1]);
        }

        if ($type === "ET") {
            return "<br>Extra Time ";
        }

        if ($type === "TWKT") {
            return "<br>Advanced from round " . trim($value[0]);
        }

        if ($type === "MENEP") {
            return "Match ended because on of the teams doesn't have enough players ";
        }

        if ($type === "WT") {
            return "<h1>The winner  of the tournament is" . self::$teams->getTeamName(trim($value[0])) . "</h1>";
        }
        return '';
    }
}
