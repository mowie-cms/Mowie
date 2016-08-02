<?php
if (isset($_POST['submit']))
{
	$dbtables = "CREATE TABLE IF NOT EXISTS `seiten` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `inhalt` longtext NOT NULL,
  `mailadresse` text NOT NULL,
  `datel` int(11) NOT NULL,
  `userl` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

INSERT INTO `".$MCONF['db_prefix']."seiten` (`name`, `inhalt`, `mailadresse`) VALUES
('Home', '<h1>Installation erfolgreich</h1><p>Diese Meldung siehst du, wenn die Installation erfolgreich war. Jetzt geh <a href=\"admin/\">schnell in den Adminbereich</a> um die erste Seite vernünftig anzulegen.</p>', ''),
('Impressum', '<h1>Impressum</h1><p>Bitte schnellstmöglichst ein Impressum einfügen, z.B. <a href=\"http://www.e-recht24.de/impressum-generator.html\">hier</a>.</p>', ''),
('Kontakt', '<p>Hier können Kontaktdaten eingetragen werden, diese erscheinen dann oben über dem Kontaktforumlar.</p>', ''),
('Datenschutzerklaerung', '<h1>Datenschutzerklärung</h1><p><strong>Geltungsbereich</strong></p><p>Diese Datenschutzerklärung klärt Nutzer über die Art, den Umfang und Zwecke der Erhebung und Verwendung personenbezogener Daten durch den verantwortlichen Anbieter [HIER BITTE IHREN NAMEN, ADRESSE, EMAIL UND TELEFONNUMMER EINTRAGEN] auf dieser Website (im folgenden “Angebot”) auf.<br />
<br />
Die rechtlichen Grundlagen des Datenschutzes finden sich im Bundesdatenschutzgesetz (BDSG) und dem Telemediengesetz (TMG).<br />
<br />
</p><p><strong>Zugriffsdaten/ Server-Logfiles</strong></p><p><span class=\"sd-muster-content\">Der Anbieter (beziehungsweise sein Webspace-Provider) erhebt Daten über jeden Zugriff auf das Angebot (so genannte Serverlogfiles). Zu den Zugriffsdaten gehören:<br />
<br />
Name der abgerufenen Webseite, Datei, Datum und Uhrzeit des Abrufs, übertragene Datenmenge, Meldung über erfolgreichen Abruf, Browsertyp nebst Version, das Betriebssystem des Nutzers, Referrer URL (die zuvor besuchte Seite), IP-Adresse und der anfragende Provider.<br />
<br />
Der Anbieter verwendet die Protokolldaten nur für statistische Auswertungen zum Zweck des Betriebs, der Sicherheit und der Optimierung des Angebotes. Der Anbieterbehält sich jedoch vor, die Protokolldaten nachträglich zu überprüfen, wenn aufgrund konkreter Anhaltspunkte der berechtigte Verdacht einer rechtswidrigen Nutzung besteht.</span></p><p><strong>Umgang mit personenbezogenen Daten</strong></p><p><span class=\"sd-muster-content\">Personenbezogene Daten sind Informationen, mit deren Hilfe eine Person bestimmbar ist, also Angaben, die zurück zu einer Person verfolgt werden können. Dazu gehören der Name, die Emailadresse oder die Telefonnummer. Aber auch Daten über Vorlieben, Hobbies, Mitgliedschaften oder welche Webseiten von jemandem angesehen wurden zählen zu personenbezogenen Daten.<br />
<br />
Personenbezogene Daten werden von dem Anbieter nur dann erhoben, genutzt und weiter gegeben, wenn dies gesetzlich erlaubt ist oder die Nutzer in die Datenerhebung einwilligen.</span></p><p><strong>Kontaktaufnahme</strong></p><p><span class=\"sd-muster-content\">Bei der Kontaktaufnahme mit dem Anbieter (zum Beispiel per Kontaktformular oder E-Mail) werden die Angaben des Nutzers zwecks Bearbeitung der Anfrage sowie für den Fall, dass Anschlussfragen entstehen, gespeichert.</span></p><p><strong>Kommentare und Beiträge</strong></p><p><span class=\"sd-muster-content\">Wenn Nutzer Kommentare im Blog oder sonstige Beiträge hinterlassen, werden ihre IP-Adressen gespeichert. Das erfolgt zur Sicherheit des Anbieters, falls jemand in Kommentaren und Beiträgen widerrechtliche Inhalte schreibt (Beleidigungen, verbotene politische Propaganda, etc.). In diesem Fall kann der Anbieter selbst für den Kommentar oder Beitrag belangt werden und ist daher an der Identität des Verfassers interessiert.</span></p><p><strong>Newsletter</strong></p><p><span class=\"sd-muster-content\">Mit dem Newsletter informieren wir Sie über uns und unsere Angebote.<br />
<br />
Wenn Sie den Newsletter empfangen möchten, benötigen wir von Ihnen eine valide Email-Adresse sowie Informationen, die uns die Überprüfung gestatten, dass Sie der Inhaber der angegebenen Email-Adresse sind bzw. deren Inhaber mit dem Empfang des Newsletters einverstanden ist. Weitere Daten werden nicht erhoben. Diese Daten werden nur für den Versand der Newsletter verwendet und werden nicht an Dritte weiter gegeben.<br />
<br />
Mit der Anmeldung zum Newsletter speichern wir Ihre IP-Adresse und das Datum der Anmeldung. Diese Speicherung dient alleine dem Nachweis im Fall, dass ein Dritter eine Emailadresse missbraucht und sich ohne Wissen des Berechtigten für den Newsletterempfang anmeldet.<br />
<br />
Ihre Einwilligung zur Speicherung der Daten, der Email-Adresse sowie deren Nutzung zum Versand des Newsletters können Sie jederzeit widerrufen. Der Widerruf kann über einen Link in den Newslettern selbst, in Ihrem Profilbereich oder per Mitteilung an die oben stehenden Kontaktmöglichkeiten erfolgen.<br />
</span></p><p><strong>Einbindung von Diensten und Inhalten Dritter</strong></p><p><span class=\"sd-muster-content\">Es kann vorkommen, dass innerhalb dieses Onlineangebotes Inhalte Dritter, wie zum Beispiel Videos von YouTube, Kartenmaterial von Google-Maps, RSS-Feeds oder Grafiken von anderen Webseiten eingebunden werden. Dies setzt immer voraus, dass die Anbieter dieser Inhalte (nachfolgend bezeichnet als \"Dritt-Anbieter\") die IP-Adresse der Nutzer wahr nehmen. Denn ohne die IP-Adresse, könnten sie die Inhalte nicht an den Browser des jeweiligen Nutzers senden. Die IP-Adresse ist damit für die Darstellung dieser Inhalte erforderlich. Wir bemühen uns nur solche Inhalte zu verwenden, deren jeweilige Anbieter die IP-Adresse lediglich zur Auslieferung der Inhalte verwenden. Jedoch  haben wir keinen Einfluss darauf, falls die Dritt-Anbieter die IP-Adresse z.B. für statistische Zwecke speichern. Soweit dies uns bekannt ist, klären wir die Nutzer darüber auf.<br />
</span></p><p><strong>Cookies</strong></p><p><span class=\"sd-muster-content\">Cookies sind kleine Files, die es ermöglichen, auf dem Zugriffsgerät der Nutzer (PC, Smartphone o.ä.) spezifische, auf das Gerät bezogene Informationen zu speichern. Sie dienen zum einem der Benutzerfreundlichkeit von Webseiten und damit den Nutzern (z.B. Speicherung von Logindaten). Zum anderen dienen sie, um die statistische Daten der Webseitennutzung zu erfassen und sie zwecks Verbesserung des Angebotes analysieren zu können. Die Nutzer können auf den Einsatz der Cookies Einfluss nehmen. Die meisten Browser verfügen eine Option mit der das Speichern von Cookies eingeschränkt oder komplett verhindert wird. Allerdings wird darauf hingewiesen, dass die Nutzung und insbesondere der Nutzungskomfort ohne Cookies eingeschränkt werden.<br />
<br />
Sie können viele Online-Anzeigen-Cookies von Unternehmen über die US-amerikanische  Seite <a href=\"http://www.aboutads.info/choices/\">http://www.aboutads.info/choices/</a> oder die EU-Seite <a href=\"http://www.youronlinechoices.com/uk/your-ad-choices/ \">http://www.youronlinechoices.com/uk/your-ad-choices/ </a> verwalten.</span></p><p><strong>Widerruf, Änderungen, Berichtigungen und Aktualisierungen</strong></p><p>Der Nutzer hat das Recht, auf Antrag unentgeltlich Auskunft zu erhalten über die personenbezogenen Daten, die über ihn gespeichert wurden. Zusätzlich hat der Nutzer das Recht auf Berichtigung unrichtiger Daten, Sperrung und Löschung seiner personenbezogenen Daten, soweit dem keine gesetzliche Aufbewahrungspflicht entgegensteht.<br />
</p><p><a href=\"http://rechtsanwalt-schwenke.de/smmr-buch/datenschutz-muster-generator-fuer-webseiten-blogs-und-social-media/\"><strong>Datenschutz-Muster von Rechtsanwalt Thomas Schwenke - I LAW it</strong></a></p>', '');

CREATE TABLE IF NOT EXISTS `seitenneu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `inhalt` longtext NOT NULL,
  `geaendertby` text NOT NULL,
  `datum` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";
	$dbtables = $DBH->prepare($dbtables);
	if($dbtables->execute())
	{
		$configini = fopen('freischalt.ini', "w") or die("Unable to open file!");
		$txt = 'freischaltmailReq = '.$_POST['freischalt_y'].' \n mail_adresse_freischalttyp = "'.$_POST['freischalt_m'].'" \n freischalten_name = "'.$_POST['freischalt_n'].'"';
		fwrite($configini, $txt);
		fclose($configini);
		echo msg('succes', 'Das Seitenmodul wurde erfolgreich eingerichtet.<br/>');
	}
	else
	{
		echo msg('fail', 'Fehler beim Einrichten des Seitenmoduls.');
		exit;
	}
}
else
{
	echo '<span>Benötigt Freischaltung der Inhalte</span><select name="freischalt_y"><option value="true">Ja</option><option value="false">Nein</option></select><br/><br/>
    <span>Benutzername des Freischaltenden Benutzers</span><input type="text" placeholder="Benutzername des Freischaltenden Benutzers" name="freischalt_n" value=""/><br/><br/>
    <span>Mailadressen des Freischaltenden Benutzers</span><input type="text" placeholder="Mailadressen des Freischaltenden Benutzers" name="freischalt_m" value=""/><br/><br/>';
}
?>