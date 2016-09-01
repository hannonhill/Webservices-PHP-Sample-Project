<?php
$soapURL = "http://localhost:8080/ws/services/AssetOperationService?wsdl";
$client = new SoapClient 
( 
	$soapURL, 
	array ('trace' => 1, 'location' => str_replace('?wsdl', '', $soapURL)) 
);	
$auth = array ('username' => 'admin', 'password' => 'admin' );

$identifier = array 
(
	'path' => array('path' => '/my-xml-block', 'siteName' => 'nameOfSite'),
	'type' => 'block'
);

$deleteParams = array ('authentication' => $auth, 'identifier' => $identifier);
$reply = $client->delete($deleteParams);

if ($reply->deleteReturn->success=='true')
	echo "Success.";
else
	echo "Error occurred when deleting: " . $reply->deleteReturn->message;
?>
