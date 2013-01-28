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
	'id' => '69b7e3140a00016c5e4c03d46a931aed',
	'type' => 'folder'
);

$workflowDefinitionIdentifier = array
(
	'id' => '3591e3107f0000010020a239a209a2e2',
	'type' => 'workflowdefinition'
);

$readParams = array ('authentication' => $auth, 'identifier' => $identifier);
$reply = $client->readWorkflowSettings($readParams);

if ($reply->readWorkflowSettingsReturn->success=='true')
{
	$workflowDefinitions = $reply->readWorkflowSettingsReturn->workflowSettings->workflowDefinitions->assetIdentifier;
	if (sizeof($workflowDefinitions)==0)
		$workflowDefinitions = array();
	else if (!is_array($workflowDefinitions)) // For less than 2 eleements, the returned object isn't an array
		$workflowDefinitions=array($workflowDefinitions);		
	$alreadyContains = false;
	foreach($workflowDefinitions as $identifier)
		if($identifier->id==$workflowDefinitionIdentifier->id)
			$alreadyContains = true;

	if ($alreadyContains)
	{
		echo "Workflow definition is alraedy assigned";
	}
	else
	{
		$workflowDefinitions[] = $workflowDefinitionIdentifier;
		$reply->readWorkflowSettingsReturn->workflowSettings->workflowDefinitions->assetIdentifier=$workflowDefinitions;
		$editParams = array
		(
			'authentication' => $auth, 
			'workflowSettings' => $reply->readWorkflowSettingsReturn->workflowSettings,
			'applyInheritWorkflowsToChildren' => false, // Optional, false is default
			'applyRequireWorkflowToChildren' => false, // Optional, false is default
		);
		$reply = $client->editWorkflowSettings($editParams);
		
	    if ($reply->editWorkflowSettingsReturn->success=='true')		
			echo "Success.";
		else
			echo "Error occurred when editing workflow settings: " . $reply->editWorkflowSettingsReturn->message;		
	}	
}
else
	echo "Error occurred: " . $reply->readWorkflowSettingsReturn->message;
?>