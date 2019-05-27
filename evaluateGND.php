<?php

set_time_limit(3600);

//require __DIR__ .'/vendor/autoload.php';
foreach (glob("classes/class_*.php") as $filename)
{
    include $filename;
}
include('functions/auxiliaryFunctions.php');
include('functions/encode.php');
include('GNDs-Leipzig.php');

function extractYear($string) {
	preg_match('~1[456789][0-9]{2}~', $string, $hits);
	if (!empty($hits[0])) {
		return($hits[0]);
	}
	return($string);
}

$count = 0;
$void = array();
foreach ($gndsLeipzig as $gnd) {
	$request = new gnd_request($gnd);
	if ($request->errorMessage) {
		$void[] = $gnd;
	}
	else {
		echo $gnd.','.extractYear($request->dateBirth).','.extractYear($request->dateDeath)."\r\n";
	}
	if ($count > 2000) {
		break;
	}
	$count++;
}

echo "\r\nFehler:\r\n".implode("\r\n", $void);



?>
