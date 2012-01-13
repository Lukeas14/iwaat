<?php

require_once('solr_php_client/Apache/Solr/Service.php');

$solr = new Apache_Solr_Service('localhost', '8983','/solr/');

/*
$documents = array();

$schools = array(
	array(
		'id' => 1,
		'name' => 'La Salle High School',
		'address' => '213 W. Sierra Madre Blvd.',
		'city' => 'Pasadena',
		'state' => 'CA'
	),
	array(
		'id' => 2,
		'name' => 'Long Beach Polytechnic',
		'address' => '3232 N. Williams St.',
		'city' => 'Long Beach',
		'state' => 'CA'
	)
);

foreach($schools as $school){
	$document = new Apache_Solr_Document();
	foreach($school as $field => $val){
		$document->$field = $val;
	}
	$documents[] = $document;
}

print_r($documents);

try{
	$solr->addDocuments( $documents );
	$solr->commit();
	$solr->optimize();
}
catch ( Exception $e ) {
	echo $e->getMessage();
}
echo"done loading docs into solr";


exit();
*/

try{
	$results = $solr->search('salle', 0, 10);
}
catch(Exception $e){
	echo $e;
}

print_r($results);