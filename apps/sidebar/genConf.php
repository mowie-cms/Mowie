<?php
if (isset($_POST['sidebarconf']))
{
	$sidebar_inhalt = 'text';
	$text = $_POST['sidebar_inhalt'];
	$active = false;
	if($_POST['aktiv'] == 'true')
	{
		$active = true;
	}

	$db->setCol('sidebar_sidebar');
	$db->data = ['active' => $active, 'content' => $text];
	if ($db->update())
	{
		echo msg('succes', 'Die &Auml;nderungen der Sidebar wurden erfolgreich gespeichert.');
	}
	else
	{
		echo msg('fail');
	}
}
else
{
	echo '<h1>Sidebar</h1>';

	$db->setCol('sidebar_sidebar');
	$db->get();

	$active = '';
	if(!$db->data[0]['active']) $active = ' selected';
	?>
	<p><span>Sidebar anzeigen:</span>
		<select name="aktiv" id="aktivswitch" onchange="toggleTextField()">
			<option value="true">Ja</option>
			<option value="false"<?php echo $active ?>>Nein</option>
		</select>
	</p>
	</div>
	<div id="editorContainer">
	Sidebar-Inhalt:
	<br/>
	<textarea type="text" name="sidebar_inhalt" id="editor"><?php echo $db->data[0]['content']; ?></textarea>
	</div>
	<input type="hidden" name="sidebarconf" value="smbt"/>
	<script>
		if($('#aktivswitch').val() == 'true') {
			$('#editorContainer').show();
		}
		else {
			$('#editorContainer').hide();
		}
		function toggleTextField(){
			if($('#aktivswitch').val() == 'true') {
				$('#editorContainer').show();
			}
			else {
				$('#editorContainer').hide();
			}
		}
	</script>
	<?php
}
?>