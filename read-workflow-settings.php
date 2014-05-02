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
	'id' => 'folder-id-here',
	'type' => 'folder'
);

$readParams = array ('authentication' => $auth, 'identifier' => $identifier);
$reply = $client->readWorkflowSettings($readParams);

if ($reply->readWorkflowSettingsReturn->success=='true')
{
	echo "Inherit workflows: ".($reply->readWorkflowSettingsReturn->workflowSettings->inheritWorkflows?"true":"false");
	echo "\r\nRequired workflow: ".($reply->readWorkflowSettingsReturn->workflowSettings->requireWorkflow?"true":"false");
	
	$workflowDefinitions = $reply->readWorkflowSettingsReturn->workflowSettings->workflowDefinitions->assetIdentifier;
	if (sizeof($workflowDefinitions)==0)
		$workflowDefinitions = array();
	else if (!is_array($workflowDefinitions)) // For less than 2 elements, the returned object isn't an array
		$workflowDefinitions=array($workflowDefinitions);		
	echo "\r\nWorkflow definitions: ";
	foreach($workflowDefinitions as $identifier)
		echo "site://" .$identifier->path->siteName."/".$identifier->path->path . " ";
		
	$inheritedWorkflowDefinitions = $reply->readWorkflowSettingsReturn->workflowSettings->inheritedWorkflowDefinitions->assetIdentifier;
	if (sizeof($inheritedWorkflowDefinitions)==0)
		$inheritedWorkflowDefinitions = array();
	else if (!is_array($inheritedWorkflowDefinitions)) // For less than 2 elements, the returned object isn't an array
		$inheritedWorkflowDefinitions=array($inheritedWorkflowDefinitions);
	echo "\r\nInherited workflow definitions: ";
	foreach($inheritedWorkflowDefinitions as $identifier)
		echo "site://" .$identifier->path->siteName."/".$identifier->path->path . " ";
	
	echo "\r\n";
}
else
	echo "Error occurred: " . $reply->readWorkflowSettingsReturn->message;
?>