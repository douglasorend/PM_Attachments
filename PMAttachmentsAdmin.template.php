<?php
/**********************************************************************************
* PMAttachments.template.php - Templates of the PM Attachments mod
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

function template_pm_maintenance()
{
	global $txt, $scripturl, $context, $settings;

	echo '
	<div id="manage_attachments">
		<div class="cat_bar">
			<h3 class="catbg">', $txt['attachment_stats'], '</h3>
		</div>
		<div class="windowbg">
			<span class="topslice"><span></span></span>
			<div class="content">
				<dl class="settings">
					<dt><strong>', $txt['pmattachment_total'], ':</strong></dt>
					<dd>', $context['num_pmattachments'], '</dd>
					<dt><strong>', $txt['pmattachmentdir_size' . ($context['pmattach_multiple_dirs'] ? '_current' : '')], ':</strong></dt>
					<dd>', $context['pmattachment_total_size'], ' ', $txt['kilobyte'], ' </dd>
					<dt><strong>', $txt['pmattachment_space' . ($context['pmattach_multiple_dirs'] ? '_current' : '')], ':</strong></dt>
					<dd>', isset($context['pmattachment_space']) ? $context['pmattachment_space'] . ' ' . $txt['kilobyte'] : $txt['attachmentdir_size_not_set'], '</dd>
				</dl>
			</div>
			<span class="botslice"><span></span></span>
		</div>
		<div class="cat_bar">
			<h3 class="catbg">', $txt['attachment_integrity_check'], '</h3>
		</div>
		<div class="windowbg">
			<span class="topslice"><span></span></span>
			<div class="content">
				<form action="', $scripturl, '?action=admin;area=manageattachments;sa=pmrepair;', $context['session_var'], '=', $context['session_id'], '" method="post" accept-charset="', $context['character_set'], '">
					<p>', $txt['attachment_integrity_check_desc'], '</p>
					<input type="submit" name="submit" value="', $txt['attachment_check_now'], '" class="button_submit" />
				</form>
			</div>
			<span class="botslice"><span></span></span>
		</div>
		<div class="cat_bar">
			<h3 class="catbg"><span class="left"></span>', $txt['pmattachment_options'], '</h3>
		</div>
		<div class="windowbg">
			<span class="topslice"><span></span></span>
			<div class="content">
				<form action="', $scripturl, '?action=admin;area=manageattachments" method="post" accept-charset="', $context['character_set'], '" onsubmit="return confirm(\'', $txt['confirm_delete_pmattachments'], '\');">
					<p>', $txt['message'], ': <input type="text" name="notice" value="', $txt['attachment_delete_admin'], '" size="40" class="input_text" /><br />
					', $txt['pmattachment_remove_old'], ' <input type="text" name="age" value="25" size="4" class="input_text" /> ', $txt['days_word'], '</p>
					<input type="submit" name="submit" value="', $txt['remove'], '" class="button_submit" />
					<input type="hidden" name="type" value="attachments" />
					<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
					<input type="hidden" name="sa" value="pmByAge" />
				</form>
				<hr />
				<form action="', $scripturl, '?action=admin;area=manageattachments" method="post" accept-charset="', $context['character_set'], '" onsubmit="return confirm(\'', $txt['confirm_delete_pmattachments'], '\');">
					<p>', $txt['message'], ': <input type="text" name="notice" value="', $txt['attachment_delete_admin'], '" size="40" class="input_text" /><br />
					', $txt['pmattachment_remove_size'], ' <input type="text" name="size" id="size" value="100" size="4" class="input_text" /> ', $txt['kilobyte'], '</p>
					<input type="submit" name="submit" value="', $txt['remove'], '" class="button_submit" />
					<input type="hidden" name="type" value="attachments" />
					<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
					<input type="hidden" name="sa" value="pmBySize" />
				</form>
				<hr />
				<form action="', $scripturl, '?action=admin;area=manageattachments" method="post" accept-charset="', $context['character_set'], '" onsubmit="return confirm(\'', $txt['confirm_delete_pmattachments'], '\');">
					<p>', $txt['message'], ': <input type="text" name="notice" value="', $txt['attachment_delete_admin'], '" size="40" class="input_text" /><br />
					', $txt['pmattachment_remove_downloads'], ' <input type="text" name="downloads" id="downloads" value="1" size="4" class="input_text" /> ', $txt['pmattachment_downloads_times'], '</p>
					<input type="submit" name="submit" value="', $txt['remove'], '" class="button_submit" />
					<input type="hidden" name="type" value="attachments" />
					<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
					<input type="hidden" name="sa" value="pmByDowns" />
				</form>
				<hr />
				<form action="', $scripturl, '?action=admin;area=manageattachments" method="post" accept-charset="', $context['character_set'], '" onsubmit="return confirm(\'', $txt['confirm_delete_pmattachments'], '\');">
					<p>', $txt['message'], ': <input type="text" name="notice" value="', $txt['attachment_delete_admin'], '" size="40" class="input_text" /><br />
					<a href="', $scripturl, '?action=helpadmin;help=pmattachments_remove_reported" onclick="return reqWin(this.href);" class="help"><img src="', $settings['images_url'], '/helptopics.gif" border="0" align="left" style="padding-right: 1ex;" /></a>', $txt['pmattachments_remove_reported'], ' 
					<select name="reportedMembers">
						<option name="all" value="all" SELECTED>', $txt['pmattach_report_all_members'], '</option>';
	if (count($context['pmattach_reported_from']) >= 1)
	{
		echo '
						<optgroup label="', $txt['pmattach_specific_members'], '">';
		foreach ($context['pmattach_reported_from'] as $key => $reportedFrom)
			echo '
							<option name="report', $key, '" value="', $reportedFrom, '">', $reportedFrom, '</option>';
		echo '
						</optgroup>';
	}
	
	echo '
					</select></p>
					<input type="submit" name="submit" value="', $txt['remove'], '" class="button_submit" />
					<input type="hidden" name="type" value="attachments" />
					<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
					<input type="hidden" name="sa" value="pmRemoveReported" />
				</form>
				<hr />
				<form action="', $scripturl, '?action=admin;area=manageattachments" method="post" accept-charset="', $context['character_set'], '" onsubmit="return confirm(\'', $txt['confirm_delete_pmattachments_members'], '\');">
					<p>', $txt['message'], ': <input type="text" name="notice" value="', $txt['attachment_delete_admin'], '" size="40" class="input_text" /><br />
					<a href="', $scripturl, '?action=helpadmin;help=pmattachments_remove_by_members" onclick="return reqWin(this.href);" class="help"><img src="', $settings['images_url'], '/helptopics.gif" border="0" align="left" style="padding-right: 1ex;" /></a>', $txt['pmattachments_remove_by_members'], ' <select name="fromtoMembers"><option name="from" value="0" SELECTED>', $txt['pmattachments_remove_by_members_from'], '</option><option name="to" value="1">', $txt['pmattachments_remove_by_members_to'], '</option></select> ', $txt['pmattachments_remove_by_members_cont'], ' <input type="text" name="members" id="members" value="" size="25" class="input_text" /></p>
					<input type="submit" name="submit" value="', $txt['remove'], '" class="button_submit" />
					<input type="hidden" name="type" value="attachments" />
					<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
					<input type="hidden" name="sa" value="pmRemoveByMembers" />
				</form>
				<hr />
				<form action="', $scripturl, '?action=admin;area=manageattachments" method="post" accept-charset="', $context['character_set'], '" onsubmit="return confirm(\'', $txt['confirm_delete_pmattachments_all'], '\');">
					<p>', $txt['message'], ': <input type="text" name="notice" value="', $txt['attachment_delete_admin'], '" size="40" class="input_text" /><br />
					<a href="', $scripturl, '?action=helpadmin;help=pmattachments_remove_all" onclick="return reqWin(this.href);" class="help"><img src="', $settings['images_url'], '/helptopics.gif" border="0" align="left" style="padding-right: 1ex;" /></a>', $txt['pmattachments_remove_all'], '</p>
					<input type="submit" name="submit" value="', $txt['remove'], '" class="button_submit" />
					<input type="hidden" name="type" value="attachments" />
					<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
					<input type="hidden" name="sa" value="pmremoveall" />
				</form>
			</div>
			<span class="botslice"><span></span></span>
		</div>
	</div>';
}

function template_pm_attachment_repair()
{
	global $context, $txt, $scripturl;

	// If we've completed just let them know!
	if ($context['completed'])
	{
		echo '
	<table width="100%" cellpadding="4" cellspacing="0" align="center" border="0" class="tborder">
		<tr>
			<td class="titlebg">', $txt['repair_attachments_complete'], '</td>
		</tr><tr>
			<td class="windowbg2" width="100%">
				', $txt['repair_attachments_complete_desc'], '
			</td>
		</tr>
	</table>';
	}
	// What about if no errors were even found?
	elseif (!$context['errors_found'])
	{
		echo '
	<table width="100%" cellpadding="4" cellspacing="0" align="center" border="0" class="tborder">
		<tr>
			<td class="titlebg">', $txt['repair_attachments_complete'], '</td>
		</tr><tr>
			<td class="windowbg2" width="100%">
				', $txt['repair_attachments_no_errors'], '
			</td>
		</tr>
	</table>';
	}
	// Otherwise, I'm sad to say, we have a problem!
	else
	{
		echo '
	<form action="', $scripturl, '?action=admin;area=manageattachments;sa=pmrepair;fixErrors=1;step=0;substep=0;', $context['session_var'], '=', $context['session_id'], '" method="post" accept-charset="', $context['character_set'], '">
	<table width="100%" cellpadding="4" cellspacing="0" align="center" border="0" class="tborder">
		<tr>
			<td class="titlebg">', $txt['repair_attachments'], '</td>
		</tr><tr>
			<td class="windowbg2">
				', $txt['repair_attachments_error_desc'], '
			</td>
		</tr>';

		// Loop through each error reporting the status
		foreach ($context['repair_errors'] as $error => $number)
		{
			if (!empty($number))
			echo '
		<tr class="windowbg2">
			<td>
				<input type="checkbox" name="to_fix[]" id="', $error, '" value="', $error, '" />
				<label for="', $error, '">', sprintf($txt['attach_pmrepair_' . $error], $number), '</label>
			</td>
		</tr>';
		}

		echo '
		<tr>
			<td align="center" class="windowbg2">
				<input type="submit" value="', $txt['repair_attachments_continue'], '" />
				<input type="submit" name="cancel" value="', $txt['repair_attachments_cancel'], '" />
			</td>
		</tr>
	</table>
	</form>';
	}

}

function template_pmattachment_paths()
{
	template_show_list('pmattach_paths');
}

?>