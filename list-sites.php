<?php
$soapURL = "http://localhost:8080/ws/services/AssetOperationService?wsdl";
$client = new SoapClient 
( 
	$soapURL, 
	array ('trace' => 1, 'location' => str_replace('?wsdl', '', $soapURL)) 
);	
$auth = array ('username' => 'admin', 'password' => 'admin' );

$listSitesParams = array ('authentication' => $auth);
$reply = $client->listSites($listSitesParams);

if ($reply->listSitesReturn->success=='true')
{
	$sites = $reply->listSitesReturn->sites->assetIdentifier;
	if (sizeof($sites)==0)
		$sites = array();
	else if (!is_array($sites)) // For less than 2 eleements, the returned object isn't an array
		$sites=array($sites);
	
	echo "Sites:\r\n";
	foreach($sites as $site)
		echo $site->path->path . " (" . $site->id . ")\r\n";
}
else
	echo "Error occurred when getting a list of sites: " . $reply->listSitesReturn->message;
?>