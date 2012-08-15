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

$readParams = array ('authentication' => $auth, 'identifier' => $identifier);
$reply = $client->readAccessRights($readParams);

if ($reply->readAccessRightsReturn->success=='true')
{
	$aclEntries = $reply->readAccessRightsReturn->accessRightsInformation->aclEntries->aclEntry;
                
    if (!is_array($aclEntries)) // For less than 2 eleements, the returned object isn't an array
		$aclEntries=array($aclEntries);

	for($i=0; $i<sizeof($aclEntries); $i++)
	{
		$aclEntry = $aclEntries[$i];
		if ($aclEntry->name=='admin' && $aclEntry->type=='user')
		{
			echo 'User admin has acl entry level of '.$aclEntry->level;
			exit;
		}
	}
	
	echo 'Could not find an acl entry for user admin';
}
else
	echo "Error occurred: " . $reply->readAccessRightsReturn->message;
?>