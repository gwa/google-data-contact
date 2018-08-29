<?php

namespace Gwa\GoogleContact;

/**
 * Trait.
 */
trait TraitHasLabel
{
    /**
     * @var boolean
     */
    private $label;

    /**
     * @return self
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @param \DOMElement $element
     */
    protected function addLabelAttribute(\DOMElement $element)
    {
        if (isset($this->label) && !empty($this->label)) {
            $element->setAttribute('label', $this->label);
        }
    }
}
