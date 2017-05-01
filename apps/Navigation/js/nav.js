//Create the Spinner
$('#title').append('<div class="spinner-container" style="display:inline-block;vertical-align: middle;padding: 0px 10px;"><svg class="spinner" style="width: 25px;" viewBox="0 0 44 44"><circle class="path" cx="22" cy="22" r="20" fill="none" stroke-width="4"></circle></svg></div>');
//Hide it
$('.spinner-container').hide();

$(function () {
    $("#sortable").sortable({
        axis: "y",
        cursor: "move",
        items: 'div:not(.top).row',
        placeholder: "sortable-placeholder",
        over: function (event, ui) {
            console.log(event, ui)
        },
        stop: function (event, ui) {// When finished sorting, send all data to the server to process
            $('.spinner-container').show(); //Show loader
            $.ajax({
                url: 'action.php?save',
                type: 'POST',
                cache: false,
                data: $("#sortable").sortable("serialize"), //Get the newly sorted array
                success: function (result) { // On success, display a message..
                    if (result == 'success') {
                        showMsg(lang.nav_saved_success);
                    } else {
                        showMsg(lang.nav_saved_fail);
                    }

                    //And reload the content. We do this to display everything including their childs
                    reloadNav();

                    $('.spinner-container').hide(); //Hide the Loader
                },
                error: function (xhr, status, error) {
                    console.log(status, error);
                    showMsg(lang.nav_saved_fail);
                }
            });
        }
    });
});

//Delete
function del(id) {

    $('#extra').html('<div class="overlay" style="display:none;"><div class="window window-confirm"><div class="head">' + lang.nav_delete + '<a onclick="closeW();" class="closeMsg"><i class="fa fa-close"></i></a></div><div id="content"><p>' + lang.nav_delete_confirm + '</p><p><a class="button btn_del" id="deleteConfirm"><i class="fa fa-trash-o"></i>&nbsp;&nbsp;' + lang.nav_delete_confirm_yes + '</a><a onclick="closeW();" class="button">' + lang.nav_delete_confirm_abort + '</a></p></div></div></div>');
    //uuund einbelnden
    $(".overlay").fadeIn(250);

    $('#deleteConfirm').click(function () {
        closeW();
        $.ajax({
            url: 'action.php?del',
            type: 'POST',
            cache: false,
            data: 'id=' + id,
            success: function (result) { // On success, display a message...
                console.log(result);
                if (result == 'success') {
                    showMsg(lang.nav_deleted_success);
                } else {
                    showMsg(lang.nav_deleted_fail);
                }

                //...and reload the content. We do this to display everything including their childs
                reloadNav();

                $('.spinner-container').hide(); //Hide the Loader
            },
            error: function (xhr, status, error) {
                console.log(status, error);
                showMsg(lang.nav_deleted_fail);
            }
        });
    });
}

//Create menuitem
function createItem() {
    $('#extra').html('<div class="overlay" style="display:none;"><div class="window window-confirm"><div class="head">' + lang.nav_create + '<a onclick="closeW();" class="closeMsg"><i class="fa fa-close"></i></a></div><div id="content"><p><input type="text" name="nav_title" id="nav_title" placeholder="' + lang.nav_create_title + '"/></p><p>' + lang.nav_create_page + ' <input type="checkbox" id="externalCheck"/><label for="externalCheck"><i></i>' + lang.nav_create_external + ' </label> <select name="nav_page" id="nav_page">' + pages + '</select><input type="text" name="nav_external" id="nav_external" style="display: none;" placeholder="' + lang.nav_create_external_input + '"/></p><p>' + lang.nav_create_parents + ': <select name="nav_parent" id="nav_parent">' + parents + '</select></p><p><a class="button" id="createConfirm"><i class="fa fa-plus"></i>&nbsp;&nbsp;' + lang.nav_create_create + '</a><a onclick="closeW();" class="button btn_del">' + lang.nav_create_abort + '</a></p></div></div></div>');
    $(".overlay").fadeIn(250);

    //Check for checked
    $('#externalCheck').change(function () {
        if (this.checked) {
            $('#nav_page').hide();
            $('#nav_external').show().focus();
            $('#nav_title').attr('placeholder', lang.nav_create_title_noptoption);
        } else {
            $('#nav_page').show();
            $('#nav_external').hide();
            $('#nav_title').attr('placeholder', lang.nav_create_title);
        }
    });

    //Send
    $('#createConfirm').click(function () {

        var external = $('#nav_external').val();
        var sendReady = true;
        if ($('#externalCheck').is(':checked')) {
            if ($('#nav_title').val() == '') {
                sendReady = false;
                showMsg(lang.nav_create_external_needs_title);
            }
        }

        //Send
        if (sendReady) {
            $.ajax({
                url: 'action.php?create',
                type: 'POST',
                cache: false,
                data: 'title=' + $('#nav_title').val() + '&page=' + $('#nav_page').val() + '&parent=' + $('#nav_parent').val() + '&external=' + external,
                success: function (result) { // On success, display a message...
                    if (result == 'success') {
                        showMsg(lang.nav_create_success);
                        reloadNav();//...and reload the content. We do this to display everything properly including their childs
                    } else if(result == 'url_invalid') {
                        showMsg(lang.nav_create_external_url_invalid);
                        $('#nav_external').focus();
                    } else {
                        showMsg(lang.nav_create_fail);
                        reloadNav();//...and reload the content. We do this to display everything properly including their childs
                    }

                    $('.spinner-container').hide(); //Hide the Loader
                },
                error: function (xhr, status, error) {
                    showMsg(lang.nav_create_fail);
                }
            });
        }
    });
}

function update(id) {
    var newParent = $('#parentChange_' + id).val();

    $.ajax({
        url: 'action.php?update',
        type: 'POST',
        cache: false,
        data: 'id=' + id + '&parent=' + newParent,
        success: function (result) { // On success, display a message...
            if (result == 'success') {
                showMsg(lang.nav_update_success);
            } else {
                showMsg(lang.nav_update_fail);
            }

            //...and reload the content. We do this to display everything including their childs
            reloadNav();

            $('.spinner-container').hide(); //Hide the Loader
        },
        error: function (xhr, status, error) {
            showMsg(lang.nav_update_fail);
            $('.spinner-container').hide(); //Hide the Loader
        }
    });
}

//Reload
function reloadNav() {
    $.get('index.php?direct', function (data) {
        $("#loader").html(data);
    }).fail(function (e) {
        if (e.status == 404) {
            showMsg(lang.not_found + ' (' + e.statusText + ')');
        } else {
            showMsg('Error.');
        }
    });
}

//Close Window
function closeW() {
    console.log('close');
    $(".overlay").fadeOut(200);
    setTimeout(function () {
        $('#extra').html('');
    }, 300);
}

window.onclick = function (event) {
    if (event.target.parentElement != null) {
        if (event.target.parentElement.id == 'extra') {
            closeW();
        }
    }
}