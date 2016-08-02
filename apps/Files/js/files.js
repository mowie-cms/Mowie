function getFiles(folder) {
    var filesContent = '';
    console.log(folder);
    $.ajax({
        url: webUri + 'module/Files/index.php?json&folder=' + folder,
        dataType: 'json',
        success: function (data) {
            msgDo('&nbsp;');
            if (folder == '') folder = 'Files';
            if (data.displayUrl == 'Files/') {
                $('#delFolder').hide();
            }
            else {
                $('#delFolder').show();
                //History
                var title = $(document).find("title").text();
                historyc(webUri + 'module/Files/index.php?folder_view=' + data.displayUrl, title);
            }

            $('#files').html('<h3 id="displayUrl">' + data.displayUrl + '</h3><table id="filesList" width="100%"><thead><tr><th>Dateiname</th><th>Änderungsdatum</th><th>Typ</th><th>Größe</th></tr></thead><tbody id="filesContent"></tbody></table><div id="extra"></div>');
            $.each(data.files, function () {
                if (this.name == '..') {
                    var name = '<a onclick="openFile(\'..\', \'' + this.icon + '\', \'' + webUri + data.displayUrl + this.name + '\');"><i class="icon-' + this.icon + '"></i>  ' + this.name;
                } else {
                    var name = '<a onclick="openFile(\'' + this.name + '\', \'' + this.icon + '\', \'' + webUri + data.displayUrl + this.name + '\');"><i class="icon-' + this.icon + '"></i>  ' + this.name;
                }

                var static_folder = '';
                if (this.icon == 'folder2') {
                    static_folder = ' class="static"';
                }
                //$('#filesContent').append('<tr><td>' + name + '</td><td>' + this.date + '</td><td>' + this.type + '</td><td>' + this.size + '</td></tr>');
                filesContent += '<tr' + static_folder + '><td>' + name + '</td><td>' + this.date + '</td><td>' + this.type + '</td><td>' + this.size + '</td></tr>';
            });
        },
        timeout: 5000,
        error: function (jqXHR, status, errorThrown) {
            console.log(errorThrown, status);
            var extra_info = status;
            if(errorThrown == 'Not Found') extra_info = 'Der Ordner wurde nicht gefunden.';
            showMsg('Ein Fehler ist aufgetreten. (' + extra_info + ') <a href="">Seite neu laden</a> und erneut versuchen');
        }
    });

    //Tabelle Sortieren
    setTimeout(function () {
        $('#filesContent').html(filesContent);
        $("table").tablesorter({
            widgets: ['staticRow']
        });
        $("table").trigger("sorton", [[[0, 0]]]);
        $('#msgDoContainer').hide();
    }, 300);
}

getFiles('');

function openFile(file, type, url) {
    console.log(file, type);
    if (type == 'folder2') {
        var folder_curr = $('#displayUrl').html();
        getFiles(folder_curr + file);
    }
    else {
        $('#extra').html('<div class="overlay" style="display:none;"><div class="window"><div class="head">' + file + '<a onclick="closeW();" class="closeMsg"><i class="icon-close"></i></a></div><div id="content"></div></div></div>');
        if (type == 'file-audio-o') {//Audio
            $('#content').html('<audio controls src="' + url + '" autoplay></audio>');
        }
        else if (type == 'file-movie-o') {//Video
            $('#content').html('<video controls src="' + url + '" autoplay></video>');
            //$('#content').html('<video class="video-js vjs-default-skin" width="640px" height="360px" controls preload="true" data-setup=\'{ "aspectRatio":"640:360" }\' src="' + url + '"></video>');
        }
        else if (type == 'file-picture-o') {//Bild oder Bild, alles was in einen iFrame passt
            $('#content').html('<div class="imgContaienr"><img src="' + url + '" alt="" id="imgZoom"/></div>');
            $('#content').css('margin-top', '32px');
            //Bild Zoom
            var img = document.getElementById('imgZoom');
            console.log()
            if (img.naturalHeight > 500 || img.naturalWidth > 984) {
                $('#imgZoom').click(function () {
                    $('#imgZoom').toggleClass('zoomedin');
                });
            }
            else {
                $('#imgZoom').css('cursor', 'default');
            }
        }
        else if (type == 'file-pdf-o') {//Bild oder Bild, alles was in einen iFrame passt
            $('#content').html('<iframe src="' + url + '" width="102%" height="500" style="margin: 0px -10px;"></iframe>');
            $('#content').css('margin-top', '32px');
        }
        else if (type == 'file-archive-o' || type == 'file-powerpoint-o' || type == 'file-word-o' || type == 'file-excel-o') {//Nix, weil die Datei nicht angezeigt werden kann -> Archive, Word, pp

        }
        else {//Sonst text -> Wenn Sonst oder code
            $('#content').html('<div><textarea id="text"></textarea></div>');
            $('#text').load(url);
        }
        $('#content').append('<p>URL:<input type="text" class="select" value="' + url + '" onClick="this.select();"/></p><p><a href="' + webUri + 'module/Files/index.php?dl=' + url + '" class="button"><i class="icon-download"></i>  Herunterladen</a>  <a onclick="deleteFile(\'' + $('#displayUrl').html() + file + '\');" class="button btn_del"><i class="icon-trash-o"></i>  Löschen</a></p>');

        //uuund einbelnden
        $(".overlay").fadeIn(250);

        //Rumschieben
        //$('.window').drags();
    }
}

