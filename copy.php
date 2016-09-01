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

$destFolderIdentifier = array 
(
	'path' => array('path' => '/my-folder', 'siteName' => 'nameOfSite'),
	'type' => 'folder'
);

$copyParams = array 
(
	'authentication' => $auth, 
	'identifier' => $identifier, 
	'copyParameters' => array
	(
		'destinationContainerIdentifier' => $destFolderIdentifier,
		'doWorkflow' => false,
		'newName' => 'my-xml-block-copy',
	)
);

$reply = $client->copy($copyParams);

if ($reply->copyReturn->success=='true')
	echo "Success.";
else
	echo "Error occurred when copying: " . $reply->copyReturn->message;
?>
