<?php

/*
 * Class Multicookie
 * Ermöglicht es, mit simpler verwaltung in einem Cookie mehrere Werte zu speichern
 *
 * Benutzung:
 * $cookie = new multicookie('TestCookie');//Erstellt einen neuen Cookie mit Namen "TestCookie" mit mehreren (möglichen) Werten. Standardwert für den Cookie ist "multicookie".
 * $cookie->setValue('test', "gedöns");//Speichert wert "gedöns" mit key "test ab, um ihn später wiederzufinden
 * $cookie->setValue('testMitArray', ['booo', 'easaf', 'Lorem' => 'IUpsum']);
 * $cookie->updateCookie();//Updated den Cookie, wenn er noch nicht existerite wird er angelegt, ansonsten werden die einzelnen Werte überschrieben. Gibt true oder false zurück.
 * $cookie->getAllValues(); //Gibt alle Werte des Cookies als Array zuück
 * $cookie->getValue('test'); // Hohlt den Wert, welcher dem Key "test" zugeordnet ist
 * $cookie->isValue($key); // Gibt true zurück, wenn für $key ein Wert existiert
 * $cookie->deleteCookie(); // Löscht den Cookie. Gibt true oder false zurück.
 *
 */

class multicookie
{
	private $cookieContent;
	private $cookieName;
	private $cookieTime;

	//Initialiesierung
	function __construct($cookie = 'multicookie', $time = 2592000)
	{
		$this->cookieName = $cookie;
		$this->cookieContent = [$cookie => []];
		if(isset($_COOKIE[$this->cookieName]))
		{
			$this->cookieContent = json_decode($_COOKIE[$this->cookieName], true);
		}
		$this->cookieTime = time() + $time;
	}

	//Vars einpflegen
	public function setValue($key, $val)
	{
		$this->cookieContent[$this->cookieName][$key] = $val;
	}

	//Vars holen
	public function getValue($key)
	{
		if(isset($_COOKIE[$this->cookieName]))
		{
			$vars = json_decode($_COOKIE[$this->cookieName], true);
			foreach ($vars[$this->cookieName] as $k => $v)
			{
				if ($k == $key)
				{
					return $v;
				}
			}
		}
		else
		{
			return null;
		}
	}

	//Existiert ein Wert?
	public function isValue($key)
	{
		if($this->getValue($key) === null)
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	//Alle vars ausgeben
	public function getAllValues()
	{
		if(isset($_COOKIE[$this->cookieName]))
		{
			$varsAlle = [];
			$vars = json_decode($_COOKIE[$this->cookieName], true);
			foreach ($vars[$this->cookieName] as $k => $v)
			{
				$varsAlle[$k] = $v;
			}
			return $varsAlle;
		}
		else
		{
			return null;
		}
	}

	//Cookie Löschen
	public function deleteCookie()
	{
		return setcookie($this->cookieName, false, time() - 30759000, '/');
	}

	//Den Cookie updaten/setzen
	public function updateCookie()
	{
		//$this->deleteCookie();
		return setcookie($this->cookieName, json_encode($this->cookieContent), $this->cookieTime, '/');
	}
}

?>