<?php
/*
 * Created on Mar 10, 2008 by Artur Tomusiak
 *
 * THE PROGRAM IS DISTRIBUTED IN THE HOPE THAT IT WILL BE USEFUL, BUT WITHOUT ANY WARRANTY. IT IS PROVIDED "AS IS" 
 * WITHOUT WARRANTY OF ANY KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES 
 * OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE. THE ENTIRE RISK AS TO THE QUALITY AND PERFORMANCE OF THE 
 * PROGRAM IS WITH YOU. SHOULD THE PROGRAM PROVE DEFECTIVE, YOU ASSUME THE COST OF ALL NECESSARY SERVICING, REPAIR OR 
 * CORRECTION.
 * 
 * IN NO EVENT UNLESS REQUIRED BY APPLICABLE LAW THE AUTHOR WILL BE LIABLE TO YOU FOR DAMAGES, INCLUDING ANY GENERAL, SPECIAL, 
 * INCIDENTAL OR CONSEQUENTIAL DAMAGES ARISING OUT OF THE USE OR INABILITY TO USE THE PROGRAM (INCLUDING BUT NOT LIMITED TO LOSS 
 * OF DATA OR DATA BEING RENDERED INACCURATE OR LOSSES SUSTAINED BY YOU OR THIRD PARTIES OR A FAILURE OF THE PROGRAM TO OPERATE 
 * WITH ANY OTHER PROGRAMS), EVEN IF THE AUTHOR HAS BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES.
 * 
 * Please feel free to distribute this code in any way, with or without this notice.
 */

/**
 * Because of a limitation with Apache Axis, the data received when doing
 * a read on an asset is not able to be directly sent back to the server
 * as-is because the server actually sends more data than necessary. This
 * function will go through a page asset and null out the unnecessary items,
 * making the asset able to be sent back to the server.
 * 
 * In essence, the server sends both the id and path information for a relationship,
 * however the server will only accept either the id or the path, but not both. This
 * method nulls out the applicable relationship paths in favor of the ids. 
 * 
 * When editing a page, the order of the steps should go like this:
 *  1. Read page (send the read request)
 *  2. Get all the needed data from the page (for example: "$title = $page['title'];")
 *  3. Null page values (call this method)
 *  4. Set all the needed data to the page (whatever needs to be modified)
 *  5. Edit the page (send the edit request)
 * 
 * @param $page the asset whose data will be intelligently nulled out to ensure
 *      it can be sent back to the server.
 */
function nullPageValues(&$page)
{
    //Never, ever send an entity type. This will lead to an error.
	unset($page['entityType']);
    //Null out the various relationship paths in favor of the ids 
	if ($page['parentFolderPath'] != null)
	    unset($page['parentFolderId']);
	if ($page['configurationSetPath'] != null)
		unset($page['configurationSetId']);
	if ($page['expirationFolderPath'] != null)
		unset($page['expirationFolderId']);
	if ($page['metadataSetPath'] != null)
		unset($page['metadataSetId']);
    //Null out the path in favor of the id
	if ($page['path'] != null)
		unset($page['id']);
		

    //If the page has structured data, null out the structured data
    //relationships as well
	$sData = $page['structuredData'];
    if ($sData != null)
    {
    	unset($page['xhtml']);
    	if ($sData->definitionPath != null)
			unset($sData->definitionId);
		$structuredDataNodes = $sData->structuredDataNodes;	
		if ($structuredDataNodes!=null)
		{
			$sNodes = $structuredDataNodes->structuredDataNode;
	        if ($sNodes != null)
	            _nullStructuredData($sNodes);
		}				
    }
	else
	{
		unset($page['structuredData']);
	}
    
    //Null out all the page configuration relationship information
	$pageConfigurations = $page['pageConfigurations'];
	$pConf = $pageConfigurations->pageConfiguration;    
	if ($pConf != null)
    {
    	if (sizeof($pConf)>1)
	        for ($i = 0; $i < sizeof($pConf); $i++)
				_nullPageConfiguration($pConf[$i]);
		else
			_nullPageConfiguration($pConf);
    }
}

/**
 * Extracts an error message from last response. Should be used when isSuccess($response)==false.
 * 
 * @return 
 * @param $response - the object returned by $client->__getLastResponse();
 */
function extractMessage($response)
{
	return substr($response, strpos($response, "<message>")+9,strpos($response, "</message>")-(strpos($response, "<message>")+9));
}

/**
 * Extracts a success flag. 
 * 
 * @return Returns true if the respnse has a success=="true" or false if it's not equal to "true".
 * @param $response - the object returned by $client->__getLastResponse();
 */
function isSuccess($response)
{
	return substr($response, strpos($response, "<success>")+9,4)=="true";
}


/**********************************************************************************************************/

