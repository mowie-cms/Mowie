<?php
require_once '../vendor/autoload.php';
require_once '../inc/config.php';
require_once '../inc/libs/functions.php';
$lang->setLangFolder('lang/');

//If Posted
if(isset($_GET['ajax']))
{
	if(isset($_GET['reset']))
	{
		if(isset($_POST['pwid'], $_POST['pw1'], $_POST['pw2']))
		{
			if($_POST['pw1'] == $_POST['pw2'])
			{
				$db->setCol('system_admins');
				$db->data['pwreset'] = $_POST['pwid'];
				$db->get();
				if (isset($db->data[0]['username']))
				{
					$db->setCol('system_admins');
					$db->data['pass'] = password_hash($_POST['pw1'], PASSWORD_DEFAULT);
					$db->data['pwreset'] = '';
					if($db->update(['pwreset' => $_POST['pwid']]))
					{
						echo 'success';
					}
					else
					{
						echo 'fail';
					}
				} else
				{
					echo 'wrongid';
				}
			}
			else
			{
				echo 'pw_no_match';
			}
		}
		else
		{
			echo 'allfields';
		}
	}
	else
	{
		$db->setCol('system_admins');
		$db->data['mail'] = $_POST['mail'];
		$db->get();
		if (isset($db->data[0]['username']))
		{
			$name = $db->data[0]['username'];
			$mail = $db->data[0]['mail'];

			//Insert
			$pwreset = random(128);
			$db->setCol('system_admins');
			$db->data['pwreset'] = $pwreset;
			if ($db->update(['username' => $name]))
			{
				$link = $MCONF['web_uri'] . 'admin/reset-pw.php?pwid=' . $pwreset;
				$message = sprintf($lang->get('reset_pass_mail_message'), $name, $link);
				if (mmail($mail, sprintf($lang->get('reset_pass_mail_title'), $MCONF['title']), $message, 'noreply@' . $_SERVER['HTTP_HOST']))
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
		} else
		{
			echo 'nomail';
		}
	}
}
else
{
	?>
	<!DOCTYPE html>
	<html lang="de">
	<head>
		<meta charset="UTF-8">
		<title><?php echo $lang->get('reset_pass_title') . ' | ' . $lang->get('admin_title') . ' | ' . $MCONF['title']; ?></title>
		<link rel="shourtcut icon" href="<?php echo $MCONF['web_uri'] ?>favicon.ico"/>
		<link rel="stylesheet" href="<?php echo $MCONF['web_uri'] ?>admin/assets/admin.css" type="text/css"/>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"/>
		<script src="<?php echo $MCONF['web_uri'] ?>admin/assets/js/jquery.min.js"></script>
	</head>
	<body>
	<div class="login_wrapper">
		<img src="<?php echo $MCONF['web_uri'] ?>admin/assets/Logo.svg" alt="Mowie"/>
		<div class="login_container">
			<div class="langselect"><a id="langselectbtn"><i class="fa fa-globe"></i> </a>
				<div class="langs">
					<?php
					//Lang
					$langs = $GLOBALS['lang']->getLangs();
					foreach ($langs as $av_lang)
					{
						echo '<a onclick="changeLang(\'' . $av_lang['LangCode'] . '\')">' . $av_lang['Lang'] . '</a>';
					} ?>
				</div>
			</div>
			<h1><?php echo $lang->get('reset_pass_title'); ?></h1>
			<form action="<?php echo $MCONF['web_uri'] ?>admin/reset-pw.php" method="post"
				  id="pwreset">
				<?php
				if(isset($_GET['pwid']))
				{
					$db->setCol('system_admins');
					$db->data['pwreset'] = $_GET['pwid'];
					$db->get();
					if(isset($db->data[0]['username']))
					{
						?>
						<input type="password" id="pw1" name="pw1" placeholder="<?php echo $lang->get('admins_cn_password'); ?>" autofocus/><br/>
						<input type="password" id="pw2" name="pw3" placeholder="<?php echo $lang->get('admins_cn_password_again'); ?>"/><br/>
						<input type="submit" value="<?php echo $lang->get('reset_pass_reset'); ?>"/>
						<?php
					}
					else
					{
						echo '<p>'.$lang->get('reset_pass_link_not_available').'</p>';
					}
				}
				else
				{
					echo $lang->get('reset_pass_msg'); ?>
					<input type="text" placeholder="<?php echo $lang->get('reset_pass_mail'); ?>" id="mail"
						   autofocus/><br/>
					<input type="submit" value="<?php echo $lang->get('reset_pass_button'); ?>"/>
					<?php
				}
				?>
			</form>
			<div id="msg"></div>
		</div>
		<p style="text-align: center;color: #fff;text-shadow: 1px 1px 1px #555;">&copy; 2017 <a
				href="http://mowie.cc" style="color: #fff;" target="_blank">Mowie</a></p>
	</div>
	<script>
		$("#pwreset").submit(function () {
			<?php
			if(isset($_GET['pwid']))
			{
				?>
			if ($('#pw1').val() == '' || $('#pw2').val() == '') {
				$('#msg').html('<?php echo $GLOBALS['lang']->get('all_fields');?>');
			}
			else {
				$('#msg').html('<div class="spinner-container"><svg class="spinner" style="width:41px;height:40px;" viewBox="0 0 44 44"><circle class="path" cx="22" cy="22" r="20" fill="none" stroke-width="4"></circle> </svg> </div>');

				$.ajax({
					type: 'POST',
					url: '<?php echo $GLOBALS['MCONF']['web_uri']; ?>admin/reset-pw.php?ajax&reset',
					data: "pw1=" + $('#pw1').val() + "&pw2=" + $('#pw2').val() + '&pwid=<?php echo $_GET['pwid'];?>',
					success: function (msg) {
						console.log(msg);
						if (msg == 'success') {
							$('#msg').html('<div class="message-success"><?php echo $GLOBALS['lang']->get('reset_pass_reset_success');?></div>');
						}
						else if(msg == 'wrongid'){
							$('#msg').html('<div class="message-fail"><?php echo $GLOBALS['lang']->get('reset_pass_reset_wrong_id');?></div>');
						}
						else {
							$('#msg').html('<div class="message-fail"><?php echo $GLOBALS['lang']->get('reset_pass_reset_fail');?></div>');
						}
					}
				});
			}
				<?php
			}
			else
			{
			?>
			if ($('#mail').val() == '') {
				$('#msg').html('<?php echo $GLOBALS['lang']->get('all_fields');?>');
			}
			else {
				$('#msg').html('<div class="spinner-container"><svg class="spinner" style="width:41px;height:40px;" viewBox="0 0 44 44"><circle class="path" cx="22" cy="22" r="20" fill="none" stroke-width="4"></circle> </svg> </div>');

				$.ajax({
					type: 'POST',
					url: '<?php echo $GLOBALS['MCONF']['web_uri']; ?>admin/reset-pw.php?ajax',
					data: "mail=" + $('#mail').val(),
					success: function (msg) {
						console.log(msg);
						if (msg == 'success') {
							$('#msg').html('<div class="message-success"><?php echo $GLOBALS['lang']->get('reset_pass_success');?></div>');
						}
						else if(msg == 'nomail'){
							$('#msg').html('<div class="message-fail"><?php echo $GLOBALS['lang']->get('reset_pass_nomail');?></div>');
						}
						else {
							$('#msg').html('<div class="message-fail"><?php echo $GLOBALS['lang']->get('reset_pass_error');?></div>');
						}
					}
				});
			}
			<?php
			}
			?>
			return false;
		});

		//Change current Language
		$('#langselectbtn').click(function () {
			$('.langs').fadeToggle(100);
		});

		function changeLang(lang) {
			$('#msg').html('<div class="spinner-container"><svg class="spinner" style="width:41px;height:40px;" viewBox="0 0 44 44"><circle class="path" cx="22" cy="22" r="20" fill="none" stroke-width="4"></circle> </svg> </div>');
			$.get('<?php echo $GLOBALS['MCONF']['home_uri'];?>admin/lang.php?set=' + lang, function (data) {
				console.log(data);
				if (data == 1) {
					location.reload();
				}
			})
		}
	</script>
	</body>
	</html>
	<?php
}