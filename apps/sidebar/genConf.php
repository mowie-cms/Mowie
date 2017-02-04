<?php
if (isset($_POST['sidebarconf']))
{
	$sidebar_inhalt = 'text';
	$text = $_POST['sidebar_inhalt'];
	$active = false;
	if(isset($_POST['active']) && $_POST['active'] == 'true')
	{
		$active = true;
	}

	$db->setCol('sidebar_sidebar');
	$db->data = ['active' => $active, 'content' => $text];
	if ($db->update())
	{
		echo msg('success', $lang->get('sidebar_edit_success'));
		stream_message('{user} edited sidebar.', 3);
	}
	else
	{
		echo msg('fail');
	}
}
else
{
	echo '<h1>'.$lang->get('sidebar_title').'</h1>';

	$db->setCol('sidebar_sidebar');
	$db->get();

	$active = '';
	if(isset($db->data[0])) {
	    if(!$db->data[0]['active']) $active = ' selected';
	}
	?>
	<p>
		<input type="checkbox" name="active" value="y" id="activeswitch" onchange="toggleTextField()"/>
		<label for="activeswitch"><i></i><?php echo $lang->get('sidebar_show');?></label>
	</p>
	</div>
	<div id="editorContainer">
		<?php echo $lang->get('sidebar_content');?>:
	<br/>
	<textarea type="text" name="sidebar_inhalt" id="editor"><?php echo $db->data[0]['content']; ?></textarea>
	</div>
	<input type="hidden" name="sidebarconf" value="smbt"/>
	<script>
		if($('#activeswitch').is(':checked')) {
			$('#editorContainer').show();
		}
		else {
			$('#editorContainer').hide();
		}

		function toggleTextField(){
			if($('#activeswitch').is(':checked')) {
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