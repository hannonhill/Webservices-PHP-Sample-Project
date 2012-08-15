<?php
$client = new SoapClient 
( 
	"http://localhost:8080/ws/services/AssetOperationService?wsdl", 
	array ('trace' => 1 ) 
);	
$auth = array ('username' => 'admin', 'password' => 'admin' );

$identifier = array 
(
	'path' => array
	(
		path => '/my-xml-block'
	),
	'type' => 'xmlBlock'
);

$xmlBlock = array
(
	'xml' => '<xml>Test</xml>',
	'metadataSetPath' => '/Default',
	'parentFolderPath' => '/',
	'name' => 'my-xml-block'
);

$asset = array('xmlBlock' => $xmlBlock);
$createParams = array ('authentication' => $auth, 'asset' => $asset);
$reply = $client->create ($createParams);

if ($reply->createReturn->success=='true')
	echo "Success. Created asset's id is " . $reply->createReturn->createdAssetId;
else
	echo "Error occurred: " . $reply->createReturn->message;
?>