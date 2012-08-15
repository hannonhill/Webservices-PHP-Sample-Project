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

$deleteParams = array ('authentication' => $auth, 'identifier' => $identifier);
$reply = $client->delete($deleteParams);

if ($reply->deleteReturn->success=='true')
	echo "Success.";
else
	echo "Error occurred when deleting: " . $reply->deleteReturn->message;
?>