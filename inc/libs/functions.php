<?php
//msg
function msg($typ, $msg = null)
{
	$msg = str_replace('{back}', '<a onclick="history.back(-1)">Zurück</a>', $msg);
	if ($typ == 'succes' || $typ == 'success')
	{
		if (!isset($msg)) $msg = 'Die Operation wurde erfolgreich durchgeführt.';
		return '<div class="message-success">' . $msg . '</div>';
	} elseif ($typ == 'fail')
	{
		if (!isset($msg)) $msg = 'Fehler. ' . $GLOBALS['texte'][2];
		return '<div class="message-fail">' . $msg . '</div>';
	} else
	{
		return '<div class="message-info">' . $msg . '</div>';
	}
}

//datum rückwärts
function datum_ruck($timestamp)
{
	$diff = time() - $timestamp;
	if ($diff < 30)
	{
		return 'vor kurzem';
	}
	if ($diff < 60)
	{
		return 'vor weniger als einer Minute';
	}
	if ($diff < 3600)
	{
		return 'vor ' . round(($diff / 60)) . ' Minuten';
	}
	if ($diff < 86400)
	{
		return 'vor ' . round((($diff / 60) / 60)) . ' Stunden';
	}
	if ($diff < 2592000)
	{
		return 'vor ' . round(((($diff / 60) / 60) / 24)) . ' Tagen';
	}
	if ($diff < 31104000)
	{
		return 'vor ' . round((((($diff / 60) / 60) / 24) / 12)) . ' Monaten';
	}
	if ($diff > 30693600)
	{
		return 'vor ' . round(($diff / 31536000)) . ' Jahren';
	}

	return $diff;
}

//nichtleeren ordner löschen
function rrmdir($dir)
{
	if (is_dir($dir))
	{
		$objects = scandir($dir);
		foreach ($objects as $object)
		{
			if ($object != "." && $object != "..")
			{
				if (filetype($dir . "/" . $object) == "dir") rrmdir($dir . "/" . $object); else unlink($dir . "/" . $object);
			}
		}
		reset($objects);
		return rmdir($dir);
	}
	else
	{
		return false;
	}
}

//Simple Image
class SimpleImage
{

	var $image;
	var $image_type;

	function load($filename)
	{

		$image_info = getimagesize($filename);
		$this->image_type = $image_info[2];
		if ($this->image_type == IMAGETYPE_JPEG)
		{

			$this->image = imagecreatefromjpeg($filename);
		} elseif ($this->image_type == IMAGETYPE_GIF)
		{

			$this->image = imagecreatefromgif($filename);
		} elseif ($this->image_type == IMAGETYPE_PNG)
		{

			$this->image = imagecreatefrompng($filename);
		}
	}

	function save($filename, $image_type = IMAGETYPE_JPEG, $compression = 95, $permissions = null)
	{

		if ($image_type == IMAGETYPE_JPEG)
		{
			imagejpeg($this->image, $filename, $compression);
		} elseif ($image_type == IMAGETYPE_GIF)
		{

			imagegif($this->image, $filename);
		} elseif ($image_type == IMAGETYPE_PNG)
		{

			imagepng($this->image, $filename);
		}
		if ($permissions != null)
		{

			chmod($filename, $permissions);
		}
	}

	function output($image_type = IMAGETYPE_JPEG)
	{

		if ($image_type == IMAGETYPE_JPEG)
		{
			imagejpeg($this->image);
		} elseif ($image_type == IMAGETYPE_GIF)
		{

			imagegif($this->image);
		} elseif ($image_type == IMAGETYPE_PNG)
		{

			imagepng($this->image);
		}
	}

	function getWidth()
	{

		return imagesx($this->image);
	}

	function getHeight()
	{

		return imagesy($this->image);
	}

	function resizeToHeight($height)
	{

		$ratio = $height / $this->getHeight();
		$width = $this->getWidth() * $ratio;
		$this->resize($width, $height);
	}

	function resizeToWidth($width)
	{
		$ratio = $width / $this->getWidth();
		$height = $this->getheight() * $ratio;
		$this->resize($width, $height);
	}

	function scale($scale)
	{
		$width = $this->getWidth() * $scale / 100;
		$height = $this->getheight() * $scale / 100;
		$this->resize($width, $height);
	}

	function resize($width, $height)
	{
		$new_image = imagecreatetruecolor($width, $height);
		imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
		$this->image = $new_image;
	}
}

