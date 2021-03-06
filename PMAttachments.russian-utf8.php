﻿<?php
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

$txt['pm_report_desc'] = 'Здесь можно оформить жалобу на личное сообщение другого пользователя. Ваша жалоба будет отправлена на рассмотрение администрации форума, вместе с содержанием оригинального сообщения, включая все вложения.';

// PM Attachment restrictions
$txt['attach_restrict_attachmentNumPerPMLimit'] = '%1$d вложения в одном ЛС';
$txt['attach_restrict_attachmentPMLimit'] = 'общий максимальный размер %1$d KB';
$txt['attach_restrict_pmAttachmentSizeLimit'] = 'индивидуальный максимальный размер %1$dKB';

// PM Attachments by Mail
$txt['pmattachments_mail'] = 'Ниже приведены прямые ссылки на все файлы, прикрепленные к данному сообщению (Обратите внимание: для загрузки этих вложений пользователь должен быть авторизован на форуме):';

// Settings Titles
$txt['pmattachment_manager_settings'] = 'Отправка вложений через ЛС';

// Maintenance Settings
$txt['pmattachment_options'] = 'Параметры файлов вложений';
$txt['pmattachment_remove_old'] = 'Удалять вложения, срок которых превышает';
$txt['pmattachment_remove_size'] = 'Удалять вложения, размер которых превышает';
$txt['pmattachment_remove_downloads'] = 'Удалить вложения, загруженные/просмотренные по крайней мере';
$txt['pmattachment_downloads_times'] = 'раз всеми получателями';
$txt['pmattachments_remove_by_members'] = 'Удалить все вложения, отправленные';
$txt['pmattachments_remove_by_members_from'] = 'от';
$txt['pmattachments_remove_by_members_to'] = 'кому';
$txt['pmattachments_remove_by_members_cont'] = ':';
$txt['pmattachments_remove_reported'] = 'Удалить вложения, отправленные следующими пользователями:';
$txt['pmattach_report_all_members'] = 'Все пользователи';
$txt['pmattach_specific_members'] = 'Указанные пользователи';
$txt['pmattachments_remove_all'] = 'Удалить ВСЕ вложения у ВСЕХ пользователей!';

// Reporting for DUTY, SIR!
$txt['pm_report_attachments_sent'] = 'Ниже перечислены все вложения, отправленные этим(и) пользователем(ями):';

// Settings Options
$txt['pmAttachmentEnable'] = 'Режим мода PM Attachments';
$txt['pmAttachmentEnable_deactivate'] = 'Запретить вложения';
$txt['pmAttachmentEnable_enable_all'] = 'Разрешить все вложения';
$txt['pmAttachmentEnable_disable_new'] = 'Запретить новые вложения';
$txt['pmAttachmentCheckExtensions'] = 'Проверять расширения вложений';
$txt['pmAttachmentExtensions'] = 'Доступные расширения вложений';
$txt['pmAttachmentShowImages'] = 'Отображать прикрепленные изображения под сообщением';
$txt['pmAttachmentEncryptFilenames'] = 'Шифровать сохраняемые файлы';
$txt['pmAttachmentUploadDir'] = 'Директория для хранения вложений<div class="smalltext"><a href="' . $scripturl . '?action=admin;area=manageattachments;sa=pmattachpaths">Установить несколько директорий</a></div>';
$txt['pmAttachmentUploadDir_multiple'] = 'Директория для хранения вложений';
$txt['pmAttachmentUploadDir_multiple_configure'] = '<a href="' . $scripturl . '?action=admin;area=manageattachments;sa=pmattachpaths">[Установить несколько директорий для хранения вложений]</a>';
$txt['pmAttachmentDirSizeLimit'] = 'Максимальный размер папки вложений<div class="smalltext">(0 — без ограничений)</div>';
$txt['attachmentPMLimit'] = 'Максимальный размер вложения для отправки через ЛС<div class="smalltext">(0 — без ограничений)</div>';
$txt['pmAttachmentSizeLimit'] = 'Максимальный размер одного вложения<div class="smalltext">(0 — без ограничений)</div>';
$txt['attachmentNumPerPMLimit'] = 'Максимальное кол-во вложений в личном сообщении<div class="smalltext">(0 — без ограничений)</div>';
$txt['pmAttachmentThumbnails'] = 'Изменять размер изображений, при отображении под личным сообщением';
$txt['pmAttachmentThumbWidth'] = 'Максимальная ширина эскиза';
$txt['pmAttachmentThumbHeight'] = 'Максимальная высота эскиза';
$txt['pmattach_dir_does_not_exist'] = 'не существует';
$txt['pmattach_dir_not_writable'] = 'не имеет прав на запись';
$txt['pmattach_dir_files_missing'] = 'Пропущенные файлы (<a href="' . $scripturl . '?action=admin;area=manageattachments;sa=pmrepair;sesc=%1$s">Починить</a>)';
$txt['pmattach_dir_unused'] = 'Не используется';
$txt['pmattach_dir_ok'] = 'OK';
$txt['pmattach_path_manage'] = 'Управление директориями вложений';
$txt['pmattach_paths'] = 'Пути вложений';
$txt['pmattach_current_dir'] = 'Текущая директория';
$txt['pmattach_path'] = 'Путь';
$txt['pmattach_current_size'] = 'Текущий размер (кБ)';
$txt['pmattach_num_files'] = 'Файлы';
$txt['pmattach_dir_status'] = 'Состояние';
$txt['pmattach_add_path'] = 'Добавить путь';
$txt['pmattach_path_current_bad'] = 'Неверный путь для хранения вложений.';
$txt['pmattachment_total'] = 'Всего вложений в ЛС';
$txt['pmattachmentdir_size'] = 'Общий размер директории для вложений';
$txt['pmattachmentdir_size_current'] = 'Общий размер текущей директории';
$txt['pmattachment_space'] = 'Общее свободное место в директории для вложений';
$txt['pmattachment_space_current'] = 'Общее свободное место в текущей директории';

