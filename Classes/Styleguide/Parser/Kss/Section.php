<?php 

namespace Thopra\Styleguide\Parser\Kss;

Class Section extends \Scan\Kss\Section {

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
        return trim(str_replace($this->getDescriptionTitle(), '', $this->getDescription()));
    }

    public function getMarkup()
    {
        $markup = parent::getMarkup();

        return $markup;
    }

    /**
     * Returns the part of the KSS Comment Block that contains the partial reference
     *
     * @return string
     */
    public function getPartial()
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

}