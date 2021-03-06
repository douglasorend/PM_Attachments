﻿<?php
/**********************************************************************************
* PMAttachments.spanish_latin-utf8.php - Spanish Latin language file
***********************************************************************************
* This mod is licensed under the 2-clause BSD License, which can be found here:
*	http://opensource.org/licenses/BSD-2-Clause
***********************************************************************************
* This program is distributed in the hope that it is and will be useful, but      *
* WITHOUT ANY WARRANTIES; without even any implied warranty of MERCHANTABILITY    *
* or FITNESS FOR A PARTICULAR PURPOSE.                                            *
***********************************************************************************
* Spanish translation by Rock Lee (https://www.bombercode.org) Copyright 2014-2017*
***********************************************************************************/
if (!defined('SMF')) 
	die('Hacking attempt...');

global $scripturl, $helptxt;

$txt['pm_report_desc'] = 'Desde esta página puede informar el mensaje personal que recibió al equipo de administración del foro. Asegúrese de incluir una descripción de por qué está informando el mensaje, ya que se enviará junto con el contenido del mensaje original, incluidos todos los archivos adjuntos.';

// PM Attachment restrictions
$txt['attach_restrict_attachmentNumPerPMLimit'] = '%1$d por la tarde';
$txt['attach_restrict_attachmentPMLimit'] = 'Tamaño total máximo %1$dKB';
$txt['attach_restrict_pmAttachmentSizeLimit'] = 'Tamaño individual máximo %1$dKB';

// PM Attachments by Mail
$txt['pmattachments_mail'] = 'Los siguientes son enlaces directos a todos los archivos adjuntos que se incluyeron en este mensaje personal (tenga en cuenta que debe iniciar sesión en el sitio para poder descargarlos):';

// Settings Titles
$txt['pmattachment_manager_settings'] = 'Configuraciones de adjuntos por mensaje privado (MP)';
$txt['pmattachment_manager_maintenance'] = 'Mantenimiento de archivos por mensaje privado (MP)';

// Maintenance Settings
$txt['pmattachment_options'] = 'Opciones de archivos adjuntos por mensaje privado';
$txt['pmattachment_remove_old'] = 'Eliminar los archivos adjuntos por mensaje privado anteriores a';
$txt['pmattachment_remove_size'] = 'Eliminar los archivos adjuntos por mensaje privado más grandes que';
$txt['pmattachment_remove_downloads'] = 'Eliminar los archivos adjuntos por mensaje privado descargados / vistos al menos';
$txt['pmattachment_downloads_times'] = 'hora(s), por todos los destinatarios';
$txt['pmattachments_remove_by_members'] = 'Eliminar todos los archivos adjuntos por mensaje privado enviados';
$txt['pmattachments_remove_by_members_from'] = 'De';
$txt['pmattachments_remove_by_members_to'] = 'a';
$txt['pmattachments_remove_by_members_cont'] = 'Los siguientes miembros:';
$txt['pmattachments_remove_reported'] = 'Eliminar los archivos adjuntos por mensaje privado informados por los siguientes miembros:';
$txt['pmattach_report_all_members'] = 'Todos los miembros';
$txt['pmattach_specific_members'] = 'Miembros específicos';
$txt['pmattachments_remove_all'] = '¡Eliminar todos los archivos adjuntos por mensaje privado de TODOS los miembros!';

// Reporting for DUTY, SIR!
$txt['pm_report_attachments_sent'] = 'Los siguientes son todos los archivos adjuntos que se enviaron a este(los) miembro(s):';

