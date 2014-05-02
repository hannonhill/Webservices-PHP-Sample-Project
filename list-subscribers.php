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
	'id' => 'your-asset-id',
	'type' => 'your-asset-type'
);

$listSubscribersParams = array ('authentication' => $auth, 'identifier' => $identifier);
$reply = $client->listSubscribers($listSubscribersParams);

if ($reply->listSubscribersReturn->success=='true')
{
	echo "Subscribers:\r\n";
	$subscribers = $reply->listSubscribersReturn->subscribers->assetIdentifier;
	if (sizeof($subscribers)==0)
	{
		echo "NONE\r\n";
		exit;
	}
	else if (!is_array($subscribers)) // For less than 2 elements, the returned object isn't an array
		$subscribers=array($subscribers);
		
	foreach($subscribers as $identifier)
		echo "[".$identifier->type."] site://".$identifier->path->siteName."/".$identifier->path->path."\r\n";
}
else
	echo "Error occurred: " . $reply->listSubscribersReturn->message;
?>