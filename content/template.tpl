<!DOCTYPE html>
<html>
<head>
    <title>{$title}</title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
{$meta}
    <link rel="stylesheet" type="text/css" href="{$website_uri}content/css/layout.css" media="screen"/>
    <link rel="stylesheet" type="text/css" href="{$website_uri}content/css/layout_print.css" media="print"/>
    <link rel="shourtcut icon" href="{$website_uri}favicon.ico"/>
</head>
<body>
    <div class="container">
        {$menu}
    <div class="seite">
        {$sidebar}
        {$content}
        {$test}
        {$test2}
	</div>
	<div class="footer">
      <p>&copy; 2015 | <a href="http://kola-entertainments.de" target="_blank">KoLa Entertainments</a> | <a href="{$website_uri}Impressum">Impressum</a></p>
	</div>
  </div>
</body>
</html>