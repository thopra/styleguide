<?php 
namespace Thopra\Styleguide\Parser\Kss;

/**
 * File
 * Acts as a placeholder for \splFileObject since that cannot be serialized (but that is required due to caching)
 * 
 */
Class File  {

	protected $fileName = '';

	public function __construct(\splFileObject $file)
	{
		$this->fileName = $file->getFileName();
	}

    public function getFileName()
    {
    	return $this->fileName;
    }

}