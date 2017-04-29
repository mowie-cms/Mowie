<?php
error_reporting(E_ALL);
require_once '../inc/autoload_adm.php';

//Get Langstrings
foreach ($apps->getApps() as $appname => $appdetail)
{
	$path = '../apps/'.$appname. '/lang/';
	if (file_exists($path) && is_dir($path))
	{
		$lang->setLangFolder($path);
	}
}

//Get Stream as JSON
if(isset($_GET['getStream']) && is_loggedin())
{
	$db->setCol('system_show_stream');
	$db->data['user'] = $_SESSION['userid'];
	$db->get();
	$loglevel = json_decode($db->data[0]['level']);

	$streamData = [];
	$db->setCol('system_stream');
	$db->get(null, null, 'id', 'DESC', 10);
	foreach($db->data as $stream)
	{
		if(in_array($stream['lvl'], $loglevel))
		{
			$message = str_replace('{user}', getUserByID($stream['user']), $lang->get($stream['message']));
			$message = str_replace('{extra}', $stream['extra'], $message);

			$streamData[] = [
				'id' => $stream['id'],
				'time' => $stream['time'],
				'user' => $stream['user'],
				'message' => $message
			];
		}
	}

	header('Charset: utf-8');
	header('Content-type: application/json');
	echo json_encode( $streamData );
	exit;
}

//Show Stream
printHeader('Stream');

echo '<div class="main">';

//Get Stream Messages
$db->setCol('system_stream');
$db->get(null, null, 'id', 'DESC', 200);
foreach ($db->data as $stream)
{
	$message = str_replace('{user}', getUserByID($stream['user']), $lang->get($stream['message']));
	$message = str_replace('{extra}', $stream['extra'], $message);
	echo '<p><b>'.date('d.m.Y H:i', $stream['time']).':</b> '.$message.'</p>';
}

echo '</div>';

require_once '../inc/footer.php';