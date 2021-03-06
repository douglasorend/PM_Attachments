<?php
/*
###########################################
PM Attachments
Version 1.6
By: SoLoGHoST
http://www.graphicsmayhem.com
Copyright 2009 - 2010 Solomon Closson

###########################################
License Information:
--------------------
By using this MOD you agree to adhere to the following conditions for all versions of the PM Attachments MOD:

1. Copyright info & links must remain intact!
2. You are FREE to use this MOD on your SMF Forums in any way you see fit.
3. You understand that this MOD is provided "as is" and the said Author/Creator will not be held responsible for any misuse of this MOD on your forum(s).
4. You may redistribute this MOD in it original state, however you may NOT redistribute any modified versions of it without written consent from the Creator/Author of PM Attachments.

###########################################
pmAttachmentsInstall.php
###########################################
*/

// If SSI.php is in the same place as this file, and SMF isn't defined...
if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
  require_once(dirname(__FILE__) . '/SSI.php');
// Hmm... no SSI.php and no SMF?
elseif (!defined('SMF'))
  die('<b>Error:</b> Cannot install - please verify you put this in the same place as SMF\'s index.php.');

if((SMF == 'SSI') && !$user_info['is_admin']) 
    die('Admin priveleges required.'); 


db_extend('packages');


// Following added by GL700Wing
// Replaced original code so the creation of the two new columns are registered with the Package Manager.
// This will give the option of keeping/deleting the columns if the package is uninstalled.
// Adding 2 columns into pm_recipients.
$smcFunc['db_add_column']('{db_prefix}pm_recipients', array(
	'name' => 'attachments', 'type' => 'varchar', 'size' => 255, 'null' => false, 'default' => '',
));
$smcFunc['db_add_column']('{db_prefix}pm_recipients', array(
	'name' => 'downloads', 'type' => 'varchar', 'size' => 255, 'null' => false, 'default' => '',
));


// No need to change anything here - it already works!  (GL700Wing)
// Create the pm_attachments TABLE, would use new database functions if it supported unsigned integers.
// But since it doesn't gotta do it the old-fashioned way...
$smcFunc['db_query']('', "CREATE TABLE IF NOT EXISTS {db_prefix}pm_attachments(
	id_attach int(10) unsigned NOT NULL auto_increment,
	id_thumb int(10) unsigned NOT NULL default '0',
	id_pm int(10) unsigned NOT NULL default '0',
	pm_report int(10) unsigned NOT NULL default '0',
	id_folder tinyint(3) NOT NULL default '1',
	attachment_type tinyint(3) unsigned NOT NULL default '0',
	filename tinytext NOT NULL,
	file_hash varchar(40) NOT NULL default '',
	fileext varchar(8) NOT NULL default '',
	size int(10) unsigned NOT NULL default '0',
	downloads mediumint(8) unsigned NOT NULL default '0',
	width mediumint(8) unsigned NOT NULL default '0',
	height mediumint(8) unsigned NOT NULL default '0',
	mime_type varchar(20) NOT NULL default '',
	PRIMARY KEY (id_attach),
	KEY (id_pm))"
);


// Following added by GL700Wing
// The only purpose of the 'db_create_table' command below is to register the 'CREATE TABLE IF NOT EXISTS' command above with the Package Manager.
// This will give the option of keeping/deleting the table if the package is uninstalled.
// Create the table 'pm_attachments' and the 'mime_type' column again - it won't actually do anything but it won't error either.
$columns = array(
	array('name' => 'mime_type', 'type' => 'varchar', 'size' => 20, 'null' => false, 'default' => ''),
);
$smcFunc['db_create_table']('{db_prefix}pm_attachments', $columns, '', 'update', 'ignore');


// No need to change anything here - it already works!  (GL700Wing)
// Insert the settings
$smcFunc['db_insert']('ignore',
            '{db_prefix}settings',
            array(
				'variable' => 'string', 'value' => 'string',
			),
            array(
                   array('pmAttachmentSizeLimit', '150'),
                   array('attachmentPMLimit', '200'),
                   array('attachmentNumPerPMLimit', '2'),
                   array('pmAttachmentDirSizeLimit', '10240'),
				   array('pmAttachmentUploadDir', $boarddir . '/pm_attachments'),
                   array('pmAttachmentExtensions', 'doc,gif,jpg,mpg,pdf,png,txt,zip'),
				   array('pmAttachmentCheckExtensions', '0'),
                   array('pmAttachmentShowImages', '1'),
				   array('pmAttachmentEnable', '1'),
                   array('pmAttachmentEncryptFilenames', '1'),
				   array('pmAttachmentThumbnails', '1'),
                   array('pmAttachmentThumbWidth', '150'),
				   array('pmAttachmentThumbHeight', '150'),
            ),
			array('variable')
        );
?>
