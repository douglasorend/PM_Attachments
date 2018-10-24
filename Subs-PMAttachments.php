<?php
/**********************************************************************************
* Subs-PMAttachments.php - Subs of the PM Attachments mod
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

/*	This file deals with PM attachments.  The following functions are used:

	boolean viewPMAttachments(array id_members, array(by_ref) cannot_receive)
		// checks to see if all members can receive PM attachments.  cannot_receive contains members that cannot.

	string getPMAttachmentFilename(string filename, int attachment_id, string dir = null, bool new = false, string file_hash = '')
		// returns PM attachment's encrypted filename.  If $new is true, won't check for file existence.

	string getLegacyPMAttachmentFilename(string filename, int attachment_id, string dir = null, bool new = false)
		// returns PM attachment's encrypted filename.  If $new is true, won't check for file existence.

	function createPMAttachment(array pmAttachmentOptions)
		// !!!!

	function userDownloads(int id_pm, int id_attach, int id_user)
		// get Individual user PM Attachment Downloads

	function PMDownload()
		// Download a PM attachment.

	function loadPMAttachmentContext(int id_pm)
		// !!!!

	function removePMAttachments(string $condition, string $query_type = '', bool $return_affected_pms = false, bool autoThumbRemoval = true, bool is_update = true)
		// !!!!

*/

function canViewPMAttachments($recipients)
{
	global $smcFunc, $modSettings, $txt, $context;

	// No members or attachments?  Then just return to the caller:
	if (empty($recipients))
		return;

	// Load the groups that are allowed to view PM attachments.
	$allowed_groups = $disallowed_groups = array();
	$request = $smcFunc['db_query']('', '
		SELECT id_group, add_deny
		FROM {db_prefix}permissions
		WHERE permission = {string:read_permission}',
		array(
			'read_permission' => 'pm_view_attachments',
		)
	);
	while ($row = $smcFunc['db_fetch_assoc']($request))
	{
		if (empty($row['add_deny']))
			$disallowed_groups[] = $row['id_group'];
		else
			$allowed_groups[] = $row['id_group'];
	}
	$smcFunc['db_free_result']($request);

	if (empty($modSettings['permission_enable_deny']))
		$disallowed_groups = array();

	// Make sure there are no duplicate recipients.
	$recipients['to'] = array_unique($recipients['to']);
	$recipients['bcc'] = array_diff(array_unique($recipients['bcc']), $recipients['to']);
	$all_to = array_merge($recipients['to'], $recipients['bcc']);

	// Is a member part of a membergroup that can/cannot view PM attachments?
	$request = $smcFunc['db_query']('', '
		SELECT
			real_name, id_member, additional_groups, id_group, id_post_group
		FROM {db_prefix}members
		WHERE id_member IN ({array_int:recipients})
		LIMIT {int:count_recipients}',
		array(
			'recipients' => $all_to,
			'count_recipients' => count($all_to),
		)
	);
	while ($row = $smcFunc['db_fetch_assoc']($request))
	{
		$groups = explode(',', $row['additional_groups']);
		$groups[] = $row['id_group'];
		$groups[] = $row['id_post_group'];
		if (!in_array(1, $groups) && (count(array_intersect($allowed_groups, $groups)) == 0 || count(array_intersect($disallowed_groups, $groups)) != 0))
			$context['send_log']['failed'][$row['id_member']] = sprintf($txt['pm_error_user_cannot_view_atts'], $row['real_name']);
	}
	$smcFunc['db_free_result']($request);

	// Return true if all members are allowed to view PM attachments.
	return empty($context['send_log']['failed']);
}

