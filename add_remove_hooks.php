<?php
/**********************************************************************************
* add_remove_hooks.php                                                            *
***********************************************************************************
* This program is distributed in the hope that it is and will be useful, but      *
* WITHOUT ANY WARRANTIES; without even any implied warranty of MERCHANTABILITY    *
* or FITNESS FOR A PARTICULAR PURPOSE.                                            *
***********************************************************************************
* This file is a simplified database installer. It does what it is suppoed to.    *
**********************************************************************************/

// If we have found SSI.php and we are outside of SMF, then we are running standalone.
if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
	require_once(dirname(__FILE__) . '/SSI.php');
elseif (!defined('SMF')) // If we are outside SMF and can't find SSI.php, then throw an error
	die('<b>Error:</b> Cannot install - please verify you put this file in the same place as SMF\'s SSI.php.');
if (SMF == 'SSI')
	db_extend('packages');
	
// Define the hooks
$hook_functions = array(
# SMF 2.x hooks:
	'integrate_pre_include' => '$sourcedir/Subs-PMAttachmentsHooks.php',
	'integrate_actions' => 'PMA_Actions',
	'integrate_admin_areas' => 'PMA_Admin',
	'integrate_load_permissions' => 'PMA_Permissions',
# SMF 2.1 Hooks:
	'integrate_admin_search' => 'PMA_Admin_Search',
	'integrate_manage_attachments' => 'PMA_Manage',
	'integrate_load_permission_levels' => 'PMA_Load_Levels',
	'integrate_load_illegal_guest_permissions' => 'PMA_Non_Guest',
);

// Adding or removing them?
if (!empty($context['uninstalling']))
	$call = 'remove_integration_function';
else
	$call = 'add_integration_function';

// Do the deed
foreach ($hook_functions as $hook => $function)
	$call($hook, $function);

if (SMF == 'SSI')
   echo 'Congratulations! You have successfully installed the hooks for this mod!';

?>