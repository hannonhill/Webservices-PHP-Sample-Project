<?php
$soapURL = "http://localhost:8080/ws/services/AssetOperationService?wsdl";
$client = new SoapClient 
( 
	$soapURL, 
	array ('trace' => 1, 'location' => str_replace('?wsdl', '', $soapURL)) 
);	
$auth = array ('username' => 'admin', 'password' => 'admin' );

$xmlBlock = array
(
	'xml' => '<xml>Test</xml>',
	'metadataSetPath' => '/Default',
	'parentFolderPath' => '/',
	'name' => 'my-xml-block',
	'siteName' => 'nameOfSite'
);

$asset = array('xmlBlock' => $xmlBlock);
$createParams = array ('authentication' => $auth, 'asset' => $asset);
$reply = $client->create ($createParams);

if ($reply->createReturn->success=='true')
	echo "Success. Created asset's id is " . $reply->createReturn->createdAssetId;
else
	echo "Error occurred: " . $reply->createReturn->message;
?>
