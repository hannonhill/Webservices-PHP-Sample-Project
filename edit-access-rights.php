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

$readParams = array ('authentication' => $auth, 'identifier' => $identifier);
$reply = $client->readAccessRights($readParams);

if ($reply->readAccessRightsReturn->success=='true')
{
	$accessRightsInformation = $reply->readAccessRightsReturn->accessRightsInformation;
	$aclEntries = $accessRightsInformation->aclEntries->aclEntry;
	
	if (sizeof($aclEntries)==0)
		$aclEntries = array();
    else if (!is_array($aclEntries)) // For less than 2 eleements, the returned object isn't an array
		$aclEntries=array($aclEntries);
	
	$aclEntries[] = array('level' => 'read', 'type' => 'user', 'name' => 'admin');
	$accessRightsInformation->aclEntries->aclEntry=$aclEntries;

	$editParams = array
	(
		'authentication' => $auth, 
		'accessRightsInformation' => $accessRightsInformation
	);

    $reply = $client->editAccessRights($editParams);
    if ($reply->editAccessRightsReturn->success=='true')		
		echo "Success.";
	else
		echo "Error occurred when editing access rights: " . $reply->editAccessRightsReturn->message;
}
else
	echo "Error occurred: " . $reply->readAccessRightsReturn->message;
?>
