<?php
require_once '../../inc/autoload.php';


/*
		 * In der php.ini:
		 *   upload_max_filesize = 4000M
		 *   post_max_size = 4000M
		 * müssen auf einen relativ hohen wert eingestellt sein, sonst kann es vorkommen,
		 * dass Dateien nicht komplett hochgeldaen werden - auch wenn ein Fortschritt angezeigt wird.
		 */

//Ist der Ordner richtig?
function checkFolder($dir)
{
	$dir = str_replace('../', '', $dir);
	$parts = explode('/', $dir);
	//print_r($parts);
	if ($parts[0] == 'Files')
	{
		return true;
	} else
	{
		return false;
	}
}

if (isset($_GET['json']))
{
	if (hasPerm('manage_files'))
	{
		//URl aufräumen
		function cleanUrl($url)
		{
			$url = str_replace('///', '/', $url);
			$url = str_replace('//', '/', $url);
			return $url;
		}

		error_reporting(0);
		header('Charset: utf-8');
		header('content-type: application/json');

		$files = [];
		$types_audio = ['mp3', 'flac', 'wav', 'wma', 'aac', 'ogg', 'rm'];
		$types_video = ['wmv', 'mpeg', 'mp4', 'avi', 'mov', 'flv'];
		$types_images = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'tif', 'tiff', 'psd', 'pdd', 'eps', 'svg', 'ai'];
		$types_archives = ['zip', 'rar', '7z', 'bzip', 'gzip', 'tar', 'tar.bz', 'wim', 'xz'];
		$types_code = ['html', 'htm', 'xml', 'json', 'php', 'css', 'js', 'php5'];
		$types_word = ['doc', 'docx', 'rtf', 'odt'];
		$types_pp = ['ppt', 'pptx', 'odp', 'uop'];

		//Filehandels vorbereiten
		if (isset($_GET['folder']))
		{
			$get_url = cleanUrl($_GET['folder']);
			if (strpos($get_url, '/..') !== false)
			{
				//Wenn /.. in GET folder vorkommt, (wenn also eine ebene drüber angefragt wurde) soll dahin weitergeleitet werden
				$folders = explode('/', str_replace('/..', '', $get_url));
				header('Location: index.php?json&folder=' . str_replace(end($folders), '', str_replace('/..', '', $get_url)), true, 301);
			}
			$dir = $get_url;
			if (strpos(substr($dir, 0, 6), 'Files/') !== false) $dir = substr($dir, 6);
			$dir = str_replace('..', '', $dir);
			$dir = '../../Files/' . str_replace('../', '', $dir) . '/';
			//echo $dir;
		} else
		{
			$dir = '../../Files/';
		}
		$files['url'] = $dir;
		$files['displayUrl'] = str_replace('../', '', cleanUrl($dir));

		//Verzeichnisse
		//Wenn man nicht im root-verzeichnis ist, soll .. als ordner angezeigt werden, um auch zurückzukommen
		//if($dir != '../../Files/') $files['files'][] = ['name' => '..', 'date' => '', 'type' => 'Ordner', 'size' => '', 'icon' => 'folder2'];
		if (cleanUrl($dir) != '../../Files/') $files['files'][] = ['name' => '..', 'date' => '', 'type' => 'Ordner', 'size' => '-', 'icon' => 'folder2'];

		$handle = opendir($dir) or die (http_response_code(404));
		while (false !== ($datei = readdir($handle)))
		{
			if ($datei != "." && $datei != ".." && is_dir($dir . $datei))
			{
				$files['files'][] = ['name' => $datei, 'date' => date("d.m.Y H:m:s", filemtime($dir . $datei)), 'type' => 'Ordner', 'size' => '-', 'icon' => 'folder2'];
			}
		}

		//Files auslesen
		$files_sort = [];
		$handle = opendir($dir) or die (http_response_code(404));
		while ($datei = readdir($handle))
		{
			//dateityp
			$dateityp = strrchr($datei, '.');
			$dateityp = str_replace('.', '', $dateityp);
			if ($dateityp != '')
			{
				$icon = 'file';
				if (in_array($dateityp, $types_audio)) $icon = 'file-audio-o';
				if (in_array($dateityp, $types_video)) $icon = 'file-movie-o';
				if (in_array($dateityp, $types_images)) $icon = 'file-picture-o';
				if (in_array($dateityp, $types_archives)) $icon = 'file-archive-o';
				if (in_array($dateityp, $types_code)) $icon = 'file-code-o';
				if (in_array($dateityp, $types_word)) $icon = 'file-word-o';
				if (in_array($dateityp, $types_pp)) $icon = 'file-powerpoint-o';
				if ($dateityp == 'pdf') $icon = 'file-pdf-o';
				if ($dateityp == 'exe') $icon = 'file-excel-o';

				$files['files'][] = ['name' => $datei, 'date' => date("d.m.Y H:m:s", filemtime($dir . $datei)), 'type' => $dateityp, 'size' => calc_filesize(filesize($dir . $datei)), 'icon' => $icon];
			}
		}

		//Ausgeben
		echo json_encode($files);
		exit;
	}
}//Downloaden
elseif (isset($_GET['dl']))
{
	//echo $_GET['dl'];
	if (checkFolder(str_replace($MCONF['web_uri'], '', $_GET['dl'])))
	{
		//$nixDw = ['doc', 'docx', 'rtf', 'odt', 'ppt', 'pptx', 'odp', 'uop', 'zip', 'rar', '7z', 'bzip', 'gzip', 'tar', 'tar.bz', 'wim', 'xz', 'psd', 'pdd', 'eps', 'ai', 'avi', 'flv'];
		$nixDw = [];
		//mime
		$finfo = finfo_open(FILEINFO_MIME_TYPE); // gib den MIME-Typ nach Art der mimetype Extension zurück
		$mime = finfo_file($finfo, '../../' . str_replace($MCONF['web_uri'], '', $_GET['dl']));
		finfo_close($finfo);
		$name = explode('/', $_GET['dl']);
		//echo $mime;
		$dateityp = str_replace('.', '', strrchr(end($name), '.'));

		if (in_array($dateityp, $nixDw))
		{
			header('Location: ' . $_GET['dl']);
		} else
		{
			header('content-Description: File Transfer');
			header('content-Type: ' . $mime);
			header('content-Disposition: attachment; filename="' . end($name) . '"');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('content-Length: ' . filesize('../../' . str_replace($MCONF['web_uri'], '', $_GET['dl'])));
			readfile('../../' . str_replace($MCONF['web_uri'], '', $_GET['dl']));
		}
	} else
	{
		echo 'fail';
	}
	exit;
}//Löschen
elseif (isset($_GET['del'], $_POST['file']))
{
	if (hasPerm('delete'))
	{
		if (checkFolder($_POST['file']))
		{
			if (is_dir('../../' . $_POST['file']))
			{
				if (rrmdir('../../' . $_POST['file']))
				{
					echo 'success';
				} else
				{
					echo 'fail';
				}
			} else
			{
				if (unlink('../../' . $_POST['file']))
				{
					echo 'success';
				} else
				{
					echo 'fail';
				}
			}
		} else
		{
			echo 'fail';
		}
	}
	exit;
}//Datei Hochladen
elseif (isset($_GET['upload']))
{
	if (hasPerm('upload'))
	{
		/*
		 * In der php.ini:
		 *   upload_max_filesize = 4000M
		 *   post_max_size = 4000M
		 * müssen auf einen relativ hohen wert eingestellt sein, sonst kann es vorkommen,
		 * dass Dateien nicht komplett hochgeldaen werden - auch wenn ein Fortschritt angezeigt wird.
		 */
		set_time_limit(0);
		if (isset($_FILES['file']) && hasPerm('upload'))
		{
			//print_r($_FILES);
			$filename = str_replace(' ', '-', $_FILES['file']['name']);
			$filename = str_replace('+', '_', $filename);
			$filename = str_replace('/', '_', $filename);
			$filename = str_replace("'", '_', $filename);

			if (file_exists('../../' . $_GET['upload']) && is_dir('../../' . $_GET['upload']) && checkFolder($_GET['upload']))
			{
				if (move_uploaded_file($_FILES['file']['tmp_name'], '../../' . $_GET['upload'] . $filename))
				{
					echo 'success';
				} else
				{
					echo 'fail';
				}
			} else
			{
				echo 'fail';
			}
		}
	}
	exit;
}//Neuer Ordner erstellen
elseif (isset($_GET['newFolder']))
{
	if (hasPerm('create_folder'))
	{
		if (isset($_POST['name'], $_POST['dir']) && checkFolder($_POST['dir']))
		{
			if (mkdir('../../' . $_POST['dir'] . str_replace(' ', '-', $_POST['name'])))
			{
				echo 'success';
			} else
			{
				echo 'fail';
			}
		}
	}
	exit;
}//Maxfilsize, wie sie in der php.ini steht rausfinden, um uploads mit dieser größe zu ermöglichen
elseif (isset($_GET['max_file_size']))
{
	echo file_upload_max_size();
	exit;
}//Header
else
{
	printHeader($lang->get('files_title'));
}
if (isset($_SESSION['user']))
{
	if (hasPerm('manage_files'))
	{
		?>
		<div class="main">
			<?php
			if (hasPerm('upload'))
			{
				?>
				<div id="dropFileArea" style="display: none">
					<div class="dropmsgcontainer">
						<div class="dropicon"><i class="icon-cloud-upload"></i></div>
						<div class="dropmsg">
							<?php echo $lang->get('files_drag_drop_area'); ?>
						</div>
					</div>
				</div>
				<button onclick="uploadFileBtn();" class="button" id="upButton"><i class="icon-cloud-upload"></i>&nbsp;&nbsp;<?php echo $lang->get('files_upload'); ?>
				</button>
				<?php
			}
			if (hasPerm('create_folder'))
			{
				?>
				<a onclick="newFolder();" class="button"><i
						class="icon-plus2"></i>&nbsp;&nbsp;<?php echo $lang->get('files_create_dir'); ?></a>
				<?php
			}
			if (hasPerm('delete'))
			{
				?><a onclick="delFolder();" class="button btn_del" id="delFolder" style="display:none;"><i
				class="icon-trash-o"></i>&nbsp;&nbsp;<?php echo $lang->get('files_delete_current_dir'); ?></a>
				<?php
			}
			?>
			<div style="display: none; vertical-align: middle;" id="msgDoContainer">
				<svg class="spinner" style="width:36px;height:35px;" viewBox="0 0 44 44">
					<circle class="path" cx="22" cy="22" r="20" fill="none" stroke-width="4"></circle>
				</svg>
				<div id="msgDo" style="display: block; margin-left: 45px; margin-top: -34px;"></div>
			</div>
			<form enctype="multipart/form-data">
				<input name="file" type="file" id="upbutton" class="upbtninput"/>
			</form>
			<div id="files">
				<div class="spinner-container">
					<svg class="spinner" style="width:41px;height:40px;" viewBox="0 0 44 44">
						<circle class="path" cx="22" cy="22" r="20" fill="none" stroke-width="4"></circle>
					</svg>
				</div>
			</div>
		</div>
		<script>
			var webUri = '<?php echo $MCONF['web_uri'];?>';
		</script>
		<!--<script src="<?php echo $MCONF['web_uri']; ?>apps/Files/js/jquery.history.js"></script>-->
		<script src="<?php echo $MCONF['web_uri']; ?>apps/Files/js/jquery.tablesorter.min.js"></script>
		<script src="<?php echo $MCONF['web_uri']; ?>apps/Files/js/jquery.tablesorter.staticrow.js"></script>
		<!--<script src="<?php echo $MCONF['web_uri']; ?>apps/Files/js/files.js"></script>-->
		<script>
			page('apps/Files/:var', function (ctx, next) {
				console.log(ctx);
				/*if(!ctx.init) {
					next();
					//window.location.replace('<?php echo $MCONF['home_uri']; ?>apps/Files/index.php#folder=');
					//location.reload();
				}*/
				//else {*/
					if (!window.location.hash) {
						var url = '';
					} else {
						var url = window.location.hash.replace('#folder=', '').replace(folder_curr, '');
					}
					var folder_curr = $('#displayUrl').html();
					getFiles(url);
				window.onbeforeunload = function() { return "Your work will be lost."; };
				//}
			});

			//getFiles('');


				function getFiles(folder) {
					var filesContent = '';
					//console.log(folder);
					$.ajax({
						url: webUri + 'apps/Files/index.php?ajax&json&folder=' + folder,
						dataType: 'json',
						success: function (data) {
							msgDo('&nbsp;');
							if (folder == '') folder = 'Files';
							if (data.displayUrl == 'Files/') {
								$('#delFolder').hide();
							}
							else {
								$('#delFolder').show();
								//History
								var title = $(document).find("title").text();
								//historyc(webUri + 'apps/Files/index.php#folder_view=' + data.displayUrl, title);
							}

							$('#files').html('<h3 id="displayUrl">' + data.displayUrl + '</h3><table id="filesList" width="100%"><thead><tr><th><?php echo $lang->get('files_filename'); ?></th><th><?php echo $lang->get('files_last_modified'); ?></th><th><?php echo $lang->get('files_filetype'); ?></th><th><?php echo $lang->get('files_filesize'); ?></th></tr></thead><tbody id="filesContent"></tbody></table><div id="extra"></div>');
							$.each(data.files, function () {
								if(this.type == 'Ordner') {
								 var name = '<a href="#folder=' + data.displayUrl + this.name + '"><i class="icon-' + this.icon + '"></i>  ' + this.name;
								 } else// onclick="openFile(\'' + this.name + '\', \'' + this.icon + '\', \'' + webUri + data.displayUrl + this.name + '\');"
								if (this.name == '..') {
									var name = '<a onclick="openFile(\'..\', \'' + this.icon + '\', \'' + webUri + data.displayUrl + this.name + '\');"><i class="icon-' + this.icon + '"></i>  ' + this.name;
								} else {
									var name = '<a onclick="openFile(\'' + this.name + '\', \'' + this.icon + '\', \'' + webUri + data.displayUrl + this.name + '\');"><i class="icon-' + this.icon + '"></i>  ' + this.name;
								}

								var static_folder = '';
								if (this.icon == 'folder2') {
									static_folder = ' class="static"';
								}
								//$('#filesContent').append('<tr><td>' + name + '</td><td>' + this.date + '</td><td>' + this.type + '</td><td>' + this.size + '</td></tr>');
								filesContent += '<tr' + static_folder + '><td>' + name + '</td><td>' + this.date + '</td><td>' + this.type + '</td><td>' + this.size + '</td></tr>';
							});
						},
						timeout: 5000,
						error: function (jqXHR, status, errorThrown) {
							console.log(errorThrown, status);
							var extra_info = status;
							if (errorThrown == 'Not Found') extra_info = '<?php echo $lang->get('files_folder_not_found'); ?>';
							showMsg('<?php echo $lang->get('files_error'); ?> (' + extra_info + ') <?php echo $lang->get('files_reload'); ?> ');
						}
					});

					//Tabelle Sortieren
					setTimeout(function () {
						$('#filesContent').html(filesContent);
						$("table").tablesorter({
							widgets: ['staticRow']
						});
						$("table").trigger("sorton", [[[0, 0]]]);
						$('#msgDoContainer').hide();
					}, 300);
				}

				//getFiles('');

				function openFile(file, type, url) {
					//console.log(file, type);
					if (type == 'folder2') {
						var folder_curr = $('#displayUrl').html();
						getFiles(folder_curr + file);
					}
					else {
						$('#extra').html('<div class="overlay" style="display:none;"><div class="window"><div class="head">' + file + '<a onclick="closeW();" class="closeMsg"><i class="icon-close"></i></a></div><div id="content"></div></div></div>');
						if (type == 'file-audio-o') {//Audio
							$('#content').html('<audio controls src="' + url + '" autoplay></audio>');
						}
						else if (type == 'file-movie-o') {//Video
							$('#content').html('<video controls src="' + url + '" autoplay></video>');
							//$('#content').html('<video class="video-js vjs-default-skin" width="640px" height="360px" controls preload="true" data-setup=\'{ "aspectRatio":"640:360" }\' src="' + url + '"></video>');
						}
						else if (type == 'file-picture-o') {//Bild oder Bild, alles was in einen iFrame passt
							$('#content').html('<div class="imgContaienr"><img src="' + url + '" alt="" id="imgZoom"/></div>');
							$('#content').css('margin-top', '32px');
							//Bild Zoom
							var img = document.getElementById('imgZoom');
							console.log()
							if (img.naturalHeight > 500 || img.naturalWidth > 984) {
								$('#imgZoom').click(function () {
									$('#imgZoom').toggleClass('zoomedin');
								});
							}
							else {
								$('#imgZoom').css('cursor', 'default');
							}
						}
						else if (type == 'file-pdf-o') {//Bild oder pdf, alles was in einen iFrame passt
							$('#content').html('<iframe src="' + url + '" width="102%" height="500" style="margin: 0px -10px;"></iframe>');
							$('#content').css('margin-top', '32px');
						}
						else if (type == 'file-archive-o' || type == 'file-powerpoint-o' || type == 'file-word-o' || type == 'file-excel-o') {//Nix, weil die Datei nicht angezeigt werden kann -> Archive, Word, pp

						}
						else {//Sonst text -> Wenn Sonst oder code
							$('#content').html('<div><textarea id="text"></textarea></div>');
							$('#text').load(url);
						}
						$('#content').append('<p><?php echo $lang->get('files_url'); ?>:<input type="text" class="select" value="' + url + '" onClick="this.select();"/></p><p><a href="' + webUri + 'apps/Files/index.php?dl=' + url + '" class="button" download="download"><i class="icon-download"></i>  <?php echo $lang->get('files_download'); ?></a>  <a onclick="deleteFile(\'' + $('#displayUrl').html() + file + '\');" class="button btn_del"><i class="icon-trash-o"></i>  <?php echo $lang->get('files_delete'); ?></a></p>');

						//uuund einbelnden
						$(".overlay").fadeIn(250);

						//Rumschieben
						//$('.window').drags();
					}
				}

				function closeW() {
					$(".overlay").fadeOut(200);
					setTimeout(function () {
						$('#extra').html('');
					}, 300);
				}

				function uploadFileBtn() {
					$("#upbutton").click();
				}

				//Neuen ordner
				function newFolder() {
					$('#extra').html('<div class="overlay" style="display:none;"><div class="window"><div class="head"><?php echo $lang->get('files_create_dir'); ?><a onclick="closeW();" class="closeMsg"><i class="icon-close"></i></a></div><div id="content"></div></div></div>');
					$('#content').append('<p><form onsubmit="newFolderSub();return false;"><input type="text" class="select" placeholder="<?php echo $lang->get('files_enter_name'); ?>" id="folderName" autofocus/><input type="submit" value="<?php echo $lang->get('files_create_dir'); ?>"/></form></p>');
					$('#folderName').focus();
					//uuund einbelnden
					$(".overlay").fadeIn(250);
				}

				function newFolderSub() {
					closeW();
					msgDo('<?php echo $lang->get('files_creating_new_folder'); ?>');
					var folder = $('#displayUrl').html();
					$.ajax({
						type: 'POST',
						url: webUri + 'apps/Files/index.php?newFolder',
						data: 'ajax&name=' + $('#folderName').val() + '&dir=' + folder,
						success: function (msg) {
							console.log(msg);
							if (msg == 'success') {
								showMsg('<?php echo $lang->get('files_create_dir_success'); ?>');
								getFiles(folder);
								$('#msgDoContainer').hide();
							}
							else {
								showMsg('<?php echo $lang->get('files_create_dir_fail'); ?>');
								$('#msgDoContainer').hide();
							}
						}
					});
					return false;
				}

				//Ordner löschen
				function delFolder() {
					var file = $('#displayUrl').html();
					$('#extra').html('<div class="overlay" style="display:none;"><div class="window window-confirm"><div class="head">"' + file + '" <?php echo $lang->get('files_delete_file'); ?><a onclick="closeW();" class="closeMsg"><i class="icon-close"></i></a></div><div id="content"><p><?php echo $lang->get('files_delete_dir_confirm'); ?></p><p><a onclick="confirmDeleteFile(\'' + file + '\', true);" class="button btn_del"><i class="icon-trash-o"></i>&nbsp;&nbsp;<?php echo $lang->get('files_delete'); ?></a><a onclick="closeW();" class="button"><?php echo $lang->get('files_abort'); ?></a></p></div></div></div>');
					//uuund einbelnden
					$(".overlay").fadeIn(250);
				}

				//Datei Löschen
				function deleteFile(file) {
					$('#extra').html('<div class="overlay" style="display:none;"><div class="window window-confirm"><div class="head">"' + file + '" <?php echo $lang->get('files_delete_file'); ?><a onclick="closeW();" class="closeMsg"><i class="icon-close"></i></a></div><div id="content"><p><?php echo $lang->get('files_delete_file_confirm'); ?></b></p><p><a onclick="confirmDeleteFile(\'' + file + '\', false);" class="button btn_del"><i class="icon-trash-o"></i>&nbsp;&nbsp;<?php echo $lang->get('files_delete'); ?></a><a onclick="closeW();" class="button"><?php echo $lang->get('files_abort'); ?></a></p></div></div></div>');
					//uuund einbelnden
					$(".overlay").fadeIn(250);
				}

				function confirmDeleteFile(file, isdir) {
					closeW();
					if (isdir) {
						msgDo('<?php echo $lang->get('files_deleting_folder'); ?>');
					}
					else {
						msgDo('<?php echo $lang->get('files_deleting_file'); ?>');
					}

					$.ajax({
						type: 'POST',
						url: webUri + 'apps/Files/index.php?del',
						data: 'file=' + file,
						success: function (msg) {
							console.log(msg);
							if (msg == 'success') {
								if (isdir) {
									showMsg('<?php echo $lang->get('files_delete_dir_success'); ?>');
									getFiles('');
								}
								else {
									showMsg('<?php echo $lang->get('files_delete_file_success'); ?>');
									getFiles($('#displayUrl').html());
								}
								$('#msgDoContainer').hide();
							}
							else {
								showMsg('<?php echo $lang->get('files_delete_error'); ?>');
								$('#msgDoContainer').hide();
							}
						}
					});
				}

				//Upload
				//Dropupload
				var obj = $(document);
				obj.on('dragenter', function (e) {
					e.stopPropagation();
					e.preventDefault();
					//$(this).css('border', '2px solid #0B85A1');
					console.log('dragenter');
					$('#dropFileArea').show();
				});

				obj.on('dragover', function (e) {
					e.stopPropagation();
					e.preventDefault();
					//console.log('dragover');
					//$('#dropFileArea').show();
				});

				obj.on('drop', function (e) {
					$('#dropFileArea').hide();
					e.preventDefault();
					var files = e.originalEvent.dataTransfer.files;

					//We need to send dropped files to Server
					handleFileUpload(files);
				});

				$('#upbutton').change(function () {
					var file = this.files[0];
					var formData = new FormData($('form')[0]);
					fileUpload(formData, file);
				});

				function handleFileUpload(files) {
					for (var i = 0; i < files.length; i++) {
						var fd = new FormData();
						fd.append('file', files[i]);

						fileUpload(fd, files[i]);
					}
				}

				function fileUpload(formData, file) {
					console.log(formData, file);
					//max filesize
					$.get(webUri + 'apps/Files/index.php?max_file_size', function (data) {
						if (file.size <= data) {

							var folder = $('#displayUrl').html();
							$.ajax({
								url: webUri + 'apps/Files/index.php?upload=' + folder,  //Server script to process data
								type: 'POST',
								xhr: function () {  // Custom XMLHttpRequest
									var myXhr = $.ajaxSettings.xhr();
									if (myXhr.upload) { // Check if upload property exists
										myXhr.upload.addEventListener('progress', progressHandlingFunction, false); // For handling the progress of the upload
									}
									return myXhr;
								},
								//Ajax events
								beforeSend: beforeSendHandler,
								success: completeHandler,
								error: errorHandler,
								// Form data
								data: formData,
								//Options to tell jQuery not to process data or worry about content-type.
								cache: false,
								contentType: false,
								processData: false
							});
						}
						else {
							showMsg('<?php echo $lang->get('files_too_large_file'); ?>');
						}
					});
				}

				function progressHandlingFunction(e) {
					if (e.lengthComputable) {
						var prozent = (e.loaded / e.total) * 100;
						msgDo('Datei wird Hochgeladen... [' + Math.round(prozent * 100) / 100 + '%]<div class="progbar_btm"></div>');
						$('.progbar_btm').css('width', prozent + '%');
						$('#msgDo').css('width', '250px');
						if (prozent == 100) {
							$('#msgDo').css('width', '232px');
							$('#msgDo').html('<?php echo $lang->get('files_processing'); ?>');
						}
					}
				}

				function beforeSendHandler() {
					console.log('ready');
					$('#upButton').prop("disabled", true);
				}

				function completeHandler(msg) {
					$('#msgDoContainer').hide();
					$('#upButton').prop("disabled", false);
					if (msg == 'success') {
						showMsg('<?php echo $lang->get('files_upload_finished'); ?>');
						var folder = $('#displayUrl').html();
						getFiles(folder);
					}
					else {
						showMsg('<?php echo $lang->get('files_upload_failed'); ?>');
					}
				}

				function errorHandler() {
					$('#progressbox').hide();
					$('#msgDoContainer').hide();
					$('#upButton').prop("disabled", false);
					showMsg('<?php echo $lang->get('files_upload_failed'); ?>');
				}

				//MsgDOO
				function msgDo(msg) {
					$('#msgDoContainer').css('display', 'inline-block');
					$('#msgDo').html(msg);
				}

				//history
				/*function historyc(url, title) {
				 //Histroy state
				 var State = History.getState(), $log = $('#log');
				 History.Adapter.bind(window, 'statechange', function () {
				 var State = History.getState();
				 });
				 History.pushState({state: 1, rand: Math.random()}, title, url);
				 }

				 //Wenn zurücktaste gedrückt
				 window.addEventListener('popstate', function (event) {
				 //Histroy state
				 var State = History.getState(), $log = $('#log');
				 History.Adapter.bind(window, 'statechange', function () {
				 var State = History.getState();
				 });
				 var title = State.title;

				 //Seite wieder zusammenbauen
				 var curr = $('#displayUrl').html();
				 if (curr == 'Files/') {
				 History.pushState({state: 1, rand: Math.random()}, title, webUri + 'apps/Files/index.php');
				 getFiles('');
				 }
				 else {
				 History.pushState({
				 state: 1,
				 rand: Math.random()
				 }, title, webUri + 'apps/Files/index.php#folder_view=' + curr);
				 getFiles(curr + '..');
				 }
				 });*/
		</script>
		<!--<script src='<?php echo $MCONF['web_uri']; ?>js/video.js'></script>-->
		<?php
	} else
	{
		echo msg('info', 'Fehlende Berechtigung. {back}');
	}
	require_once '../../inc/footer.php';
}
?>