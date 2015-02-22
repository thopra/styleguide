<?php

namespace Thopra\Styleguide\Source;

class Typo3Source extends AbstractSource implements SourceInterface {
	
	protected $path = 'Templates/Frameworks/Typo3/Reference';
	protected $key = 'typo3';
	protected $name = 'Typo3 Content';

	public function getPath()
	{
		return dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . $this->path;
	}

}