// Settings Options
$txt['pmAttachmentEnable'] = 'Modo Adjuntos por mensaje privado';
$txt['pmAttachmentEnable_deactivate'] = 'Deshabilitar archivos adjuntos';
$txt['pmAttachmentEnable_enable_all'] = 'Habilitar todos los archivos adjuntos';
$txt['pmAttachmentEnable_disable_new'] = 'Deshabilitar nuevos archivos adjuntos';
$txt['pmAttachmentCheckExtensions'] = 'Verifique la extensión del archivo adjunto';
$txt['pmAttachmentExtensions'] = 'Extensiones de archivos adjuntos permitidas';
$txt['pmAttachmentShowImages'] = 'Mostrar archivos adjuntos de imagen como imágenes bajo el/los mensaje(s) privado(s)';
$txt['pmAttachmentEncryptFilenames'] = 'Cifrar nombres de archivos almacenados';
$txt['pmAttachmentUploadDir'] = 'Adjuntos por mensaje privado directorio<div class="smalltext"><a href="' . $scripturl . '?action=admin;area=manageattachments;sa=pmattachpaths">Configurar múltiples directorios de adjuntos por mensaje privado</a></div>';
$txt['pmAttachmentUploadDir_multiple'] = 'Adjuntos por mensaje privado directorio';
$txt['pmAttachmentUploadDir_multiple_configure'] = '<a href="' . $scripturl . '?action=admin;area=manageattachments;sa=pmattachpaths">[Configurar múltiples directorios de adjuntos por mensaje privado]</a>';
$txt['pmAttachmentDirSizeLimit'] = 'Máximo espacio de carpeta adjunta<div class="smalltext">(0 sin límite)</div>';
$txt['attachmentPMLimit'] = 'Máximo tamaño de archivo adjunto por mensaje privado<div class="smalltext">(0 sin límite)</div>';
$txt['pmAttachmentSizeLimit'] = 'Tamaño máximo por archivo adjunto<div class="smalltext">(0 sin límite)</div>';
$txt['attachmentNumPerPMLimit'] = 'Número máximo de archivos adjuntos por mensaje privado<div class="smalltext">(0 sin límite)</div>';
$txt['pmAttachmentThumbnails'] = 'Cambiar el tamaño de las imágenes cuando se muestra bajo el/los mensaje(s) privado(s)';
$txt['pmAttachmentThumbWidth'] = 'Ancho máximo de miniaturas';
$txt['pmAttachmentThumbHeight'] = 'Altura máxima de las miniaturas';

$txt['pmattach_dir_does_not_exist'] = 'No existe';
$txt['pmattach_dir_not_writable'] = 'No se puede escribir';
$txt['pmattach_dir_files_missing'] = 'Archivos perdidos (<a href="' . $scripturl . '?action=admin;area=manageattachments;sa=pmrepair;sesc=%1$s">Reparar</a>)';
$txt['pmattach_dir_unused'] = 'No usado';
$txt['pmattach_dir_ok'] = 'OK';

$txt['pmattach_path_manage'] = 'Administrar rutas de archivos adjuntos';
$txt['pmattach_paths'] = 'Caminos de apego';
$txt['pmattach_current_dir'] = 'Directorio actual';
$txt['pmattach_path'] = 'Camino';
$txt['pmattach_current_size'] = 'Tamaño actual (KB)';
$txt['pmattach_num_files'] = 'Archivos';
$txt['pmattach_dir_status'] = 'Estado';
$txt['pmattach_add_path'] = 'Agregar ruta';
$txt['pmattach_path_current_bad'] = 'Ruta de acceso actual no válida.';

$txt['pmattachment_total'] = 'Total de archivos adjuntos por mensaje privado';
$txt['pmattachmentdir_size'] = 'Tamaño total del directorio de archivos adjuntos por mensaje privado';
$txt['pmattachmentdir_size_current'] = 'Tamaño total del directorio actual de archivos adjuntos por mensaje privado';
$txt['pmattachment_space'] = 'Espacio total disponible en el directorio de archivos adjuntos por mensaje privado';
$txt['pmattachment_space_current'] = 'Espacio total disponible en el directorio actual de archivos adjuntos por mensaje privado';

// Deletion of PM Attachments
$txt['confirm_delete_pmattachments_all'] = '¿Seguro que quieres eliminar todos los archivos adjuntos por mensaje privado?';
$txt['confirm_delete_pmattachments'] = '¿Seguro que quieres eliminar los archivos adjuntos por mensaje privado seleccionados?';
$txt['confirm_delete_pmattachments_members'] = '¿Está seguro de que desea eliminar todos los archivos adjuntos por mensaje privado enviados, ya sea desde o hacia los siguientes miembros?';

