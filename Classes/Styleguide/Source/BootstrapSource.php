<?php

namespace Thopra\Styleguide\Source;

class BootstrapSource extends AbstractSource implements SourceInterface {
	
	protected $path = 'Templates/Frameworks/Bootstrap/Reference';
	protected $key = 'bootstrap3';
	protected $name = 'Bootstrap';

	public function getPath()
	{
		return dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . $this->path;
	}

}