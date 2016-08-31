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
	'path' => array('path' => '/my-xml-block', 'siteName'=> 'nameOfSite'),
	'type' => 'block'
);

$readParams = array ('authentication' => $auth, 'identifier' => $identifier);
$reply = $client->read($readParams);

if ($reply->readReturn->success=='true')
{
	$xmlBlock = $reply->readReturn->asset->xmlBlock;
	$xmlBlock->metadata->title="A new title";
	$editParams = array ('authentication' => $auth, 'asset' => array('xmlBlock' => $xmlBlock));
	$reply = $client->edit($editParams);
	if ($reply->editReturn->success=='true')		
		echo "Success.";
	else
		echo "Error occurred when issuing an edit: " . $reply->editReturn->message;
}
else
	echo "Error occurred when reading: " . $reply->readReturn->message;
?>
