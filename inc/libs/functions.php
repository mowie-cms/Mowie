<?php
//msg
function msg($type, $msg = null)
{
	$out = [];
	$out['msg'] = str_replace('{back}', '<a onclick="history.back(-1)">'.$GLOBALS['lang']->get('back').'</a>', $msg);
	if ($type === 'success')
	{
		$out['type'] = 'success';
	} elseif ($type === 'fail' || $type === 'error')
	{
	    if(!isset($msg)) $out['msg'] = $GLOBALS['lang']->get('error_occured');
		$out['type'] = 'fail';
	} else
	{
		$out['type'] = 'info';
	}

	if (isset($_GET['json']))
	{
		header('Content-Type: application/json');
		return json_encode($out);

	} else
	{
		return '<div class="message">
        <div class="icon-' . $out['type'] . '"></div>
        <div class="msg">' . $out['msg'] . '</div>
        <div style="clear: both"></div>
    </div>';
	}
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
	} else
	{
		return false;
	}
}

//Tinymce
function tinymce($edit_area = '#editor')
{
	if (!isset($_GET['json']))
	{
		echo '<script src="' . $GLOBALS['MCONF']['web_uri'] . 'admin/assets/js/tinymce/tinymce.min.js"></script>
		<script>
			tinymce.init({
				selector: "' . $edit_area . '",
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
}

function random($size)
{
	$zahlen_und_buchstaben = array('a', 'A', 'b', 'B', 'c', 'C', 'd', 'D', 'e', 'E', 'f', 'F', 'G', 'g', 'h', 'H', 'i', 'I', 'j', 'J', 'k', 'K', 'l', 'L', 'm', 'M', 'n', 'N', 'o', 'O', 'p', 'P', 'q', 'Q', 'r', 'R', 's', 'S', 't', 'T', 'u', 'U', 'v', 'V', 'w', 'W', 'x', 'X', 'y', 'Y', 'z', 'Z', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0');
	for ($i = 0, $random = ''; $i < $size; $i++)
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
    global $db;
	if (isset($_SESSION['user'], $_SESSION['token']))
	{
		$db->setCol('system_loggedin');
		$db->data['token'] = $_SESSION['token'];
		$db->data['user'] = $_SESSION['userid'];
		$db->get();
		if (isset($db->data[0]['token']))
		{
			$token = $db->data[0]['token'];
			$db->clear();
			if ($token != '')
			{
				if ($token == $_SESSION['token'])
				{
					//last request was more than 30 minutes ago
					if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800))
					{
						$db->setCol('system_loggedin');
						$db->data['token'] = $_SESSION['token'];
						$db->delete();

						session_unset();     // unset $_SESSION variable for the run-time
						session_destroy();   // destroy session data in storage

						return false;
					} else
					{
						$_SESSION['LAST_ACTIVITY'] = time();
						return true;
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
	} else
	{
		return false;
	}
}

//Print header
function printHeader($title)
{

	//Globals
	global $apps, $MCONF, $lang;

	if (!is_loggedin())
	{
		$title = $lang->get('login');
	}

	//Get Current App dir
    $appDir = '';
    $paths = explode('/', str_replace($MCONF['home_uri'], '', $_SERVER['REQUEST_URI']));
    if($paths[0] == 'apps' && isset($paths[1]))	$appDir = $paths[1];

	if (isset($_REQUEST['direct'])) //Send just the Content of the current page
	{
		if (!is_loggedin())
		{
			header("Content-Type: text/plain");
			echo 'Login First.';
			exit;
		}
	} elseif (isset($_GET['title'])) //Send the Title to the Router
	{
		if (!is_loggedin())
		{
			header("Content-Type: text/plain");
			echo 'Login First.';
			exit;
		} else
		{
			header("Content-Type: text/plain");
			echo $title;
			exit;
		}
	} elseif (isset($_GET['css'])) //Send extra CSS Files for Apps
	{
		header('Content-Type: application/json');

		$appInfo = $apps->getAppByPath($appDir);
		if (isset($appInfo['css']))
		{
			echo json_encode(['css' => true, 'css_files' => $appInfo['css'], 'fullUri' => $MCONF['web_uri'] . 'apps/' . $appDir . '/']);
		} else
		{
			echo json_encode(['css' => false]);
		}

		exit;
	} else
	{
	    //Get apps-folder location
		$appsPath = '../apps/';
		$i = 0; // To prevent endless searching
		while(!file_exists($appsPath) && $i < 31)
		{
			$appsPath = '../'.$appsPath;
			$i++;
		}

		//Get Apps, build app-menu (We're building the menu here and output it later because we want the name of the current app to use it for App-CSS)
		$appmenu = '';
		$appsLoop = $apps->getApps();
		$appCurr = '';
		foreach ($appsLoop as $app => $appconf)
		{
			if (isset($appconf['menu_top']) && $appconf['menu_top'] !== '')
			{
				//Build the menu
				$now = '';
				if (strpos($_SERVER['REQUEST_URI'], $app) !== false)
				{
					$now = ' class="active"';
					$appCurr = $app;
				}

				if (array_key_exists('menu_top', $appconf['menu']))
				{
					$appmenu .= "\n" . '<li' . $now . ' id="mw-menu-apps-' . $app . '-top"><a href="' . $MCONF['home_uri'] . 'apps/' . $appconf['app_path'] . '/' . $appconf['menu']['menu_top'] . '">' . $appconf['menu_top'] . '</a>' . "\n";
				} else
				{
					$first_itm = array_keys($appconf['menu']);
					$appmenu .= "\n" . '<li' . $now . ' id="mw-menu-apps-' . $app . '-top"><a href="' . $MCONF['home_uri'] . 'apps/' . $appconf['app_path'] . '/' . $appconf['menu'][$first_itm[0]] . '">' . $appconf['menu_top'] . '<i class="fa fa-chevron-right sub_menu"></i></a>' . "\n" . '<ul>';
					foreach ($appconf['menu'] as $app_name => $app_name_url)
					{
						$now = '';
						if (strpos($_SERVER['REQUEST_URI'], $app_name_url) !== false && strpos($_SERVER['REQUEST_URI'], $app) !== false)
						{
							$now = ' class="active"';
						}
						$appmenu .= '<li' . $now . ' id="mw-menu-apps-' . $app . '-' . str_replace(['.php', '?', '&'], '', str_replace('/', '-', $app_name_url)) . '"><a href="' . $MCONF['home_uri'] . 'apps/' . $appconf['app_path'] . '/' . $app_name_url . '">' . $app_name . '</a></li>' . "\n";
					}
					$appmenu .= '</ul></li>' . "\n";
				}
				$appconf['menu_top'] = '';
			}
		}

		//<link rel="stylesheet prefetch" href="' . $MCONF['web_uri'] . 'css/video-js.css" type="text/css"/>
		echo '<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>' . $title . ' | ' . $lang->get('admin_title') . ' | ' . $MCONF['title'] . '</title>
    <link rel="shourtcut icon" href="' . $MCONF['web_uri'] . 'favicon.ico"/>
    <link rel="stylesheet" href="' . $MCONF['web_uri'] . 'admin/assets/bootstrap.min.css" type="text/css"/>
    <link rel="stylesheet" href="' . $MCONF['web_uri'] . 'admin/assets/admin.css" type="text/css"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
	<script src="' . $MCONF['web_uri'] . 'admin/assets/js/jquery.min.js"></script>
	
	<script src="' . $MCONF['web_uri'] . 'admin/assets/js/page.js"></script>
	<script src="' . $MCONF['web_uri'] . 'admin/assets/js/page.bodyparser.js"></script>
	<script>
	page.base(\'' . $MCONF['home_uri'] . '\');
	</script>
';
		//Get App-CSS and output it
        $app_css = $apps->getApp($appCurr);
		if (isset($app_css['css']))
		{
			foreach ($app_css['css'] as $style)
			{
				echo '	<link rel="stylesheet" href="' . $MCONF['web_uri'] . 'apps/' . $app_css['app_path'] . '/' . $style . '" type="text/css"/>';
			}
		}

		echo '
</head>
<body>';
		if (is_loggedin())
		{
			echo '<div class="toploading"></div> <header>
    <span id="title">' . $title . '</span>
    <div style="float: right;">
    <div class="stream"><a onclick="showStream();"><i class="fa fa-bell-o" aria-hidden="true"></i></a><div id="streamContent"><div class="spinner-container" style="margin: 12px auto 0;">
						<svg class="spinner" style="width:41px;height:40px;" viewBox="0 0 44 44">
							<circle class="path" cx="22" cy="22" r="20" fill="none" stroke-width="4"></circle>
						</svg>
					</div></div></div>
    <div class="options" tabindex="0">
    	<input type="checkbox" id="options_menu" />
    	<label for="options_menu">
			<p><span class="usr_info">';
			//<img src="http://www.gravatar.com/avatar/' . md5(strtolower(trim($_SESSION['mail']))) . '?s=40&d=mm" alt=""/>
			echo '<img src="http://www.gravatar.com/avatar/' . md5(strtolower(trim($_SESSION['mail']))) . '?s=40&d=mm" alt=""/>  ' . $_SESSION['user'] . '</span>  <span class="fa fa-chevron-down"></span></p>
			<ul>
				<li><a href="' . $MCONF['web_uri'] . 'admin/user_settings.php"><span class="fa fa-gear"></span> ' . $lang->get('settings') . '</a></li>
				<li><a href="' . $MCONF['web_uri'] . 'admin/logout.php" rel="external"><span class="fa fa-sign-out"></span> ' . $lang->get('logout') . '</a></li>
			</ul>
    	</label>
    </div>
    </div>
</header>
<label for="show-menu" class="show-menu"><i class="fa fa-bars"></i> </label>
<input type="checkbox" id="show-menu" role="button">
<nav id="topnav">
    <header>
    	<a href="' . $MCONF['home_uri'] . 'admin/"><img src="' . $MCONF['web_uri'] . 'admin/assets/Logo.svg" alt="Mowie CMS"/></a>
    </header>
    <ul id="menulist"><li><a href="' . $MCONF['home_uri'] . '" target="_blank"><i class="fa fa-external-link"></i>  ' . $lang->get('main_page') . '</a></li>
    <li';
			if ($title == $lang->get('dashboard_title')) echo ' class="active"';
			echo ' id="mw-menu-admin-"><a href="' . $MCONF['home_uri'] . 'admin/"><i class="fa fa-dashboard"></i>  ' . $lang->get('dashboard') . '</a></li>';

			if (hasPerm('manage_system', 'System'))
			{
				echo '<li';
				if ($title == $lang->get('general_config')) echo ' class="active"';
				echo ' id="mw-menu-admin-general_config"><a href="' . $MCONF['home_uri'] . 'admin/general_config.php"><i
				class="fa fa-sliders"></i>
			' . $lang->get('general_config') . '</a></li>';
			}
			if (hasPerm('manage_admins', 'System'))
			{
				?>
                <li<?php
				if ($title == $lang->get('admins_list') || $title == $lang->get('admins_create_new') || $title == $lang->get('admins_groups') || $title == $lang->get('admins_permissions')) echo ' class="active"';
				?> id="mw-menu-admin-users-top"><a href="<?php echo $MCONF['home_uri']; ?>admin/users.php"><i
                                class="fa fa-group"></i>
						<?php echo $lang->get('admins_title'); ?><i class="fa fa-chevron-right sub_menu"></i></a>
                    <ul>
                        <li id="mw-menu-admin-users"><a
                                    href="<?php echo $MCONF['home_uri']; ?>admin/users.php"<?php
							if ($title == $lang->get('admins_list')) echo ' class="active"';
							?>><i class="fa fa-id-card-o"></i> <?php echo $lang->get('admins_list'); ?></a></li>
                        <li id="mw-menu-admin-roles"><a
                                    href="<?php echo $MCONF['home_uri']; ?>admin/roles.php"<?php
							if ($title == $lang->get('admins_groups')) echo ' class="active"';
							?>><i class="fa fa-group"></i> <?php echo $lang->get('admins_groups'); ?></a>
                        </li>
                        <li id="mw-menu-admin-permissions"><a
                                    href="<?php echo $MCONF['home_uri']; ?>admin/permissions.php"<?php
							if ($title == $lang->get('admins_permissions')) echo ' class="active"';
							?>><i class="fa fa-exchange"></i> <?php echo $lang->get('admins_permissions'); ?>
                            </a>
                        </li>
                        <li id="mw-menu-admin-new_user"><a
                                    href="<?php echo $MCONF['home_uri']; ?>admin/new_user.php"<?php
							if ($title == $lang->get('admins_create_new')) echo ' class="active"';
							?>><i class="fa fa-user-plus"></i> <?php echo $lang->get('admins_create_new'); ?>
                            </a></li>
                    </ul>
                </li>
				<?php
			}

			echo $appmenu;

			echo '</ul>
<div class="copy"> © 2016 <a href="http://mowie.cc">Mowie</a></div><div class="langselect"><a id="langselectbtn"><i class="fa fa-globe"></i> </a><div class="langs">';
			//Lang
			$langs = $lang->getLangs();
			foreach ($langs as $langS)
			{
				echo '<a onclick="changeLang(\'' . $langS['LangCode'] . '\')">' . $langS['Lang'] . '</a>';
			}
			echo '</div></div></nav>
<label for="show-menu" class="mobile-overlay"></label>
<div style="height: 40px;"></div>
<div class="loader-overlay"></div>
<div id="loader">
';
		} else
		{
			?>
            <div class="login_wrapper">
                <img src="<?php echo $MCONF['web_uri']; ?>admin/assets/Logo.svg" alt="Mowie"/>
                <div class="login_container">
                    <div class="langselect"><a id="langselectbtn"><i class="fa fa-globe"></i> </a>
                        <div class="langs">
							<?php
							//Lang
							$langs = $lang->getLangs();
							foreach ($langs as $langS)
							{
								echo '<a onclick="changeLang(\'' . $langS['LangCode'] . '\')">' . $langS['Lang'] . '</a>';
							} ?>
                        </div>
                    </div>
                    <h1><?php echo $lang->get('login'); ?></h1>
                    <form action="<?php echo $MCONF['web_uri']; ?>admin/login.php" method="post"
                          id="login">
                        <input type="text" placeholder="<?php echo $lang->get('username'); ?>" id="username"
                               autofocus/><br/>
                        <input type="password" placeholder="<?php echo $lang->get('password'); ?>"
                               id="pw"/><br/>
                        <div id="2faContainer" style="display: none">
                            <input type="text" id="2fa" autocomplete="off"
                                   placeholder="<?php echo $lang->get('2fa_code'); ?>"><br/>
                        </div>
                        <a href="reset-pw.php"><?php echo $lang->get('reset_pass_lost'); ?></a><br/>
                        <input type="submit" value="<?php echo $lang->get('login'); ?>"/>
                    </form>
                    <div id="msg"></div>
                </div>
                <p style="text-align: center;color: #fff;text-shadow: 1px 1px 1px #555;">&copy; 2016 <a
                            href="http://mowie.cc" style="color: #fff;">Mowie</a></p>
            </div>
            <script>
                $("#login").submit(function () {
                    if ($('#username').val() == '' || $('#pw').val() == '') {
                        $('#msg').html('<?php echo $lang->get('all_fields');?>');
                    }
                    else {
                        $('#msg').html('<div class="spinner-container"><svg class="spinner" style="width:41px;height:40px;" viewBox="0 0 44 44"><circle class="path" cx="22" cy="22" r="20" fill="none" stroke-width="4"></circle> </svg> </div>');

                        $.ajax({
                            type: 'POST',
                            url: '<?php echo $MCONF['web_uri']; ?>admin/login.php',
                            data: "username=" + $('#username').val() + "&pw=" + $('#pw').val() + "&2fa=" + $('#2fa').val(),
                            success: function (msg) {
                                console.log(msg);
                                if (msg == 'success') {
                                    location.reload();
                                }
                                else if (msg == '2fa') {
                                    $('#2faContainer').show();
                                    $('#msg').hide();
                                }
                                else if (msg == '2fafail') {
                                    $('#msg').html('<div class="message-fail"><?php echo $lang->get('error_2fa');?></div>');
                                }
                                else {
                                    $('#msg').html('<div class="message-fail"><?php echo $lang->get('wrong_username_or_pass');?></div>');
                                }
                            }
                        });
                    }
                    return false;
                });

                //Change current Language
                $('#langselectbtn').click(function () {
                    $('.langs').fadeToggle(100);
                });

                function changeLang(lang) {
                    $('#msg').html('<div class="spinner-container"><svg class="spinner" style="width:41px;height:40px;" viewBox="0 0 44 44"><circle class="path" cx="22" cy="22" r="20" fill="none" stroke-width="4"></circle> </svg> </div>');
                    $.get('<?php echo $MCONF['home_uri'];?>admin/lang.php?set=' + lang, function (data) {
                        console.log(data);
                        if (data == 1) {
                            location.reload();
                        }
                    })
                }
            </script>
            </body>
            </html><?php
			exit;
		}

	}


	//Check App dependencies
	$appCurr = $apps->getAppByPath($appDir);
	if (!$apps->checkDependencies($appCurr['app_name']) && is_loggedin())
	{
		foreach ($apps->unresolvedDependencies as $dependency_type => $dependency)
		{
			//Apps
			if ($dependency_type == 'apps')
			{
				foreach ($dependency as $depApp)
				{
					echo msg('info', sprintf($lang->get('general_needs_other_app'), $depApp));
				}
			}

			//Mowie-Version
			if ($dependency_type == 'mowie-version')
			{
				echo msg('info', sprintf($lang->get('general_needs_other_version'), $dependency));
			}

			//Mowie-Version
			if ($dependency_type == 'php')
			{
				echo msg('info', sprintf($lang->get('general_needs_other_php'), $dependency));
			}
		}

		exit;
	}

}

// Returns a file size limit in bytes based on the PHP upload_max_filesize
// and post_max_size
function file_upload_max_size()
{
	static $max_size = -1;

	if ($max_size < 0)
	{
		// Start with post_max_size.
		$max_size = parse_size(ini_get('post_max_size'));

		if ($max_size == 0)
		{
			$max_size = parse_size(ini_get('upload_max_filesize'));
		} else
		{
			// If upload_max_size is less, then reduce. Except if upload_max_size is
			// zero, which indicates no limit.
			$upload_max = parse_size(ini_get('upload_max_filesize'));
			if ($upload_max > 0 && $upload_max < $max_size)
			{
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
		if ($_SESSION['lvl'] == 1)
		{
			return true;
		} else
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

			if ($scope == 'System') $scopeUri .= '../admin/';

			//echo $appuri;

			if (file_exists($scopeUri . 'permissions.json'))
			{
				$perms = json_decode(file_get_contents($scopeUri . 'permissions.json'), true);
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
							if (array_key_exists($_CONF['app_name'], $perms)) $perms_f = $perms[$_CONF['app_name']];
						} else
						{
							$perms_f = $perms['System'];
						}
						if (in_array($permkey, $perms_f))
						{
							return true;
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
			} else
			{
				return false;
			}
		}
	} else
	{
		return false;
	}
}

//Get Username based on its ID
function getUserByID($userid)
{
	if ($userid == $_SESSION['userid'])
	{
		return $_SESSION['user'];
	} else
	{
		$GLOBALS['db']->setCol('system_admins');
		$GLOBALS['db']->data['id'] = $userid;
		$GLOBALS['db']->get();
		if (isset($GLOBALS['db']->data[0]))
		{
			return $GLOBALS['db']->data[0]['username'];
		} else
		{
			return $userid;
		}
	}
}

//Test mail
function smail($mailaddr, $subject, $message, $header)
{
	$mail = 'To: ' . $mailaddr . "\n";
	$mail .= 'Subject: ' . $subject . "\n";
	$mail .= $header . "\n\n";
	$mail .= $message;

	if (file_put_contents('mail_' . $mailaddr . '_' . time() . '.txt', $mail))
	{
		return true;
	} else
	{
		return false;
	}
}

//SMTP-Mailer
function mmail($mailaddr, $subject, $message, $from, $html = false)
{
	if ($GLOBALS['MCONF']['smtp'])
	{
		require_once 'PHP-mailer/class.phpmailer.php';
		require_once 'PHP-mailer/class.smtp.php';

		$mail = new PHPMailer;

		$mail->isSMTP();
		$mail->Host = $GLOBALS['MCONF']['smtp_host'];
		$mail->SMTPAuth = true;
		$mail->Username = $GLOBALS['MCONF']['smtp_user'];
		$mail->Password = $GLOBALS['MCONF']['smtp_pass'];
		$mail->SMTPSecure = $GLOBALS['MCONF']['smtp_secure'];
		$mail->Port = $GLOBALS['MCONF']['smtp_port'];

		$mail->setFrom($from);
		$mail->addAddress($mailaddr);
		$mail->isHTML($html);

		$mail->Subject = $subject;
		$mail->Body = $message;

		return $mail->send();
	} else
	{
		$header = 'From: ' . $from . "\n";
		if ($html) $header .= "Content-Type: text/html\n";

		return mail($mailaddr, $subject, $message, $header);
	}
}

//File exists on remote Server
function remote_file_exists($url)
{
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_NOBODY, true);
	curl_exec($ch);
	$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);

	if ($retcode == 200)
	{
		return true;
	} else
	{
		return false;
	}
}

//New Message to the stream
function stream_message($msg, $lvl, $extra = '', $time = null, $user = null)
{
	if (!isset($time)) $time = time();
	if (!isset($user)) $user = $_SESSION['userid'];

	global $db;
	$db->setCol('system_stream');
	$db->data['time'] = $time;
	$db->data['user'] = intval($user);
	$db->data['lvl'] = $lvl;
	$db->data['message'] = $msg;
	$db->data['extra'] = $extra;
	return $db->insert();
}

?>