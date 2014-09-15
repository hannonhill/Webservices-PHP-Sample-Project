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
	'path' => array('path' => '/my-xml-block','siteName' => 'nameOfSite'),
	//OR 'id' => 'string', and then the type
	'type' => 'block'
);

$readParams = array ('authentication' => $auth, 'identifier' => $identifier);
$reply = $client->read($readParams);

if ($reply->readReturn->success=='true')
	echo "Success. Block's xml: " . $reply->readReturn->asset->xmlBlock->xml;
else
	echo "Error occurred: " . $reply->readReturn->message;
?>
