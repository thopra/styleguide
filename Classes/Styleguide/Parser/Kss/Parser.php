<?php 

namespace Thopra\Styleguide\Parser\Kss;

Class Parser extends \Kss\Parser {

	protected $source;

	public function __construct($paths, $source = null)
    {
        parent::__construct($paths);
        $this->source = $source;
    }

	public function removeSection($reference)
	{
		unset($this->sections[$reference]);
	}

	public function getSource()
	{
		return $this->source;
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
        $section->setParser($this);
        $this->sections[$section->getReference(true)] = $section;
        $this->sectionsSortedByReference = false;
    }

}