// Error Handling
$txt['pm_attach_not_allowed'] = 'Lo sentimos, no está permitido enviar archivos adjuntos a otros miembros a través de mensajes personales!';
$txt['attach_pmrepair_missing_thumbnail_parent'] = '%d las miniaturas faltan un archivo adjunto principal';
$txt['attach_pmrepair_parent_missing_thumbnail'] = '%d los padres están marcados con miniaturas pero no';
$txt['attach_pmrepair_file_missing_on_disk'] = '%d los archivos adjuntos tienen una entrada pero ya no existen en el disco';
$txt['attach_pmrepair_file_wrong_size'] = '%d los archivos adjuntos se informan como el tamaño de archivo incorrecto';
$txt['attach_pmrepair_file_size_of_zero'] = '%d los archivos adjuntos tienen un tamaño de cero en el disco. (Estos serán eliminados)';
$txt['attach_pmrepair_attachment_no_pm'] = '%d los archivos adjuntos ya no tienen un mensaje personal asociado a ellos';
$txt['attach_pmrepair_wrong_folder'] = '%d los archivos adjuntos están en la carpeta incorrecta';
$txt['pm_cant_upload_type'] = 'No puedes subir ese tipo de archivo. Las únicas extensiones permitidas son';
$txt['pm_restricted_filename'] = 'Ese es un nombre de archivo restringido. Por favor intente con un nombre de archivo diferente.';
$txt['cannot_pm_view_attachments'] = 'No tienes permiso para acceder a esta sección';
$txt['pmattach_no_selection'] = 'Algo extraño sucedió, no se seleccionó nada!';

// PM Attachment Permissions...
$txt['permissionname_pm_view_attachments'] = 'Ver archivos adjuntos por mensaje privado';
$txt['permissionhelp_pm_view_attachments'] = 'Los archivos adjuntos por mensaje privado son archivos que están adjuntos a mensajes personales. Esta característica se puede habilitar y configurar en &quot;Datos adjuntos y avatares - Configuración de archivos adjuntos de mensajes privados&quot;. Como los archivos adjuntos de mensajes privados no tienen acceso directo, puede protegerlos para que no los descarguen los usuarios que no tienen este permiso.';
$txt['permissionname_pm_post_attachments'] = 'Cargar archivos adjuntos de los mensajes privados';
$txt['permissionhelp_pm_post_attachments'] = 'Los archivos adjuntos por mensaje privado son archivos que están adjuntos a mensajes personales. Un mensaje personal puede contener múltiples archivos adjuntos. Desmarcar esto inhabilita por completo a estos usuarios para que no puedan cargar archivos adjuntos en mensajes personales.';

// PM Help text:
$helptxt['pmattachment_manager_settings'] = 'Los archivos adjuntos por mensaje privado son archivos que los miembros pueden cargar y adjuntar a un mensaje personal que envían a otros miembros.<br /><br />
		<strong>Verifique la extensión del archivo adjunto</strong>:<br /> Comprueba las extensiones de los archivos que se cargan antes de permitir este archivo.<br />
		<strong>Extensiones de archivos adjuntos permitidas</strong>:<br /> Establece las extensiones permitidas de los archivos adjuntos.<br />
		<strong>Adjuntos por mensaje privado directorio</strong>:<br /> La ruta a su carpeta de archivos adjuntos por mensaje privado, donde irán todos los archivos cargados<br />(ejemplo: /home/sites/yoursite/www/forum/pm_attachments)<br />
		<strong>Máximo espacio de carpeta adjunta</strong> (en KB):<br /> Establece el tamaño de la carpeta de archivos adjuntos por mensaje privado, incluidos todos los archivos que contiene.<br />
		<strong>Máximo tamaño de archivo adjunto por mensaje privado</strong> (en KB):<br /> Establece el tamaño máximo de archivo de todos los archivos adjuntos realizados por mensaje privado. Si esto es menor que el límite por adjunto, este será el límite.<br />
		<strong>Tamaño máximo por archivo adjunto</strong> (en KB):<br /> Establece el tamaño máximo de archivo de cada archivo adjunto por mensaje privado por separado.<br />
		<strong>Número máximo de archivos adjuntos por mensaje privado</strong>:<br /> Establece la cantidad de archivos adjuntos que una persona puede enviar desde un mensaje personal.<br />
		<strong>Mostrar archivo adjunto como imagen en mensajes privados</strong>:<br /> Si el archivo cargado es una imagen, esto lo mostrará debajo del mensaje personal.<br />
		<strong>Cambiar el tamaño de las imágenes cuando se muestra debajo en mensajes privados</strong>:<br /> Si se selecciona la opción anterior, esto guardará un archivo adjunto separado (más pequeño) para la miniatura para disminuir el ancho de banda.<br />
		<strong>Ancho y altura máximos de las miniaturas</strong>:<br /> Solo se usa con las &quot;Cambiar el tamaño de las imágenes cuando se muestra en los mensajes privados &quot; opción, el ancho y alto máximos para cambiar el tamaño de los archivos adjuntos desde. Se les cambiará el tamaño proporcionalmente.';

