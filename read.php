<?php
$client = new SoapClient 
( 
	"http://localhost:8080/ws/services/AssetOperationService?wsdl", 
	array ('trace' => 1 ) 
);	
$auth = array ('username' => 'admin', 'password' => 'admin' );

$identifier = array 
(
	'path' => array(path => '/my-xml-block'),
	'type' => 'block'
);

$readParams = array ('authentication' => $auth, 'identifier' => $identifier);
$reply = $client->read($readParams);

if ($reply->readReturn->success=='true')
	echo "Success. Block's xml: " . $reply->readReturn->asset->xmlBlock->xml;
else
	echo "Error occurred: " . $reply->readReturn->message;
?>