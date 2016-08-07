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
			$db->insert();
		}
	}
}
else
{
	echo '<h1>Metadaten </h1><p>Leere Metafelder werden nicht gespeichert.</p><div id="meta_container"><input type="hidden" name="metaconf" value="snd"/>';
	$i = 1;
	$db->setCol('meta_meta');
	$db->get();
	foreach ($db->data as $data)
	{
		echo  '<p id="'.$i.'"><span><a onclick="delMeta('.$i.');" title="Meta-Feld löschen" class="del"><i class="fa fa-trash-o"></i></a>  '.$data['name'].':</span><input type="text" name="metaContent[]" value="' . $data['content'] . '"/><input type="hidden" name="metaName[]" value="'.$data['name'].'"/></p>';
		$i++;
	}
	echo '</div>';
	?>
	<p><a onclick="addMeta()" class="button">Metafeld hinzufügen</a></p>
	<script>
		function addMeta(){
			var i = Math.floor((Math.random() * 10000) + 9000);
			$('#meta_container').append('<p id="' + i + '"><span><a onclick="delMeta(\'' + i + '\');" title="Meta-Feld löschen" class="del"><i class="fa fa-trash-o"></i></a>  <input type="text" name="metaName[]" placeholder="Name" style="width: 160px; margin-top: 0px;"/>:</span><input type="text" name="metaContent[]" placeholder="Meta-Inhalt" style="margin-top: 5px;"/></p>');
		}

		function delMeta(metaCont){
			$('#' + metaCont).remove();
		}
	</script>
<?php
}
?>