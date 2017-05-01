<?php
if (!isset($_GET['direct']))
{
	?>


    </div>
    <div id="showMsg"></div>
    <script src="<?php echo $MCONF['web_uri'] ?>admin/assets/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>

    <script src="<?php echo $MCONF['web_uri'] ?>admin/assets/js/moment.js"></script>

    <!--<script src="<?php echo $MCONF['web_uri'] ?>admin/assets/js/page.js"></script>
	<script src="<?php echo $MCONF['web_uri'] ?>admin/assets/js/page.bodyparser.js"></script>-->
    <script>
        moment.locale('de');

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
                if (data == 1) {
                    location.reload();
                } else {
                    showMsg('Error.');
                }
            })
        }

        //showStream
        function showStream() {
            $('#streamContent').fadeToggle(100, function () {
                if ($('#streamContent').is(":visible")) {
                    $.getJSON('<?php echo $MCONF['home_uri'];?>admin/stream.php?getStream&limit=10', function (streamData) {
                        $('#streamContent').html('');
                        $.each(streamData, function (key, val) {
                            $('#streamContent').append('<p>' + val.message + ' (' + moment(val.time * 1000).fromNow() + ')</p>');
                        });
                        $('#streamContent').append('<a href="<?php echo $MCONF['home_uri'];?>admin/stream.php" class="button">Mehr</a>');
                    });
                }
            });
        }

        function closeW() {
            $('.overlay').fadeOut(200);
            $('.overlay').html('');
        }

        function sendPost(ctx, requestData) {
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

        $(document).ready(function () {

            //Router
            $('#topnav').addClass('no-transition');

            page('*', findPage);
            page();

            pageBodyParser();

            function findPage(ctx, next) {
                if (!ctx.init) {
                    if (ctx.body) { //If POST-Request, send Post via ajax

                        var isAjax = false;
                        var requestData = 'direct=true';
                        var editorname = '';
                        var needsPwConfirm = false;
                        //console.log(typeof(tinyMCE));
                        if (typeof(tinyMCE) != "undefined" && tinyMCE.activeEditor != null) {
                            editorname = $('#' + tinyMCE.activeEditor.id).attr("name");
                        }//Get the new Content, not the old

                        for (var key in ctx.body) {
                            if (!ctx.body.hasOwnProperty(key)) continue;

                            //Check users password
                            if (key == 'askPW') {
                                needsPwConfirm = true;
                            }

                            //If we have content edited with tinymce, we want the new content to be passed with the POST-Request
                            if (key == editorname) {
                                console.log(tinyMCE);
                                requestData += '&' + key + '=' + encodeURIComponent(tinyMCE.activeEditor.getContent());
                            } else {
                                requestData += '&' + key + '=' + encodeURIComponent(ctx.body[key]);
                            }
                            if (key == 'ajax') isAjax = true;
                        }

                        //Confirm user password
                        if (needsPwConfirm) {
                            $('#showMsg').html('<div class="overlay" style="display:none;"><div class="window-confirm"><div class="head"><?php echo $lang->get('legitimate_title')?><a onclick="closeW();" class="closeMsg"><i class="fa fa-close"></i></a></div><div id="content"></div></div></div>');
                            $('#content').append('<p><?php echo $lang->get('legitimate_text')?></p><p><input type="password" placeholder="<?php echo $lang->get('password')?>" id="password_legitimate" autofocus/><input type="submit" value="<?php echo $lang->get('legitimate_confirm')?>" id="legitimateSmbt"/><a onclick="closeW();" class="button btn_del"><?php echo $lang->get('legitimate_abort')?></a></p><span id="sendMsg"></span>');
                            $('#password_legitimate').focus();
                            $(".overlay").fadeIn(250);

                            $('#legitimateSmbt').click(function () {
                                $.ajax({
                                    url: 'login.php?checkPassword',
                                    type: 'POST',
                                    cache: false,
                                    data: 'pw=' + $('#password_legitimate').val(),
                                    success: function (result) { // On success, display a message...
                                        if (result == 'success') {
                                            closeW();

                                            //Send the request
                                            if (!isAjax) {
                                                sendPost(ctx, requestData);
                                            }
                                        } else if (result == 'fail') {
                                            $('#sendMsg').html('<p style="color:red;"><?php echo $lang->get('legitimate_fail')?></p>');
                                        } else {
                                            $('#sendMsg').html('<p style="color:red;"><?php echo $lang->get('legitimate_error')?></p>');
                                        }
                                    },
                                    error: function (xhr, status, error) {
                                        console.log(status, error);
                                        showMsg('<?php echo $lang->get('legitimate_error')?>');
                                    }
                                });
                            });
                        } else {
                            if (!isAjax) {
                                sendPost(ctx, requestData);
                            }
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

                                //Get CSS
                                $('#addedCss').remove(); // Remove old CSS
                                $.get(ctx.pathname + '?css' + query, function (data) {
                                    if (data.css) {
                                        for (var i = 0; i < data.css_files.length; i++) {
                                            var cssFile = data.css_files[i];
                                            $('head').append('<link rel="stylesheet" href="' + data.fullUri + cssFile + '" type="text/css" id="addedCss">');
                                        }
                                    }
                                });
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