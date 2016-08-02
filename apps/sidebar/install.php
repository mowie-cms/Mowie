<?php
if (isset($_POST['submit']))
{
	$dbtables = "CREATE TABLE IF NOT EXISTS `".$MCONF['db_prefix']."sidebar` (
  `aktiv` text NOT NULL,
  `inhalt_typ` text NOT NULL,
  `inhalt` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `".$MCONF['db_prefix']."sidebar` (`aktiv`, `inhalt_typ`, `inhalt`) VALUES
('nein', 'text', '<p>Hier erscheint der Inhalt der Sidebar</p>');";
	$dbtables = $DBH->prepare($dbtables);
	if($dbtables->execute())
	{
		echo msg('succes', 'Das Sidebarmodul wurde erfolgreich eingerichtet.<br/>');
	}
	else
	{
		echo msg('fail', 'Fehler beim Einrichten des Sidebarmoduls.');
		exit;
	}
}
?>