<?php
if (isset($_POST['submit']))
{
	if($db->query('CREATE TABLE `' . $_POST['db_prefix'] . 'simplePages_pages` (
  `id` int(11) NOT NULL,
  `title` text CHARACTER SET utf8 NOT NULL,
  `alias` longtext CHARACTER SET utf8 NOT NULL,
  `content` text CHARACTER SET utf8 NOT NULL,
  `user` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `meta_description` text CHARACTER SET utf8 NOT NULL,
  `meta_keywords` longtext CHARACTER SET utf8 NOT NULL,
  `created` int(11) NOT NULL,
  `lastedit` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `' . $_POST['db_prefix'] . 'simplePages_pages_confirm` (
  `id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  `title` text CHARACTER SET utf8 NOT NULL,
  `alias` longtext CHARACTER SET utf8 NOT NULL,
  `content` text CHARACTER SET utf8 NOT NULL,
  `user` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `meta_description` text CHARACTER SET utf8 NOT NULL,
  `meta_keywords` longtext CHARACTER SET utf8 NOT NULL,
  `created` int(11) NOT NULL,
  `lastedit` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `' . $_POST['db_prefix'] . 'simplePages_permissions` (
  `id` int(11) NOT NULL,
  `page` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `lastedit` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `' . $_POST['db_prefix'] . 'simplePages_pages`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `' . $_POST['db_prefix'] . 'simplePages_pages_confirm`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `' . $_POST['db_prefix'] . 'simplePages_permissions`
  ADD PRIMARY KEY (`id`);
    
ALTER TABLE `' . $_POST['db_prefix'] . 'simplePages_pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
ALTER TABLE `' . $_POST['db_prefix'] . 'simplePages_pages_confirm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `' . $_POST['db_prefix'] . 'simplePages_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
  '))
	{
		$confirmIni = 'confirmationRequierd = false
confirmationUser = 0';
		if(isset($_POST['confirmationRequired']))
		{
			$db->setCol('system_admins');
			$db->data['username'] = $_POST['confirmationUser'];
			$db->get();
			$confirmIni = 'confirmationRequierd = false
confirmationUser = '.$db->data[0]['id'];
		}

		file_put_contents('backend/confirm.ini', $confirmIni, FILE_USE_INCLUDE_PATH);
		echo msg('succes', 'SimplePages was installed successfully.');
	}
	else
	{
		echo msg('fail', 'An error occured while installing SimplePages.');
		exit;
	}
}
else
{
	?>
	<h2>SimplePages</h2>
	<span>&nbsp;</span>
	<input type="checkbox" name="confirmationRequired" value="true" id="confirmationRequired" onchange="fadeInput('confirmationUser');"/>
	<label for="confirmationRequired"><i></i> Needs confirmation for contents</label>
	<div id="confirmationUser" style="display:none;">
		<span>Confirmation User:</span> <input type="text" name="confirmationUser" placeholder="Confirmation User"/>
	</div>
	<?php
}
?>