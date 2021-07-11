<?php

namespace euro;

class LogEvents
{
    static function emptyLog()
    {
        $myfile = fopen("euro\log.txt", "w") or die("Unable to open file!");
        fclose($myfile);
    }

    static function log(string $message)
    {
//        echo($message.'<br/>');
        $myfile = fopen("euro\log.txt", "a") or die("Unable to open file!");
        fwrite($myfile, $message . '<br/>');
        fclose($myfile);
    }

    public static function readAllLog()
    {
        $myfile = fopen("euro\log.txt", "r") or die("Unable to open file!");
        $log = [];
        while (!feof($myfile)) {
            $log[] = fgets($myfile) . "<br>";
        }

        fclose($myfile);
        return $log;
    }
}
