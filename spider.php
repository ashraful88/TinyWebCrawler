<?php
/**
 * Add urls in  $url_array
 * running the script will create a csv file with data
 * */
 
$url_array[] = 'http://www.example.com/blahblah-url';
$url_array[] = 'http://www.example.com/blahblah-url-2';

$fp = fopen(time().'_file.csv', 'w');
$reload = false;
$start = (isset($_GET['limit'])?$_GET['limit']:0);
$total = sizeof($url_array);
$stop = $start+20;
if($stop > $total){
$stop = $total;
}else{
    $reload = true;
}

for($i=$start; $i <= $stop; $i++){
	
	if(!isset($url_array[$i])){ break; }
	$url = $url_array[$i];
	echo $url;
	$page_data = file_get_contents($url);
	$doc = new DOMDocument();
       	@$doc->loadHTML($page_data);
		
	$finder = new DomXPath($doc);
	// modify this based on data you need from target website
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
if($reload){
    echo '<meta http-equiv="refresh" content="0; url=index.php?limit='.$stop.'" />';
}
?>
