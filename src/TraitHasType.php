<?php

namespace Gwa\GoogleContact;

/**
 * Trait.
 */
trait TraitHasType
{
    /**
     * @var boolean
     */
    private $type = 'work';

    /**
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param \DOMElement $element
     */
    protected function addTypeAttribute(\DOMElement $element)
    {
        $element->setAttribute('rel', 'http://schemas.google.com/g/2005#' . $this->type);
    }
}