function Add_PM_JavaScript()
{
	global $context;
	
	// Make sure we have the language strings we need:
	loadLanguage('PMAttachments');

	// Add the necessary javascript:
	$context['html_headers'] .= '
	<script type="text/javascript"><!-- // --><![CDATA[
		function expandThumb(thumbID)
		{
			var img = document.getElementById(\'thumb_\' + thumbID);
			var link = document.getElementById(\'link_\' + thumbID);
			var tmp = img.src;
			img.src = link.href;
			link.href = tmp;
			img.style.width = \'\';
			img.style.height = \'\';
			return false;
		}

		// Open a new window.
		function reqWin(desktopURL, alternateWidth, alternateHeight, noScrollbars)
		{
			if ((alternateWidth && self.screen.availWidth * 0.8 < alternateWidth) || (alternateHeight && self.screen.availHeight * 0.8 < alternateHeight))
			{
				noScrollbars = false;
				alternateWidth = Math.min(alternateWidth, self.screen.availWidth * 0.8);
				alternateHeight = Math.min(alternateHeight, self.screen.availHeight * 0.8);
			}
			else
				noScrollbars = typeof(noScrollbars) != "undefined" && noScrollbars == true;
		
			window.open(desktopURL, \'requested_popup\', \'toolbar=no,location=no,status=no,menubar=no,scrollbars=\' + (noScrollbars ? \'no\' : \'yes\') + \',width=\' + (alternateWidth ? alternateWidth : 480) + \',height=\' + (alternateHeight ? alternateHeight : 220) + \',resizable=no\');
		
			// Return false so the click will not follow the link ;).
			return false;
		}
	// ]]></script>';
}

// Get a PM attachment's encrypted filename.  If $new is true, won't check for file existence.
function getPMAttachmentFilename($filename, $attachment_id, $dir = null, $new = false, $file_hash = '')
{
	global $modSettings, $smcFunc;

	// Just make up a nice hash...
	if ($new)
		return sha1(md5($filename . time()) . mt_rand());

	// Grab the file hash if it wasn't added.
	if ($file_hash === '')
	{
		$request = $smcFunc['db_query']('', '
			SELECT file_hash
			FROM {db_prefix}pm_attachments
			WHERE id_attach = {int:id_attach}',
			array(
				'id_attach' => $attachment_id,
		));

		if ($smcFunc['db_num_rows']($request) === 0)
			return false;

		list ($file_hash) = $smcFunc['db_fetch_row']($request);
		$smcFunc['db_free_result']($request);
	}

	// In case of files from the old system, do a legacy call.
	if (empty($file_hash))
		return getLegacyPMAttachmentFilename($filename, $attachment_id, $dir, $new);

	// Are we using multiple directories?
	if (!empty($modSettings['pmCurrentAttachmentUploadDir']))
	{
		if (!is_array($modSettings['pmAttachmentUploadDir']))
			$modSettings['pmAttachmentUploadDir'] = unserialize($modSettings['pmAttachmentUploadDir']);
		$path = $modSettings['pmAttachmentUploadDir'][$dir];
	}
	else
		$path = $modSettings['pmAttachmentUploadDir'];

	return $path . '/' . $attachment_id . '_' . $file_hash;
}


function getLegacyPMAttachmentFilename($filename, $attachment_id, $dir = null, $new = false)
{
	global $modSettings;

	// Remove special accented characters - ie. sí.
	$clean_name = strtr($filename, 'ŠŽšžŸÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜÝàáâãäåçèéêëìíîïñòóôõöøùúûüýÿ', 'SZszYAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy');
	$clean_name = strtr($clean_name, array('Þ' => 'TH', 'þ' => 'th', 'Ð' => 'DH', 'ð' => 'dh', 'ß' => 'ss', 'Œ' => 'OE', 'œ' => 'oe', 'Æ' => 'AE', 'æ' => 'ae', 'µ' => 'u'));

	// Sorry, no spaces, dots, or anything else but letters allowed.
	$clean_name = preg_replace(array('/\s/', '/[^\w_\.\-]/'), array('_', ''), $clean_name);

	$enc_name = $attachment_id . '_' . strtr($clean_name, '.', '_') . md5($clean_name);
	$clean_name = preg_replace('~\.[\.]+~', '.', $clean_name);

	if ($attachment_id == false || ($new && empty($modSettings['pmAttachmentEncryptFilenames'])))
		return $clean_name;
	elseif ($new)
		return $enc_name;

	// Are we using multiple directories?
	if (!empty($modSettings['pmCurrentAttachmentUploadDir']))
	{
		if (!is_array($modSettings['pmAttachmentUploadDir']))
			$modSettings['pmAttachmentUploadDir'] = unserialize($modSettings['pmAttachmentUploadDir']);
		$path = $modSettings['pmAttachmentUploadDir'][$dir];
	}
	else
		$path = $modSettings['pmAttachmentUploadDir'];

	if (file_exists($path . '/' . $enc_name))
		$filename = $path . '/' . $enc_name;
	else
		$filename = $path . '/' . $clean_name;

	return $filename;
}

function createPMAttachment(&$pmAttachmentOptions)
{
	global $modSettings, $sourcedir, $smcFunc, $context;

	// We need to know where this pm attachment is going.
	if (!empty($modSettings['pmCurrentAttachmentUploadDir']))
	{
		if (!is_array($modSettings['pmAttachmentUploadDir']))
			$modSettings['pmAttachmentUploadDir'] = unserialize($modSettings['pmAttachmentUploadDir']);

		// Just use the current path for temp files.
		$pmattach_dir = $modSettings['pmAttachmentUploadDir'][$modSettings['pmCurrentAttachmentUploadDir']];
		$id_folder = $modSettings['pmCurrentAttachmentUploadDir'];
	}
	else
	{
		$pmattach_dir = $modSettings['pmAttachmentUploadDir'];
		$id_folder = 1;
	}

	$pmAttachmentOptions['errors'] = array();
	if (!isset($pmAttachmentOptions['pm']))
		$pmAttachmentOptions['pm'] = 0;

	$already_uploaded = preg_match('~^post_tmp_' . $pmAttachmentOptions['sender'] . '_\d+$~', $pmAttachmentOptions['tmp_name']) != 0;
	$file_restricted = @ini_get('open_basedir') != '' && !$already_uploaded;

	if ($already_uploaded)
		$pmAttachmentOptions['tmp_name'] = $pmattach_dir . '/' . $pmAttachmentOptions['tmp_name'];

	// Make sure the file actually exists... sometimes it doesn't.
	if ((!$file_restricted && !file_exists($pmAttachmentOptions['tmp_name'])) || (!$already_uploaded && !is_uploaded_file($pmAttachmentOptions['tmp_name'])))
	{
		$pmAttachmentOptions['errors'] = array('could_not_upload');
		return false;
	}

	// These are the only valid image types for SMF.
	$validImageTypes = array(1 => 'gif', 2 => 'jpeg', 3 => 'png', 5 => 'psd', 6 => 'bmp', 7 => 'tiff', 8 => 'tiff', 9 => 'jpeg', 14 => 'iff');

 	if (!$file_restricted || $already_uploaded)
	{
		$size = @getimagesize($pmAttachmentOptions['tmp_name']);
		list ($pmAttachmentOptions['width'], $pmAttachmentOptions['height']) = $size;

		// If it's an image get the mime type right.
		if (empty($pmAttachmentOptions['mime_type']) && $pmAttachmentOptions['width'])
		{
			// Got a proper mime type?
			if (!empty($size['mime']))
				$pmAttachmentOptions['mime_type'] = $size['mime'];
			// Otherwise a valid one?
			elseif (isset($validImageTypes[$size[2]]))
				$pmAttachmentOptions['mime_type'] = 'image/' . $validImageTypes[$size[2]];
		}
	}

	// PM Attachments BEGIN...  Just here to be sure Subs.php gets loaded.
	require_once($sourcedir . '/Subs.php');

	// Get the hash if no hash has been given yet.
	if (empty($pmAttachmentOptions['file_hash']))
		$pmAttachmentOptions['file_hash'] = getPMAttachmentFilename($pmAttachmentOptions['name'], false, null, true);

	// Is the file too big?
	if (!empty($modSettings['pmAttachmentSizeLimit']) && $pmAttachmentOptions['size'] > $modSettings['pmAttachmentSizeLimit'] * 1024)
		$pmAttachmentOptions['errors'][] = 'too_large';

	if (!empty($modSettings['pmAttachmentCheckExtensions']))
	{
		$allowed = explode(',', strtolower($modSettings['pmAttachmentExtensions']));
		foreach ($allowed as $k => $dummy)
			$allowed[$k] = trim($dummy);

		if (!in_array(strtolower(substr(strrchr($pmAttachmentOptions['name'], '.'), 1)), $allowed))
			$pmAttachmentOptions['errors'][] = 'bad_extension';
	}

	if (!empty($modSettings['pmAttachmentDirSizeLimit']))
	{
		// Make sure the directory isn't full.
		$dirSize = 0;
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

			$dirSize += filesize($pmattach_dir . '/' . $file);
		}
		closedir($dir);

		// Too big!  Maybe you could zip it or something...
		if ($pmAttachmentOptions['size'] + $dirSize > $modSettings['pmAttachmentDirSizeLimit'] * 1024)
			$pmAttachmentOptions['errors'][] = 'directory_full';
		// Soon to be too big - warn the admins...
		if (!isset($modSettings['pmattachment_full_notified']) && $modSettings['pmAttachmentDirSizeLimit'] > 4000 && $pmAttachmentOptions['size'] + $dirSize > ($modSettings['pmAttachmentDirSizeLimit'] - 2000) * 1024)
		{
			require_once($sourcedir . '/Subs-Admin.php');
			loadLanguage('PMAttachments');
			emailAdmins('admin_pm_attachments_full');
			updateSettings(array('pmattachment_full_notified' => 1));
		}
	}

	// Check if the file already exists.... (for those who do not encrypt their filenames...)
	if (empty($modSettings['pmAttachmentEncryptFilenames']))
	{
		// Make sure they aren't trying to upload a nasty file.
		$disabledFiles = array('con', 'com1', 'com2', 'com3', 'com4', 'prn', 'aux', 'lpt1', '.htaccess', 'index.php');
		if (in_array(strtolower(basename($pmAttachmentOptions['name'])), $disabledFiles))
			$pmAttachmentOptions['errors'][] = 'bad_filename';

		// Check if there's another file with that name...
		$request = $smcFunc['db_query']('', '
			SELECT id_attach
			FROM {db_prefix}pm_attachments
			WHERE filename = {string:filename}
			LIMIT 1',
			array(
				'filename' => strtolower($pmAttachmentOptions['name']),
			)
		);
		if ($smcFunc['db_num_rows']($request) > 0)
			$pmAttachmentOptions['errors'][] = 'taken_filename';
		$smcFunc['db_free_result']($request);
	}

	if (!empty($pmAttachmentOptions['errors']))
		return false;

	if (!is_writable($pmattach_dir))
		fatal_lang_error('attachments_no_write', 'critical');

	// Assuming no-one set the extension let's take a look at it.
	if (empty($pmAttachmentOptions['fileext']))
	{
		$pmAttachmentOptions['fileext'] = strtolower(strrpos($pmAttachmentOptions['name'], '.') !== false ? substr($pmAttachmentOptions['name'], strrpos($pmAttachmentOptions['name'], '.') + 1) : '');
		if (strlen($pmAttachmentOptions['fileext']) > 8 || '.' . $pmAttachmentOptions['fileext'] == $pmAttachmentOptions['name'])
			$pmAttachmentOptions['fileext'] = '';
	}

	$smcFunc['db_insert']('',
		'{db_prefix}pm_attachments',
		array(
			'id_folder' => 'int', 'id_pm' => 'int', 'filename' => 'string-255', 'file_hash' => 'string-40', 'fileext' => 'string-8',
			'size' => 'int', 'width' => 'int', 'height' => 'int',
			'mime_type' => 'string-20',
		),
		array(
			$id_folder, (int) $pmAttachmentOptions['pm'], $pmAttachmentOptions['name'], $pmAttachmentOptions['file_hash'], $pmAttachmentOptions['fileext'],
			(int) $pmAttachmentOptions['size'], (empty($pmAttachmentOptions['width']) ? 0 : (int) $pmAttachmentOptions['width']), (empty($pmAttachmentOptions['height']) ? '0' : (int) $pmAttachmentOptions['height']),
			(!empty($pmAttachmentOptions['mime_type']) ? $pmAttachmentOptions['mime_type'] : ''),
		),
		array('id_attach')
	);

	$pmAttachmentOptions['id'] = $smcFunc['db_insert_id']('{db_prefix}pm_attachments', 'id_attach');

	$pmAttachmentOptions['destination'] = getPMAttachmentFilename(basename($pmAttachmentOptions['name']), $pmAttachmentOptions['id'], $id_folder, false, $pmAttachmentOptions['file_hash']);

	if ($already_uploaded)
		rename($pmAttachmentOptions['tmp_name'], $pmAttachmentOptions['destination']);
	elseif (!move_uploaded_file($pmAttachmentOptions['tmp_name'], $pmAttachmentOptions['destination']))
		fatal_lang_error('attach_timeout', 'critical');
	// We couldn't access the file before...
	elseif ($file_restricted)
	{
		$size = @getimagesize($pmAttachmentOptions['destination']);
		list ($pmAttachmentOptions['width'], $pmAttachmentOptions['height']) = $size;

		// Have a go at getting the right mime type.
		if (empty($pmAttachmentOptions['mime_type']) && $pmAttachmentOptions['width'])
		{
			if (!empty($size['mime']))
				$pmAttachmentOptions['mime_type'] = $size['mime'];
			elseif (isset($validImageTypes[$size[2]]))
				$pmAttachmentOptions['mime_type'] = 'image/' . $validImageTypes[$size[2]];
		}

		if (!empty($pmAttachmentOptions['width']) && !empty($pmAttachmentOptions['height']))
			$smcFunc['db_query']('', '
				UPDATE {db_prefix}pm_attachments
				SET
					width = {int:width},
					height = {int:height},
					mime_type = {string:mime_type}
				WHERE id_attach = {int:id_attach}',
				array(
					'width' => (int) $pmAttachmentOptions['width'],
					'height' => (int) $pmAttachmentOptions['height'],
					'id_attach' => $pmAttachmentOptions['id'],
					'mime_type' => empty($pmAttachmentOptions['mime_type']) ? '' : $pmAttachmentOptions['mime_type'],
				)
			);
	}

	// Attempt to chmod it.
	@chmod($pmAttachmentOptions['destination'], 0644);

	if (!empty($pmAttachmentOptions['skip_thumbnail']) || (empty($pmAttachmentOptions['width']) && empty($pmAttachmentOptions['height'])))
		return true;

	// Like thumbnails, do we?
	if (!empty($modSettings['pmAttachmentThumbnails']) && !empty($modSettings['pmAttachmentThumbWidth']) && !empty($modSettings['pmAttachmentThumbHeight']) && ($pmAttachmentOptions['width'] > $modSettings['pmAttachmentThumbWidth'] || $pmAttachmentOptions['height'] > $modSettings['pmAttachmentThumbHeight']))
	{
		require_once($sourcedir . '/Subs-Graphics.php');
		if (createThumbnail($pmAttachmentOptions['destination'], $modSettings['pmAttachmentThumbWidth'], $modSettings['pmAttachmentThumbHeight']))
		{
			// Figure out how big we actually made it.
			$size = @getimagesize($pmAttachmentOptions['destination'] . '_thumb');
			list ($thumb_width, $thumb_height) = $size;

			if (!empty($size['mime']))
				$thumb_mime = $size['mime'];
			elseif (isset($validImageTypes[$size[2]]))
				$thumb_mime = 'image/' . $validImageTypes[$size[2]];
			// Lord only knows how this happened...
			else
				$thumb_mime = '';

			$thumb_filename = $pmAttachmentOptions['name'] . '_thumb';
			$thumb_size = filesize($pmAttachmentOptions['destination'] . '_thumb');
			$thumb_file_hash = getPMAttachmentFilename($thumb_filename, false, null, true);

			// To the database we go!
			$smcFunc['db_insert']('',
				'{db_prefix}pm_attachments',
				array(
					'id_folder' => 'int', 'id_pm' => 'int', 'attachment_type' => 'int', 'filename' => 'string-255', 'file_hash' => 'string-40', 'fileext' => 'string-8',
					'size' => 'int', 'width' => 'int', 'height' => 'int', 'mime_type' => 'string-20',
				),
				array(
					$id_folder, (int) $pmAttachmentOptions['pm'], 3, $thumb_filename, $thumb_file_hash, $pmAttachmentOptions['fileext'],
					$thumb_size, $thumb_width, $thumb_height, $thumb_mime,
				),
				array('id_attach')
			);
			$pmAttachmentOptions['thumb'] = $smcFunc['db_insert_id']('{db_prefix}pm_attachments', 'id_attach');

			if (!empty($pmAttachmentOptions['thumb']))
			{
				$smcFunc['db_query']('', '
					UPDATE {db_prefix}pm_attachments
					SET id_thumb = {int:id_thumb}
					WHERE id_attach = {int:id_attach}',
					array(
						'id_thumb' => $pmAttachmentOptions['thumb'],
						'id_attach' => $pmAttachmentOptions['id'],
					)
				);

				rename($pmAttachmentOptions['destination'] . '_thumb', getPMAttachmentFilename($thumb_filename, $pmAttachmentOptions['thumb'], $id_folder, false, $thumb_file_hash));
			}
		}
	}

	return true;

}

// get Individual user PM Attachment Downloads
function userDownloads($id_pm, $id_attach, $id_user)
{
	global $smcFunc;

	// How many downloads does this user have?
	$request = $smcFunc['db_query']('', '
		SELECT attachments, downloads
		FROM {db_prefix}pm_recipients
		WHERE id_pm = {int:id_pm}
			AND id_member = {int:id_member}
		LIMIT 1',
		array(
			'id_pm' => $id_pm,
			'id_member' => $id_user,
		)
	);
	list ($attach, $downs) = $smcFunc['db_fetch_row']($request);

	$smcFunc['db_free_result']($request);

	$pmr_attach = explode(',', $attach);
	$pmr_downs = explode(',', $downs);

	foreach ($pmr_attach as $key => $value) {
		$attachint = intval($value);

		if ($attachint == $id_attach) // found attach id in here...
				$user_downloads = $pmr_downs[$key];
	}
	$pmr_attach = array_values($pmr_attach);
	$pmr_downs = array_values($pmr_downs);

	// Must be the user who sent it and is viewing their sent folder, let them see total downloads...
	if (empty($user_downloads))
	{
		$request = $smcFunc['db_query']('', '
			SELECT pa.downloads, pm.id_member_from
			FROM {db_prefix}pm_attachments AS pa
			INNER JOIN {db_prefix}personal_messages AS pm ON (pm.id_pm = pa.id_pm)
			WHERE pa.id_attach = {int:id_attach}
				AND pa.id_pm = {int:id_pm}
				AND pa.attachment_type = {int:not_thumb}
			LIMIT 1',
			array(
				'id_attach' => $id_attach,
				'id_pm' => $id_pm,
				'not_thumb' => 0,
			)
		);
		list ($downloads, $member_from) = $smcFunc['db_fetch_row']($request);

		$smcFunc['db_free_result']($request);

		if ($member_from == $id_user)
			$user_downloads = $downloads;
	}

	if (empty($user_downloads))
		$user_downloads = '0';

	return $user_downloads;
}


// Download a PM attachment.
function PMDownload()
{
	global $txt, $modSettings, $user_info, $scripturl, $context, $sourcedir, $smcFunc;

	// No guests allowed!
	is_not_guest();

	// Make sure some attachment was requested!
	if (!isset($_REQUEST['attach']) && !$user_info['is_admin'])
		fatal_lang_error('no_access', false);

	$_REQUEST['pm'] = isset($_REQUEST['pm']) ? (int) $_REQUEST['pm'] : fatal_lang_error('no_access', false);

	$_REQUEST['attach'] = isset($_REQUEST['attach']) ? (int) $_REQUEST['attach'] : fatal_lang_error('no_access', false);

	isAllowedTo('pm_view_attachments');

	// lets get the id_member_from first...
	$request2 = $smcFunc['db_query']('', '
		SELECT id_member_from
		FROM {db_prefix}personal_messages
		WHERE id_pm = {int:id_pm}',
		array(
			'id_pm' => $_REQUEST['pm'],
		)
	);

	list ($member_from) = $smcFunc['db_fetch_row']($request2);

	$smcFunc['db_free_result']($request2);

	$sent = !empty($member_from) && $user_info['id'] == $member_from ? 1 : 0;

	// Make sure this attachment is in the PM!  Or was it reported?
	$request = $smcFunc['db_query']('', '
		SELECT pa.id_folder, pa.filename, pa.file_hash, pa.fileext, pa.id_attach, pa.attachment_type, pa.mime_type, pa.id_pm, pa.pm_report' . (empty($sent) ? ', pmr.attachments, pmr.downloads' : '') . '
		FROM {db_prefix}pm_attachments AS pa
			INNER JOIN {db_prefix}personal_messages AS pm ON ((pm.id_pm = pa.pm_report) OR (pm.id_pm = pa.id_pm'
			 . (!empty($sent) ? ' AND pm.id_member_from = {int:id_member}))' : ')) INNER JOIN {db_prefix}pm_recipients AS pmr ON (((pmr.id_pm = pa.id_pm) OR (pmr.id_pm = pa.pm_report)) AND pmr.id_member = {int:id_member})') . '
		WHERE pa.id_attach = {int:attach}',
		array(
			'attach' => $_REQUEST['attach'],
			'id_member' => empty($sent) ? $user_info['id'] : $member_from,
			'id_pm_report' => $_REQUEST['pm'],
		)
	);

	if ($smcFunc['db_num_rows']($request) == 0)
		fatal_lang_error('no_access', false);

	if (empty($sent))
		list ($id_folder, $real_filename, $file_hash, $file_ext, $id_attach, $attachment_type, $mime_type, $id_pm, $id_pm_report, $pmrattachments, $pmrdownloads) = $smcFunc['db_fetch_row']($request);
	else
		list ($id_folder, $real_filename, $file_hash, $file_ext, $id_attach, $attachment_type, $mime_type, $id_pm, $id_pm_report) = $smcFunc['db_fetch_row']($request);

	$smcFunc['db_free_result']($request);

	// Update the download counters (unless it's a thumbnail).
	if ($attachment_type != 3) {
		// Main download counter
		$smcFunc['db_query']('attach_download_increase', '
			UPDATE LOW_PRIORITY {db_prefix}pm_attachments
			SET downloads = downloads + 1
			WHERE id_attach = {int:id_attach}',
			array(
				'id_attach' => $id_attach,
			)
		);


		// Update the Individual download counter if not downloading attachments through sent folder ofcourse...
		if (empty($sent))
		{
			$pmr_attach = explode(',', $pmrattachments);
			$pmr_downs = explode(',', $pmrdownloads);

			// which 1 is it?
			foreach ($pmr_attach as $key => $value) {
				$attachint = intval($value);
				if ($attachint == $id_attach) // found attach id in here...
						$pmr_downs[$key] = $pmr_downs[$key]+1;
			}

			$smcFunc['db_query']('', '
			UPDATE LOW_PRIORITY {db_prefix}pm_recipients
				SET downloads = {string:downs}
				WHERE id_pm = {int:id_pm} AND
				id_member = {int:id_member}',
				array(
					'downs' => implode(',', $pmr_downs),
					'id_pm' => !empty($id_pm_report) ? $id_pm_report : $id_pm,
					'id_member' => $user_info['id'],
				)
			);
		}

	}

	$filename = getPMAttachmentFilename($real_filename, $_REQUEST['attach'], $id_folder, false, $file_hash);

	// This is done to clear any output that was made before now. (would use ob_clean(), but that's PHP 4.2.0+...)
	ob_end_clean();
	if (!empty($modSettings['enableCompressedOutput']) && @version_compare(PHP_VERSION, '4.2.0') >= 0 && @filesize($filename) <= 4194304)
		@ob_start('ob_gzhandler');
	else
	{
		ob_start();
		header('Content-Encoding: none');
	}

	// No point in a nicer message, because this is supposed to be an attachment anyway...
	if (!file_exists($filename))
	{
		loadLanguage('Errors');

		header('HTTP/1.0 404 ' . $txt['attachment_not_found']);
		header('Content-Type: text/plain; charset=' . (empty($context['character_set']) ? 'ISO-8859-1' : $context['character_set']));

		// We need to die like this *before* we send any anti-caching headers as below.
		die('404 - ' . $txt['attachment_not_found']);
	}

	// If it hasn't been modified since the last time this attachement was retrieved, there's no need to display it again.
	if (!empty($_SERVER['HTTP_IF_MODIFIED_SINCE']))
	{
		list($modified_since) = explode(';', $_SERVER['HTTP_IF_MODIFIED_SINCE']);
		if (strtotime($modified_since) >= filemtime($filename))
		{
			ob_end_clean();

			// Answer the question - no, it hasn't been modified ;).
			header('HTTP/1.1 304 Not Modified');
			exit;
		}
	}

	// Check whether the ETag was sent back, and cache based on that...
	$file_md5 = '"' . md5_file($filename) . '"';
	if (!empty($_SERVER['HTTP_IF_NONE_MATCH']) && strpos($_SERVER['HTTP_IF_NONE_MATCH'], $file_md5) !== false)
	{
		ob_end_clean();

		header('HTTP/1.1 304 Not Modified');
		exit;
	}

	// Send the attachment headers.
	header('Pragma: ');
	if (!$context['browser']['is_gecko'])
		header('Content-Transfer-Encoding: binary');
	header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 525600 * 60) . ' GMT');
	header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($filename)) . ' GMT');
	header('Accept-Ranges: bytes');
	header('Connection: close');
	header('ETag: ' . $file_md5);

	// Does this have a mime type?
	if ($mime_type && (isset($_REQUEST['image']) || !in_array($file_ext, array('jpg', 'gif', 'jpeg', 'x-ms-bmp', 'png', 'psd', 'tiff', 'iff'))))
		header('Content-Type: ' . $mime_type);
	else
	{
		header('Content-Type: ' . ($context['browser']['is_ie'] || $context['browser']['is_opera'] ? 'application/octetstream' : 'application/octet-stream'));
		if (isset($_REQUEST['image']))
			unset($_REQUEST['image']);
	}

	if (!isset($_REQUEST['image']))
	{
		// Convert the file to UTF-8, cuz most browsers dig that.
		$utf8name = !$context['utf8'] && function_exists('iconv') ? iconv($context['character_set'], 'UTF-8', $real_filename) : (!$context['utf8'] && function_exists('mb_convert_encoding') ? mb_convert_encoding($real_filename, 'UTF-8', $context['character_set']) : $real_filename);
		$fixchar = create_function('$n', '
			if ($n < 32)
				return \'\';
			elseif ($n < 128)
				return chr($n);
			elseif ($n < 2048)
				return chr(192 | $n >> 6) . chr(128 | $n & 63);
			elseif ($n < 65536)
				return chr(224 | $n >> 12) . chr(128 | $n >> 6 & 63) . chr(128 | $n & 63);
			else
				return chr(240 | $n >> 18) . chr(128 | $n >> 12 & 63) . chr(128 | $n >> 6 & 63) . chr(128 | $n & 63);');

      // Different browsers like different standards...
      if ($context['browser']['is_firefox'])
         header('Content-Disposition: attachment; filename*=UTF-8\'\'' . rawurlencode(preg_replace_callback('~&#(\d{3,8});~', 'fixchar__callback', $utf8name)));

      elseif ($context['browser']['is_opera'])
         header('Content-Disposition: attachment; filename="' . preg_replace_callback('~&#(\d{3,8});~', 'fixchar__callback', $utf8name) . '"');

      elseif ($context['browser']['is_ie'])
         header('Content-Disposition: attachment; filename="' . urlencode(preg_replace_callback('~&#(\d{3,8});~', 'fixchar__callback', $utf8name)) . '"');

      else
         header('Content-Disposition: attachment; filename="' . $utf8name . '"');
	}

	// If this has an "image extension" - but isn't actually an image - then ensure it isn't cached cause of silly IE.
	if (!isset($_REQUEST['image']) && in_array($file_ext, array('gif', 'jpg', 'bmp', 'png', 'jpeg', 'tiff')))
		header('Cache-Control: no-cache');
	else
		header('Cache-Control: max-age=' . (525600 * 60) . ', private');

	if (empty($modSettings['enableCompressedOutput']) || filesize($filename) > 4194304)
		header('Content-Length: ' . filesize($filename));

	// Try to buy some time...
	@set_time_limit(0);

	// For text files.....
	if (!isset($_REQUEST['image']) && in_array($file_ext, array('txt', 'css', 'htm', 'html', 'php', 'xml')))
	{
		// We need to check this isn't unicode before we start messing around with it!
		$fp = fopen($filename, 'rb');
		$header = fread($fp, 2);
		fclose($fp);

		if ($header != chr(255).chr(254) && $header != chr(254).chr(255))
		{
			if (strpos($_SERVER['HTTP_USER_AGENT'], 'Windows') !== false)
				$callback = create_function('$buffer', 'return preg_replace(\'~[\r]?\n~\', "\r\n", $buffer);');
			elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Mac') !== false)
				$callback = create_function('$buffer', 'return preg_replace(\'~[\r]?\n~\', "\r", $buffer);');
			else
				$callback = create_function('$buffer', 'return preg_replace(\'~\r~\', "\r\n", $buffer);');
		}
	}

	// Since we don't do output compression for files this large...
	if (filesize($filename) > 4194304)
	{
		// Forcibly end any output buffering going on.
		if (function_exists('ob_get_level'))
		{
			while (@ob_get_level() > 0)
				@ob_end_clean();
		}
		else
		{
			@ob_end_clean();
			@ob_end_clean();
			@ob_end_clean();
		}

		$fp = fopen($filename, 'rb');
		while (!feof($fp))
		{
			if (isset($callback))
				echo $callback(fread($fp, 8192));
			else
				echo fread($fp, 8192);
			flush();
		}
		fclose($fp);
	}
	// On some of the less-bright hosts, readfile() is disabled.  It's just a faster, more byte safe, version of what's in the if.
	elseif (isset($callback) || @readfile($filename) == null)
		echo isset($callback) ? $callback(file_get_contents($filename)) : file_get_contents($filename);

	obExit(false);

}

function loadPMAttachmentContext($id_pm)
{
	global $attachments, $modSettings, $txt, $scripturl, $sourcedir, $smcFunc, $user_info, $attachmentData;

	// Set up the attachment info - based on code by Meriadoc.
	$attachmentData = array();

	if (isset($attachments[$id_pm]) && !empty($modSettings['pmAttachmentEnable']))
	{
		foreach ($attachments[$id_pm] as $i => $attachment)
		{
			$attachmentData[$i] = array(
				'id' => $attachment['id_attach'],
				'name' => preg_replace('~&amp;#(\\d{1,7}|x[0-9a-fA-F]{1,6});~', '&#\\1;', htmlspecialchars($attachment['filename'])),
				'downloads' => userDownloads($id_pm, $attachment['id_attach'], $user_info['id']),
				'size' => round($attachment['filesize'] / 1024, 2) . ' ' . $txt['kilobyte'],
				'byte_size' => $attachment['filesize'],
				'href' => $scripturl . '?action=dlpmattach;pm=' . $id_pm . ';attach=' . $attachment['id_attach'],
				'link' => '<a href="' . $scripturl . '?action=dlattach;pm=' . $id_pm . ';attach=' . $attachment['id_attach'] . '">' . htmlspecialchars($attachment['filename']) . '</a>',
				'is_image' => !empty($attachment['width']) && !empty($attachment['height']) && !empty($modSettings['pmAttachmentShowImages']),
			);

			if (!$attachmentData[$i]['is_image'])
				continue;

			$attachmentData[$i]['real_width'] = $attachment['width'];
			$attachmentData[$i]['width'] = $attachment['width'];
			$attachmentData[$i]['real_height'] = $attachment['height'];
			$attachmentData[$i]['height'] = $attachment['height'];

			// Let's see, do we want thumbs?
			if (!empty($modSettings['pmAttachmentThumbnails']) && !empty($modSettings['pmAttachmentThumbWidth']) && !empty($modSettings['pmAttachmentThumbHeight']) && ($attachment['width'] > $modSettings['pmAttachmentThumbWidth'] || $attachment['height'] > $modSettings['pmAttachmentThumbHeight']) && strlen($attachment['filename']) < 249)
			{
				// A proper thumb doesn't exist yet? Create one!
				if (empty($attachment['id_thumb']) || $attachment['thumb_width'] > $modSettings['pmAttachmentThumbWidth'] || $attachment['thumb_height'] > $modSettings['pmAttachmentThumbHeight'] || ($attachment['thumb_width'] < $modSettings['pmAttachmentThumbWidth'] && $attachment['thumb_height'] < $modSettings['pmAttachmentThumbHeight']))
				{
					$filename = getPMAttachmentFilename($attachment['filename'], $attachment['id_attach'], $attachment['id_folder']);

					require_once($sourcedir . '/Subs-Graphics.php');
					if (createThumbnail($filename, $modSettings['pmAttachmentThumbWidth'], $modSettings['pmAttachmentThumbHeight']))
					{
						// So what folder are we putting this image in?
						if (!empty($modSettings['pmCurrentAttachmentUploadDir']))
						{
							if (!is_array($modSettings['pmAttachmentUploadDir']))
								$modSettings['pmAttachmentUploadDir'] = @unserialize($modSettings['pmAttachmentUploadDir']);
							$path = $modSettings['pmAttachmentUploadDir'][$modSettings['pmCurrentAttachmentUploadDir']];
							$id_folder_thumb = $modSettings['pmCurrentAttachmentUploadDir'];
						}
						else
						{
							$path = $modSettings['pmAttachmentUploadDir'];
							$id_folder_thumb = 1;
						}

						// Calculate the size of the created thumbnail.
						list ($attachment['thumb_width'], $attachment['thumb_height']) = @getimagesize($filename . '_thumb');
						$thumb_size = filesize($filename . '_thumb');

						$thumb_filename = $attachment['filename'] . '_thumb';
						$thumb_hash = getPMAttachmentFilename($thumb_filename, false, null, true);

						// Add this beauty to the database.
						$smcFunc['db_insert']('',
							'{db_prefix}pm_attachments',
	// Does this have a mime type?
							array('id_folder' => 'int', 'id_pm' => 'int', 'attachment_type' => 'int', 'filename' => 'string', 'file_hash' => 'string', 'size' => 'int', 'width' => 'int', 'height' => 'int'),
							array($id_folder_thumb, $id_pm, 3, $thumb_filename, $thumb_hash, (int) $thumb_size, (int) $attachment['thumb_width'], (int) $attachment['thumb_height']),
							array('id_attach')
						);
						$attachment['id_thumb'] = $smcFunc['db_insert_id']('{db_prefix}pm_attachments', 'id_attach');
						if (!empty($attachment['id_thumb']))
						{
							$smcFunc['db_query']('', '
								UPDATE {db_prefix}pm_attachments
								SET id_thumb = {int:id_thumb}
								WHERE id_attach = {int:id_attach}',
								array(
									'id_thumb' => $attachment['id_thumb'],
									'id_attach' => $attachment['id_attach'],
								)
							);

							// Does this have a mime type?
							$thumb_realname = getPMAttachmentFilename($thumb_filename, $attachment['id_thumb'], $id_folder_thumb, false, $thumb_hash);
							rename($filename . '_thumb', $thumb_realname);
						}
					}
				}

				// Only adjust dimensions on successful thumbnail creation.
				if (!empty($attachment['thumb_width']) && !empty($attachment['thumb_height']))
				{
					$attachmentData[$i]['width'] = $attachment['thumb_width'];
					$attachmentData[$i]['height'] = $attachment['thumb_height'];
				}
			}

			if (!empty($attachment['id_thumb']))
				$attachmentData[$i]['thumbnail'] = array(
					'id' => $attachment['id_thumb'],
					'href' => $scripturl . '?action=dlpmattach;pm=' . $id_pm . ';attach=' . $attachment['id_thumb'] . ';image',
				);
			$attachmentData[$i]['thumbnail']['has_thumb'] = !empty($attachment['id_thumb']);

			// If thumbnails are disabled, check the maximum size of the image.
			if (!$attachmentData[$i]['thumbnail']['has_thumb'] && ((!empty($modSettings['max_image_width']) && $attachment['width'] > $modSettings['max_image_width']) || (!empty($modSettings['max_image_height']) && $attachment['height'] > $modSettings['max_image_height'])))
			{
				if (!empty($modSettings['max_image_width']) && (empty($modSettings['max_image_height']) || $attachment['height'] * $modSettings['max_image_width'] / $attachment['width'] <= $modSettings['max_image_height']))
				{
					$attachmentData[$i]['width'] = $modSettings['max_image_width'];
					$attachmentData[$i]['height'] = floor($attachment['height'] * $modSettings['max_image_width'] / $attachment['width']);
				}
				elseif (!empty($modSettings['max_image_width']))
				{
					$attachmentData[$i]['width'] = floor($attachment['width'] * $modSettings['max_image_height'] / $attachment['height']);
					$attachmentData[$i]['height'] = $modSettings['max_image_height'];
				}
			}
			elseif ($attachmentData[$i]['thumbnail']['has_thumb'])
			{
				// If the image is too large to show inline, make it a popup.
				if (((!empty($modSettings['max_image_width']) && $attachmentData[$i]['real_width'] > $modSettings['max_image_width']) || (!empty($modSettings['max_image_height']) && $attachmentData[$i]['real_height'] > $modSettings['max_image_height'])))
					$attachmentData[$i]['thumbnail']['javascript'] = 'return reqWin(\'' . $attachmentData[$i]['href'] . ';image\', ' . ($attachment['width'] + 20) . ', ' . ($attachment['height'] + 20) . ', true);';
				else
					$attachmentData[$i]['thumbnail']['javascript'] = 'return expandThumb(' . $attachment['id_attach'] . ');';
			}

			if (!$attachmentData[$i]['thumbnail']['has_thumb'])
				$attachmentData[$i]['downloads']++;
		}
	}

	return $attachmentData;
}

// Removes pm attachments - allowed query_types: '', 'personalmessages'
function removePMAttachments($condition, $query_type = '', $return_affected_pms = false, $autoThumbRemoval = true, $is_update = true)
{
	global $modSettings, $smcFunc;

	//!!! This might need more work!
	$new_condition = array();
	$query_parameter = array(
		'thumb_attachment_type' => 3,
	);

	if (is_array($condition))
	{
		foreach ($condition as $real_type => $restriction)
		{
			// Doing a NOT?
			$is_not = substr($real_type, 0, 4) == 'not_';
			$type = $is_not ? substr($real_type, 4) : $real_type;

			if (in_array($type, array('id_attach', 'id_pm')))
				$new_condition[] = 'pa.' . $type . ($is_not ? ' NOT' : '') . ' IN (' . (is_array($restriction) ? '{array_int:' . $real_type . '}' : '{int:' . $real_type . '}') . ')';
			elseif ($type == 'attachment_type')
				$new_condition[] = 'pa.attachment_type = {int:' . $real_type . '}';
			elseif ($type == 'msgtime')
				$new_condition[] = 'pm.msgtime < {int:' . $real_type . '}';
			elseif ($type == 'pm_report')
				$new_condition[] = 'pa.pm_report > {int:' . $real_type . '}';
			elseif ($type == 'size')
				$new_condition[] = 'pa.size > {int:' . $real_type . '}';

			// Add the parameter!
			$query_parameter[$real_type] = $restriction;
		}
		$condition = implode(' AND ', $new_condition);
	}

	// Delete it only if it exists...
	$pms = array();
	$attach = array();
	$parents = array();

	// Get all the attachment names and id_pm's.
	$request = $smcFunc['db_query']('', '
		SELECT
			pa.id_pm, pa.id_folder, pa.downloads, pa.filename, pa.file_hash, pa.attachment_type, pa.id_attach' . ($query_type == 'personalmessages' ? ', pm.id_pm' : '') . ',
			thumb.id_folder AS thumb_folder, COALESCE(thumb.id_attach, 0) AS id_thumb, thumb.filename AS thumb_filename, thumb.file_hash as thumb_file_hash, thumb_parent.id_attach AS id_parent
		FROM {db_prefix}pm_attachments AS pa' .($query_type == 'personalmessages' ? '
			INNER JOIN {db_prefix}personal_messages AS pm ON (pm.id_pm = pa.id_pm)' : '') . '
			LEFT JOIN {db_prefix}pm_attachments AS thumb ON (thumb.id_attach = pa.id_thumb)
			LEFT JOIN {db_prefix}pm_attachments AS thumb_parent ON (thumb.attachment_type = {int:thumb_attachment_type} AND thumb_parent.id_thumb = pa.id_attach)
		WHERE ' . $condition,
		$query_parameter
	);

	while ($row = $smcFunc['db_fetch_assoc']($request))
	{
		// Figure out the "encrypted" filename and delete it ;).
			$filename = getPMAttachmentFilename($row['filename'], $row['id_attach'], $row['id_folder'], false, $row['file_hash']);
			@unlink($filename);

			// If this was a thumb, the parent attachment should know about it.
			if (!empty($row['id_parent']))
				$parents[] = $row['id_parent'];

			// If this attachments has a thumb, remove it as well.
			if (!empty($row['id_thumb']) && $autoThumbRemoval)
			{
				$thumb_filename = getPMAttachmentFilename($row['thumb_filename'], $row['id_thumb'], $row['thumb_folder'], false, $row['thumb_file_hash']);
				@unlink($thumb_filename);
				$attach[] = $row['id_thumb'];
			}

		// Make a list.
		if ($return_affected_pms && empty($row['attachment_type']))
			$pms[] = $row['id_pm'];

		$attach[] = $row['id_attach'];
	}
	$smcFunc['db_free_result']($request);

	// Removed attachments don't have to be updated anymore.
	$parents = array_diff($parents, $attach);
	if (!empty($parents))

		$smcFunc['db_query']('', '
			UPDATE {db_prefix}pm_attachments
			SET id_thumb = {int:no_thumb}
			WHERE id_attach IN ({array_int:parent_attachments})',
			array(
				'parent_attachments' => $parents,
				'no_thumb' => 0,
			)
		);

	if (!empty($attach)) {

		// Remove the attachment from pm_attachments...
		$smcFunc['db_query']('', '
			DELETE FROM {db_prefix}pm_attachments
			WHERE id_attach IN ({array_int:attachment_list})',
			array(
				'attachment_list' => $attach,
			)
		);

	if ($is_update) {
			$request = $smcFunc['db_query']('', '
				SELECT
				downloads, attachments, id_pm, id_member
				FROM {db_prefix}pm_recipients',
				array(
				)
			);

			while ($row = $smcFunc['db_fetch_assoc']($request))
			{
				if (strlen($row['attachments']) >= 1 && strlen($row['downloads']) >= 1 && in_array($row['id_pm'], array_unique($pms)))
				{
					$pmr_attach = explode(',', $row['attachments']);
					$pmr_downs = explode(',', $row['downloads']);

					// get outta here...
					$pmr_attach = array_diff($pmr_attach, $attach);
					$pmr_downs = array_intersect_key($pmr_downs, $pmr_attach);

					$smcFunc['db_query']('', '
						UPDATE {db_prefix}pm_recipients
						SET attachments = {string:new_attach},
							downloads = {string:new_downs}
							WHERE id_pm = {int:id_pm} AND
							id_member = {int:id_member}',
						array(
							'new_downs' => implode(',', $pmr_downs),
							'new_attach' =>  implode(',', array_unique($pmr_attach)),
							'id_pm' => $row['id_pm'],
							'id_member' => $row['id_member'],
						)
					);
				}
			}
			$smcFunc['db_free_result']($request);
		}
	}

	if ($return_affected_pms)
		return array_unique($pms);
}

?>