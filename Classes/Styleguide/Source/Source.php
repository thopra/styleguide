<?php

namespace Thopra\Styleguide\Source;

class Source extends AbstractSource implements SourceInterface {

	public function __construct($path, $key, $name) 
	{
		$this->setName($name);
		$this->setKey($key);
		$this->setPath($path);
		$this->parse();
	}

}