<?php 

namespace Thopra\Styleguide\Parser\Kss;
use Thopra\Styleguide\View;

Class Section extends \Scan\Kss\Section {

    protected $parser;
    protected $partial;
    protected $partialParams = array();

    /**
     * Creates a section with the KSS Comment Block and source file
     *
     * @param string $comment
     * @param \SplFileObject $file
     */
    public function __construct($comment = '', \SplFileObject $file = null)
    {
        $this->rawComment = $comment;
        $this->file = new File($file);
    }

    /**
     * Returns the source filename for where the comment block was located
     *
     * @return string
     */
    public function getFilename()
    {
        if ($this->file === null) {
            return '';
        }

        return $this->file->getFilename();
    }

    public function getTitle() 
    {
        if ( $this->isReferenceNumeric(parent::getTitle()) ) {
            return parent::getTitle() . ': ' . $this->getDescriptionTitle();
        } else {
            return parent::getTitle();
        }
    }

    public function getDescriptionTitle()
    {
        return $this->getTitleComment() ? $this->getTitleComment() : strtok($this->getDescription(), "\n");
    }

    public function getDescriptionText()
    {
        $pos = strpos($this->getDescription(),$this->getDescriptionTitle());
        if ($pos !== false) {
            return trim(substr_replace($this->getDescription(),'',$pos,strlen($this->getDescriptionTitle())));
        }

        return trim($this->getDescription());
    }

    public function setParser($parser)
    {
        $this->parser = $parser;
    }

    public function getParser()
    {
        return $this->parser;
    }

    public function getMarkup()
    {
        $markup = parent::getMarkup();

        if ( $this->getPartial() ) {
            ob_start();
            $this->renderPartial();
            $markup = ob_get_contents();
            ob_end_clean();
        }

        return $markup;
    }

    /**
     * Returns the part of the KSS Comment Block that contains the partial reference
     *
     * @return string
     */
    public function getPartial()
    {
        if ($this->partial === null) {
            if ($partialComment = $this->getPartialComment()) {
                $this->partial = trim(preg_replace('/^\s*Partial:/i', '', $partialComment));
            }
        }

        return $this->partial;
    }

    /**
     * Returns the description for the section
     *
     * @return string
     */
    public function getDescription()
    {
        $descriptionSections = array();

        foreach ($this->getCommentSections() as $commentSection) {
            // Anything that is not the section comment or modifiers comment
            // must be the description comment
            if ($commentSection != $this->getReferenceComment()
                && $commentSection != $this->getTitleComment()
                && $commentSection != $this->getMarkupComment()
                && $commentSection != $this->getDeprecatedComment()
                && $commentSection != $this->getExperimentalComment()
                && $commentSection != $this->getCompatibilityComment()
                && $commentSection != $this->getModifiersComment()
                && $commentSection != $this->getParametersComment()
                && $commentSection != $this->getPartialComment()
                && $commentSection != $this->getPartialParamsComment()
                && $commentSection != $this->getAlignComment()
            ) {
                $descriptionSections[] = $commentSection;
            }
        }

        return implode("\n\n", $descriptionSections);
    }


    /**
     * Renders a partial of a section
     *
     * @return string
     */
    public function renderPartial()
    {
        if (!$this->getPartial()) {
            return false;
        }

        $partial = new View\Partial($this->getPartial(), $this->getParser()->getSource());
        $partial->setVars( $this->getPartialParams() );
        $partial->render();
       
    }

    public function getPartialParams()
    {
        if (!count($this->partialParams)) {
            if ($partialParamsComment = $this->getPartialParamsComment()) {
                $this->partialParams = json_decode(trim(preg_replace('/^\s*PartialParams:/i', '', $partialParamsComment)), true) ;
            }
        }
        $params = (array)$this->partialParams;

        if (!isset($params['modifierClass'])) {
            $params['modifierClass'] = '$modifierClass';
        }
        
        return $params;
    }


     /** 
     * Get Tags
     */
    public function getTags($tagName = false)
    {
        $tags = array();
        if (count($this->getParameters(true)) > 0) {
            foreach ($this->getParameters(true) as $parameter) { 
                $tag = FALSE;
                if ( $this->isTag($parameter) ) {
                    $tag = $parameter;
                }

                if ($tag) {
                    $newTag = new \Scan\Kss\Parameter(substr($tag->getName(), 1, strlen($tag->getName())-2), $tag->getDescription());
                    if (!$tagName || $newTag->getName() == $tagName) {
                        $tags[] = $newTag;
                    }
                }
            }
        }

        return $tags;
    }

    public function getParameters($includeTags = false) 
    {
        $params = parent::getParameters();
        if ($includeTags) {
            return $params;
        }
        foreach ($params as $key => $param) {
            if ($this->isTag($param)) {
                unset($params[$key]);
            }
        }

        return $params;
    }


    /**
     * Returns the alignment of modifier elements in markup previews as an array
     * Every array entry represents the columns that should be displayed in one row per viewport
     *
     * @return string
     */
    public function getAlignment()
    {
        $cols = $this->getTags('align');
        if (count($cols)) {
            $cols = explode(",", trim(array_shift($cols)->getDescription()));
        }

        if (!count($cols)) {
            return array(1,1,1,1);
        }

        for ($x = 1; $x < 4; $x++) {
            if (!isset($cols[$x])) {
                $cols[$x] = $cols[$x] -1;
            }
        }

        return $cols;
       
    }

    /** 
     * Gets wether or not the element should be used without a modifier
     */
    public function getStandalone()
    {
        if (count($this->getTags('nostandalone'))) {
            return false;
        }

        return true;
    }

    /** 
     * Gets wether or not the element should be validated
     */
    public function getValidate()
    {
        if (count($this->getTags('novalidation'))) {
            return false;
        }

        return true;
    }

    /** 
     * Returns the Todo Tags
     * 
     */
    public function getTodo()
    {
        $todo = $this->getTags('todo');
        if (count($todo) > 0) {
            return $todo;
        }

        return false;
    }

    /** 
     * Returns the Review Tags
     * 
     */
    public function getReview()
    {
        $review = $this->getTags('review');
        if (count($review) > 0) {
            return $review;
        }

        return false;
    }

   /**
     * Returns the part of the KSS Comment Block that contains the partial reference
     *
     * @return string
     */
    public function getPartialComment()
    {
        $partial = null;

        foreach ($this->getCommentSections() as $commentSection) {
            if (preg_match('/^\s*Partial:/i', $commentSection)) {
                $partial = $commentSection;
                break;
            }
        }

        return $partial;
    }

    /**
     * Returns the part of the KSS Comment Block that contains the partial placeholder values
     *
     * @return string
     */
    public function getPartialParamsComment()
    {
        $params = null;

        foreach ($this->getCommentSections() as $commentSection) {
            if (preg_match('/^\s*PartialParams:/i', $commentSection)) {
                $params = $commentSection;
                break;
            }
        }

        return $params;
    }

    /**
     * Returns the part of the KSS Comment Block defining the alignment options of the markup preview
     *
     * @return string
     */
    public function getAlignComment()
    {
        $params = null;

        foreach ($this->getCommentSections() as $commentSection) {
            if (preg_match('/^\s*Align:/i', $commentSection)) {
                $params = $commentSection;
                break;
            }
        }

        return $params;
    }

    protected function isTag($parameter)
    {
        if (strpos($parameter->getName(), '!') === strlen($parameter->getName())-1 
            || preg_match("/^\@[a-zA-Z0-9_\-]+\!/",$parameter->getDescription())) {
            return true;
        }

        return false;
    }

}