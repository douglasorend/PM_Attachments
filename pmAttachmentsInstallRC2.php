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

// Adding 2 columns into pm_recipients
$columns_pm_recipients = array();
$columns_pm_recipients[] = array(
	'table' => '{db_prefix}pm_recipients',
	'info' => array(
		'name' => 'attachments',
		'type' => 'varchar',
		'size' => 255,
		'null' => false,
		'default' => '',
	),
);

$columns_pm_recipients[] = array(
	'table' => '{db_prefix}pm_recipients',
	'info' => array(
		'name' => 'downloads',
		'type' => 'varchar',
		'size' => 255,
		'null' => false,
		'default' => '',
	),
);

// Let's build the columns for the pm_attachments table
$pm_attachments_columns[] = array(
	'name' => 'id_attach',
	'type' => 'int',
	'size' => 10,
	'unsigned' => true,
	'null' => false,
	'auto' => true,
	);		
$pm_attachments_columns[] = array(
	'name' => 'id_thumb',
	'type' => 'int',
	'size' => 10,
	'null' => false,
	'default' => 0,
	'unsigned' => true,
);
$pm_attachments_columns[] = array(
	'name' => 'id_pm',
	'type' => 'int',
	'size' => 10,
	'null' => false,
	'default' => 0,
	'unsigned' => true,
);
$pm_attachments_columns[] = array(
	'name' => 'pm_report',
	'type' => 'int',
	'size' => 10,
	'null' => false,
	'default' => 0,
	'unsigned' => true,
);
$pm_attachments_columns[] = array(
	'name' => 'id_folder',
	'type' => 'tinyint',
	'size' => 3,
	'null' => false,
	'default' => 1,
);
$pm_attachments_columns[] = array(
	'name' => 'attachment_type',
	'type' => 'tinyint',
	'size' => 3,
	'null' => false,
	'default' => 0,
	'unsigned' => true,
);
$pm_attachments_columns[] = array(
	'name' => 'filename',
	'type' => 'tinytext',
	'null' => false,
);
$pm_attachments_columns[] = array(
	'name' => 'file_hash',
	'type' => 'varchar',
	'size' => 40,
	'null' => false,
	'default' => '',
);
$pm_attachments_columns[] = array(
	'name' => 'fileext',
	'type' => 'varchar',
	'size' => 8,
	'null' => false,
	'default' => '',
);
$pm_attachments_columns[] = array(
	'name' => 'size',
	'type' => 'int',
	'size' => 10,
	'null' => false,
	'default' => 0,
	'unsigned' => true,
);
$pm_attachments_columns[] = array(
	'name' => 'downloads',
	'type' => 'mediumint',
	'size' => 8,
	'null' => false,
	'default' => 0,
	'unsigned' => true,
);
$pm_attachments_columns[] = array(
	'name' => 'width',
	'type' => 'mediumint',
	'size' => 8,
	'null' => false,
	'default' => 0,
	'unsigned' => true,
);
$pm_attachments_columns[] = array(
	'name' => 'height',
	'type' => 'mediumint',
	'size' => 8,
	'null' => false,
	'default' => 0,
	'unsigned' => true,
);

$pm_attachments_columns[] = array(
	'name' => 'mime_type',
	'type' => 'varchar',
	'size' => 20,
	'null' => false,
	'default' => '',
);

// Indexes
$pm_attachments_indexes[] = array(
	'type' => 'primary',
	'columns' => array('id_attach')
);

$pm_attachments_indexes[] = array(
	'type' => 'key',
	'columns' => array('id_pm')
);


// Create the tables...
$smcFunc['db_create_table']('{db_prefix}pm_attachments', $pm_attachments_columns, $pm_attachments_indexes, array(), 'ignore');

// Insert the column(s) into the pm_recipients table
if (!empty($columns_pm_recipients))
	foreach ($columns_pm_recipients as $recipients)
		$smcFunc['db_add_column']($recipients['table'], $recipients['info']);

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
