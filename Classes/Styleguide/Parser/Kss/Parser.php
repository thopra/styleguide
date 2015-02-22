<?php 

namespace Thopra\Styleguide\Parser\Kss;

Class Parser extends \Scan\Kss\Parser {

	public function removeSection($reference)
	{
		unset($this->sections[$reference]);
	}

	/**
     * Adds a section to the Sections collection
     *
     * @param string $comment
     * @param \splFileObject $file
     */
    protected function addSection($comment, \splFileObject $file)
    {
        $section = new Section($comment, $file);
        $this->sections[$section->getReference(true)] = $section;
        $this->sectionsSortedByReference = false;
    }

}