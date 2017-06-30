<?php

/*
	Configuration (hardcode):
		sourcedata: 	the csv should look like exampledata file included here
		destination: 	the name of the xml file you want to generate
		termkey: 			some string that must be included in the course id for this to process
		thisdate:			hardcode something close to when this will be sent to your server
*/

$sourcedata = "drop_three.csv";
$destination = "exampleresults/dropthree.xml";
$termkey = '201820';
$thisdate = '2018-06-29T16:00:33';
$thiscollege = 'Smith College';

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

$writer->startElement("enterprise");
	$writer->startElement("properties");
	$writer->writeAttribute("lang", "en");
		$writer->writeElement('datasource', $thiscollege);
		$writer->writeElement('datetime', $thisdate);
	$writer->endElement();

	//loop over records to create memberships
	foreach ($xmlprep as $membership) {
		$writer->startElement("membership");

			$writer->startElement("sourcedid");
				$writer->writeElement("source", $thiscollege);
				$writer->writeElement("id", $membership['course']);
			$writer->endElement();

			$writer->startElement("member");
				$writer->startElement("sourcedid");
					$writer->writeElement("source", $thiscollege);
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
