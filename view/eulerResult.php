<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Problem 753</title>
</head>
<body>
<?php

use coreServices\DataForView;

$dv = DataForView::getInstance();
if ($dv->getValue("success")) {
    echo "The count of solutions for Project Euler Problem 753, with maximum prime " . $dv->getValue("prime") . " is: " . $dv->getValue("result") . "<br>";
    echo "Elapsed time: " . $dv->getValue("time")[0] . ":" . $dv->getValue("time")[1];
} else {
    echo "Error: " . $dv->getValue("result");
}
?>
</body>
</html>
