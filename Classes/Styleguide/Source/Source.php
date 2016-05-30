<?php

namespace Thopra\Styleguide\Source;

class Source extends AbstractSource implements SourceInterface {

	public function __construct($path, $key, $name, $autoParse=true) 
	{
		$this->setName($name);
		$this->setKey($key);
		$this->setPath($path);
		if ($autoParse) {
			$this->parse();
		}
	}

}