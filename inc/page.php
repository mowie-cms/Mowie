<?php

class page extends Smarty
{
	private $url;
	private $content = '';
	private $baseUrl;
	private $responseCode;
	private $title;
	private $domain;
	private $template;

	//url fkt
	public function setUrl($url)
	{
		$this->url = $url;
	}

	public function getUrl()
	{
		//echo parse_url($this->url, PHP_URL_PATH);
		//print_r($_GET);
		return parse_url($this->url, PHP_URL_PATH);
	}

	//baseUrl
	public function setBaseUrl($baseUrl)
	{
		$this->baseUrl = $baseUrl;
	}

	public function getBaseUrl()
	{
		return $this->baseUrl;
	}

	//content
	public function setContent($content)
	{
		$this->content .= $content;
	}

	public function getContent()
	{
		return $this->content;
	}

	//responseCode
	public function setResponseCode($responseCode)
	{
		$this->responseCode = $responseCode;
	}

	public function getResponseCode()
	{
		return $this->responseCode;
	}

	///title
	public function setTitle($title)
	{
		$this->title = $title;
	}

	public function getTitle()
	{
		return $this->title;
	}

	//Template
	public function setTemplate($template)
	{
	 	$this->template = $template;
	}

	public function getTemplate()
	{
		return $this->template;
	}

	public function getDomain()
	{
		$this->domain = $_SERVER['HTTP_HOST'];
		return $this->domain;
	}
}