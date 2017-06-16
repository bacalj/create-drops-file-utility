<?php

/*
	Configuration: set the data source and resulting filename yourself for now.
	TODO: web interface, dynamic time
*/

$sourcedata = "exampledata.csv";
$destination = "results/thedrops.xml";
$termkey = '201801';
$thisdate = '2017-06-16T11:05:33';

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
$writer->setIndent(true);
$writer->startDocument("1.0", "UTF-8");


// $writer->startDTD('enterprise');
// $writer->endDTD();


$writer->startElement("enterprise");
	$writer->startElement("properties");
	$writer->writeAttribute("lang", "en");
		$writer->writeElement('datasource', 'Smith College');
		$writer->writeElement('datetime', $thisdate);
	$writer->endElement();

	//loop over records to create memberships
	foreach ($xmlprep as $membership) {
		$writer->startElement("membership");

			$writer->startElement("sourcedid");
				$writer->writeElement("source", "Smith College");
				$writer->writeElement("id", $membership['course']);
			$writer->endElement();

			$writer->startElement("member");
				$writer->startElement("sourcedid");
					$writer->writeElement("source", "Smith College");
					$writer->writeElement("id", $membership['student']);
				$writer->endElement();

				$writer->writeElement("idtype", 1);

				$writer->startElement("role");
				$writer->writeAttribute("roletype", "01");
					$writer->writeElement("status", "0");
				$writer->endElement();
			$writer->endElement(); //end member element

		$writer->endElement(); //end membership element
	}

$writer->endElement();
$writer->endDocument();
$writer->flush();


print_r($xmlprep);
