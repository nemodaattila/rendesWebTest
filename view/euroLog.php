<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Euro 2020 log</title>
</head>
<body>
<?php

use coreServices\DataForView;

$log = (DataForView::getInstance())->getValue('log');

foreach ($log as $value) {
    echo $value . "<br>";
}
?>
</body>
</html>

