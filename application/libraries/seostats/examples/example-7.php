<?php ini_set('max_execution_time', 180);
 
include '../src/Seostats.php';
$_GET['url'] = 'http://www.slacker.com';
try 
{
	$url = new SEOstats(array('url'=>$_GET['url']));
	
	print_r($url->Seomoz_Domainauthority_Array());
	//print_r($url->Seomoz_Linkdetails_Array());
	//
	echo"\n";
	print_r($url->Twitter_Mentions_Total());
	echo"\n";
	print_r($url->Facebook_Mentions_Total());
	echo"\n";
	print_r($url->Facebook_Mentions_Array());
	echo"\n";
	//$url->print_array('Seomoz_Domainauthority_Array');
} 
catch (SEOstatsException $e) 
{
	/**
	 * Error handling (print it, log it, leave it.. whatever you want.)
	 */
	die($e->getMessage());
}
?>