// Deletion of PM Attachments
$txt['confirm_delete_pmattachments_all'] = 'Вы действительно хотите удалить все вложения?';
$txt['confirm_delete_pmattachments'] = 'Вы действительно хотите удалить все выбранные вложения?';
$txt['confirm_delete_pmattachments_members'] = 'Вы действительно хотите удалить все вложения, отправленные кем-либо из следующих пользователей?';

// Error Handling
$txt['pm_attach_not_allowed'] = 'Извините, Вам не разрешена отправка вложений другим пользователям через ЛС!';
$txt['attach_pmrepair_missing_thumbnail_parent'] = '%d эскизов неизвестных изображений';
$txt['attach_pmrepair_parent_missing_thumbnail'] = '%d вложенных изображений, помеченных как имеющие эскизы (но не имеющие их на самом деле)';
$txt['attach_pmrepair_file_missing_on_disk'] = '%d вложений, уже не существующих физически (но записи о них остались)';
$txt['attach_pmrepair_file_wrong_size'] = '%d вложений, имеющих неправильный размер';
$txt['attach_pmrepair_file_size_of_zero'] = '%d вложений, имеющих нулевой размер (они будут удалены)';
$txt['attach_pmrepair_attachment_no_pm'] = '%d вложений, не связанных ни с какими личными сообщениями';
$txt['attach_pmrepair_wrong_folder'] = '%d вложений в неправильной папке';
$txt['pm_cant_upload_type'] = 'Вы не можете загружать файлы данного типа. Разрешены только следующие расширения';
$txt['pm_restricted_filename'] = 'Указанное имя файла не подходит. Пожалуйста, попытайтесь с другим.';
$txt['cannot_pm_view_attachments'] = 'Вам не разрешён доступ к данной секции';
$txt['pmattach_no_selection'] = 'Произошла странная вещь: Вы ничего не выбрали!';

// PM Attachment Permissions...
$txt['permissionname_pm_view_attachments'] = 'Просмотр вложений';
$txt['permissionhelp_pm_view_attachments'] = 'Эта функция может быть активирована и настроена в разделе "Вложения и аватары — Отправка вложений через ЛС". Поскольку к файлам вложений нет прямого доступа, Вы можете защитить их от прямой загрузки пользователями, не имеющими соответствующих прав.';
$txt['permissionname_pm_post_attachments'] = 'Отправка вложений';
$txt['permissionhelp_pm_post_attachments'] = 'В одном личном сообщении может содержаться несколько вложений. Если отключить данное разрешение, пользователи НЕ смогут прикреплять вложения к личным сообщениям.';

