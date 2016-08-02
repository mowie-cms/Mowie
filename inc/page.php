<?php

class page extends Smarty
{
	private $url;
	private $content = '';
	private $baseUrl;
	private $responseCode;
	private $title;
	private $tplAssign = [];
	private $templateFile;

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

	//templatekram
	/*public function assign($key, $value, $add = false)
	{
		if (array_key_exists($key, $this->tplAssign) && $add)
		{
			$this->tplAssign[$key] .= $value;
		}elseif (array_key_exists($key, $this->tplAssign) && !$add)
		{
			$this->tplAssign[$key] = $value;
		} elseif (!array_key_exists($key, $this->tplAssign))
		{
			$this->tplAssign[$key] = $value;
		}
	}*/

	public function getTplAssign()
	{
		return $this->tplAssign;
	}

	//Template Parsen
	public function setTemplateFile($templateFile)
	{
		$this->templateFile = $templateFile;
	}

	public function parseTpl()
	{
		$tplFile = file_get_contents($this->templateFile);
		$tplKeys = $this->getTplAssign();
		function page_key($key)
		{
			$tplKeys = $GLOBALS['page']->getTplAssign();
			$key_tpl = $key[0];
			$key_tpl = str_replace('{', '', $key_tpl);
			$key_tpl = str_replace('}', '', $key_tpl);
			if (array_key_exists($key_tpl, $tplKeys))
			{
				return $tplKeys[$key_tpl];
			}
			else
			{
				return '';
			}
		}

		echo preg_replace_callback('/{[A-Za-z0-9_:,\-\|]+}/', 'page_key', $tplFile);
	}
}