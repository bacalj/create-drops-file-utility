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
//we have the data in an array so we can put down the file
fclose($hasdata);

//start on the xml out
$writer = new XMLWriter();
$writer->openURI($destination);
$writer->startDocument("1.0", "UTF-8");

// $writer->startDTD('enterprise');
// $writer->endDTD();
// $writer->startElement('enterprise');
// 	$writer->startElement('properties');
//
// 	$writer->endElement('properties');
// $writer->endElement('enterprise');

$writer->startElement("greeting");
	$writer->writeAttribute("feeling", "nice");
	$writer->text('Hello World');
$writer->endElement("greeting");

$writer->endDocument();
$writer->flush();


print_r($xmlprep);
