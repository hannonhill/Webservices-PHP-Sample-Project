<?php
// Publish a page or file

$soapURL = "http://localhost:8080/ws/services/AssetOperationService?wsdl";
$client = new SoapClient 
( 
	$soapURL, 
	array ('trace' => 1, 'location' => str_replace('?wsdl', '', $soapURL)) 
);	
$auth = array ('username' => 'admin', 'password' => 'admin' );

$identifier = array 
(
	// ID or path of the asset
	'id' =>'Your-Page-ID-Here',
	'type' => 'page'
);

$publishParams = array ('authentication' => $auth, 'identifier' => $identifier, 'unpublish' => false); 
$reply = $client->publish($publishParams);

//FYI: publishes to all available destinations, live and test - can't limit this 
if ($reply->publishReturn->success=='true')
	echo "Success: Published.";
else
	echo "Error occurred when publishing: " . $reply->publishReturn->message;

?>
