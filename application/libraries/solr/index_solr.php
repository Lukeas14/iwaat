<?php

require_once('../settings.php');

//Connect to solr
require_once('php_client/Apache/Solr/Service.php');

try{
	$solr = new Apache_Solr_Service(SOLR_HOST,SOLR_PORT,SOLR_DIR);
	$solr->deleteByQuery('*:*');
	$solr->commit(); //commit to see the deletes and the document
	$solr->optimize(); //merges multiple segments into one
}
catch(Exception $e){
	print_r($e);
	echo $e->getMessage();
}

$documents = array();

$query = mysql_query("
	SELECT id, name, address, city, state, zip, lowest_grade, highest_grade, total_enrollment
	FROM schools
")or die(mysql_error());
$school_count = 0;
while($result = mysql_fetch_array($query)){
	$document = new Apache_Solr_Document();
	$document->id = $result['id'];
	$document->name = ucwords(strtolower(trim($result['name'])));
	$document->address = $result['address'];
	$document->city = ucwords(strtolower(trim($result['city'])));
	$document->state = $result['state'];
	$document->zip = $result['zip'];
	$document->lowest_grade = $result['lowest_grade'];
	$document->highest_grade = $result['highest_grade'];
	$document->total_enrollment = $result['total_enrollment'];
	$documents[] = $document;
	
	echo $result['id']."\n";
	
	$school_count++;
	if($school_count % 10000 === 0){
		try{
			$solr->addDocuments( $documents );
		}
		catch(Exception $e){
			echo $e->getMessage();
		}
		
		$documents = array();
	}
}

try{
	if(!empty($documents)){
		$solr->addDocuments( $documents );
	}
	$solr->commit();
	$solr->optimize();
}
catch ( Exception $e ) {
	print_r($e);
	echo $e->getMessage();
}
		
echo"done\n";
