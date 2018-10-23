<?php
/*
###########################################
PM Attachments
Version 1.6
Created By: SoLoGHoST
http://www.graphicsmayhem.com
Copyright 2009 - 2010 Solomon Closson

###########################################
License Information:
--------------------
By using this MOD you agree to adhere to the following conditions for all versions of the PM 

Attachments MOD:

1. Copyright info & links must remain intact!
2. You are FREE to use this MOD on your SMF Forums in any way you see fit.
3. You understand that this MOD is provided "as is" and the said Author/Creator will not be held responsible for any misuse of this MOD on your forum(s).
4. You may redistribute this MOD in it original state, however you may NOT redistribute any modified versions of it without written consent from the Creator/Author of PM Attachments.

###########################################
pmAttachmentsUninstall.php
###########################################
*/

if (file_exists(dirname(__FILE__) . '/SSI.php'))
  require_once(dirname(__FILE__) . '/SSI.php');
// Hmm... no SSI.php and no SMF?
elseif (!defined('SMF'))
  die('<b>Error:</b> Cannot install - please verify you put this in the same place as SMF\'s index.php.');

// Load the settings to be removed into an array ;)
$row_settings[] = 'pmAttachmentSizeLimit';
$row_settings[] = 'attachmentPMLimit';
$row_settings[] = 'attachmentNumPerPMLimit';
$row_settings[] = 'pmAttachmentDirSizeLimit';
$row_settings[] = 'pmAttachmentUploadDir';
$row_settings[] = 'pmAttachmentExtensions';
$row_settings[] = 'pmAttachmentCheckExtensions';
$row_settings[] = 'pmAttachmentShowImages';
$row_settings[] = 'pmAttachmentEnable';
$row_settings[] = 'pmAttachmentEncryptFilenames';
$row_settings[] = 'pmAttachmentThumbnails';
$row_settings[] = 'pmAttachmentThumbWidth';
$row_settings[] = 'pmAttachmentThumbHeight';


// UNINSTALLING PM Attachments...
db_extend('packages');

// Let's remove the settings
if (!empty($row_settings))
	foreach ($row_settings as $row)
		$smcFunc['db_query']('', '
               DELETE FROM {db_prefix}settings
               WHERE variable = {string:daRow}',
			   array(
					'daRow' => $row,
			   )
			);

?>