function _nullPageConfiguration(&$thisPConf)
{
	if ($thisPConf->stylesheetPath!=null)
		unset($thisPConf->stylesheetId);
	if ($thisPConf->templatePath!=null)
		unset($thisPConf->templateId);
	unset($thisPConf->entityType);

    // fix page regions
	$pageRegions = $thisPConf->pageRegions;
	$pRegs = $pageRegions->pageRegion;

    if ($pRegs != null)
    {
		if (sizeof($pRegs)>1)
            for ($j = 0; $j < sizeof($pRegs); $j++)
            	_nullPageRegion($pRegs[$j]);
		else
			_nullPageRegion($pRegs);
    }	
}

function _nullPageRegion(&$thisPReg)
{
	unset($thisPReg->entityType);
	if ($thisPReg->blockPath!=null)
		unset($thisPReg->blockId);
	if ($thisPReg->stylesheetPath!=null)
		unset($thisPReg->stylesheetId);
	unset($thisPReg->entityType);	
}


function _nullStructuredDataNode(&$thisNode)
{
	if ($thisNode->type=='asset')			
    {
        unset($thisNode->text);
		unset($thisNode->structuredDataNodes);

		if ($thisNode->assetType=='block')				
        {
       		if ($thisNode->blockId==null && $thisNode->blockPath==null)
				$thisNode->blockPath="";						
            else if ($thisNode->blockPath!=null)
				unset($thisNode->blockId);
			unset($thisNode->pageId);
			unset($thisNode->pagePath);
			unset($thisNode->symlinkId);
			unset($thisNode->symlinkPath);
			unset($thisNode->fileId);
			unset($thisNode->filePath);
        }
		else if ($thisNode->assetType=='file')				
        {
       		if ($thisNode->fileId==null && $thisNode->filePath==null)
				$thisNode->filePath="";						
            else if ($thisNode->filePath!=null)
				unset($thisNode->fileId);
			unset($thisNode->pageId);
			unset($thisNode->pagePath);
			unset($thisNode->symlinkId);
			unset($thisNode->symlinkPath);
			unset($thisNode->blockId);
			unset($thisNode->blockPath);
        }
		else if ($thisNode->assetType=='page')				
        {
       		if ($thisNode->pageId==null && $thisNode->pagePath==null)
				$thisNode->pagePath="";						
            else if ($thisNode->pagePath!=null)
				unset($thisNode->pageId);
				unset($thisNode->blockId);
			unset($thisNode->blockPath);
			unset($thisNode->symlinkId);
			unset($thisNode->symlinkPath);
			unset($thisNode->fileId);
			unset($thisNode->filePath);
       }
		else if ($thisNode->assetType=='symlink')				
        {
       		if ($thisNode->symlinkId==null && $thisNode->symlinkPath==null)
				$thisNode->symlinkPath="";						
            else if ($thisNode->symlinkPath!=null)
				unset($thisNode->symlinkId);
				unset($thisNode->pageId);
			unset($thisNode->pagePath);
			unset($thisNode->blockId);
			unset($thisNode->blockPath);
			unset($thisNode->fileId);
			unset($thisNode->filePath);
       }
    }
    else if ($thisNode->type=='group')	
    {	
		$structuredDataNodes = $thisNode->structuredDataNodes;		
		if ($structuredDataNodes!=null)
		{
			$sNodes = $structuredDataNodes->structuredDataNode;
	        if ($sNodes != null)
	        {
	            _nullStructuredData($sNodes);
	        }
		}		
        unset($thisNode->text);
		unset($thisNode->assetType);
		unset($thisNode->blockId);
		unset($thisNode->blockPath);
		unset($thisNode->symlinkId);
		unset($thisNode->symlinkPath);
		unset($thisNode->fileId);
		unset($thisNode->filePath);				        
		unset($thisNode->pageId);
		unset($thisNode->pagePath);				        
	}
    else if ($thisNode->type=='text')	
    {
    	unset($thisNode->assetType); 
		unset($thisNode->structuredDataNodes); 
		unset($thisNode->blockId);
		unset($thisNode->blockPath);
		unset($thisNode->symlinkId);
		unset($thisNode->symlinkPath);
		unset($thisNode->fileId);
		unset($thisNode->filePath);				        
		unset($thisNode->pageId);
		unset($thisNode->pagePath);		
		if ($thisNode->text==null)
			$thisNode->text="";
    }
    else
    {
        unset($thisNode->text);
		unset($thisNode->assetType); 
    }	
}

function _nullStructuredData(&$sDataNodes)
{
    if ($sDataNodes != null)
    {
    	if (sizeof($sDataNodes)>1)
	        for ($k = 0; $k < sizeof($sDataNodes); $k++)
				_nullStructuredDataNode($sDataNodes[$k]);
		else	
			_nullStructuredDataNode($sDataNodes);
    }
}




?>
