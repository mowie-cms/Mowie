<div id="showMsg"></div>
<script>
	function showMsg(msg){
		$('#showMsg').html('<div class="snackbar"><a onclick="closeMsg();" class="closeMsg"><i class="icon-close"></i> </a><p>' + msg + '</p></div>');
	}

	function closeMsg(){
		$('#showMsg').html('');
	}
</script>
</body>
</html>