$helptxt['pmAttachmentEncryptFilenames'] = 'Cifrar los nombres de los archivos adjuntos permite a los miembros tener más de un archivo adjunto de la
	mismo nombre, para usar archivos .php de forma segura para los archivos adjuntos, y aumenta la seguridad. Sin embargo, podría hacerlo más
	difícil de reconstruir su base de datos si algo drástico sucedió.';

$helptxt['pmattachments_remove_reported'] = 'Esta opción eliminará todos los archivos adjuntos que se hayan informado dentro de los mensajes personales a un administrador que fueron enviados por los siguientes miembros o por todos los miembros. Esto no elimina el mensaje personal, solo los archivos adjuntos informados dentro de ese mensaje personal.';

$helptxt['pmattachments_remove_by_members'] = 'Ingrese los nombres de usuario separados por una coma y eliminaremos TODOS los archivos adjuntos por mensaje privado enviados, de los siguientes miembros de su foro. Los nombres de usuario ingresados no distinguen entre mayúsculas y minúsculas.<br /><br /><strong>Por ejemplo</strong>:<br />Para eliminar todos los archivos adjuntos por mensaje privado enviados al nombre de usuario 1, nombre de usuario 2 y nombre de usuario 3, debe <strong>seleccionarlo</strong> en el cuadro de selección y luego puede ingresar lo siguiente en el área de texto antes de hacer clic en el botón Eliminar: <br /><br /><center>NOMBRE DE USUARIO 1, nombre de usuario 2, nombre de usuario 3</center>';

$helptxt['pmattachments_remove_all'] = 'Esto eliminará todos los archivos adjuntos por mensaje privado y se enviarán a todos los miembros, incluido usted. Debe ser utilizado como último recurso.<br /><br />Algunos escenarios que pueden justificar la necesidad de que usted haga esto, son los siguientes:<br />Si está a punto de desinstalar los archivos adjuntos por mensaje privado y quiere que sus usuarios sepan que el administrador eliminó todos los archivos adjuntos por mensaje privado antes de desinstalarlos. Si ya no tienes el espacio en tu servidor para estos archivos. Si planea comenzar de cero y/o desea reducir o aumentar el tamaño para el directorio de archivos adjuntos por mensaje privado, o cambie el directorio y no quiere ningún archivo en este directorio antes de realizar cambios. Si muchos usuarios están abusando del uso de archivos adjuntos por mensaje privado en su servidor, y muchas otras razones para esto también.';

$txt['emails']['admin_pm_attachments_full'] = array(
	/*
		@additional_params: admin_pm_attachments_full
			REALNAME:
		@description:
	*/
	'subject' => '¡Urgente! Carpeta Adjuntos por mensaje privado casi llena',
	'body' => '{REALNAME},

La carpeta de adjuntos por mensaje privado en {FORUMNAME} está casi lleno Por favor visite el foro para resolver este problema.

Una vez que la carpeta de adjuntos por mensaje privado alcanza su tamaño máximo permitido, los usuarios no podrán continuar cargando archivos adjuntos a través de mensajes personales. (Si está habilitado).

{REGARDS}',
		);
?>