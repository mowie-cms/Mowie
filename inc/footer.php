<?php
if(!isset($_GET['direct']))
{
	?>
	</div>
	<div id="showMsg"></div>
	<script src="<?php echo $MCONF['web_uri']?>admin/assets/js/page.js"></script>
	<script>
		function showMsg(msg) {
			$('#showMsg').html('<div class="snackbar"><a onclick="closeMsg();" class="closeMsg"><i class="icon-close"></i> </a><p>' + msg + '</p></div>');
		}

		function closeMsg() {
			$('#showMsg').html('');
		}

		//Show Loader
		function showTopLoader() {
			$('.loader-overlay').fadeIn(150);
			$('.toploading').animate({height: "8px"}, 150);
		}

		function hideTopLoader() {
			$('.loader-overlay').fadeOut(150);
			$('.toploading').animate({height: "0"}, 150);
		}

		$(document).ready(function() {
		//Form
		// pre-submit callback
		function showLoader(formData, jqForm, options) {
			showTopLoader();
			console.log('Form');
			return true;
		}

		// post-submit callback
		function showResponse(responseText, statusText, xhr, $form) {
			//return false;
			hideTopLoader();
			console.log(statusText);
		}

		//Error
		function showError(e) {
			console.log(e);
		}

		var options = {
			target: '#loader',
			beforeSubmit: showLoader,
			success: showResponse,
			error: showError,
			resetForm: false,
			data: {direct: ''}
		};
		$('form').ajaxForm(options);

			//Router
			$('#topnav').addClass('no-transition');

			page.base('<?php echo $MCONF['home_uri'];?>');
			page('*', findPage);
			page();

			function findPage(ctx, next) {
				//console.log(ctx);
				if(!ctx.init) {
					//if('<?php echo str_replace($MCONF['home_uri'], '', $MCONF['web_uri']);?>' + ctx.canonicalPath != window.location.href) {

					showTopLoader();
					//Load Title
					var title = '';
					var query = '';
					if (ctx.querystring != '') {
						query += '&' + ctx.querystring;
					}
					$.get(ctx.pathname + '?title' + query, function (data) {
						title = data;
					}).fail(function (e) {
						if(e.status == 404){
							showMsg('<?php echo $lang->get('404_not_found');?> (' + e.statusText + ')');
						} else {
							showMsg('Error.');
						}
					});

					//Load Content
					$.get(ctx.pathname + '?direct' + query, function (data) {
						hideTopLoader();
						if (data == 'Login First.') {
							location.reload();
						} else {
							$("#loader").html(data);

							//Set Title
							$("#title").html(title);
							document.title = title + ' | <?php echo $lang->get('admin_title') . ' | ' . $MCONF['title']?>';

							//Update Menu
							$('li').each(function (index) {
								$(this).removeClass('active');
							});

							//Find Class & Parent for menu
							var menuitem = 'mw-menu-' + ctx.path.replace(/\//g, '-').replace('.php', '').replace('?', '').replace('&', '').replace('=', '');
							//console.log(menuitem);
							$('#' + menuitem).addClass('active');

							//Find Top item
							var topitems = menuitem.split('-');
							//console.log(topitems);
							$('#' + 'mw-menu-' + topitems[2] + '-' + topitems[3] + '-top').addClass('active');
							if (topitems[3] == 'roles' || topitems[3] == 'users' || topitems[3] == 'permissions' || topitems[3] == 'new_user') {
								$('#' + 'mw-menu-admin-users-top').addClass('active');
							}
						}
					}).fail(function (e) {
						if(e.status == 404){
							showMsg('<?php echo $lang->get('404_not_found');?> (' + e.statusText + ')');
						} else {
							showMsg('Error.');
						}
					});
					//	}
				}
			}
		});
	</script>
	</body>
	</html>
	<?php
}