<?php

namespace euro;

class LogEvents
{
    static function emptyLog()
    {
        $myfile = fopen("log.txt", "w") or die("Unable to open file!");
        fclose($myfile);
    }

    static function log(string $message)
    {
//        echo($message.'<br/>');
        $myfile = fopen("log.txt", "a") or die("Unable to open file!");
        fwrite($myfile, $message . '<br/>');
        fclose($myfile);
    }

    public static function displayLog()
    {
        $myfile = fopen("log.txt", "r") or die("Unable to open file!");
// Output one line until end-of-file
        while (!feof($myfile)) {
            echo fgets($myfile) . "<br>";
        }

        fclose($myfile);
    }
}
