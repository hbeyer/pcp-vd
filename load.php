<?php

set_time_limit(3600);

require __DIR__ . '/vendor/autoload.php';
require('functions/mainFunctions.php');

$sets = array();
$sets[] = array('uni' => 'Leipzig', 'century' => '16', 'csv' => 'VD16Leipzig.csv');
$sets[] = array('uni' => 'Leipzig', 'century' => '17', 'csv' => 'VD17Leipzig.csv');
$sets[] = array('uni' => 'Leipzig', 'century' => '18', 'csv' => 'VD18Leipzig.csv'); 
$sets[] = array('uni' => 'Helmstedt', 'century' => '16', 'csv' => 'VD16Helmstedt.csv');
$sets[] = array('uni' => 'Helmstedt', 'century' => '17', 'csv' => 'VD17Helmstedt.csv');
$sets[] = array('uni' => 'Helmstedt', 'century' => '18', 'csv' => 'VD18Helmstedt.csv');

foreach ($sets as $set) {
	getFromUNApi($set['csv'], 'marcxml', 'vd'.$set['century'].'/'.$set['uni'].'/marcxml/', true);
	echo "MARCXML geladen für ".$set['uni']." ".$set['century'].". Jh.\r\n";
	makeRawDC('vd'.$set['century'].'/'.$set['uni'].'/marcxml/', 'vd'.$set['century'].'/'.$set['uni'].'/dc_raw/');
	echo "DC erzeugt für ".$set['uni']." ".$set['century'].". Jh.\r\n";
	makeRDF('vd'.$set['century'].'/'.$set['uni'].'/dc_raw/', 'vd'.$set['century'].$set['uni']);
	echo "RDF gespeichert für ".$set['uni']." ".$set['century'].". Jh.\r\n";
}

?>
