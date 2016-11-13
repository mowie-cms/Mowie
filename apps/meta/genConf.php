<?php
if (isset($_POST['metaconf']))
{
	//meta leermachen
	$db->setCol('meta_meta');
	$db->delete();

	$db->clear();
	$db->setCol('meta_meta');

	foreach ($_POST['metaName'] as $pos => $metaName)
	{
		if($metaName!='' && $_POST['metaContent'][$pos]!='')
		{
			$db->data = ['name' => $metaName, 'content' => $_POST['metaContent'][$pos]];
			if($db->insert())
			{
				$success = true;
			}
			else
			{
				$success = false;
			}
		}
	}

	if($success)
	{
		echo msg('success', $lang->get('meta_saved_success'));
		stream_message('{user} edited meta.', 3);
	}
	else
	{
		echo msg('fail', $lang->get('meta_saved_fail'));
	}
}
else
{
	echo '<h1>'.$lang->get('meta_title').'</h1><p>'.$lang->get('meta_wont_save_empty').'</p><div id="meta_container"><input type="hidden" name="metaconf" value="snd"/>';
	$i = 1;
	$db->setCol('meta_meta');
	$db->get();
	foreach ($db->data as $data)
	{
		echo  '<p id="'.$i.'"><span><a onclick="delMeta('.$i.');" title="'.$lang->get('meta_delete').'" class="del"><i class="fa fa-trash-o"></i></a>  '.$data['name'].':</span><input type="text" name="metaContent[]" value="' . $data['content'] . '"/><input type="hidden" name="metaName[]" value="'.$data['name'].'"/></p>';
		$i++;
	}
	echo '</div>';
	?>
	<p><a onclick="addMeta()" class="button"><?php echo $lang->get('meta_add');?></a></p>
	<script>
		function addMeta(){
			var i = Math.floor((Math.random() * 10000) + 9000);
			$('#meta_container').append('<p id="' + i + '"><span><a onclick="delMeta(\'' + i + '\');" title="<?php echo $lang->get('meta_delete');?>" class="del"><i class="fa fa-trash-o"></i></a>  <input type="text" name="metaName[]" placeholder="<?php echo $lang->get('meta_name');?>" style="width: 160px; margin-top: 0px;"/>:</span><input type="text" name="metaContent[]" placeholder="<?php echo $lang->get('meta_content');?>" style="margin-top: 5px;"/></p>');
		}

		function delMeta(metaCont){
			$('#' + metaCont).remove();
		}
	</script>
<?php
}
?>