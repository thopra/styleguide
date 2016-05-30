<?php 

namespace Thopra\Styleguide;

Class Styleguide {
	
	/**
	 * @var string
	 */
	protected $templateDir = '';	

	/**
	 * @var string
	 */
	protected $defaultTemplateDir = '';

	/**
	 * @var mixed
	 */
	protected $ref = false;

	/**
	 * @var string
	 */
	protected $src = "default";

	/**
	 * @var string
	 */
	protected $title = '';

	/**
	 * @var string
	 */
	protected $sources = array();

	/**
	 * @var string
	 */
	protected $cacheDir = FALSE;

	/**
	 * @var Zend_Cache
	 */
	protected $cache = null;

	/**
	 * @var integer
	 */
	protected $cacheLifetime = 5;

	/**
	 * Still have to test if this makes sense or actually slows down performance
	 * @var bool
	 */
	protected $enableTemplateCache = FALSE;



	/**
	 * Constructor
	 *
	 * @param mixed $paths
	 * @return void
	 */
	public function __construct($title = 'Styleguide', $path = false) 
	{
		$this->defaultTemplateDir = dirname(dirname(dirname(__FILE__))) . '/Templates';
		$this->setTemplateDir($this->defaultTemplateDir);
		$this->setTitle($title);

		if ($path) {
			$source = new Source\Source($path, $this->src, $title);
			$this->addSource($source);
		}
		

		$this->getParams();
	}


	/**
	 * @param string $ref
	 */
	public function setRef($ref)
	{
		$this->ref = $ref;
	}

	/**
	 * @return string
	 */
	public function getRef()
	{
		return $this->ref;
	}

	/**
	 * @param string $src
	 */
	public function setSrc($src)
	{
		$this->src = $src;
	}

	/**
	 * @return string
	 */
	public function getSrc($src)
	{
		return $this->src;
	}

	/**
	 * @param string $title
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}

	/**
	 * @return string
	 */
	public function getTitle()
	{	
		return $this->title;
	}

	public function getSources()
	{
		return $this->sources;
	}

	public function getSource()
	{
		return $this->sources[$this->src];
	}

	/**
	 * Add Components from a Frontend Framework to the styleguide (such as twitter bootstrap)
	 * 
	 * @var \Thopra\Styleguide\Source\SourceInterface $source
	 */
	public function addSource($source)
	{
		if ($this->cache) {
			$source = $this->parseAndCache($source);
		} else {
			$source->parse();
		}
		$this->sources[$source->getKey()] = $source;

		$this->getParams();
	}

	/**
	 * Remove source
	 *
	 * @var \Thopra\Styleguide\Source\SourceInterface $source
	 */
	public function removeSource($source)
	{
		unset($this->sources[$source->getName()]);
	}

	/**
	 * sets the template Dir
	 * @var string $dir
	 */
	public function setTemplateDir($dir)
	{
		$this->templateDir = $dir;
	}

	/**
	 * gets the template Dir
	 * @return string 
	 */
	public function getTemplateDir()
	{	
		return $this->templateDir;
	}

	/**
	 * sets the cache dir - if set to false (default), caching is disabled
	 * @var string $dir
	 */
	public function setCacheDir($dir)
	{
		if (!is_dir($dir)) {
			if (!mkdir($dir)) {
				$dir = false;
			}
		}
		$this->cacheDir = $dir;
		$this->initCache();
	}

	/**
	 * gets the cache dir
	 * @return string 
	 */
	public function getCacheDir()
	{	
		return $this->cacheDir;
	}

	/**
	 * cache lifetime (in seconds)
	 * @var integer $ttl
	 */
	public function setCacheLifetime($ttl)
	{
		$this->cacheLifetime = (int)$ttl;
	}

	/**
	 * gets the cache dir
	 * @return integer 
	 */
	public function getCacheLifetime()
	{	
		return $this->cacheLifetime;
	}


	/**
	 * Renders the styleguide
	 *
	 * @return void
	 */
	public function render()
	{
		if (!count($this->sources)) {
			throw new \Exception("No Source specified. Please add a source or specify a path in the constructor.");
		}
		if ($this->ref) {
			if (isset($_GET['preview'])) {
				$this->displayPreview($this->sources[$this->src], $this->ref, isset($_GET['modifier']) ? $_GET['modifier'] : '');
			} else {
				$this->displayReference($this->sources[$this->src], $this->ref);
			}
		} else {
			$this->displayTemplate( 'Layout/Styleguide', array('template' => 'Index'));
		}
	}

	/**
	 * Renders the styleguide
	 *
	 * @return void
	 */
	public function renderFrame()
	{
		if (!count($this->sources)) {
			throw new \Exception("No Source specified. Please add a source or specify a path in the constructor.");
		}
		$this->displayTemplate( 'Layout/Frame');
	}

	/**
	 * Renders a Template within Styleguide::templateDir
	 * 
	 * @var string $templateName
	 * @var array $vars
	 */
	public function displayTemplate($templateName, $vars = array())
	{
		$templateName = $templateName.'.phtml';
		$templateName = $this->getAbsTemplatePath($templateName);
		$Styleguide = $this;

		$tag = 'template_'.str_replace(array("/","."), "_", $templateName).md5(serialize($vars));

		if ($this->cache && $this->enableTemplateCache) {
			$result = $this->cache->getItem($tag, $success);
			if (!$success) {
				ob_start();
				include($templateName);
				$result = ob_get_contents();
				ob_end_flush();
			    $this->cache->setItem($tag, $result);

			    return;

			} 
			echo $result;

		} else {
			include($templateName);
		}
	}

	/**
	 * Displays a Section of the styleguide
	 *
	 * @var \Thopra\Styleguide\Source\SourceInterface
	 * @var string $reference
	 */
	public function displayReference($source, $reference) {
		try {
	        $section = $source->getParser()->getSection($reference);

	        $this->displayTemplate( 	'Layout/Styleguide', 
							        	array(
							        		'template' => 'Reference',
							        		'section' => $section
							        	)
							        );

	    } catch (UnexpectedValueException $e) {
	        $this->displayTemplate( 'Layout/Styleguide', 
	        						array(
	        							'template' => 'Index'
	        						)
	        					);
	    }
	}

	/**
	 * Displays the preview of a given reference and modifier class
	 *
	 * @var \Thopra\Styleguide\Source\SourceInterface
	 * @var string $reference
	 */
	public function displayPreview($source, $reference, $modifier=false) {
		try {
	        $section = $source->getParser()->getSection($reference);

	        if (count($section->getTags('blank'))) {
	        	echo $section->getMarkup();
	        	return;
	        }

	        $this->displayTemplate( 	'Layout/Preview', 
							        	array(
							        		'template' => 'Reference',
							        		'section' => $section,
							        		'modifier' => $modifier
							        	)
							        );

	    } catch (UnexpectedValueException $e) {
	        die('Preview for reference '.$reference.' could not be displayed.');
	    }
	}



	public function lastModified()
	{
		$modificationDate = filemtime(__FILE__);
		foreach ($this->getSource() as $key => $value) {
			foreach( $value->getCSSResources() as $file ) {
				$modificationDate = max($modificationDate, filemtime($value->getPath().'/'.$file));
			}
		}
		return strftime("%d. %B %Y - %H:%M", $modificationDate);
	}
	

	/**
	 * @param string $templateName
	 */
	private function getAbsTemplatePath($templateName)
	{
		if (!file_exists($this->getAbsTemplateDir($this->templateDir).DIRECTORY_SEPARATOR.$templateName)) {
			if (!file_exists($this->getAbsTemplateDir($this->defaultTemplateDir).DIRECTORY_SEPARATOR.$templateName)) {
				throw new \Exception("Template not found: ".$this->getAbsTemplateDir($this->defaultTemplateDir).DIRECTORY_SEPARATOR.$templateName, 1);
			} else {
				return $this->getAbsTemplateDir($this->defaultTemplateDir).DIRECTORY_SEPARATOR.$templateName;
			}
		}
		return $this->getAbsTemplateDir($this->templateDir).DIRECTORY_SEPARATOR.$templateName;
	}

	/**
	 * @param string $dir
	 */
	private function getAbsTemplateDir($dir)
	{
		if(is_dir($dir)) {
			return $dir;
		}

		if(is_dir(realpath($dir))) {
			return realpath($dir);
		}

		$dir = dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR.$dir;
		if(is_dir($dir)) {
			return $dir;
		}

		throw new \Exception("Template Directory does not exist: ".$dir, 1);
	}

	/**
	 * gets the cache of a parsed source
	 * @experimental
	 * @todo: 	seems we cannot cache the source class, since these include splFileObjects ...
	 * 			Until that is changed, do not use this method
	 */
	protected function parseAndCache($source)
	{
		$tag = 'source_'.str_replace(" ", "--", $source->getKey());

		$result = $this->cache->getItem($tag, $success);
		if (!$success) {
			$source->parse();
		    $result = $source;
		    $this->cache->setItem($tag, $result);
		}

		return $result;
	}

	protected function initCache() 
	{
		if (!$this->getCacheDir()) {
			$this->cache = null;
			return;
		}

	    $this->cache = \Zend\Cache\StorageFactory::factory(array(
		    'adapter' => array(
		        'name' => 'filesystem',
		        'options' => array(
			        'cache_dir' => $this->getCacheDir(),
			        'ttl' => $this->getCacheLifetime() // kept short, just enough to cache all previews on one page
		        )
		    ),
		    'plugins' => array(
		        // Don't throw exceptions on cache errors
		        'exception_handler' => array(
		            'throw_exceptions' => false
		        ),
		        'serializer'
		    )
		));
	}

	protected function getDefaultSource()
	{
		if (!count($this->sources)) {
			return $this->src;
		}
		foreach ($this->sources as $src) {
			return $src->getKey();
		}
	}

	protected function getParams()
	{
		if (isset($_GET['ref'])) {
			$this->setRef($_GET['ref']);
		}
		if (isset($_GET['src'])) {
			$this->setSrc($_GET['src']);
		} else {
			$this->setSrc($this->getDefaultSource());
		}
	}

}