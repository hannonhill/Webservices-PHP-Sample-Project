<?php
// Publish a page or file

$client = new SoapClient 
( 
	"http://localhost:8080/ws/services/AssetOperationService?wsdl", 
	array ('trace' => 1 ) 
);	
$auth = array ('username' => 'admin', 'password' => 'admin' );

$identifier = array 
(
// ID or path of the asset
	'id' =>'Your-Page-ID-Here',
	'type' => 'page'
);

	$publishParams = array ('authentication' => $auth, 'identifier' => $identifier, ); $reply = $client->publish($publishParams);
		echo "<br />";
	//FYI: publishes to all available destinations, live and test - can't limit this 
	if ($reply->publishReturn->success=='true')
		echo "Success: Published.";
	else
		echo "Error occurred when publishing: " . $reply->publishReturn->message;

}

?>
