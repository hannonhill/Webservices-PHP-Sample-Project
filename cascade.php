<?php
/*
Function List:
$obj = new Cascade($username, $password, $domain); //wrap in try/catch
$obj->changeAuth($username, $password); //wrap in try/catch
$identifier = $obj->identifier($type, $path, $siteName);
$identifier = $obj->identifierByPath($type, $path, $siteName);
$identifier = $obj->identifier($type, $id);
$identifier = $obj->identifierById($type, $id);
$acl= $obj->createACL($name, $level, $type);
$bool = $obj->result($reply);
$reply = $obj->listSites();
$reply = $obj->listSubscribers($identifier);
$reply = $obj->read($identifier);
$reply = $obj->readWorkflowSettings($identifier);
$reply = $obj->readAccessRights($identifier);
$reply = $obj->search($searchInfo);
$reply = $obj->edit($asset);
$reply = $obj->editWorkflowSettings($workflowSettings, $childrenInherit, $childrenRequire);
$reply = $obj->editAccessRights($acls, $children);
$reply = $obj->move($identifier, $destIdentifier, $newName, $doWorkflow = false);
$reply = $obj->create($asset);
$reply = $obj->copy($identifier, $destIdentifier, $newName);
$reply = $obj->delete($identifier);
$reply = $obj->publish($identifier, $destIdentifiers, $unpublish);

$array = $obj->objectToArray($object);
$object = $obj->arrayToObject($array);
$obj->test($arr);
*/
class Cascade
{
	private $auth;
	private $client;
	/*
	Builds $auth and $client, then tests authentication
	NOTE:Wrap in try/catch
	*/
	function __construct($username, $password, $domain)
	{
		$this->auth = array ('username' => $username, 'password' => $password );
		$soapURL = "https://".$domain."/ws/services/AssetOperationService?wsdl";
		$this->client = new SoapClient
		(
			$soapURL,
			array ('trace' => 1, 'location' => str_replace('?wsdl', '', $soapURL))
		);
		$this->testAuth();
	}
	/*
	Allows: identifier($type,$path,$siteName);
	Allows: identifier($type,$id);
	*/
	function __call($method, $arguments)
	{
		/*
		Allows:
		$obj->identifier($type,$path,$siteName);
		$obj->identifier($type,$id);
		*/
		if($method == 'identifier')
		{
			if(count($arguments) == 2)
				return call_user_func_array(array ($this,'identifierById'), $arguments);
			elseif(count($arguments) == 3)
				return call_user_func_array(array ($this,'identifierByPath'), $arguments);
		}
	}
	/*
	Changes the authenticated user, and test the authentication
	NOTE:Wrap in try/catch
	*/
	function changeAuth($username, $password)
	{
		$this->auth = array ('username' => $username, 'password' => $password );
		$this->testAuth();
	}
	/*
	Tests authentication using listSites()
	Throws an exception on failure
	*/
	private function testAuth()
	{
		if(!$this->result($this->listSites()))
			throw new Exception('Invalid Username/Password');
	}
	/*
	Return boolean on $reply->success
	*/
	function result($reply)
	{
		return ($reply->success == "true");
	}
	/*
	Builds path based identifier
	*/
	function identifierByPath($type, $path, $siteName)
	{
		return array(
			'path' => array(
				'path' => $path,
				'siteName' => $siteName),
			'type' => $type
		);
	}
	/*
	Builds id based identifier
	*/
	function identifierById($type, $id)
	{
		return array(
			'id' => $id,
			'type' => $type
		);
	}
	/*
	Builds single ACL
	*/
	function createACL($name, $level = 'read', $type = 'group')
	{
		return array(
			'name' => $name,
			'level' => $level,
			'type' => $type
		);
	}
	/*
	Returns list of sites
	*/
	function listSites()
	{
		$params = array ('authentication' => $this->auth);
		$reply = $this->client->listSites($params);
		return $reply->listSitesReturn;
	}
	/*
	Returns list of subscribers
	*/
	function listSubscribers($identifier)
	{
		$params = array ('authentication' => $this->auth, 'identifier' => $identifier);
		$reply = $this->client->listSubscribers($params);
		return $reply->listSubscribersReturn;
	}
	/*
	Returns readReturn from identifier
	*/
	function read($identifier)
	{
		$params = array ('authentication' => $this->auth, 'identifier' => $identifier);
		$reply = $this->client->read($params);
		return $reply->readReturn;
	}
	/*
	Returns readWorkflowSettingsReturn from identifier
	*/
	function readWorkflowSettings($identifier)
	{
		$params = array ('authentication' => $this->auth, 'identifier' => $identifier);
		$reply = $this->client->readWorkflowSettings($params);
		return $reply->readWorkflowSettingsReturn;
	}
	/*
	Returns readAccessRightsReturn from identifier
	*/
	function readAccessRights($identifier)
	{
		$params = array ('authentication' => $this->auth, 'identifier' => $identifier);
		$reply = $this->client->readAccessRights($params);
		return $reply->readAccessRightsReturn;
	}
	/*
	Returns searchReturn from identifier
	*/
	function search($searchInfo)
	{
		$params = array ('authentication' => $this->auth, 'searchInformation' => $searchInfo);
		$reply = $this->client->search($params);
		return $reply->searchReturn;
	}
	/*
	Returns editReturn from identifier
	*/
	function edit($asset)
	{
		$params = array ('authentication' => $this->auth, 'asset' => $asset);
		$reply = $this->client->edit($params);
		return $reply->editReturn;
	}
	/*
	Returns editWorkflowSettingsReturn from identifier
	*/
	function editWorkflowSettings($workflowSettings, $childrenInherit, $childrenRequire)
	{
		$params = array ('authentication' => $this->auth, 'workflowSettings' => $workflowSettings, 'applyInheritWorkflowsToChildren' => $childrenInherit, 'applyRequireWorkflowToChildren' => $childrenRequire);
		$reply = $this->client->editWorkflowSettings($params);
		return $reply->editWorkflowSettingsReturn;
	}
	/*
	Returns editAccessRightsReturn from identifier
	*/
	function editAccessRights($acls, $children)
	{
		$params = array ('authentication' => $this->auth, 'accessRightsInformation' => $acls, 'applyToChildren' => $children);
		$reply = $this->client->editAccessRights($params);
		return $reply->editAccessRightsReturn;
	}
	/*
	Returns moveReturn from identifier
	*/
	function move($identifier, $destIdentifier, $newName, $doWorkflow = false)
	{
		$params = array ('authentication' => $this->auth, 'identifier' => $identifier, 'moveParameters' => array('destinationContainerIdentifier' => $destIdentifier, 'newName' => $newName, 'doWorkflow' => $doWorkflow));
		$reply = $this->client->move($params);
		return $reply->moveReturn;
	}
	/*
	Returns createReturn from identifier
	*/
	function create($asset)
	{
		$params = array ('authentication' => $this->auth, 'asset' => $asset);
		$reply = $this->client->create($params);
		return $reply->createReturn;
	}
	/*
	Returns copyReturn from identifier
	*/
	function copy($identifier, $destIdentifier, $newName)
	{
		$params = array ('authentication' => $this->auth, 'identifier' => $identifier, 'copyParameters' => array('destinationContainerIdentifier' => $destIdentifier, 'newName' => $newName, 'doWorkflow' => false));
		$reply = $this->client->copy($params);
		return $reply->copyReturn;
	}
	/*
	Returns deleteReturn from identifier
	*/
	function delete($identifier)
	{
		$params = array ('authentication' => $this->auth, 'identifier' => $identifier);
		$reply = $this->client->delete($params);
		return $reply->deleteReturn;
	}
	/*
	Returns publishReturn from identifier
	*/
	function publish($identifier, $destIdentifiers = false, $unpublish = false)
	{
		$publishInformation = array
		(
			'identifier' => $identifier,
			'unpublish' => $unpublish
		);
		if($destIdentifiers)
		{
			if(is_array($destIdentifiers))
				$publishInformation['destinations'] = $destIdentifiers;
			else
				$publishInformation['destinations'] = array($destIdentifiers);
		}
		$params = array ('authentication' => $this->auth, 'publishInformation' => $publishInformation);
		$reply = $this->client->publish($params);
		return $reply->publishReturn;
	}
	/*
	For converting the multi-dimensional object to a multi-dimensional array
	*/
	function objectToArray($object)
	{
		if (is_object($object))
			$object = get_object_vars($object);
		if (is_array($object))
			return array_map(array ($this,'objectToArray'), $object);
		else
			return $object;
	}
	/*
	For converting the a multi-dimensional array to a multi-dimensional object
	*/
	function arrayToObject($array)
	{
		if (is_array($array) && (bool)count(array_filter(array_keys($array), 'is_string')))
			return (object) array_map(array ($this,'arrayToObject'), $array);
		elseif(is_array($array) && !(bool)count(array_filter(array_keys($array), 'is_string')))
		{
			$temp = array();
			foreach($array as $arr)
			{
				if(is_array($arr))
					$temp[] = (object) array_map(array ($this,'arrayToObject'), $arr);
				else
					$temp[] = $arr;
			}
			return $temp;
		}
		else
			return $array;
	}
	/*
	For checking values in array/object
	*/
	function test($arr)
	{
		echo "<pre>";
		print_r($arr);
		echo "</pre>";
	}
}
?>
