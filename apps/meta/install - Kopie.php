<?php
if (isset($_POST['submit']))
{
	$dbtables = "CREATE TABLE IF NOT EXISTS `".$MCONF['db_prefix']."meta` (
  `meta_name` text NOT NULL,
  `inhalt` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `".$MCONF['db_prefix']."meta` (`meta_name`, `inhalt`) VALUES
('generator', 'Kolacms'),
('author', 'Kola Entertainments'),
('languange', ''),
('Organization', 'Kola Entertainments'),
('Description', ''),
('Keywords', ''),
('Language', 'de');";
	$dbtables = $DBH->prepare($dbtables);
	if($dbtables->execute())
	{
		echo msg('succes', 'Das Metamodul wurde erfolgreich eingerichtet.<br/>');
	}
	else
	{
		echo msg('fail', 'Fehler beim Einrichten des Metamoduls.');
		exit;
	}
}
?>