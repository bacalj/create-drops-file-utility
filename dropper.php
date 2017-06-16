<?php

/*
	Configuration: set the data source and resulting filename yourself for now.
	TODO: web interface
*/

$sourcedata = "exampledata.csv";
$destination = "results/thedrops.xml";
$termkey = '201801';

//get the data from a file
$hasdata = fopen($sourcedata, "r");

//parse into an array of arrays
if( $hasdata !== FALSE ) {
	$drops = [];
	while ( ! feof($hasdata) ){
		$data = fgetcsv($hasdata, ",");
		array_push($drops, $data);
	}
}

//make them keyed for ease later on
$xmlprep = [];
foreach ($drops as $arr) {
	if (strpos($arr[0], $termkey) > 0 ) {
		$record = array(
			"course" => $arr[0],
			"student" => $arr[1]
		);
		array_push( $xmlprep, $record );
	}
}

fclose($hasdata);

print_r($xmlprep);
