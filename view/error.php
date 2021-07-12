<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Error</title>
</head>
<body>
<?php

use coreServices\DataForView;

$dw = DataForView::getInstance();
echo $dw->getValue("error");
?>
</body>
</html>
