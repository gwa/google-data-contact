<?php

namespace Gwa\GoogleContact;

/**
 * Trait.
 */
trait TraitHasPrimary
{
    /**
     * @var boolean
     */
    private $primary = false;

    /**
     * @return self
     */
    public function setPrimary($primary = true)
    {
        $this->primary = $primary;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isPrimary()
    {
        return $this->primary;
    }

    /**
     * @param \DOMElement $element
     */
    protected function addPrimaryAttribute(\DOMElement $element)
    {
        if ($this->isPrimary()) {
            $element->setAttribute('primary', 'true');
        }
    }
}
