<?php
if(isset($_POST['submit']))
{
	if($db->query('CREATE TABLE `' . $_POST['db_prefix'] . 'meta_meta` (
  `name` text NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;'))
	{
		echo msg('succes', 'Meta was installed successfully.');
	}
	else
	{
		echo msg('fail', 'An error occured while installing Meta.');
		exit;
	}
}