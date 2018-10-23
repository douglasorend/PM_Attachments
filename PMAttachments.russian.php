<?php
/**********************************************************************************
* PMAttachments.russian.php - Russian language file
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

$txt['pm_report_desc'] = '����� ����� �������� ������ �� ������ ��������� ������� ������������. ���� ������ ����� ���������� �� ������������ ������������� ������, ������ � ����������� ������������� ���������, ������� ��� ��������.';

// PM Attachment restrictions
$txt['attach_restrict_attachmentNumPerPMLimit'] = '%1$d �������� � ����� ��';
$txt['attach_restrict_attachmentPMLimit'] = '����� ������������ ������ %1$d KB';
$txt['attach_restrict_pmAttachmentSizeLimit'] = '�������������� ������������ ������ %1$dKB';

// PM Attachments by Mail
$txt['pmattachments_mail'] = '���� ��������� ������ ������ �� ��� �����, ������������� � ������� ��������� (�������� ��������: ��� �������� ���� �������� ������������ ������ ���� ����������� �� ������):';

// Settings Titles
$txt['pmattachment_manager_settings'] = '�������� �������� ����� ��';

// Maintenance Settings
$txt['pmattachment_options'] = '��������� ������ ��������';
$txt['pmattachment_remove_old'] = '������� ��������, ���� ������� ���������';
$txt['pmattachment_remove_size'] = '������� ��������, ������ ������� ���������';
$txt['pmattachment_remove_downloads'] = '������� ��������, �����������/������������� �� ������� ����';
$txt['pmattachment_downloads_times'] = '��� ����� ������������';
$txt['pmattachments_remove_by_members'] = '������� ��� ��������, ������������';
$txt['pmattachments_remove_by_members_from'] = '��';
$txt['pmattachments_remove_by_members_to'] = '����';
$txt['pmattachments_remove_by_members_cont'] = ':';
$txt['pmattachments_remove_reported'] = '������� ��������, ������������ ���������� ��������������:';
$txt['pmattach_report_all_members'] = '��� ������������';
$txt['pmattach_specific_members'] = '��������� ������������';
$txt['pmattachments_remove_all'] = '������� ��� �������� � ���� �������������!';

// Reporting for DUTY, SIR!
$txt['pm_report_attachments_sent'] = '���� ����������� ��� ��������, ������������ ����(�) �������������(���):';

// Settings Options
$txt['pmAttachmentEnable'] = '����� ���� PM Attachments';
$txt['pmAttachmentEnable_deactivate'] = '��������� ��������';
$txt['pmAttachmentEnable_enable_all'] = '��������� ��� ��������';
$txt['pmAttachmentEnable_disable_new'] = '��������� ����� ��������';
$txt['pmAttachmentCheckExtensions'] = '��������� ���������� ��������';
$txt['pmAttachmentExtensions'] = '��������� ���������� ��������';
$txt['pmAttachmentShowImages'] = '���������� ������������� ����������� ��� ����������';
$txt['pmAttachmentEncryptFilenames'] = '��������� ����������� �����';
$txt['pmAttachmentUploadDir'] = '���������� ��� �������� ��������<div class="smalltext"><a href="' . $scripturl . '?action=admin;area=manageattachments;sa=pmattachpaths">���������� ��������� ����������</a></div>';
$txt['pmAttachmentUploadDir_multiple'] = '���������� ��� �������� ��������';
$txt['pmAttachmentUploadDir_multiple_configure'] = '<a href="' . $scripturl . '?action=admin;area=manageattachments;sa=pmattachpaths">[���������� ��������� ���������� ��� �������� ��������]</a>';
$txt['pmAttachmentDirSizeLimit'] = '������������ ������ ����� ��������<div class="smalltext">(0 � ��� �����������)</div>';
$txt['attachmentPMLimit'] = '������������ ������ �������� ��� �������� ����� ��<div class="smalltext">(0 � ��� �����������)</div>';
$txt['pmAttachmentSizeLimit'] = '������������ ������ ������ ��������<div class="smalltext">(0 � ��� �����������)</div>';
$txt['attachmentNumPerPMLimit'] = '������������ ���-�� �������� � ������ ���������<div class="smalltext">(0 � ��� �����������)</div>';
$txt['pmAttachmentThumbnails'] = '�������� ������ �����������, ��� ����������� ��� ������ ����������';
$txt['pmAttachmentThumbWidth'] = '������������ ������ ������';
$txt['pmAttachmentThumbHeight'] = '������������ ������ ������';
$txt['pmattach_dir_does_not_exist'] = '�� ����������';
$txt['pmattach_dir_not_writable'] = '�� ����� ���� �� ������';
$txt['pmattach_dir_files_missing'] = '����������� ����� (<a href="' . $scripturl . '?action=admin;area=manageattachments;sa=pmrepair;sesc=%1$s">��������</a>)';
$txt['pmattach_dir_unused'] = '�� ������������';
$txt['pmattach_dir_ok'] = 'OK';
$txt['pmattach_path_manage'] = '���������� ������������ ��������';
$txt['pmattach_paths'] = '���� ��������';
$txt['pmattach_current_dir'] = '������� ����������';
$txt['pmattach_path'] = '����';
$txt['pmattach_current_size'] = '������� ������ (��)';
$txt['pmattach_num_files'] = '�����';
$txt['pmattach_dir_status'] = '���������';
$txt['pmattach_add_path'] = '�������� ����';
$txt['pmattach_path_current_bad'] = '�������� ���� ��� �������� ��������.';
$txt['pmattachment_total'] = '����� �������� � ��';
$txt['pmattachmentdir_size'] = '����� ������ ���������� ��� ��������';
$txt['pmattachmentdir_size_current'] = '����� ������ ������� ����������';
$txt['pmattachment_space'] = '����� ��������� ����� � ���������� ��� ��������';
$txt['pmattachment_space_current'] = '����� ��������� ����� � ������� ����������';

// Deletion of PM Attachments
$txt['confirm_delete_pmattachments_all'] = '�� ������������� ������ ������� ��� ��������?';
$txt['confirm_delete_pmattachments'] = '�� ������������� ������ ������� ��� ��������� ��������?';
$txt['confirm_delete_pmattachments_members'] = '�� ������������� ������ ������� ��� ��������, ������������ ���-���� �� ��������� �������������?';

// Error Handling
$txt['pm_attach_not_allowed'] = '��������, ��� �� ��������� �������� �������� ������ ������������� ����� ��!';
$txt['attach_pmrepair_missing_thumbnail_parent'] = '%d ������� ����������� �����������';
$txt['attach_pmrepair_parent_missing_thumbnail'] = '%d ��������� �����������, ���������� ��� ������� ������ (�� �� ������� �� �� ����� ����)';
$txt['attach_pmrepair_file_missing_on_disk'] = '%d ��������, ��� �� ������������ ��������� (�� ������ � ��� ��������)';
$txt['attach_pmrepair_file_wrong_size'] = '%d ��������, ������� ������������ ������';
$txt['attach_pmrepair_file_size_of_zero'] = '%d ��������, ������� ������� ������ (��� ����� �������)';
$txt['attach_pmrepair_attachment_no_pm'] = '%d ��������, �� ��������� �� � ������ ������� �����������';
$txt['attach_pmrepair_wrong_folder'] = '%d �������� � ������������ �����';
$txt['pm_cant_upload_type'] = '�� �� ������ ��������� ����� ������� ����. ��������� ������ ��������� ����������';
$txt['pm_restricted_filename'] = '��������� ��� ����� �� ��������. ����������, ����������� � ������.';
$txt['cannot_pm_view_attachments'] = '��� �� �������� ������ � ������ ������';
$txt['pmattach_no_selection'] = '��������� �������� ����: �� ������ �� �������!';

// PM Attachment Permissions...
$txt['permissionname_pm_view_attachments'] = '�������� ��������';
$txt['permissionhelp_pm_view_attachments'] = '��� ������� ����� ���� ������������ � ��������� � ������� "�������� � ������� � �������� �������� ����� ��". ��������� � ������ �������� ��� ������� �������, �� ������ �������� �� �� ������ �������� ��������������, �� �������� ��������������� ����.';
$txt['permissionname_pm_post_attachments'] = '�������� ��������';
$txt['permissionhelp_pm_post_attachments'] = '� ����� ������ ��������� ����� ����������� ��������� ��������. ���� ��������� ������ ����������, ������������ �� ������ ����������� �������� � ������ ����������.';

$helptxt['pmattachment_manager_settings'] = '������������ ����� ����������� ����� � ������ ���������� ��� �������� ���� �����.<br /><br />
		<strong>�������� ���������� ��������</strong>:<br /> ����� ��� ��� ���� ����� �������� � ��������, ��� ���������� ����� ����� ������ ��������.<br />
		<strong>��������� ���������� ��������</strong>:<br /> ���������� ����� �� ���������� ������, ������� ������������ ������ ����������� � ����� ������ ����������.<br />
		<strong>���������� ��� �������� ��������</strong>:<br /> ���� � ����� ��������, ���� ����� ����������� ��� ������������� �����<br />(��������: /home/sites/yoursite/www/forum/pm_attachments)<br />
		<strong>����������� ��������� �����</strong> (� ��):<br /> �������, ��������� ������� ������ ������ ���� � ����� �������� (� ������ ���� ����������� � ��� ������).<br />
		<strong>������������ ������ �������� � ����� ������ ���������</strong> (� ��):<br /> ������� ����������� ���������� ������ ����� ��� ���� ��������, ������������� � ������ ����������.<br />
		<strong>������������ ������ ������ ��������</strong> (� ��):<br /> ���������� ������������ ������ ����� ������� ���������� ��������.<br />
		<strong>������������ ���������� �������� � ����� ������ ���������</strong>:<br /> ���������� ���������� ������, ������� ������������ ������ ���������� � ������������� ������� ���������.<br />
		<strong>���������� ������������� ����������� ��� ����������</strong>:<br /> ���� ����������� ���� �������� ������������, ��� ����� ����� ������������ � ������ ����� ���������.<br />
		<strong>�������� ������ �����������, ��� ����������� ��� ������ ����������</strong>:<br /> ��� ���������� ���������� ����������� ������� ���� ����������� ������ ����������� ����� ���������� �������� ��������� ���� ���������� (������ � ������).<br />
		<strong>������������ ������ � ������ �������</strong>:<br /> ������ �������� ������������ ������ � �������������� �������� ����. ������ � ������ ����� ���������� ���������������.';
$helptxt['pmAttachmentEncryptFilenames'] = '���������� ������������� ������ ��������� ������������� ����� ��������� �������� � ����������� �������, ������������ .php-����� �� ���������, � �������� ������������. ������ � ������ ���� ��� �� ������� ����� �������� � ����� �������� �������������� ���� ������.';
$helptxt['pmattachments_remove_reported'] = '������ �������� �������� � �������� ���� ��������, � ������� ���� �������� ��������������, ������������� � ������ ���������� ��������� (���� ����) �������������.';
$helptxt['pmattachments_remove_by_members'] = '������� ����� �������������, �������� �� ��������. ����� ������������� � ��������.<br /><br /><strong>��������</strong>:<br />��� �������� ���� ��������, ������������ ������������� username1, username2 � username3, �������� ��������������� �������� ������ ("����") � ������� ����� ���� ������������� � ���� ������: <br /><br /><center>USERNAME1,userName2,username3</center>';
$helptxt['pmattachments_remove_all'] = '������ �������� ������ ����  �������� � ���� ������������� � ������������ ���� �������������, ������� ���. ����������� ��� � �������� ���������� �������� (��� ���������� �� �������� ������� � ��.).<br /><br />��������, � �������:<br />���� �� ����������� ������� �������� � ������, ����� ������������ �����, ��� ��� �������� ���� ������� ���������������. ��� ���� �� ������� ��� �� ������� ����� ��� �������� ����� ��������. ��� ���� �� ���������� ���������� ����� �� ����� ��� ������� ����� ��� �������� ��������. ��� ���� ������������ �������������� �������������� ��������...';

$txt['emails']['admin_pm_attachments_full'] = array(
		/*
			@additional_params: admin_pm_attachments_full
				REALNAME:
			@description:
		*/
		'subject' => '������! ����� ��� �������� ��� ����� ���������!',
		'body' => '{REALNAME},

����� ��� ��������, ������������ ����� ��, �� ������ {FORUMNAME} ����� ������. ����������, ������� �� ����� ��� ������� ���� ��������.

����� ���� ��� ������ ����� ��������� ����������� ���������� �������, ������������ ��� �� ������ ������������ ���������� ����� ��. (���� ��������).

{REGARDS}',
	);

?>