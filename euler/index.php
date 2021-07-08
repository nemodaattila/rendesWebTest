<?php
namespace global;
set_time_limit(0);
ini_set('memory_limit',-1);

namespace euler;

require_once "EulerCounter.php";
echo "Figyelem, ez a művelet sokáig fog tartani!<br/>";
echo (new \DateTime())->format('Y-m-d\TH:i:s.u')."<br/>";
$eu = new EulerCounter();
$count = $eu->countSolutions();
echo "Összes megoldás száma: ".$count."<br/>";
echo (new \DateTime())->format('Y-m-d\TH:i:s.u')."<br/>";
