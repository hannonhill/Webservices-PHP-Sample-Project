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

$destFolderIdentifier = array 
(
	'path' => array(path => '/my-folder'),
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