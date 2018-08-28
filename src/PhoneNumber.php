<?php

namespace Gwa\GoogleContact;

/**
 * Phone number GD element.
 */
class PhoneNumber extends AbstractElement
{
    use TraitHasPrimary;
    use TraitHasType;

    /**
     * @param string $phoneNumber
     * @return self
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->setContent($phoneNumber);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function getDomElementName()
    {
        return 'gd:phoneNumber';
    }
}
