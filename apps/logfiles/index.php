<?php
require_once '../../inc/autoload.php';
printHeader('Logfiles');
?>
	<div class="main">
<?php
if (hasPerm('view_logfiles'))
{
	$monate = array('01' => "Januar",
		'02' => "Februar",
		'03' => "M&auml;rz",
		'04' => "April",
		'05' => "Mai",
		'06' => "Juni",
		'07' => "Juli",
		'08' => "August",
		'09' => "September",
		'10' => "Oktober",
		'11' => "November",
		'12' => "Dezember");
	if (isset($_GET['detail']))
	{
		$logfile = $_GET['detail'] . '.log';
		$logN = explode('-', str_replace('.log', '', $logfile));
		echo '<h1>'. $lang->get('log_detail_title'). ' ' . $logN[2] . '. ' . $monate[$logN[1]] . ' ' . $logN[0] . '</h1>';
		//var_dump($_SERVER);
		?>
		<form action="?detail=<?php echo $_GET['detail']; ?>" method="post">
			<input type="text" name="filter" value="" placeholder="<?php echo $lang->get('log_filter_logs');?>"/>
			<input type="submit" value="<?php echo $lang->get('log_filter_logs');?>"/>
		</form>
		<?php
		$i = 0;
		if (file_exists('../../' . $MCONF['log_uri'] . '/' . $logfile))
		{
			$lines = file('../../' . $MCONF['log_uri'] . '/' . $logfile);
			foreach ($lines as $line_num => $line)//logfile ausgeben
			{
				if ($line_num > 1)
				{
					if (isset($_POST['filter']))//filtern
					{
						if (strpos($line, $_POST['filter']) !== false)//mit post
						{
							$line_s = explode(' ', $line);
							echo '<b>'.$line_s[0].'</b> '.str_replace($line_s[0], '', $line). '<br/>';
							//echo $line . '<br/>';
							$i++;
						}
					} elseif (isset($_GET['filter']))//filtern
					{
						if (strpos($line, $_GET['filter']) !== false)//mit get
						{
							$line_s = explode(' ', $line);
							echo '<b>'.$line_s[0].'</b> '.str_replace($line_s[0], '', $line). '<br/>';
							$i++;
						}
					} else//ungefiltert
					{
						$line_s = explode(' ', $line);
						echo '<b>'.$line_s[0].'</b> '.str_replace($line_s[0], '', $line). '<br/>';
						$i++;
					}
				}
			}
			echo '<b>'.$i . ' ' . $lang->get('log_views_total').'</b>';
		} else
		{
			echo '<p>'.$lang->get('log_file_not_found').'</p>';
		}
	} elseif (isset($_GET['detailmon']))
	{
		$logmon = $_GET['detailmon'];
		$logmonN = explode('-', $logmon);

		echo '<h1>'.$lang->get('log_detail_title_from').' ' . $monate[$logmonN[0]] . ' ' . $logmonN[1] . '</h1>';
		?>
		<form action="?detailmon=<?php echo $_GET['detailmon']; ?>" method="post">
			<input type="text" name="filter" value="" placeholder="<?php echo $lang->get('log_filter_logs');?>"/>
			<input type="submit" value="<?php echo $lang->get('log_filter_logs');?>" class="speichern"/>
		</form>
		<?php
		$i = 0;
		if ($handle = opendir('../../' . $MCONF['log_uri'] . ''))//logs anzeiugen
		{
			while ((false !== ($file = readdir($handle))))
			{
				if (strpos($file, $logmonN[1] . '-' . $logmonN[0]) !== false)//nur zum monat passende logs anzeigen
				{
					$lines = file('../../' . $MCONF['log_uri'] . '/' . $file);
					foreach ($lines as $line_num => $line)//ausgeben
					{
						if ($line_num > 1)
						{
							if (isset($_POST['filter']))//filtern
							{
								if (strpos($line, $_POST['filter']) !== false)//mit post
								{
									$line_s = explode(' ', $line);
									echo '<b>'.$line_s[0].'</b> '.str_replace($line_s[0], '', $line). '<br/>';
									$i++;
								}
							} elseif (isset($_GET['filter']))//filtern
							{
								if (strpos($line, $_GET['filter']) !== false)//mit get
								{
									$line_s = explode(' ', $line);
									echo '<b>'.$line_s[0].'</b> '.str_replace($line_s[0], '', $line). '<br/>';
									$i++;
								}
							} else//ungefiltert
							{
								$line_s = explode(' ', $line);
								echo '<b>'.$line_s[0].'</b> '.str_replace($line_s[0], '', $line). '<br/>';
								$i++;
							}
						}
					}
				}
			}
		}
		echo '<b>'.$i . ' ' . $lang->get('log_views_total').'</b>';
	} else
	{
		echo '<h2>'.$lang->get('log_total_pageviews').' ' . file_get_contents('../../' . $MCONF['log_uri'] . '/count.counter') . '</h2>';
		$monatelogs = [];
		//monatslogliste erstellen
		if ($handle = opendir('../../' . $MCONF['log_uri'] . ''))
		{
			while (false !== ($file = readdir($handle)))
			{
				if ($file != "." && $file != ".." && $file != 'count.counter')
				{
					$strtiel = [];
					$strtiel = explode('-', str_replace('.log', '', $file));
					$logsatr = $strtiel[0] . '-' . $strtiel[1];
					if (!in_array($logsatr, $monatelogs))
					{
						$monatelogs[] = $strtiel[0] . '-' . $strtiel[1];
					}
					//echo '<a href="?detail='.$file.'">'.$file.'</a><br/>';
				}
			}
			closedir($handle);
		}

		//var_dump($monatelogs);
		//monatsloglist ausgeben
		asort($monatelogs);
		foreach ($monatelogs as $log)
		{
			$logN = explode('-', $log);
			echo '<h2>' . $monate[$logN[1]] . ' ' . $logN[0] . '</h2>';
			if ($handle = opendir('../../' . $MCONF['log_uri'] . ''))
			{
				while (false !== ($file = readdir($handle)))
				{
					if ($file != "." && $file != ".." && strpos($file, $log) !== false)
					{
						echo '<a href="?detail=' . str_replace('.log', '', $file) . '">' . str_replace('.log', '', $file) . '</a><br/>';
					}
				}
				closedir($handle);
			}
			echo '<a href="?detailmon=' . $logN[1] . '-' . $logN[0] . '">'.sprintf($lang->get('log_show_full_logs'), $monate[$logN[1]], $logN[0]) . '</a>';
		}
	}
} else
{
	echo msg('info', $lang->get('missing_permission'));
}
?>
	</div>
<?php
require_once '../../inc/footer.php';
?>