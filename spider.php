<?php

$fp = fopen(time().'_file.csv', 'w');
	
$url_array[] = 'http://www.example.com/blahblah-url';
$url_array[] = 'http://www.example.com/blahblah-url-2';

foreach($url_array as $url){
	echo $url;
	$page_data = file_get_contents($url);
	$doc = new DOMDocument();
       	@$doc->loadHTML($page_data);
		
	$finder = new DomXPath($doc);
	$classname="column3li";
	$nodes = $finder->query("//*[contains(@class, '$classname')]/li/h2");
	$param[] = $url;
	foreach($nodes as $n){ 
		echo $n->nodeValue;
	       	echo '====';
	       	$param[] = $n->nodeValue;			
	}
	echo '<hr/>';
		
		//put in csv file
	fputcsv($fp, $param);
		
	unset($param);
	unset($page_data);
	unset($doc);
	unset($nodes);
	unset($finder);
}
fclose($fp);
?>