//Tinymce
function tinymce($css = '../../css/tinymce.css', $edit_area = '#editor')
{
	echo '<script src="' . $GLOBALS['MCONF']['web_uri'] . 'admin/assets/js/tinymce/tinymce.min.js"></script>
		<script>
			tinymce.init({
				selector: "'.$edit_area.'",
				auto_focus: "editor",
				theme: "modern",
				skin: "light",
				menubar: false,
				plugins: [
					"advlist autolink lists link image charmap print preview hr anchor",
					"searchreplace wordcount visualblocks visualchars code fullscreen",
					"media nonbreaking save table contextmenu directionality",
					"template paste textcolor colorpicker textpattern"
				],
				toolbar1: "newdocument fullpage | undo redo | cut copy paste | searchreplace | print fullscreen preview code charmap | outdent indent",
				toolbar2: "table | hr removeformat | ltr rtl | spellchecker | visualchars visualblocks nonbreaking  | bullist numlist blockquote | link unlink anchor image media",
				toolbar3: "formatselect fontsizeselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | forecolor backcolor | subscript superscript",

				image_advtab: true,
				language: "de",
				content_css: "' . $GLOBALS['MCONF']['tinymce_css'] . '",
				relative_urls: false,
				height : 500,
				width: "100%"
			});
		</script>';
}

