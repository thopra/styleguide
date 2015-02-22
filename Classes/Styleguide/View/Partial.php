<?php

namespace Thopra\Styleguide\View;

class Partial {
	
	protected $file;

	protected $path;

	protected $vars = array();

	protected $source;

	protected $fileExtension = 'phtml';

	public function __construct($path, $source)
	{
		$this->source = $source;
		$this->path = $path;
		$this->setFile();
	}

	public function render()
	{
		extract($this->vars);
		include($this->file);
	}

	public function setVars($data)
	{
		$this->vars = $data;
	}

	public function getVars()
	{
		return $this->vars;
	}

	public function assign($varName, $data)
	{
		$this->vars[$varName] = $data;
	}

	public function unassign($varName)
	{
		unset($this->vars[$varName]);
	}

	protected function setFile()
	{
		$filename = $this->source->getPath().DIRECTORY_SEPARATOR.$this->source->getPartialDir().DIRECTORY_SEPARATOR.$this->path.'.'.$this->fileExtension;
		if (!file_exists($filename)) {
			throw new \Exception("Partial not found: ".$filename);
		}

		$this->file = $filename;
	}


}