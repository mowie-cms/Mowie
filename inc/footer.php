<?php
if (!isset($_GET['direct']))
{
	?>
	</div>
	<div id="showMsg"></div>
	<!--<script src="<?php echo $MCONF['web_uri'] ?>admin/assets/js/page.js"></script>
	<script src="<?php echo $MCONF['web_uri'] ?>admin/assets/js/page.bodyparser.js"></script>-->
	<script>
		//Msg
		function showMsg(msg) {
			$('#showMsg').html('<div class="snackbar"><a onclick="closeMsg();" class="closeMsg"><i class="fa fa-close"></i> </a><p>' + msg + '</p></div>');
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

		//Change current Language
		$('#langselectbtn').click(function () {
			$('.langs').fadeToggle(100);
		});

		function changeLang(lang) {
			showTopLoader();
			$.get('<?php echo $MCONF['home_uri'];?>admin/lang.php?set=' + lang, function (data) {
				console.log(data);
				if(data == 1){
					location.reload();
				} else {
					showMsg('Error.');
				}
			})
		}
		$(document).ready(function () {
			//Router
			$('#topnav').addClass('no-transition');

			///page.base('<?php echo $MCONF['home_uri'];?>');

			page('*', findPage);
			page();

			pageBodyParser();

			function findPage(ctx, next) {
				console.log(ctx);
				if(!ctx.init) {
					if (ctx.body) { //If POST-Request, send Post via ajax

						var isAjax = false;
						var requestData = 'direct=true';
						for (var key in ctx.body) {
							if (!ctx.body.hasOwnProperty(key)) continue;

							requestData += '&' + key + '=' + ctx.body[key];
							if(key == 'ajax') isAjax = true;
						}

						if(!isAjax) {
							$.ajax({
								url: ctx.canonicalPath,
								type: 'POST',
								cache: false,
								data: requestData,
								beforeSend: function () {
									showTopLoader();
								},
								complete: function () {
									hideTopLoader();
								},
								success: function (result) {
									$("#loader").html(result);
								},
								error: function (xhr, status, error) {
									console.log(status, error);
								}
							});
						}
					} else {//Otherwise display Contents

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
							if (e.status == 404) {
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
							if (e.status == 404) {
								showMsg('<?php echo $lang->get('404_not_found');?> (' + e.statusText + ')');
							} else {
								showMsg('Error.');
							}
						});
					}
				}
			}
		});
	</script>
	</body>
	</html>
	<?php
}