<?php
/**********************************************************************************
* Subs-PMAttachmentsAdmin.php - Admin subs of the PM Attachments mod
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

function MaintainPMFiles()
{
	global $context, $modSettings, $txt, $smcFunc, $sourcedir;

	loadTemplate('PMAttachmentsAdmin');
	require_once($sourcedir . '/Subs-PMAttachments.php');
	$context['sub_template'] = 'pm_maintenance';

	if (!empty($modSettings['pmCurrentAttachmentUploadDir']))
		$pmattach_dirs = unserialize($modSettings['pmAttachmentUploadDir']);
	else
		$pmattach_dirs = array($modSettings['pmAttachmentUploadDir']);

	// Get the number of pm attachments....
	$request = $smcFunc['db_query']('', '
		SELECT COUNT(*)
		FROM {db_prefix}pm_attachments
		WHERE attachment_type = {int:attachment_type}',
		array(
			'attachment_type' => 0,
		)
	);
	list ($context['num_pmattachments']) = $smcFunc['db_fetch_row']($request);
	$smcFunc['db_free_result']($request);

	// Find out how big the directory is. We have to loop through all our pm attachment paths in case there's an old temp file in one of them.
	$pmAttachmentDirSize = 0;
	foreach ($pmattach_dirs as $id => $pmattach_dir)
	{
		$dir = @opendir($pmattach_dir) or fatal_lang_error('cant_access_upload_path', 'critical');
		while ($file = readdir($dir))
		{
			if ($file == '.' || $file == '..')
				continue;

			if (preg_match('~^post_tmp_\d+_\d+$~', $file) != 0)
			{
				// Temp file is more than 5 hours old!
				if (filemtime($pmattach_dir . '/' . $file) < time() - 18000)
					@unlink($pmattach_dir . '/' . $file);
				continue;
			}

			// We're only counting the size of the current pm attachment directory.
			if (empty($modSettings['pmCurrentAttachmentUploadDir']) || $modSettings['pmCurrentAttachmentUploadDir'] == $id)
				$pmAttachmentDirSize += filesize($pmattach_dir . '/' . $file);
		}
		closedir($dir);
	}
	// Divide it into kilobytes.
	$pmAttachmentDirSize /= 1024;

	// If they specified a limit only....
	if (!empty($modSettings['pmAttachmentDirSizeLimit']))
		$context['pmattachment_space'] = max(round($modSettings['pmAttachmentDirSizeLimit'] - $pmAttachmentDirSize, 2), 0);
	$context['pmattachment_total_size'] = round($pmAttachmentDirSize, 2);

	$context['pmattach_multiple_dirs'] = !empty($modSettings['pmCurrentAttachmentUploadDir']);

	// calling all reporters...
	$request = $smcFunc['db_query']('', '
		SELECT DISTINCT
			pm.from_name
		FROM {db_prefix}personal_messages AS pm
			INNER JOIN {db_prefix}pm_attachments AS pa ON (pa.attachment_type = {int:not_thumb})
		WHERE
			pm.id_pm = pa.pm_report
		ORDER BY pm.from_name ASC',
		array(
			'not_thumb' => 0,
		)
	);
	$context['pmattach_reported_from'] = array();
	while ($row = $smcFunc['db_fetch_assoc']($request))
		$context['pmattach_reported_from'][] = (string) $row['from_name'];

	$smcFunc['db_free_result']($request);
}

function ManagePMAttachmentSettings($return_config = false)
{
	global $txt, $modSettings, $scripturl, $context, $options, $sourcedir;

	$context['valid_pm_upload_dir'] = is_dir($modSettings['pmAttachmentUploadDir']) && is_writable($modSettings['pmAttachmentUploadDir']);

	$config_vars = array(
		array('title', 'pmattachment_manager_settings'),
			// Are attachments enabled?
			array('select', 'pmAttachmentEnable', array(&$txt['pmAttachmentEnable_deactivate'], &$txt['pmAttachmentEnable_enable_all'], &$txt['pmAttachmentEnable_disable_new'])),
		'',
			// Extension checks etc.
			array('check', 'pmAttachmentCheckExtensions'),
			array('text', 'pmAttachmentExtensions', 40),
			array('check', 'pmAttachmentEncryptFilenames'),
		'',
			// Directory and size limits.
			empty($modSettings['pmCurrentAttachmentUploadDir']) ? array('text', 'pmAttachmentUploadDir', 40, 'invalid' => !$context['valid_pm_upload_dir']) : array('var_message', 'pmAttachmentUploadDir_multiple', 'message' => 'pmAttachmentUploadDir_multiple_configure'),
			array('text', 'pmAttachmentDirSizeLimit', 6, 'postinput' => $txt['kilobyte']),
			array('text', 'attachmentPMLimit', 6, 'postinput' => $txt['kilobyte']),
			array('text', 'pmAttachmentSizeLimit', 6, 'postinput' => $txt['kilobyte']),
			array('text', 'attachmentNumPerPMLimit', 6),
		'',
			// Thumbnail settings.
			array('check', 'pmAttachmentShowImages'),
			array('check', 'pmAttachmentThumbnails'),
			array('text', 'pmAttachmentThumbWidth', 6),
			array('text', 'pmAttachmentThumbHeight', 6),
	);

	if ($return_config)
		return $config_vars;

	// These are very likely to come in handy! (i.e. without them we're doomed!)
	require_once($sourcedir .'/ManagePermissions.php');
	require_once($sourcedir .'/ManageServer.php');

	// Saving settings?
	if (isset($_GET['save']))
	{
		saveDBSettings($config_vars);
		redirectexit('action=admin;area=manageattachments;sa=pmattachments');
	}

	$context['post_url'] = $scripturl . '?action=admin;area=manageattachments;save;sa=pmattachments';
	prepareDBSettingContext($config_vars);

	$context['sub_template'] = 'show_settings';
}

// Prepare the actual PM attachment directories to be displayed in the list.
function list_getPMAttachDirs()
{
	global $smcFunc, $modSettings, $sc, $txt;

	// The pm dirs should already have been unserialized but just in case...
	if (!is_array($modSettings['pmAttachmentUploadDir']))
		$modSettings['pmAttachmentUploadDir'] = unserialize($modSettings['pmAttachmentUploadDir']);

	$request = $smcFunc['db_query']('', '
		SELECT id_folder, COUNT(id_attach) AS num_attach
		FROM {db_prefix}pm_attachments
		GROUP BY id_folder',
		array(
		)
	);

	$expected_files = array();
	while ($row = $smcFunc['db_fetch_assoc']($request))
		$expected_files[$row['id_folder']] = $row['num_attach'];
	$smcFunc['db_free_result']($request);

	$attachdirs = array();
	foreach ($modSettings['pmAttachmentUploadDir'] as $id => $dir)
	{
		// If there aren't any attachments in this directory this won't exist.
		if (!isset($expected_files[$id]))
			$expected_files[$id] = 0;

		// Check if the directory is doing okay.
		list ($status, $error, $size) = attachDirStatus($dir, $expected_files[$id]);

		$attachdirs[] = array(
			'id' => $id,
			'current' => $id == $modSettings['pmCurrentAttachmentUploadDir'],
			'path' => $dir,
			'current_size' => $size,
			'num_files' => $expected_files[$id],
			'status' => ($error ? '<span class="error">' : '') . sprintf($txt['pmattach_dir_' . $status], $sc) . ($error ? '</span>' : ''),
		);
	}

	// Just stick a new directory on at the bottom.
	if (isset($_REQUEST['new_path']))
		$attachdirs[] = array(
			'id' => max(array_merge(array_keys($expected_files), array_keys($modSettings['pmAttachmentUploadDir']))) + 1,
			'current' => false,
			'path' => '',
			'current_size' => '',
			'num_files' => '',
			'status' => '',
		);

	return $attachdirs;
}

function ManagePMAttachmentPaths()
{
	global $modSettings, $scripturl, $context, $txt, $sourcedir, $smcFunc;

	// Saving?
	if (isset($_REQUEST['save']))
	{
		$new_dirs = array();
		foreach ($_POST['dirs'] as $id => $path)
		{
			$id = (int) $id;
			if ($id < 1)
				continue;

			if (empty($path))
			{
				// Let's not try to delete a path with files in it.
				$request = $smcFunc['db_query']('', '
					SELECT COUNT(id_attach) AS num_attach
					FROM {db_prefix}pm_attachments
					WHERE id_folder = {int:id_folder}',
					array(
						'id_folder' => (int) $id,
					)
				);

				list ($num_attach) = $smcFunc['db_fetch_row']($request);
				$smcFunc['db_free_result']($request);

				// It's safe to delete.
				if ($num_attach == 0)
					continue;
			}


			$new_dirs[$id] = $path;
		}

		// We need to make sure the current directory is right.
		$_POST['current_dir'] = (int) $_POST['current_dir'];
		if (empty($_POST['current_dir']) || empty($new_dirs[$_POST['current_dir']]))
			fatal_lang_error('pmattach_path_current_bad', false);

		// Going back to just one path?
		if (count($new_dirs) == 1)
		{
			// We might need to reset the paths. This loop will just loop through once.
			foreach ($new_dirs as $id => $dir)
			{
				if ($id != 1)
					$smcFunc['db_query']('', '
						UPDATE {db_prefix}pm_attachments
						SET id_folder = {int:default_folder}
						WHERE id_folder = {int:current_folder}',
						array(
							'default_folder' => 1,
							'current_folder' => $id,
						)
					);

				updateSettings(array(
					'pmCurrentAttachmentUploadDir' => 0,
					'pmAttachmentUploadDir' => $dir,
				));
			}
		}
		else
			// Save it to the database.
			updateSettings(array(
				'pmCurrentAttachmentUploadDir' => $_POST['current_dir'],
				'pmAttachmentUploadDir' => serialize($new_dirs),
			));
	}

	// Are they here for the first time?
	if (empty($modSettings['pmCurrentAttachmentUploadDir']))
	{
		$modSettings['pmAttachmentUploadDir'] = array(
			1 => $modSettings['pmAttachmentUploadDir']
		);
		$modSettings['pmCurrentAttachmentUploadDir'] = 1;
	}
	// Otherwise just load up their attachment paths.
	else
		$modSettings['pmAttachmentUploadDir'] = unserialize($modSettings['pmAttachmentUploadDir']);

	$listOptions = array(
		'id' => 'pmattach_paths',
		'base_href' => $scripturl . '?action=admin;area=manageattachments;sa=pmattachpaths;' . $context['session_var'] . '=' . $context['session_id'],
		'title' => $txt['pmattach_paths'],
		'get_items' => array(
			'function' => 'list_getPMAttachDirs',
		),
		'columns' => array(
			'current_dir' => array(
				'header' => array(
					'value' => $txt['pmattach_current_dir'],
				),
				'data' => array(
					'function' => create_function('$rowData', '
						return \'<input type="radio" name="current_dir" value="\' . $rowData[\'id\'] . \'" \' . ($rowData[\'current\'] ? \'checked="checked"\' : \'\') . \' class="check" />\';
					'),
					'style' => 'text-align: center; width: 15%;',
				),
			),
			'path' => array(
				'header' => array(
					'value' => $txt['pmattach_path'],
				),
				'data' => array(
					'function' => create_function('$rowData', '
						return \'<input type="text" size="50" name="dirs[\' . $rowData[\'id\'] . \']" value="\' . $rowData[\'path\'] . \'" />\';
					'),
					'style' => 'text-align: center; width: 30%;',
				),
			),
			'current_size' => array(
				'header' => array(
					'value' => $txt['pmattach_current_size'],
				),
				'data' => array(
					'db' => 'current_size',
					'style' => 'text-align: center; width: 15%;',
				),
			),
			'num_files' => array(
				'header' => array(
					'value' => $txt['pmattach_num_files'],
				),
				'data' => array(
					'db' => 'num_files',
					'style' => 'text-align: center; width: 15%;',
				),
			),
			'status' => array(
				'header' => array(
					'value' => $txt['pmattach_dir_status'],
				),
				'data' => array(
					'db' => 'status',
					'style' => 'text-align: center; width: 25%;',
				),
			),
		),
		'form' => array(
			'href' => $scripturl . '?action=admin;area=manageattachments;sa=pmattachpaths;' . $context['session_var'] . '=' . $context['session_id'],
		),
		'additional_rows' => array(
			array(
				'position' => 'below_table_data',
				'value' => '<input type="submit" name="new_path" value="' . $txt['pmattach_add_path'] . '" />&nbsp;<input type="submit" name="save" value="' . $txt['save'] . '" />',
				'class' => 'titlebg',
				'style' => 'text-align: right;',
			),
		),
	);

	require_once($sourcedir . '/Subs-List.php');
	createList($listOptions);

	// Fix up our template.
	$context[$context['admin_menu_name']]['current_subsection'] = 'pmattachments';
	$context['page_title'] = $txt['pmattach_path_manage'];
	$context['sub_template'] = 'pmattachment_paths';
}

function RemovePMAttachByDownloads()
{
	global $modSettings, $smcFunc;

	checkSession('post', 'admin');

	$store_ids = array();
	$remove_ids = array();

	// this could take awhile...
	$request = $smcFunc['db_query']('', '
		SELECT
		downloads, attachments
		FROM {db_prefix}pm_recipients',
		array(
		)
	);

	while ($row = $smcFunc['db_fetch_assoc']($request))
	{
		// calling all recipients!  How many times have you each downloaded the file?
		$downloads = explode(',', $row['downloads']);
		$attachments =  explode(',', $row['attachments']);

		foreach ($attachments as $attachid => $value) {
			$attachint = intval($value);

			// does it apply?
			if (intval($downloads[$attachid]) >= $_POST['downloads'])
				$store_ids[] = $attachint;
			else
				$remove_ids[] = $attachint;
		}
	}

	$smcFunc['db_free_result']($request);

	$store_ids = array_unique($store_ids);
	$remove_ids = array_unique($remove_ids);
	$attaches = array_diff($store_ids, $remove_ids);

	if (!empty($attaches))
	{
		// Find humungous pm attachments.
		$personalmessages = removePMAttachments(array('id_attach' => $attaches), '', true);

		// And make a note on the pm.
		if (!empty($personalmessages))
			$smcFunc['db_query']('', '
				UPDATE {db_prefix}personal_messages
				SET body = CONCAT(body, ' . (!empty($_POST['notice']) ? '{string:notice}' : '') . ')
				WHERE id_pm IN ({array_int:personalmessages})',
				array(
					'personalmessages' => $personalmessages,
					'notice' => empty($_POST['notice']) ? '' : '<br /><br />' . $_POST['notice'],
				)
			);
	}
		redirectexit('action=admin;area=manageattachments;sa=maintenance');
}

function RemoveReportedPMAttachments()
{
	global $smcFunc;

	checkSession('post', 'admin');

	$_POST['reportedMembers'] = isset($_POST['reportedMembers']) ? (string) $_POST['reportedMembers'] : '';

	// how can this happen with a select??  You never know!!
	if (strlen($_POST['reportedMembers']) <= 0)
		fatal_error($txt['pmattach_no_selection'], false);

	// Are we getting reports from a member?  Well then, let's get all attach ids...
	if ($_POST['reportedMembers'] != 'all')
	{
		$request = $smcFunc['db_query']('', '
			SELECT DISTINCT
				pa.id_attach
			FROM {db_prefix}personal_messages AS pm
				LEFT JOIN {db_prefix}pm_attachments AS pa ON (pa.attachment_type = {int:not_thumb})
			WHERE
				pm.id_pm = pa.pm_report AND pm.from_name = {string:member_from}',
			array(
				'not_thumb' => 0,
				'member_from' => $_POST['reportedMembers'],
			)
		);

		$attaches = array();

		while ($row = $smcFunc['db_fetch_assoc']($request))
			$attaches[] = (int) $row['id_attach'];

		$smcFunc['db_free_result']($request);

		if (!empty($attaches))
			$personalmessages = removePMAttachments(array('id_attach' => $attaches), '', true);
	} else
		$personalmessages = removePMAttachments(array('attachment_type' => 0, 'pm_report' => 0), '', true);

	// And make a note on the pm.
	if (!empty($personalmessages))
		$smcFunc['db_query']('', '
			UPDATE {db_prefix}personal_messages
			SET body = CONCAT(body, ' . (!empty($_POST['notice']) ? '{string:notice}' : '') . ')
			WHERE id_pm IN ({array_int:personalmessages})',
			array(
				'personalmessages' => $personalmessages,
				'notice' => empty($_POST['notice']) ? '' : '<br /><br />' . $_POST['notice'],
			)
		);

	redirectexit('action=admin;area=manageattachments;sa=maintenance');
}

function RemovePMAttachByMembers()
{
	global $smcFunc;

	checkSession('post', 'admin');

	$_POST['fromtoMembers'] = isset($_POST['fromtoMembers']) ? (int) $_POST['fromtoMembers'] : fatal_error($txt['pmattach_no_selection'], false);

	$strMembers = trim(strtolower($_POST['members']));

	if (substr($strMembers, 0, 1) == ',')
		$strMembers = substr($strMembers, 1);

	if (substr($strMembers, -1) == ',')
		$strMembers = substr($strMembers, 0, strlen($strMembers - 1));

	// now let's get the members.
	$members_inputted = array();
	$members = array();

	$members_inputted = explode(',', $strMembers);
	$members_inputted = array_unique($members_inputted);

	// let's trim it down if needed...
	foreach ($members_inputted as $key => $username)
	{
		$user = (string) trim($username);
		if (strlen($user) >= 1)
			$members[] = $user;
	}

	// what, no members?? How dare you!
	if (count($members) >= 1)
	{
		// is it coming from or going to?
		if (!empty($_POST['fromtoMembers']))
		{
			// TO MEMBERS, just getting a distinct feeling here...
			$request = $smcFunc['db_query']('', '
				SELECT DISTINCT
					pa.id_attach
				FROM {db_prefix}members AS mem
					INNER JOIN {db_prefix}pm_recipients AS pmr ON (pmr.id_member = mem.id_member)
					LEFT JOIN {db_prefix}pm_attachments AS pa ON (pa.id_pm = pmr.id_pm AND pa.attachment_type = {int:not_thumb})
				WHERE
					LOWER(mem.real_name) IN ({array_string:member_names})',
				array(
					'not_thumb' => 0,
					'member_names' => $members,
				)
			);
		}
		else
		{
			// FROM MEMBERS, just getting a distinct feeling here...
			$request = $smcFunc['db_query']('', '
				SELECT DISTINCT
					pa.id_attach
				FROM {db_prefix}personal_messages AS pm
					LEFT JOIN {db_prefix}pm_attachments AS pa ON (pa.id_pm = pm.id_pm AND pa.attachment_type = {int:not_thumb})
				WHERE
					LOWER(pm.from_name) IN ({array_string:member_names})',
				array(
					'not_thumb' => 0,
					'member_names' => $members,
				)
			);
		}

		$attaches = array();

		while ($row = $smcFunc['db_fetch_assoc']($request))
			$attaches[] = (int) $row['id_attach'];

		$smcFunc['db_free_result']($request);

		if (!empty($attaches))
		{
			// Find humungous pm attachments.
			$personalmessages = removePMAttachments(array('id_attach' => $attaches), '', true);

			// And make a note on the pm.
			if (!empty($personalmessages))
				$smcFunc['db_query']('', '
					UPDATE {db_prefix}personal_messages
					SET body = CONCAT(body, ' . (!empty($_POST['notice']) ? '{string:notice}' : '') . ')
					WHERE id_pm IN ({array_int:personalmessages})',
					array(
						'personalmessages' => $personalmessages,
						'notice' => empty($_POST['notice']) ? '' : '<br /><br />' . $_POST['notice'],
					)
				);
		}
	}
		redirectexit('action=admin;area=manageattachments;sa=maintenance');
}

function RemovePMAttachmentByAge()
{
	global $modSettings, $smcFunc;

	checkSession('post', 'admin');

	// Deleting an attachment?
		// Get all the old attachments.
		$personalmessages = removePMAttachments(array('attachment_type' => 0,  'msgtime' => (time() - 24 * 60 * 60 * $_POST['age'])), 'personalmessages', true);

		// Update the messages to reflect the change.
		if (!empty($personalmessages))
			$smcFunc['db_query']('', '
				UPDATE {db_prefix}personal_messages
				SET body = CONCAT(body, ' . (!empty($_POST['notice']) ? '{string:notice}' : '') . ')
				WHERE id_pm IN ({array_int:personalmessages})',
				array(
					'personalmessages' => $personalmessages,
					'notice' => empty($_POST['notice']) ? '' : '<br /><br />' . $_POST['notice'],
				)
			);

	redirectexit('action=admin;area=manageattachments;sa=maintenance');
}

function RemovePMAttachmentBySize()
{
	global $modSettings, $smcFunc;

	checkSession('post', 'admin');

	// Find humungous pm attachments.
	$personalmessages = removePMAttachments(array('attachment_type' => 0, 'size' => 1024 * $_POST['size']), '', true);

	// And make a note on the pm.
	if (!empty($personalmessages))
		$smcFunc['db_query']('', '
			UPDATE {db_prefix}personal_messages
			SET body = CONCAT(body, ' . (!empty($_POST['notice']) ? '{string:notice}' : '') . ')
			WHERE id_pm IN ({array_int:personalmessages})',
			array(
				'personalmessages' => $personalmessages,
				'notice' => empty($_POST['notice']) ? '' : '<br /><br />' . $_POST['notice'],
			)
		);

	redirectexit('action=admin;area=manageattachments;sa=maintenance');
}

function RemoveAllPMAttachments()
{
	global $txt, $smcFunc;

	checkSession('request', 'admin');

	$personalmessages = removePMAttachments(array('attachment_type' => 0), '', true);

	if (!isset($_POST['notice']))
		$_POST['notice'] = $txt['attachment_delete_admin'];

	// Add the notice on the end of the changed personal messages.
	if (!empty($personalmessages))
		$smcFunc['db_query']('', '
			UPDATE {db_prefix}personal_messages
			SET body = CONCAT(body, {string:deleted_personalmessage})
			WHERE id_pm IN ({array_int:personalmessages})',
			array(
				'personalmessages' => $personalmessages,
				'deleted_personalmessage' => '<br /><br />' . $_POST['notice'],
			)
		);

	redirectexit('action=admin;area=manageattachments;sa=maintenance');
}

// This function should find pm attachments in the database that no longer exist and clear them, and fix pm filesize issues.
function RepairPMAttachments()
{
	global $modSettings, $context, $txt, $smcFunc, $sourcedir;

	checkSession('get');
	require_once($sourcedir . '/Subs-PMAttachments.php');

	// If we choose cancel, redirect right back.
	if (isset($_POST['cancel']))
		redirectexit('action=admin;area=manageattachments;sa=maintenance');

	// Try give us a while to sort this out...
	@set_time_limit(600);

	$_GET['step'] = empty($_GET['step']) ? 0 : (int) $_GET['step'];
	$_GET['substep'] = empty($_GET['substep']) ? 0 : (int) $_GET['substep'];

	// Don't recall the session just in case.
	if ($_GET['step'] == 0 && $_GET['substep'] == 0)
	{
		unset($_SESSION['attachments_to_fix']);
		unset($_SESSION['attachments_to_fix2']);

		// If we're actually fixing stuff - work out what.
		if (isset($_GET['fixErrors']))
		{
			// Nothing?
			if (empty($_POST['to_fix']))
				redirectexit('action=admin;area=manageattachments;sa=maintenance');

			$_SESSION['attachments_to_fix'] = array();
			//!!! No need to do this I think.
			foreach ($_POST['to_fix'] as $key => $value)
				$_SESSION['attachments_to_fix'][] = $value;
		}
	}

	// All the valid problems are here:
	$context['repair_errors'] = array(
		'missing_thumbnail_parent' => 0,
		'parent_missing_thumbnail' => 0,
		'file_missing_on_disk' => 0,
		'file_wrong_size' => 0,
		'file_size_of_zero' => 0,
		'attachment_no_pm' => 0,
		'wrong_folder' => 0,
	);

	$to_fix = !empty($_SESSION['attachments_to_fix']) ? $_SESSION['attachments_to_fix'] : array();
	$context['repair_errors'] = isset($_SESSION['attachments_to_fix2']) ? $_SESSION['attachments_to_fix2'] : $context['repair_errors'];
	$fix_errors = isset($_GET['fixErrors']) ? true : false;

	// Get stranded thumbnails.
	if ($_GET['step'] <= 0)
	{
		$result = $smcFunc['db_query']('', '
			SELECT MAX(id_attach)
			FROM {db_prefix}pm_attachments
			WHERE attachment_type = {int:thumbnail}',
			array(
				'thumbnail' => 3,
			)
		);
		list ($thumbnails) = $smcFunc['db_fetch_row']($result);
		$smcFunc['db_free_result']($result);

		for (; $_GET['substep'] < $thumbnails; $_GET['substep'] += 500)
		{
			$to_remove = array();

			$result = $smcFunc['db_query']('', '
				SELECT thumb.id_attach, thumb.id_folder, thumb.filename, thumb.file_hash
				FROM {db_prefix}pm_attachments AS thumb
					LEFT JOIN {db_prefix}pm_attachments AS tparent ON (tparent.id_thumb = thumb.id_attach)
				WHERE thumb.id_attach BETWEEN {int:substep} AND {int:substep} + 499
					AND thumb.attachment_type = {int:thumbnail}
					AND tparent.id_attach IS NULL
				GROUP BY thumb.id_attach',
				array(
					'thumbnail' => 3,
					'substep' => $_GET['substep'],
				)
			);
			while ($row = $smcFunc['db_fetch_assoc']($result))
			{
				$to_remove[] = $row['id_attach'];
				$context['repair_errors']['missing_thumbnail_parent']++;

				// If we are repairing remove the file from disk now.
				if ($fix_errors && in_array('missing_thumbnail_parent', $to_fix))
				{
					$filename = getPMAttachmentFilename($row['filename'], $row['id_attach'], $row['id_folder'], false, $row['file_hash']);
					@unlink($filename);
				}
			}
			if ($smcFunc['db_num_rows']($result) != 0)
				$to_fix[] = 'missing_thumbnail_parent';
			$smcFunc['db_free_result']($result);

			// Do we need to delete what we have?
			if ($fix_errors && !empty($to_remove) && in_array('missing_thumbnail_parent', $to_fix))
				$smcFunc['db_query']('', '
					DELETE FROM {db_prefix}pm_attachments
					WHERE id_attach IN ({array_int:to_remove})
						AND attachment_type = {int:attachment_type}',
					array(
						'to_remove' => $to_remove,
						'attachment_type' => 3,
					)
				);

			pauseAttachmentMaintenance($to_fix, $thumbnails, true);
		}

		$_GET['step'] = 1;
		$_GET['substep'] = 0;
		pauseAttachmentMaintenance($to_fix, 0, true);
	}

	// Find parents which think they have thumbnails, but actually, don't.
	if ($_GET['step'] <= 1)
	{
		$result = $smcFunc['db_query']('', '
			SELECT MAX(id_attach)
			FROM {db_prefix}pm_attachments
			WHERE id_thumb != {int:no_thumb}',
			array(
				'no_thumb' => 0,
			)
		);
		list ($thumbnails) = $smcFunc['db_fetch_row']($result);
		$smcFunc['db_free_result']($result);

		for (; $_GET['substep'] < $thumbnails; $_GET['substep'] += 500)
		{
			$to_update = array();

			$result = $smcFunc['db_query']('', '
				SELECT pa.id_attach
				FROM {db_prefix}pm_attachments AS pa
					LEFT JOIN {db_prefix}pm_attachments AS thumb ON (thumb.id_attach = pa.id_thumb)
				WHERE pa.id_attach BETWEEN {int:substep} AND {int:substep} + 499
					AND pa.id_thumb != {int:no_thumb}
					AND thumb.id_attach IS NULL',
				array(
					'no_thumb' => 0,
					'substep' => $_GET['substep'],
				)
			);
			while ($row = $smcFunc['db_fetch_assoc']($result))
			{
				$to_update[] = $row['id_attach'];
				$context['repair_errors']['parent_missing_thumbnail']++;
			}
			if ($smcFunc['db_num_rows']($result) != 0)
				$to_fix[] = 'parent_missing_thumbnail';
			$smcFunc['db_free_result']($result);

			// Do we need to delete what we have?
			if ($fix_errors && !empty($to_update) && in_array('parent_missing_thumbnail', $to_fix))
				$smcFunc['db_query']('', '
					UPDATE {db_prefix}pm_attachments
					SET id_thumb = {int:no_thumb}
					WHERE id_attach IN ({array_int:to_update})',
					array(
						'to_update' => $to_update,
						'no_thumb' => 0,
					)
				);

			pauseAttachmentMaintenance($to_fix, $thumbnails, true);
		}

		$_GET['step'] = 2;
		$_GET['substep'] = 0;
		pauseAttachmentMaintenance($to_fix, 0, true);
	}

	// This may take forever I'm afraid, but life sucks... recount EVERY attachments!
	if ($_GET['step'] <= 2)
	{
		$result = $smcFunc['db_query']('', '
			SELECT MAX(id_attach)
			FROM {db_prefix}pm_attachments',
			array(
			)
		);
		list ($thumbnails) = $smcFunc['db_fetch_row']($result);
		$smcFunc['db_free_result']($result);

		for (; $_GET['substep'] < $thumbnails; $_GET['substep'] += 250)
		{
			$to_remove = array();
			$errors_found = array();

			$result = $smcFunc['db_query']('', '
				SELECT id_attach, id_folder, filename, file_hash, size, attachment_type
				FROM {db_prefix}pm_attachments
				WHERE id_attach BETWEEN {int:substep} AND {int:substep} + 249',
				array(
					'substep' => $_GET['substep'],
				)
			);
			while ($row = $smcFunc['db_fetch_assoc']($result))
			{
				// Get the filename.
				if ($row['attachment_type'] == 1)
					$filename = $modSettings['custom_avatar_dir'] . '/' . $row['filename'];
				else
					$filename = getPMAttachmentFilename($row['filename'], $row['id_attach'], $row['id_folder'], false, $row['file_hash']);

				// File doesn't exist?
				if (!file_exists($filename))
				{
					// If we're lucky it might just be in a different folder.
					if (!empty($modSettings['pmCurrentAttachmentUploadDir']))
					{
						// Get the attachment name with out the folder.
						$attachment_name = !empty($row['file_hash']) ? $row['id_attach'] . '_' . $row['file_hash'] : getLegacyPMAttachmentFilename($row['filename'], $row['id_attach'], null, true);

						if (!is_array($modSettings['attachmentUploadDir']))
							$modSettings['pmAttachmentUploadDir'] = unserialize($modSettings['pmAttachmentUploadDir']);

						// Loop through the other folders.
						foreach ($modSettings['pmAttachmentUploadDir'] as $id => $dir)
							if (file_exists($dir . '/' . $attachment_name))
							{
								$context['repair_errors']['wrong_folder']++;
								$errors_found[] = 'wrong_folder';

								// Are we going to fix this now?
								if ($fix_errors && in_array('wrong_folder', $to_fix))
									$smcFunc['db_query']('', '
										UPDATE {db_prefix}pm_attachments
										SET id_folder = {int:new_folder}
										WHERE id_attach = {int:id_attach}',
										array(
											'new_folder' => $id,
											'id_attach' => $row['id_attach'],
										)
									);

								continue 2;
							}
					}

					$to_remove[] = $row['id_attach'];
					$context['repair_errors']['file_missing_on_disk']++;
					$errors_found[] = 'file_missing_on_disk';
				}
				elseif (filesize($filename) == 0)
				{
					$context['repair_errors']['file_size_of_zero']++;
					$errors_found[] = 'file_size_of_zero';

					// Fixing?
					if ($fix_errors && in_array('file_size_of_zero', $to_fix))
					{
						$to_remove[] = $row['id_attach'];
						@unlink($filename);
					}
				}
				elseif (filesize($filename) != $row['size'])
				{
					$context['repair_errors']['file_wrong_size']++;
					$errors_found[] = 'file_wrong_size';

					// Fix it here?
					if ($fix_errors && in_array('file_wrong_size', $to_fix))
					{
						$smcFunc['db_query']('', '
							UPDATE {db_prefix}pm_attachments
							SET size = {int:filesize}
							WHERE id_attach = {int:id_attach}',
							array(
								'filesize' => filesize($filename),
								'id_attach' => $row['id_attach'],
							)
						);
					}
				}
			}

			if (in_array('file_missing_on_disk', $errors_found))
				$to_fix[] = 'file_missing_on_disk';
			if (in_array('file_size_of_zero', $errors_found))
				$to_fix[] = 'file_size_of_zero';
			if (in_array('file_wrong_size', $errors_found))
				$to_fix[] = 'file_wrong_size';
			if (in_array('wrong_folder', $errors_found))
				$to_fix[] = 'wrong_folder';
			$smcFunc['db_free_result']($result);

			// Do we need to delete what we have?
			if ($fix_errors && !empty($to_remove))
			{
				$smcFunc['db_query']('', '
					DELETE FROM {db_prefix}pm_attachments
					WHERE id_attach IN ({array_int:to_remove})',
					array(
						'to_remove' => $to_remove,
					)
				);
				$smcFunc['db_query']('', '
					UPDATE {db_prefix}pm_attachments
					SET id_thumb = {int:no_thumb}
					WHERE id_thumb IN ({array_int:to_remove})',
					array(
						'to_remove' => $to_remove,
						'no_thumb' => 0,
					)
				);
			}

			pauseAttachmentMaintenance($to_fix, $thumbnails, true);
		}

		$_GET['step'] = 3;
		$_GET['substep'] = 0;
		pauseAttachmentMaintenance($to_fix, 0, true);
	}

	// What about attachments, who are missing a pm :'(
	if ($_GET['step'] <= 4)
	{
		$result = $smcFunc['db_query']('', '
			SELECT MAX(id_attach)
			FROM {db_prefix}pm_attachments',
			array(
			)
		);
		list ($thumbnails) = $smcFunc['db_fetch_row']($result);
		$smcFunc['db_free_result']($result);

		for (; $_GET['substep'] < $thumbnails; $_GET['substep'] += 500)
		{
			$to_remove = array();

			$result = $smcFunc['db_query']('', '
				SELECT pa.id_attach, pa.id_folder, pa.filename, pa.file_hash
				FROM {db_prefix}pm_attachments AS pa
					LEFT JOIN {db_prefix}personal_messages AS pm ON (pm.id_pm = pa.id_pm)
				WHERE pa.id_attach BETWEEN {int:substep} AND {int:substep} + 499
					AND pa.id_pm != {int:no_pm}
					AND pm.id_pm IS NULL',
				array(
					'no_pm' => 0,
					'substep' => $_GET['substep'],
				)
			);
			while ($row = $smcFunc['db_fetch_assoc']($result))
			{
				$to_remove[] = $row['id_attach'];
				$context['repair_errors']['attachment_no_pm']++;

				// If we are repairing remove the file from disk now.
				if ($fix_errors && in_array('attachment_no_pm', $to_fix))
				{
					$filename = getPMAttachmentFilename($row['filename'], $row['id_attach'], $row['id_folder'], false, $row['file_hash']);
					@unlink($filename);
				}
			}
			if ($smcFunc['db_num_rows']($result) != 0)
				$to_fix[] = 'attachment_no_pm';
			$smcFunc['db_free_result']($result);

			// Do we need to delete what we have?
			if ($fix_errors && !empty($to_remove) && in_array('attachment_no_pm', $to_fix))
				$smcFunc['db_query']('', '
					DELETE FROM {db_prefix}pm_attachments
					WHERE id_attach IN ({array_int:to_remove})
						AND id_pm != {int:no_pm}',
					array(
						'to_remove' => $to_remove,
						'no_pm' => 0,
					)
				);

			pauseAttachmentMaintenance($to_fix, $thumbnails, true);
		}

		$_GET['step'] = 5;
		$_GET['substep'] = 0;
		pauseAttachmentMaintenance($to_fix, 0, true);
	}

	// Got here we must be doing well - just the template! :D
	$context['page_title'] = $txt['repair_attachments'];
	$context[$context['admin_menu_name']]['current_subsection'] = 'maintenance';
	$context['sub_template'] = 'pm_attachment_repair';

	// What stage are we at?
	$context['completed'] = $fix_errors ? true : false;
	$context['errors_found'] = !empty($to_fix) ? true : false;

}

?>