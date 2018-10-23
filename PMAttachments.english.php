<?php
/**********************************************************************************
* PMAttachments.english.php - English language file
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

global $scripturl, $helptxt;

$txt['pm_report_desc'] = 'From this page you can report the personal message you received to the admin team of the forum. Please be sure to include a description of why you are reporting the message, as this will be sent along with the contents of the original message, including all attachments.';

// PM Attachment restrictions
$txt['attach_restrict_attachmentNumPerPMLimit'] = '%1$d per pm';
$txt['attach_restrict_attachmentPMLimit'] = 'maximum total size %1$dKB';
$txt['attach_restrict_pmAttachmentSizeLimit'] = 'maximum individual size %1$dKB';

// PM Attachments by Mail
$txt['pmattachments_mail'] = 'The following are direct links to all of the attachments that were included in this Personal Message (Please note, you must be logged in to the site in order to be able to download them):';

// Settings Titles
$txt['pmattachment_manager_settings'] = 'PM Attachment Settings';
$txt['pmattachment_manager_maintenance'] = 'PM File Maintenance';

// Maintenance Settings
$txt['pmattachment_options'] = 'PM File Attachment Options';
$txt['pmattachment_remove_old'] = 'Remove PM attachments older than';
$txt['pmattachment_remove_size'] = 'Remove PM attachments larger than';
$txt['pmattachment_remove_downloads'] = 'Remove PM attachments downloaded/viewed atleast';
$txt['pmattachment_downloads_times'] = 'time(s), by all recipients';
$txt['pmattachments_remove_by_members'] = 'Remove All PM Attachments sent';
$txt['pmattachments_remove_by_members_from'] = 'from';
$txt['pmattachments_remove_by_members_to'] = 'to';
$txt['pmattachments_remove_by_members_cont'] = 'the following members:';
$txt['pmattachments_remove_reported'] = 'Remove PM Attachments reported from the following members:';
$txt['pmattach_report_all_members'] = 'All Members';
$txt['pmattach_specific_members'] = 'Specific Members';
$txt['pmattachments_remove_all'] = 'Remove All PM Attachments by ALL Members!';

// Reporting for DUTY, SIR!
$txt['pm_report_attachments_sent'] = 'The following are all attachments that were sent to this user(s):';

// Settings Options
$txt['pmAttachmentEnable'] = 'PM Attachments mode';
$txt['pmAttachmentEnable_deactivate'] = 'Disable attachments';
$txt['pmAttachmentEnable_enable_all'] = 'Enable all attachments';
$txt['pmAttachmentEnable_disable_new'] = 'Disable new attachments';
$txt['pmAttachmentCheckExtensions'] = 'Check attachment\'s extension';
$txt['pmAttachmentExtensions'] = 'Allowed attachment extensions';
$txt['pmAttachmentShowImages'] = 'Display image attachments as pictures under PM\'s';
$txt['pmAttachmentEncryptFilenames'] = 'Encrypt stored filenames';
$txt['pmAttachmentUploadDir'] = 'PM Attachments directory<div class="smalltext"><a href="' . $scripturl . '?action=admin;area=manageattachments;sa=pmattachpaths">Configure multiple PM attachment directories</a></div>';
$txt['pmAttachmentUploadDir_multiple'] = 'PM Attachments directory';
$txt['pmAttachmentUploadDir_multiple_configure'] = '<a href="' . $scripturl . '?action=admin;area=manageattachments;sa=pmattachpaths">[Configure multiple PM attachment directories]</a>';
$txt['pmAttachmentDirSizeLimit'] = 'Max attachment folder space<div class="smalltext">(0 for no limit)</div>';
$txt['attachmentPMLimit'] = 'Max attachment size per PM<div class="smalltext">(0 for no limit)</div>';
$txt['pmAttachmentSizeLimit'] = 'Max size per attachment<div class="smalltext">(0 for no limit)</div>';
$txt['attachmentNumPerPMLimit'] = 'Max number of attachments per PM<div class="smalltext">(0 for no limit)</div>';
$txt['pmAttachmentThumbnails'] = 'Resize images when showing under PM\'s';
$txt['pmAttachmentThumbWidth'] = 'Maximum width of thumbnails';
$txt['pmAttachmentThumbHeight'] = 'Maximum height of thumbnails';

$txt['pmattach_dir_does_not_exist'] = 'Does Not Exist';
$txt['pmattach_dir_not_writable'] = 'Not Writable';
$txt['pmattach_dir_files_missing'] = 'Files Missing (<a href="' . $scripturl . '?action=admin;area=manageattachments;sa=pmrepair;sesc=%1$s">Repair</a>)';
$txt['pmattach_dir_unused'] = 'Unused';
$txt['pmattach_dir_ok'] = 'OK';

$txt['pmattach_path_manage'] = 'Manage Attachment Paths';
$txt['pmattach_paths'] = 'Attachment Paths';
$txt['pmattach_current_dir'] = 'Current Directory';
$txt['pmattach_path'] = 'Path';
$txt['pmattach_current_size'] = 'Current Size (KB)';
$txt['pmattach_num_files'] = 'Files';
$txt['pmattach_dir_status'] = 'Status';
$txt['pmattach_add_path'] = 'Add Path';
$txt['pmattach_path_current_bad'] = 'Invalid current attachment path.';

$txt['pmattachment_total'] = 'Total PM Attachments';
$txt['pmattachmentdir_size'] = 'Total Size of PM Attachment Directory';
$txt['pmattachmentdir_size_current'] = 'Total Size of Current PM Attachment Directory';
$txt['pmattachment_space'] = 'Total Space Available in PM Attachment Directory';
$txt['pmattachment_space_current'] = 'Total Space Available in Current PM Attachment Directory';

// Deletion of PM Attachments
$txt['confirm_delete_pmattachments_all'] = 'Are you sure you want to delete all PM attachments?';
$txt['confirm_delete_pmattachments'] = 'Are you sure you want to delete the selected PM attachments?';
$txt['confirm_delete_pmattachments_members'] = 'Are you sure you want to delete all PM attachments sent, either, from or to the following member(s)?';

// Error Handling
$txt['pm_attach_not_allowed'] = 'Sorry, you are not allowed to send attachments to other users via Personal Messages!';
$txt['attach_pmrepair_missing_thumbnail_parent'] = '%d thumbnails are missing a parent attachment';
$txt['attach_pmrepair_parent_missing_thumbnail'] = '%d parents are flagged as having thumbnails but don\'t';
$txt['attach_pmrepair_file_missing_on_disk'] = '%d attachments have an entry but no longer exist on disk';
$txt['attach_pmrepair_file_wrong_size'] = '%d attachments are being reported as the wrong filesize';
$txt['attach_pmrepair_file_size_of_zero'] = '%d attachments have a size of zero on disk. (These will be deleted)';
$txt['attach_pmrepair_attachment_no_pm'] = '%d attachments no longer have a personal message associated with them';
$txt['attach_pmrepair_wrong_folder'] = '%d attachments are in the wrong folder';
$txt['pm_cant_upload_type'] = 'You cannot upload that type of file. The only allowed extensions are';
$txt['pm_restricted_filename'] = 'That is a restricted filename. Please try a different filename.';
$txt['cannot_pm_view_attachments'] = 'You are not allowed to access this section';
$txt['pmattach_no_selection'] = 'Strange thing happened, nothing was selected!';

// PM Attachment Permissions...
$txt['permissionname_pm_view_attachments'] = 'View PM attachments';
$txt['permissionhelp_pm_view_attachments'] = 'PM Attachments are files that are attached to personal messages. This feature can be enabled and configured in \'Attachments and Avatars - PM Attachment Settings\'. Since PM attachments are not directly accessed, you can protect them from being downloaded by users that don\'t have this permission.';
$txt['permissionname_pm_post_attachments'] = 'Upload PM attachments';
$txt['permissionhelp_pm_post_attachments'] = 'PM Attachments are files that are attached to personal messages. One personal message can contain multiple attachments. Unchecking this completely disables these users from being able to upload attachments in personal messages.';

// PM Help text:
$helptxt['pmattachment_manager_settings'] = 'PM Attachments are files that members can upload, and attach to a Personal Message they send to other members.<br /><br />
		<strong>Check attachment\'s extension</strong>:<br /> Checks extensions of the files being uploaded before permitting this file.<br />
		<strong>Allowed attachment extensions</strong>:<br /> Sets the allowed extensions of attached files.<br />
		<strong>PM Attachments directory</strong>:<br /> The path to your PM attachment folder, where all files uploaded will go<br />(example: /home/sites/yoursite/www/forum/pm_attachments)<br />
		<strong>Max attachment folder space</strong> (in KB):<br /> Sets how large the PM attachment folder can be, including all files within it.<br />
		<strong>Max attachment size per PM</strong> (in KB):<br /> Sets the maximum filesize of all attachments made per PM.  If this is lower than the per-attachment limit, this will be the limit.<br />
		<strong>Max size per attachment</strong> (in KB):<br /> Sets the maximum filesize of each separate PM attachment.<br />
		<strong>Max number of attachments per PM</strong>:<br /> Sets the number of attachments a person can send from a Personal Message.<br />
		<strong>Display attachment as picture in PM\'s</strong>:<br /> If the uploaded file is a picture, this will show it underneath the personal message.<br />
		<strong>Resize images when showing under PM\'s</strong>:<br /> If the above option is selected, this will save a separate (smaller) attachment for the thumbnail to decrease bandwidth.<br />
		<strong>Maximum width and height of thumbnails</strong>:<br /> Only used with the &quot;Resize images when showing under PM\'s&quot; option, the maximum width and height to resize attachments down from.  They will be resized proportionally.';

$helptxt['pmAttachmentEncryptFilenames'] = 'Encrypting attachment filenames allows members to have more than one attachment of the
	same name, to safely use .php files for attachments, and heightens security.  It, however, could make it more
	difficult to rebuild your database if something drastic happened.';

$helptxt['pmattachments_remove_reported'] = 'This option will remove all attachments that have been reported within Personal Messages to an Admin that were sent by, either, the following members, or all members.  This does not remove the Personal Message, just the reported attachment(s) within that personal message.';

$helptxt['pmattachments_remove_by_members'] = 'Input any usernames separated by a comma and we will remove ALL PM Attachments sent, either, from or to the following members on your forum.  Usernames entered are case-insensitive.<br /><br /><strong>For Example</strong>:<br />To remove all PM Attachments sent to username1, username2, and username3, you would select <strong>to</strong> from the select box, and then you could enter the following into the text area before clicking the Remove button: <br /><br /><center>USERNAME1,userName2,username3</center>';

$helptxt['pmattachments_remove_all'] = 'This will remove all PM Attachments from and sent to all members, including yourself.  Should be used as a last resort.<br /><br />Some scenario\'s that may warrant a need for you to do this, are as follows:<br />If you are about to uninstall PM Attachments and want your users to know that all PM Attachments have been removed by the Admin before you uninstall it.  If you just don\'t have the space on your server for these files anymore.  If you plan on starting fresh and/or want to reduce or increase the size for the PM Attachments Directory, or change the directory and you don\'t want any files in this directory before you make any changes.  If many users are abusing the use of PM Attachments on your server, and many other reasons for this as well.';

$txt['emails']['admin_pm_attachments_full'] = array(
	/*
		@additional_params: admin_pm_attachments_full
			REALNAME:
		@description:
	*/
	'subject' => 'Urgent! PM Attachments folder almost full',
	'body' => '{REALNAME},

The PM attachments folder at {FORUMNAME} is almost full. Please visit the forum to resolve this problem.

Once the PM attachments folder reaches it\'s maximum permitted size users will not be able to continue to upload attachments via Personal Messages. (If enabled).

{REGARDS}',
		);

?>