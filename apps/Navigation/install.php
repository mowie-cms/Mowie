<?php
if(isset($_POST['submit']))
{
	if($db->query('CREATE TABLE `' . $_POST['db_prefix'] . 'nav_nav` (
  `id` int(11) NOT NULL,
  `title` text CHARACTER SET latin1 NOT NULL,
  `page` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  `nav_order` int(11) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `' . $_POST['db_prefix'] . 'nav_nav`
  ADD PRIMARY KEY (`id`);
  
ALTER TABLE `' . $_POST['db_prefix'] . 'nav_nav`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
  
  ALTER TABLE `' . $_POST['db_prefix'] . 'nav_nav` ADD external VARCHAR(300) NULL;
ALTER TABLE `' . $_POST['db_prefix'] . 'nav_nav`
  MODIFY COLUMN external VARCHAR(300) AFTER page;
'))
	{
		echo msg('success', 'Navigation was installed successfully.');
	}
	else
	{
		echo msg('fail', 'An error occured while installing Navigation.');
		exit;
	}
}