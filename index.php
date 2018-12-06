<?php
/**
 * Add urls in  $url_array
 * running the script will create a csv file with data
 * */

// input urls from CSV file, urls have to be in the first column of csv file
// use url file name in query strung like spider.php?urlcsv=myurlfilename
if (!empty($_GET['urlcsv'])) {
  $inputFile = $_GET['urlcsv'] . '.csv';
  if (($handle = fopen($inputFile, "r")) !== false) {
    while (($data = fgetcsv($handle, 1000, ",")) !== false) {
      $num = count($data);
      $url_array[] = $data[0];
    }
    fclose($handle);
  }
} else {
  //or url initialized
  $url_array[] = 'http://example.com/url-slug';
  $url_array[] = 'http://example.com/url-slug-2';
}

$fileName = (isset($_GET['fn']) ? $_GET['fn'] : time());
$fp = fopen($fileName . '_file.csv', 'w');
$reload = false;
$start = (isset($_GET['limit']) ? $_GET['limit'] : 0);
$total = sizeof($url_array);
$stop = $start + 5;
if ($stop > $total) {
  $stop = $total;
} else {
  $reload = true;
}
echo $start . ' = ' . $stop . ' = ' . $total;

for ($i = $start; $i <= $stop; $i++) {

  if (!isset($url_array[$i])) {
    break;
  }
  $url = $url_array[$i];
  echo $url;
  $page_data = file_get_contents($url);
  $doc = new DOMDocument();
  @$doc->loadHTML($page_data);

  $finder = new DomXPath($doc);
  // modify this based on data you need from target website
  $classname = "column3li";
  $nodes = $finder->query("//*[contains(@class, '$classname')]/li/h2");
  $param[] = $url;
  foreach ($nodes as $n) {
    echo $n->nodeValue;
    echo '====';
    
    // nasted selector
    $childDom = $finder->query("descendant::*[@class='grid-price-holder']", $ad);
  
    $param[] = [ 
      'cur' => $n->nodeValue, 
      'child'=> $childDom[0]->nodeValue
    ];
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
if ($reload) {
  echo '<meta http-equiv="refresh" content="0; url=spider.php?limit=' . $stop . '&fn=' . $fileName . '" />';
}
