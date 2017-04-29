<?php
if(isset($_POST['submit']))
{
	if($db->query('CREATE TABLE `' . $_POST['db_prefix'] . 'sidebar_sidebar` (
  `active` tinyint(1) NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `' . $_POST['db_prefix'] . 'sidebar_sidebar` (`active`, `content`) VALUES
(0, \'\');'))
	{
		echo msg('success', 'Sidebar was installed successfully.');
	}
	else
	{
		echo msg('fail', 'An error occured while installing Sidebar.');
		exit;
	}
}