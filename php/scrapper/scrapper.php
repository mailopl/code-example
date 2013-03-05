<?php
require_once './library/Requests.php'; // wrapper for cURL and streams
require_once("./library/idiorm.php"); // simple ORM

ORM::configure('mysql:host=localhost;dbname=scrap;');
ORM::configure('username', 'root');
ORM::configure('password', '');

Requests::register_autoloader();

// Does not handle pagination.
$config = array(
	"http://www.katalogiseo.info/" => array(
		'longestCommonXpath' => '/html/body/table/tr[2]/td/table[2]/tr', // path to common row in data table
		'urlXpath' => 'td[2]/a',
		'prXpath' => 'td[2]/span/a[1]/b',
		'scriptXpath' => 'td[3]/a'
	),
	"http://www.katalogi-seo.com/" => array(
		'longestCommonXpath' => '/html/body/div/div[2]/div/div[1]/div/div[3]/table/tr/td[2]/div[1]',
		'urlXpath' => 'a',
		'prXpath' => null, // if pr doesnt exist...
		'scriptXpath' => null
	),
	"http://www.dodawarka.info/katalogstron.phtml" => array(
		'longestCommonXpath' => '//*[@id="list"]/tr',
		'urlXpath' => 'td[1]/abbr/a/span',
		'prXpath' => 'td[3]/b',
		'scriptXpath' => 'td[4]'
	)
);

// Here may be problem with the execution time, 
// one way could be to use one redirect for one page in this loop
foreach($config as $base => $xpathData) {
	$request 	= Requests::get($base);
	$html 		= $request->body;
	$query 		= $xpathData['longestCommonXpath'];

	$dom = new DOMDocument();
	@$dom->loadHTML($html);

	$xpath = new DOMXPath($dom);
	$rows = $xpath->query($query);


	foreach ($rows as $row) {
		
		if (!$xpath->query($xpathData['urlXpath'], $row)->item(0)) {
			continue;
		}
	    
	    $url = ORM::for_table('urls')->create();

	    $url->url = $xpath->query($xpathData['urlXpath'], $row)->item(0)->nodeValue;
	    
	    if (!empty($xpathData['prXpath'])) {
	    	$url->pr = $xpath->query($xpathData['prXpath'], $row)->item(0)->nodeValue;	
		}
		if (!empty($xpathData['scriptXpath'])) {
	    	$url->script = $xpath->query($xpathData['scriptXpath'], $row)->item(0)->nodeValue;
	    }
	    
	    $url->base = $base;
	    try{
	    	$url->save(); // update db
	    } catch(PDOException $e) {
    		//Probably just "Integrity constraint violation 1062 Duplicate entry"
    		continue;
	    }
	}
}
die;