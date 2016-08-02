<?php
if(isset($_POST['log_folder']))
{
	$CONFIG['General']['log_uri'] = $_POST['log_folder'];
}
else
{
	?>
	<h2>Logfiles</h2>
	<span>Log-Folder</span>
	<input type="text" name="log_folder"/>
	<?php
}