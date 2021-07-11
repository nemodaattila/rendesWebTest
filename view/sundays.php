<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sundays</title>
</head>
<body>
<?php
$dv = \coreServices\DataForView::getInstance();
if ($dv->getValue("success")) {

    echo "The count of Sundays that are the first days of a month since " . $dv->getValue("result")[0] . " is: " . $dv->getValue("result")[1];
}
else
{
    echo "Error: ".$dv->getValue("result");
}
?>
</body>
</html>
