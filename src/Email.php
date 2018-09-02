<?php

namespace Gwa\GoogleContact;

/**
 * Email GD element.
 */
class Email extends AbstractElement
{
    use TraitHasPrimary;
    use TraitHasType;

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->getAttribute('address');
    }

    /**
     * @param string $address
     * @return self
     */
    public function setAddress($address)
    {
        $this->setAttribute('address', $address);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function getDomElementName()
    {
        return 'gd:email';
    }
}