function random($lange)
{
	$zahlen_und_buchstaben = array('a', 'A', 'b', 'B', 'c', 'C', 'd', 'D', 'e', 'E', 'f', 'F', 'G', 'g', 'h', 'H', 'i', 'I', 'j', 'J', 'k', 'K', 'l', 'L', 'm', 'M', 'n', 'N', 'o', 'O', 'p', 'P', 'q', 'Q', 'r', 'R', 's', 'S', 't', 'T', 'u', 'U', 'v', 'V', 'w', 'W', 'x', 'X', 'y', 'Y', 'z', 'Z', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0');
	for ($i = 0, $random = ''; $i < $lange; $i++)
	{
		$random .= $zahlen_und_buchstaben[array_rand($zahlen_und_buchstaben)];
	}
	return $random;
}

function calc_filesize($bytes)
{
	$symbol = ' B ';
	if ($bytes > 1024)
	{
		$symbol = ' KB';
		$bytes /= 1024;
	}
	if ($bytes > 1024)
	{
		$symbol = ' MB';
		$bytes /= 1024;
	}
	if ($bytes > 1024)
	{
		$symbol = ' GB';
		$bytes /= 1024;
	}
	$bytes = round($bytes, 2);
	return $bytes . $symbol;
}

//User eingeloggt?
function is_loggedin()
{
	if(isset($_SESSION['user'], $_SESSION['token']))
	{

		$GLOBALS['db']->setCol('system_loggedin');
		$GLOBALS['db']->data['token'] = $_SESSION['token'];
		$GLOBALS['db']->data['user'] = $_SESSION['userid'];
		$GLOBALS['db']->get();
		if(isset($GLOBALS['db']->data[0]['token']))
		{
			$token = $GLOBALS['db']->data[0]['token'];
			$GLOBALS['db']->clear();
			if ($token != '')
			{
				if ($token == $_SESSION['token'])
				{
					//Session nach 30 min inaktivität löschen
					if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800))
					{
						$GLOBALS['db']->setCol('system_loggedin');
						$GLOBALS['db']->data['token'] = $_SESSION['token'];
						$GLOBALS['db']->data['user'] = $_SESSION['userid'];
						$GLOBALS['db']->delete();

						// last request was more than 30 minutes ago
						session_unset();     // unset $_SESSION variable for the run-time
						session_destroy();   // destroy session data in storage

						return false;
					}
					else
					{
						$_SESSION['LAST_ACTIVITY'] = time();
						return true;
					}
				}
				else
				{
					return false;
				}
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	else
	{
		return false;
	}
}

//Print header
function printHeader($title)
{
	if (!is_loggedin())
	{
		$title = $GLOBALS['lang']->get('login');
	}

	//<link rel="stylesheet prefetch" href="' . $GLOBALS['MCONF']['web_uri'] . 'css/video-js.css" type="text/css"/>
	echo '<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>' . $title . ' | '.$GLOBALS['lang']->get('admin_title').' | ' . $GLOBALS['MCONF']['title'] . '</title>
    <link rel="shourtcut icon" href="' . $GLOBALS['MCONF']['web_uri'] . 'favicon.ico"/>
    <link rel="stylesheet" href="' . $GLOBALS['MCONF']['web_uri'] . 'admin/assets/admin.css" type="text/css"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
	<script src="' . $GLOBALS['MCONF']['web_uri'] . 'admin/assets/js/jquery.min.js"></script>
</head>
<body>';
	if (is_loggedin())
	{
		echo '<header>
    ' . $title . '
    <div class="options" tabindex="0">
    	<input type="checkbox" id="options_menu" />
    	<label for="options_menu">
			<p><span class="usr_info"><img src="http://www.gravatar.com/avatar/' . md5(strtolower(trim($_SESSION['mail']))) . '?s=40&d=mm" alt=""/>' . $_SESSION['user'] . '</span>  <span class="icon-"></span></p>
			<ul>
				<li><a href="' . $GLOBALS['MCONF']['web_uri'] . 'admin/user_settings.php"><span class="icon-gear"></span> '.$GLOBALS['lang']->get('settings').'</a></li>
				<li><a href="' . $GLOBALS['MCONF']['web_uri'] . 'admin/logout.php"><span class="icon-exit"></span> '.$GLOBALS['lang']->get('logout').'</a></li>
			</ul>
    	</label>
    </div>
</header>
<label for="show-menu" class="show-menu"><i class="icon-navicon"></i> </label>
<input type="checkbox" id="show-menu" role="button">
<nav>
    <header>
    	<a href="' . $GLOBALS['MCONF']['web_uri'] . 'admin/"><img src="' . $GLOBALS['MCONF']['web_uri'] . 'admin/assets/Logo.svg" alt="Mowie CMS"/></a>
    </header>
    <ul><li><a href="' . $GLOBALS['MCONF']['web_uri'] . '" target="_blank"><i class="icon-external-link"></i>  '.$GLOBALS['lang']->get('main_page').'</a></li>
    <li';
		if ($title == $GLOBALS['lang']->get('dashboard_title')) echo ' class="active"';
		echo '><a href="' . $GLOBALS['MCONF']['web_uri'] . 'admin/"><i class="icon-dashboard"></i>  '.$GLOBALS['lang']->get('dashboard').'</a></li>';

		if (hasPerm('manage_system', 'System'))
		{
			echo '<li';
			if ($title == $GLOBALS['lang']->get('general_config')) echo ' class="active"';
			echo '><a href="'.$GLOBALS['MCONF']['web_uri'].'admin/general_config.php"><i
				class="icon-sliders"></i>
			'.$GLOBALS['lang']->get('general_config').'</a></li>';
		}
		if (hasPerm('manage_admins', 'System'))
		{
			?>
			<li<?php
			if ($title == $GLOBALS['lang']->get('admins_list') || $title == $GLOBALS['lang']->get('admins_create_new') || $title == $GLOBALS['lang']->get('admins_groups') || $title == $GLOBALS['lang']->get('admins_permissions')) echo ' class="active"';
			?>><a href="<?php echo $GLOBALS['MCONF']['web_uri']; ?>admin/users.php"><i class="icon-users2"></i>
					<?php echo $GLOBALS['lang']->get('admins_title');?><i class="icon-chevron-right sub_menu"></i></a>
				<ul>
					<li><a href="<?php echo $GLOBALS['MCONF']['web_uri']; ?>admin/users.php"<?php
						if ($title == $GLOBALS['lang']->get('admins_list')) echo ' class="now"';
						?>><i class="icon-users2"></i> <?php echo $GLOBALS['lang']->get('admins_list');?></a></li>
					<li><a href="<?php echo $GLOBALS['MCONF']['web_uri']; ?>admin/roles.php"<?php
						if ($title == $GLOBALS['lang']->get('admins_groups')) echo ' class="now"';
						?>><i class="icon-group"></i> <?php echo $GLOBALS['lang']->get('admins_groups');?></a></li>
					<li><a href="<?php echo $GLOBALS['MCONF']['web_uri']; ?>admin/permissions.php"<?php
						if ($title == $GLOBALS['lang']->get('admins_permissions')) echo ' class="now"';
						?>><i class="icon-group"></i> <?php echo $GLOBALS['lang']->get('admins_permissions');?></a></li>
					<li><a href="<?php echo $GLOBALS['MCONF']['web_uri']; ?>admin/new_user.php"<?php
						if ($title == $GLOBALS['lang']->get('admins_create_new')) echo ' class="now"';
						?>><i class="icon-user-plus2"></i> <?php echo $GLOBALS['lang']->get('admins_create_new');?></a></li>
				</ul>
			</li>
			<?php
		}
		$moduluri = '../apps/';
		$pos = strpos($_SERVER['REQUEST_URI'], '/apps/');
		if ($pos !== false)
		{
			$moduluri = '../';
			$rel = explode('/', str_replace($GLOBALS['MCONF']['home_uri'].'apps/', '', $_SERVER['REQUEST_URI']));
			$count = count($rel);
			$count = $count -1;

			$i = 1;
			while($i<$count)
			{
				$moduluri .= '../';
				$i++;
			}
		}

		if ($handle = opendir($moduluri))
		{
			while (false !== ($mod = readdir($handle)))
			{
				if ($mod != "." && $mod != ".." && is_dir($moduluri . $mod))
				{
					require $moduluri . $mod . '/config.php';
					if ($_CONF['menu_top'] !== '')
					{
						$now = '';
						if (strpos($_SERVER['REQUEST_URI'], $mod) !== false)
						{
							$now = ' class="active"';
						}

						if (array_key_exists('menu_top', $_CONF['menu']))
						{
							echo "\n" . '<li' . $now . '><a href="' . $moduluri . $mod . '/' . $_CONF['menu']['menu_top'] . '">' . $_CONF['menu_top'] . '</a>' . "\n";
						} else
						{
							$first_itm = array_keys($_CONF['menu']);
							echo "\n" . '<li' . $now . '><a href="' . $moduluri . $mod . '/' . $_CONF['menu'][$first_itm[0]] . '">' . $_CONF['menu_top'] . '<i class="icon-chevron-right sub_menu"></i></a>' . "\n" . '<ul>';
							foreach ($_CONF['menu'] as $mod_name_anz => $mod_name_url)
							{
								$now = '';
								if (strpos($_SERVER['REQUEST_URI'], $mod_name_url) !== false && strpos($_SERVER['REQUEST_URI'], $mod) !== false)
								{
									$now = ' class="now"';
								}
								echo '<li><a href="' . $moduluri . $mod . '/' . $mod_name_url . '"' . $now . '>' . $mod_name_anz . '</a></li>' . "\n";
							}
							echo '</ul></li>' . "\n";
						}
						$_CONF['menu_top'] = '';
					}
				}
			}
			closedir($handle);
		}
		echo '</ul>
</nav>
<label for="show-menu" class="mobile-overlay"></label>
<div style="height: 40px;"></div>';
	}
	else
	{
		?>
		<div class="login_wrapper">
			<img src="<?php echo $GLOBALS['MCONF']['web_uri']; ?>admin/assets/Logo.svg" alt="Mowie"/>
			<div class="login_container">
				<h1><?php echo $GLOBALS['lang']->get('login');?></h1>
				<form action="<?php echo $GLOBALS['MCONF']['web_uri']; ?>admin/login.php" method="post"
					  id="login">
					<input type="text" placeholder="<?php echo $GLOBALS['lang']->get('username');?>" id="username" autofocus/><br/>
					<input type="password" placeholder="<?php echo $GLOBALS['lang']->get('password');?>" id="pw"/><br/>
					<div id="2faContainer" style="display: none">
						<input type="text" id="2fa" autocomplete="off" placeholder="<?php echo $GLOBALS['lang']->get('2fa_code');?>"><br/>
					</div>
					<input type="submit" value="<?php echo $GLOBALS['lang']->get('login');?>"/>
				</form>
				<div id="msg"></div>
			</div>
			<p style="text-align: center;color: #fff;text-shadow: 1px 1px 1px #555;">&copy; 2016 Mowie CMS</p>
		</div>
		<script>
			$("#login").submit(function () {
				if ($('#username').val() == '' || $('#pw').val() == '') {
					$('#msg').html('<?php echo $GLOBALS['lang']->get('all_fields');?>');
				}
				else {
					$('#msg').html('<div class="spinner-container"><svg class="spinner" style="width:41px;height:40px;" viewBox="0 0 44 44"><circle class="path" cx="22" cy="22" r="20" fill="none" stroke-width="4"></circle> </svg> </div>');

					$.ajax({
						type: 'POST',
						url: '<?php echo $GLOBALS['MCONF']['web_uri']; ?>admin/login.php',
						data: "username=" + $('#username').val() + "&pw=" + $('#pw').val() + "&2fa=" + $('#2fa').val(),
						success: function (msg) {
							console.log(msg);
							if (msg == 'success') {
								location.reload();
							}
							else if(msg == '2fa') {
								$('#2faContainer').show();
								$('#msg').hide();
							}
							else if(msg == '2fafail') {
								$('#msg').html('<div class="message-fail"><?php echo $GLOBALS['lang']->get('error_2fa');?></div>');
							}
							else {
								$('#msg').html('<div class="message-fail"><?php echo $GLOBALS['lang']->get('wrong_username_or_pass');?></div>');
							}
						}
					});
				}
				return false;
			});
		</script>
		</body>
		</html><?php
		exit;
	}
}

// Returns a file size limit in bytes based on the PHP upload_max_filesize
// and post_max_size
function file_upload_max_size()
{
	static $max_size = -1;

	if ($max_size < 0) {
		// Start with post_max_size.
		$max_size = parse_size(ini_get('post_max_size'));

		if($max_size == 0)
		{
			$max_size = parse_size(ini_get('upload_max_filesize'));
		}
		else
		{
			// If upload_max_size is less, then reduce. Except if upload_max_size is
			// zero, which indicates no limit.
			$upload_max = parse_size(ini_get('upload_max_filesize'));
			if ($upload_max > 0 && $upload_max < $max_size) {
				$max_size = $upload_max;
			}
		}
	}
	return $max_size;
}

function parse_size($size)
{
	$unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
	$size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
	if ($unit)
	{
		// Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
		return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
	} else
	{
		return round($size);
	}
}

//Permissions
function hasPerm($permkey, $scope = '')
{
	if (isset($_SESSION['userid']))
	{
		if($_SESSION['lvl'] == 1)
		{
			return true;
		}
		else
		{
			$scopeUri = '';
			$pos = strpos($_SERVER['REQUEST_URI'], '/apps/');
			if ($pos !== false)
			{
				//$scopeUri = '../';
				$rel = explode('/', str_replace($GLOBALS['MCONF']['home_uri'] . 'apps/', '', $_SERVER['REQUEST_URI']));
				$count = count($rel);
				$count = $count - 1;

				$i = 1;
				while ($i < $count)
				{
					$scopeUri .= '../';
					$i++;
				}

			}

			if($scope == 'System') $scopeUri .= '../admin/';

			//echo $moduluri;

			if (file_exists($scopeUri.'permissions.json'))
			{
				$perms = json_decode(file_get_contents($scopeUri.'permissions.json'), true);
				$permsTotal = [];
				foreach ($perms['permissions'] as $perm)
				{
					$permsTotal[] = $perm['key'];
				}

				if (in_array($permkey, $permsTotal))
				{
					$GLOBALS['db']->setCol('system_roles');
					$GLOBALS['db']->data['id'] = $_SESSION['lvl'];
					$GLOBALS['db']->get();
					if (isset($GLOBALS['db']->data[0]))
					{
						$perms = json_decode($GLOBALS['db']->data[0]['permissions'], true);

						$perms_f = [];
						$pos = strpos($_SERVER['REQUEST_URI'], '/apps/');
						if ($pos !== false)
						{
							require $scopeUri . 'config.php';
							if(array_key_exists($_CONF['mod_name'], $perms)) $perms_f = $perms[$_CONF['mod_name']];
						}
						else
						{
							$perms_f = $perms['System'];
						}
						if (in_array($permkey, $perms_f))
						{
							return true;
						}
						else
						{
							return false;
						}
					} else
					{
						return false;
					}
				} else
				{
					return false;
				}
			} else
			{
				return false;
			}
		}
	}
	else
	{
		return false;
	}
}

//Get Username based on its ID
function getUserByID($userid)
{
	if($userid == $_SESSION['userid'])
	{
		return $_SESSION['user'];
	}
	else
	{
		$GLOBALS['db']->setCol('system_admins');
		$GLOBALS['db']->data['id'] = $userid;
		$GLOBALS['db']->get();
		if (isset($GLOBALS['db']->data[0]))
		{
			return $GLOBALS['db']->data[0]['username'];
		}
		else
		{
			return $userid;
		}
	}
}

//Test mail
function smail($mailaddr, $subject, $message, $header)
{
	$mail = 'To: '.$mailaddr."\n";
	$mail .= 'Subject: '.$subject."\n";
	$mail .= $header."\n\n";
	$mail .= $message;

	if(file_put_contents('mail_'.$mailaddr.'_'.time().'.txt', $mail))
	{
		return true;
	}
	else
	{
		return false;
	}
}
?>