function closeW() {
    $(".overlay").fadeOut(200);
    setTimeout(function () {
        $('#extra').html('');
    }, 300);
}

function uploadFileBtn() {
    $("#upbutton").click();
}

//Neuen ordner
function newFolder() {
    $('#extra').html('<div class="overlay" style="display:none;"><div class="window"><div class="head">Neuen Ordner erstellen<a onclick="closeW();" class="closeMsg"><i class="icon-close"></i></a></div><div id="content"></div></div></div>');
    $('#content').append('<p><form action="" onsubmit="newFolderSub();return false;"><input type="text" class="select" placeholder="Namen eingeben..." id="folderName" autofocus/><input type="submit" value="Neuen Ordner erstellen"/></form></p>');
    $('#folderName').focus();
    //uuund einbelnden
    $(".overlay").fadeIn(250);
}

function newFolderSub() {
    closeW();
    msgDo('Erstelle neuen Ordner...');
    var folder = $('#displayUrl').html();
    $.ajax({
        type: 'POST',
        url: webUri + 'module/Files/index.php?newFolder',
        data: 'name=' + $('#folderName').val() + '&dir=' + folder,
        success: function (msg) {
            console.log(msg);
            if (msg == 'success') {
                showMsg('Der neue Ordner wurde erfolgreich erstellt.');
                getFiles(folder);
                $('#msgDoContainer').hide();
            }
            else {
                showMsg('Fehler beim erstellen des Ordners.');
                $('#msgDoContainer').hide();
            }
        }
    });
    return false;
}

//Ordner löschen
function delFolder() {
    var file = $('#displayUrl').html();
    $('#extra').html('<div class="overlay" style="display:none;"><div class="window window-confirm"><div class="head">"' + file + '" löschen<a onclick="closeW();" class="closeMsg"><i class="icon-close"></i></a></div><div id="content"><p>Ordner wirklich löschen? Dies wird den gesamten Ordner mit Inhalt löschen! <b>Dieser Vorgang kann nicht rückgängig gemacht werden!</b></p><p><a onclick="confirmDeleteFile(\'' + file + '\', true);" class="button btn_del"><i class="icon-trash-o"></i>&nbsp;&nbsp;Löschen</a><a onclick="closeW();" class="button">Abbrechen</a></p></div></div></div>');
    //uuund einbelnden
    $(".overlay").fadeIn(250);
}

//Datei Löschen
function deleteFile(file) {
    $('#extra').html('<div class="overlay" style="display:none;"><div class="window window-confirm"><div class="head">"' + file + '" löschen<a onclick="closeW();" class="closeMsg"><i class="icon-close"></i></a></div><div id="content"><p>Datei "' + file + '" wirklich löschen? <b>Dieser Vorgang kann nicht rückgängig gemacht werden!</b></p><p><a onclick="confirmDeleteFile(\'' + file + '\', false);" class="button btn_del"><i class="icon-trash-o"></i>&nbsp;&nbsp;Löschen</a><a onclick="closeW();" class="button">Abbrechen</a></p></div></div></div>');
    //uuund einbelnden
    $(".overlay").fadeIn(250);
}

function confirmDeleteFile(file, isdir) {
    closeW();
    if (isdir) {
        msgDo('Lösche Ordner...');
    }
    else {
        msgDo('Lösche Datei...');
    }

    $.ajax({
        type: 'POST',
        url: webUri + 'module/Files/index.php?del',
        data: 'file=' + file,
        success: function (msg) {
            console.log(msg);
            if (msg == 'success') {
                if (isdir) {
                    showMsg('Der Ordner "' + file + '" wurde erfolgreich gelöscht.');
                    getFiles('');
                }
                else {
                    showMsg('Die Datei "' + file + '" wurde erfolgreich gelöscht.');
                    getFiles($('#displayUrl').html());
                }
                $('#msgDoContainer').hide();
            }
            else {
                showMsg('Fehler beim Löschen der Datei.');
                $('#msgDoContainer').hide();
            }
        }
    });
}

