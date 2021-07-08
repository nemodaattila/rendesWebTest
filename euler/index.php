<?php
namespace global;
set_time_limit(0);

namespace euler;
require_once "EulerCounter.php";
$eu = new EulerCounter();
$eu->countSolutions();
