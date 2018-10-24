<?php
/**********************************************************************************
* Subs-PMAttachmentsHooks.php - Hooks of the PM Attachments mod
***********************************************************************************
* This mod is licensed under the 2-clause BSD License, which can be found here:
*	http://opensource.org/licenses/BSD-2-Clause
***********************************************************************************
* This program is distributed in the hope that it is and will be useful, but      *
* WITHOUT ANY WARRANTIES; without even any implied warranty of MERCHANTABILITY    *
* or FITNESS FOR A PARTICULAR PURPOSE.                                            *
**********************************************************************************/
if (!defined('SMF'))
	die('Hacking attempt...');

function PMA_Actions(&$actions)
{
	$actions['dlpmattach'] = array('Subs-PMAttachments.php', 'PMDownload');
	if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'help')
		loadLanguage('PMAttachments');
}

function PMA_Admin(&$admin_areas)
{
	global $txt;
	loadLanguage('PMAttachments');
	$subsections = array();
	$original = &$admin_areas['layout']['areas']['manageattachments']['subsections'];
	foreach ($original as $id => $area)
	{
		$subsections[$id] = $area;
		if ($id == 'attachments')
			$subsections['pmattachments'] = array($txt['pmattachment_manager_settings']);
		elseif ($id == 'maintenance')
			$subsections['pmMaintenance'] = array($txt['pmattachment_manager_maintenance']);
	}
	$original = $subsections;		
}

function PMA_Admin_Search(&$language_files, &$include_files, &$settings_search)
{
	global $sourcedir;
	loadLanguage('PMAttachments');
	require_once($sourcedir . '/Subs-PMAttachmentsAdmin.php');
	$settings_search[] = array('ManagePMAttachmentSettings', 'area=manageattachments;sa=pmattachments');
}

function PMA_Manage(&$subActions)
{
	global $txt, $sourcedir;

	$subActions = array_merge($subActions, array(
		'pmattachments' => 'ManagePMAttachmentSettings',
		'pmattachpaths' => 'ManagePMAttachmentPaths',
		'pmByAge' => 'RemovePMAttachmentByAge',
		'pmBySize' => 'RemovePMAttachmentBySize',
		'pmByDowns' => 'RemovePMAttachByDownloads',
		'pmMaintenance' => 'MaintainPMFiles',
		'pmRemoveReported' => 'RemoveReportedPMAttachments',
		'pmRemoveByMembers' => 'RemovePMAttachByMembers',
		'pmremoveall' => 'RemoveAllPMAttachments',
		'pmrepair' => 'RepairPMAttachments',
	));
	loadTemplate('PMAttachmentsAdmin');
	require_once($sourcedir . '/Subs-PMAttachmentsAdmin.php');
}

function PMA_Load_Levels(&$groupLevels, &$boardLevels)
{
	$groupLevels['global']['standard'][] = 'pm_view_attachments';
	$groupLevels['global']['standard'][] = 'pm_post_attachments';
}

function PMA_Permissions(&$permissionGroups, &$permissionList, &$leftPermissionGroups, &$hiddenPermissions, &$relabelPermissions)
{
	$permissionList['membergroup']['pm_view_attachments'] = array(false, 'pm', 'use_pm_system');
	$permissionList['membergroup']['pm_post_attachments'] = array(false, 'pm', 'use_pm_system');
}

function PMA_Non_Guest()
{
	global $context;
	$context['non_guest_permissions'][] = 'pm_view_attachments';
	$context['non_guest_permissions'][] = 'pm_post_attachments';
}

?>