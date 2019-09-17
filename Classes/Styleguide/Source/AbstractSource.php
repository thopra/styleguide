<?php

namespace Thopra\Styleguide\Source;
use Thopra\Styleguide\Parser;
use Thopra\Styleguide\View;

abstract class AbstractSource {

	const PARTIAL_TYPE_PHP = 'phtml';
	const PARTIAL_TYPE_FLUID = 'fluid';
	const PARTIAL_TYPE_TWIG = 'twig';

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
	protected $partialType;

	/**
	 * @var array
	 */
	protected $resources = array('script' => array(), 'stylesheet' => array());

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
		$this->partialType = self::PARTIAL_TYPE_PHP;
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

	public function getPartialType() 
	{
		return $this->partialDir;
	}

	public function setPartialType($type = null) 
	{
	    switch ($type) {
            case self::PARTIAL_TYPE_FLUID:
                $this->partialType = self::PARTIAL_TYPE_FLUID;
                break;

            case self::PARTIAL_TYPE_TWIG:
                $this->partialType = self::PARTIAL_TYPE_TWIG;
                break;

            case self::PARTIAL_TYPE_PHP:
            default:
                $this->partialType = self::PARTIAL_TYPE_PHP;
        }
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

	public function getCSSResources()
	{
		return $this->resources['stylesheet'];
	}

	public function getJSResources()
	{
		return $this->resources['script'];
	}

	public function addResource($path, $type = false)
	{
		if (!$type) {
			if (strripos($path, '.js') == strlen($path)-3) {
				$type = 'script';
			} else {
				$type = 'stylesheet';
			}
		}
		$this->resources[$type][] = $path;
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

	public function renderPartial($partialName, $vars = array(), $directOutput = true)
	{
		switch ($this->partialType) {

            case self::PARTIAL_TYPE_TWIG:
                $partial = new View\TwigPartial($partialName, $this);
                break;

			case self::PARTIAL_TYPE_FLUID:
				$partial = new View\FluidPartial($partialName, $this);
				break;

			case self::PARTIAL_TYPE_PHP:
			default:
				$partial = new View\Partial($partialName, $this);
				break;
		}
		
		$partial->setVars($vars);   
		return $partial->render($directOutput);  
	}

}