$helptxt['pmattachment_manager_settings'] = 'Пользователи могут прикреплять файлы к личным сообщениям для отправки друг другу.<br /><br />
		<strong>Проверка расширений вложений</strong>:<br /> Перед тем как файл будет разрешён к загрузке, его расширение дожно будет пройти проверку.<br />
		<strong>Доступные расширения вложений</strong>:<br /> Установите здесь те расширения файлов, которые пользователи смогут прикреплять к своим личным сообщениям.<br />
		<strong>Директория для хранения вложений</strong>:<br /> Путь к папке вложений, куда будут загружаться все прикрепляемые файлы<br />(например: /home/sites/yoursite/www/forum/pm_attachments)<br />
		<strong>Максимально доступное место</strong> (в кБ):<br /> Укажите, насколько большой размер должен быть у папки вложений (с учётом всех находящихся в ней файлов).<br />
		<strong>Максимальный размер вложения в одном личном сообщении</strong> (в кБ):<br /> Укажите максимально допустимый размер файла для всех вложений, прикрепляемых к личным сообщениям.<br />
		<strong>Максимальный размер одного вложения</strong> (в кБ):<br /> Установите максимальный размер файла каждого отдельного вложения.<br />
		<strong>Максимальное количество вложений в одном личном сообщении</strong>:<br /> Установите количество файлов, которое пользователь сможет прикрепить к отправляемому личному сообщению.<br />
		<strong>Отображать прикрепленные изображения под сообщением</strong>:<br /> Если загружаемый файл является изображением, его эскиз будет отображаться в нижней части сообщения.<br />
		<strong>Изменять размер изображений, при отображении под личным сообщением</strong>:<br /> Для увеличения пропускной способности размеры всех загружаемых файлов изображений будут изменяться согласно указанным ниже параметрам (ширина и высота).<br />
		<strong>Максимальная ширина и высота эскизов</strong>:<br /> Данные значения используются только с активированной функцией выше. Ширина и высота будут изменяться пропорционально.';
$helptxt['pmAttachmentEncryptFilenames'] = 'Шифрование прикрепляемых файлов позволяет пользователям иметь несколько вложений с одинаковыми именами, использовать .php-файлы во вложениях, и повышает безопасность. Однако в случае чего эта же функция может привести к более трудному восстановлению базы данных.';
$helptxt['pmattachments_remove_reported'] = 'Данная операция приведет к удалению всех вложений, о которых было сообщено администратору, прикрепленных к личным сообщениям указанных (либо всех) пользователей.';
$helptxt['pmattachments_remove_by_members'] = 'Введите имена пользователей, разделяя их запятыми. Имена чувствительны к регистру.<br /><br /><strong>Например</strong>:<br />Для удаления ВСЕХ вложений, отправленных пользователям username1, username2 и username3, выберите соответствующее значение списка ("кому") и введите имена этих пользователей в поле справа: <br /><br /><center>USERNAME1,userName2,username3</center>';
$helptxt['pmattachments_remove_all'] = 'Данная операция удалит всех  вложения у всех пользователей и отправленные всем пользователям, включая Вас. Используйте это в качестве последнего средства (при подозрении на рассылку вирусов и пр.).<br /><br />Например, в случаях:<br />Если Вы собираетесь удалить вложения и хотите, чтобы пользователи знали, что все вложения были удалены администратором. Или если на сервере уже не хватает места для загрузки новых вложений. Или если Вы планируете освободить место на диске или сменить папку для хранения вложений. Или если пользователи злоупотребляют использованием вложений...';

$txt['emails']['admin_pm_attachments_full'] = array(
		/*
			@additional_params: admin_pm_attachments_full
				REALNAME:
			@description:
		*/
		'subject' => 'Срочно! Папка для вложений уже почти заполнена!',
		'body' => '{REALNAME},

Папка для вложений, пересылаемых через ЛС, на форуме {FORUMNAME} почти полная. Пожалуйста, зайдите на форум для решения этой проблемы.

После того как размер папки достигнет максимально допустимой отметки, пользователи уже не смогут обмениваться вложениями через ЛС. (Если включено).

{REGARDS}',
	);

?>