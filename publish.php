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

$destinationIdentifier = array
(
	// ID or path of the destination
	'id' => 'Your-Destination-ID-Here',
	'type' => 'destination'
);

$publishInformation = array
(
	'identifier' => $identifier,
 	'destinations' => array ($destinationIdentifier), // This is optional, not providing this will result in publishing to all enabled destinations available to authenticating user 
	'unpublish' => false // This is optional, default is false
);

$publishParams = array ('authentication' => $auth, 'publishInformation' => $publishInformation); 
$reply = $client->publish($publishParams);

if ($reply->publishReturn->success=='true')
	echo "Success: Published.";
else
	echo "Error occurred when publishing: " . $reply->publishReturn->message;

?>
