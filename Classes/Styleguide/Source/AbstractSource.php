<?php

namespace Thopra\Styleguide\Source;
use Thopra\Styleguide\Parser;

abstract class AbstractSource {

	/**
	 * @var string
	 */
	protected $path = '';

	/**
	 * @var string
	 */
	protected $key;

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var string
	 */
	protected $partialDir = 'Partials';

	/**
	 * @var \Thopra\Styleguide\Parser\Kss\Parser
	 */
	protected $parser;

	public function __construct()
	{
		$this->parse();
	}

	public function getName() 
	{
		return $this->name;
	}

	public function setName($name) 
	{
		$this->name = $name;
	}

	public function getKey() 
	{
		return $this->key;
	}

	public function setKey($key) 
	{
		$this->key = $key;
	}

	public function getPartialDir() 
	{
		return $this->partialDir;
	}

	public function setPartialDir($dir) 
	{
		$this->partialDir = $dir;
	}

	public function getSections()
	{
		return $this->getParser()->getSections();
	}

	public function setPath($path)
	{
		$this->path = $path;
	}

	public function getPath()
	{
		return $this->path;
	}

	public function excludeSections($exclude = array())
	{
		foreach ( $exclude as $ref ) {
			foreach ( $this->getParser()->getSections() as $reference => $section ) {
				if ($section->belongsToReference($ref)) {
					$this->getParser()->removeSection($reference);
				}
			}
		}
	}

	public function parse()
	{
		$this->parser = new Parser\Kss\Parser($this->getPath(), $this);
	}

	/**
	 * @param string $parser
	 */
	public function setParser($parser)
	{
		$this->parser = $parser;
	}

	/**
	 * @return string
	 */
	public function getParser()
	{
		return $this->parser;
	}
/*
	protected function setSections()
	{
		$finder = new Finder();
        $finder->files()->name('/\.(css|sass|scss|less|styl(?:us)?)$/')->in($this->getPath());
   
        foreach ($finder as $fileInfo) {
            $file = new \splFileObject($fileInfo);
            $commentParser = new Parser\Kss\CommentParser($file);
            foreach ($commentParser->getBlocks() as $commentBlock) {
                if (Parser\Kss\Parser::isKssBlock($commentBlock)) {
                    $section = new \Scan\Kss\Section($commentBlock, $file);
        			$this->sections[$section->getReference(true)] = $section;
                }
            }
        }
	}*/

}