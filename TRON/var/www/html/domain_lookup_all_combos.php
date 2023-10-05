<?php
// be sure to have php curl installed -- apt install php-curl  -- or this won't work

if (isset($_GET['searchtext'])){

	$searchTerm = htmlspecialchars($_GET['searchtext']);
	$searchTerm = preg_replace("/[^A-Za-z0-9\. -]/", '', $searchTerm);

	$headers = array(
        	'Content-Type: text/xml; charset=utf-8',
        	'SOAPAction: "http://schemas.microsoft.com/exchange/2010/Autodiscover/Autodiscover/GetFederationInformation"',
        	'User-Agent: AutodiscoverClient',
        	'Accept-Encoding: identity'
	);

	$url='https://autodiscover-s.outlook.com/autodiscover/autodiscover.svc';
	$domain =  $searchTerm;
	$xml = '<?xml version="1.0" encoding="utf-8"?><soap:Envelope xmlns:exm="http://schemas.microsoft.com/exchange/services/2006/messages" xmlns:ext="http://schemas.microsoft.com/exchange/services/2006/types" xmlns:a="http://www.w3.org/2005/08/addressing" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><soap:Header><a:Action soap:mustUnderstand="1">http://schemas.microsoft.com/exchange/2010/Autodiscover/Autodiscover/GetFederationInformation</a:Action><a:To soap:mustUnderstand="1">https://autodiscover-s.outlook.com/autodiscover/autodiscover.svc</a:To><a:ReplyTo><a:Address>http://www.w3.org/2005/08/addressing/anonymous</a:Address></a:ReplyTo></soap:Header><soap:Body><GetFederationInformationRequestMessage xmlns="http://schemas.microsoft.com/exchange/2010/Autodiscover"><Request><Domain>' . $domain . '</Domain></Request></GetFederationInformationRequestMessage></soap:Body></soap:Envelope>';
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL,$url);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $xml);
	curl_setopt($curl, CURLOPT_TIMEOUT, 35);
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 20);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

	$result = curl_exec($curl);

	if(curl_errno($curl)){
	    throw new Exception(curl_error($curl));
	}
	//Close the cURL handle.
	curl_close($curl);



$teststring = "/<Domain>(.*?)<\/Domain>/";
$matches = array();
if (preg_match_all($teststring, $result, $matches)) {
	$domainArray = array();
	$tenantArray = array();
	$primarytenant = "";

	for ($i=0; $i<count($matches[0]); $i++){
		$testmatch = $matches[0][$i];
		#echo "Testmatch is " . $testmatch . " <BR>";

		if (preg_match('/.onmicrosoft.com/', $testmatch) === 0) {
			preg_match('/<Domain>(.*?)<\/Domain>/', $testmatch, $matched_domain);
			array_push($domainArray,strtolower($matched_domain[1]));
		} elseif (preg_match('/.mail.onmicrosoft.com/', $testmatch) === 1) {
			$primarytenant = strtolower($matches[0][$i]);
			//echo "Primary tenant found: " . $primarytenant . "<BR>";
    } elseif (preg_match('/([a-zA-Z0-9._-]+).onmicrosoft.com/', $testmatch, $matched_tenant) === 1) {
			array_push($tenantArray,strtolower($matched_tenant[1]));
		}else{
			echo "ERROR.  Something is up.";
			print_r(matches[0]);
		}
	}

	foreach ($tenantArray as &$tenant){
		foreach ($domainArray as &$domain){
			echo "" . $tenant . "," . $domain . "" .PHP_EOL;
		}
	}
}else{
	echo "NOT FOUND:" . strtolower($searchTerm) . PHP_EOL;
}

}

?>
