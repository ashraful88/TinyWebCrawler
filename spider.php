<?php
	$fp = fopen(time().'_file.csv', 'w');
	
	$url_array[] = 'http://www.ekhanei.com/en/dhaka-city/gulshan/cars/toyota-f-premio-g-superior-14650258';
	$url_array[] = 'http://www.ekhanei.com/en/dhaka-city/gulshan/cars/nissan-juke-14650201';
	
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
			echo '<hr/>';
			$param[] = $n->nodeValue;			
		}
		//print_r($param);
		fputcsv($fp, $param);
		
		unset($param);
		unset($page_data);
		unset($doc);
		unset($nodes);
		unset($finder);
	}
	fclose($fp);
?>