//Upload
//Dropupload
var obj = $(document);
obj.on('dragenter', function (e)
{
    e.stopPropagation();
    e.preventDefault();
    //$(this).css('border', '2px solid #0B85A1');
    console.log('dragenter');
    $('#dropFileArea').show();
});

obj.on('dragover', function (e)
{
    e.stopPropagation();
    e.preventDefault();
    //console.log('dragover');
    //$('#dropFileArea').show();
});

obj.on('drop', function (e)
{
    $('#dropFileArea').hide();
    e.preventDefault();
    var files = e.originalEvent.dataTransfer.files;

    //We need to send dropped files to Server
    handleFileUpload(files);
});

$('#upbutton').change(function () {
    var file = this.files[0];
    var formData = new FormData($('form')[0]);
    fileUpload(formData, file);
});

function handleFileUpload(files)
{
    for (var i = 0; i < files.length; i++)
    {
        var fd = new FormData();
        fd.append('file', files[i]);

        fileUpload(fd, files[i]);
    }
}

function fileUpload(formData, file){
    console.log(formData, file);
    //max filesize
    $.get(webUri + 'module/Files/index.php?max_file_size', function (data) {
        if (file.size <= data) {

            var folder = $('#displayUrl').html();
            $.ajax({
                url: webUri + 'module/Files/index.php?upload=' + folder,  //Server script to process data
                type: 'POST',
                xhr: function () {  // Custom XMLHttpRequest
                    var myXhr = $.ajaxSettings.xhr();
                    if (myXhr.upload) { // Check if upload property exists
                        myXhr.upload.addEventListener('progress', progressHandlingFunction, false); // For handling the progress of the upload
                    }
                    return myXhr;
                },
                //Ajax events
                beforeSend: beforeSendHandler,
                success: completeHandler,
                error: errorHandler,
                // Form data
                data: formData,
                //Options to tell jQuery not to process data or worry about content-type.
                cache: false,
                contentType: false,
                processData: false
            });
        }
        else {
            showMsg('Die ausgewählte Datei ist zu groß!');
        }
    });
}

function progressHandlingFunction(e) {
    if (e.lengthComputable) {
        var prozent = (e.loaded / e.total) * 100;
        msgDo('Datei wird Hochgeladen... [' + Math.round(prozent * 100) / 100 + '%]<div class="progbar_btm"></div>');
        $('.progbar_btm').css('width', prozent + '%');
        $('#msgDo').css('width', '250px');
        if (prozent == 100)
        {
            $('#msgDo').css('width', '232px');
            $('#msgDo').html('Datei wird verarbeitet...');
        }
    }
}

function beforeSendHandler() {
    console.log('ready');
    $('#upButton').prop("disabled", true);
}

function completeHandler(msg) {
    console.log('upload abgeschlossen', msg);
    $('#msgDoContainer').hide();
    $('#upButton').prop("disabled", false);
    if(msg == 'success') {
        showMsg('Upload abgeschlossen.');
        var folder = $('#displayUrl').html();
        getFiles(folder);
    }
    else {
        showMsg('Upload fehlgeschlagen.');
    }
}

function errorHandler() {
    console.log('upload fehlgeschlagen');
    $('#progressbox').hide();
    $('#msgDoContainer').hide();
    $('#upButton').prop("disabled", false);
    showMsg('Upload fehlgeschlagen.');
}

//MsgDOO
function msgDo(msg) {
    $('#msgDoContainer').css('display', 'inline-block');
    $('#msgDo').html(msg);
}

//history
function historyc(url, title) {
    //Histroy state
    var State = History.getState(), $log = $('#log');
    History.Adapter.bind(window, 'statechange', function () {
        var State = History.getState();
    });
    History.pushState({state: 1, rand: Math.random()}, title, url);
}

//Wenn zurücktaste gedrückt
window.addEventListener('popstate', function (event) {
    //Histroy state
    var State = History.getState(), $log = $('#log');
    History.Adapter.bind(window, 'statechange', function () {
        var State = History.getState();
    });
    var title = State.title;

    //Seite wieder zusammenbauen
    var curr = $('#displayUrl').html();
    if (curr == 'Files/') {
        History.pushState({state: 1, rand: Math.random()}, title, webUri + 'module/Files/index.php');
        getFiles('');
    }
    else {
        History.pushState({state: 1, rand: Math.random()}, title, webUri + 'module/Files/index.php?folder_view=' + curr);
        getFiles(curr + '..');
    }
});