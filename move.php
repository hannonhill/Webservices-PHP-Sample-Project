<?php
// Move/Rename an asset

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

$destFolderIdentifier = array 
// Optional if you're renaming not moving
(
	'id' =>'Your-New-Folder-ID',
	'type' => 'folder'
);

$moveParams = array 
(
	'authentication' => $auth, 
	'identifier' => $identifier, 
	'moveParameters' => array
	(
// Must specify a new name and/or new container for the asset - only one is required
		'destinationContainerIdentifier' => $destFolderIdentifier,
		'newName' => 'New-Page-Name',
// Required: true or false
		'doWorkflow' => false,
	)
);

$reply = $client->move($moveParams);

if ($reply->moveReturn->success=='true')
	echo "Success.";
else
	echo "Error occurred when moving/renaming: " . $reply->moveReturn->message;
?>
