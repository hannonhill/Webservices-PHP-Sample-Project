<?php
include("web_services_util.php");

$client = new SoapClient ( "http://localhost:8080/ws/services/AssetOperationService?wsdl", array ('trace' => 1 ) );	
$auth = array ('username' => 'admin', 'password' => 'admin' );
$id = array ('type' => 'page', 'id' => 'b54d846f0a00016c6a59d8da787f87cf' );	
$params = array ('authentication' => $auth, 'identifier' => $id );
	
// Read asset
$reply = $client->read ( $params );
if ($reply->readReturn->success == 'true') 
{
	$asset = ( array ) $reply->readReturn->asset->page;

	// Get its title
	$metadata = $asset["metadata"];
	$title = $metadata->title;
	
	// Update title
	date_default_timezone_set('America/New_York');
	$title = date('l dS \of F Y h:i:s A');
	$metadata->title = $title;
	
	// Edit page
	$params = array ('authentication' => $auth, 'asset' => array ('page' => $asset ) );
	try
	{
		$reply = $client->edit ( $params );
	}
	catch(Exception $e)
	{
		echo "\r\nProblem: {$e->getMessage()}\n";
	}		
	$result = $client->__getLastResponse();
	if (!isSuccess($result))
	{
		echo "\r\nError occured:";
		echo "\r\n".extractMessage($result)."\r\n";
	}
	else	
		echo "\r\nAsset updated successfully\r\n";
	echo "Done";
}
else
{
	echo "Problem occured\n";
}



?>