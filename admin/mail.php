<?php
require_once '../inc/autoload_adm.php';
printHeader($lang->get('mail_write'));

if (isset($_GET['to']))
{
	if (isset($_POST['submit']))
	{
		if ($_POST['from'] != "" && $_POST['mail'] != "" && $_POST['subject'] != "" && $_POST['message'] != "")
		{
			$header = 'From: ' . $_POST['from'] . ' <' . $_POST['mail'] . ">\n";
			$header .= 'Reply-To: ' . $_POST['mail'] . "\n";
			$header .= "Content-Type: text/html\n";

			if (mail($_GET['to'], $_POST['subject'], $_POST['message'], $header))
			{
				echo msg('success', sprintf($lang->get('mail_success'), $_GET['to']));
			}
			else
			{
				echo msg('fail', $lang->get('mail_fail'));
			}
		}
		else
		{
			echo msg('info', $lang->get('all_fields') . ' {back}');
		}
	}
	else
	{
		?>
		<div class="main">
			<h1><?php printf($lang->get('mail_write_to'), $_GET['to']); ?></h1>
			<div class="form">
				<form action="<?php echo $_SERVER['REQUEST_URI']?>" method="post">
					<p><span><?php echo $lang->get('admins_username'); ?>:</span>
						<input type="text" value="<?php echo $_SESSION['user']; ?>" name="from"/>
					</p>
					<p><span><?php echo $lang->get('admins_mail'); ?>:</span>
						<input type="text" name="mail" value="<?php echo $_GET['to']; ?>"/>
					</p>
					<p><span><?php echo $lang->get('mail_subject'); ?>:</span>
						<input type="text" name="subject"/>
					</p>
					<p><span><?php echo $lang->get('mail_message'); ?>:</span><br/>
						<textarea name="message"></textarea></p>
					<input type="submit" value="<?php echo $lang->get('mail_send'); ?>" name="submit"/>
				</form>
			</div>
		</div>
		<?php
	}
}
else
{
	echo msg('info', $lang->get('mail_success'));
}
require_once '../inc/footer.php';
?>