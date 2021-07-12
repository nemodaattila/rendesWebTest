<?php

namespace euro;

/**
 * Class LogEvents logs events of the tournament
 * @package euro
 */
class LogEvents
{
    /**
     * empties the log file
     */
    static function emptyLog()
    {
        $file = fopen("euro\log.txt", "w") or die("Unable to open file!");
        fclose($file);
    }

    /**
     * writes (logs) data to file
     * @param string $message data to be logged
     */
    static function log(string $message)
    {
        $file = fopen("euro\log.txt", "a") or die("Unable to open file!");
        fwrite($file, $message . "\n");
        fclose($file);
    }

    /**
     * reads and returns the content of the log file (as array)
     * @return array the content of the log file
     */
    public static function readAllLog(): array
    {
        $file = fopen("euro\log.txt", "r") or die("Unable to open file!");
        $log = [];
        while (!feof($file)) {
            $log[] = explode(",", fgets($file));
        }
        fclose($file);
        return $log;
    }
}
