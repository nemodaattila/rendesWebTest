<?php
$log = \euro\LogEvents::readAllLog();

foreach ($log as $value)
{
    echo $value."<br>";
}
