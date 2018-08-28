<?php

namespace Gwa\GoogleContact;

/**
 * Name GD element.
 */
class Name extends AbstractElement
{
    /**
     * @var string
     */
    private $familyName;

    /**
     * @var string
     */
    private $givenName;

    /**
     * @var string
     */
    private $additionalName;

    /**
     * @var string
     */
    private $namePrefix;

    /**
     * @var string
     */
    private $nameSuffix;

    /**
     * @param string $familyName
     *
     * @return self
     */
    public function setFamilyName($familyName)
    {
        $this->familyName = $familyName;
        return $this;
    }

    /**
     * @param string $givenName
     *
     * @return self
     */
    public function setGivenName($givenName)
    {
        $this->givenName = $givenName;
        return $this;
    }

    /**
     * @param string $additionalName
     *
     * @return self
     */
    public function setAdditionalName($additionalName)
    {
        $this->additionalName = $additionalName;
        return $this;
    }

    /**
     * @param string $namePrefix
     *
     * @return self
     */
    public function setNamePrefix($namePrefix)
    {
        $this->namePrefix = $namePrefix;
        return $this;
    }

    /**
     * @param string $nameSuffix
     *
     * @return self
     */
    public function setNameSuffix($nameSuffix)
    {
        $this->nameSuffix = $nameSuffix;
        return $this;
    }

    protected function populateDomElement(\DOMElement $element, \DOMDocument $dom)
    {
        // Add child elements.
        if (isset($this->givenName)) {
            $element->appendChild($dom->createElement('gd:givenName', $this->givenName));
        }

        if (isset($this->additionalName)) {
            $element->appendChild($dom->createElement('gd:additionalName', $this->additionalName));
        }

        if (isset($this->familyName)) {
            $element->appendChild($dom->createElement('gd:familyName', $this->familyName));
        }

        if (isset($this->namePrefix)) {
            $element->appendChild($dom->createElement('gd:namePrefix', $this->namePrefix));
        }

        if (isset($this->nameSuffix)) {
            $element->appendChild($dom->createElement('gd:nameSuffix', $this->nameSuffix));
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getDomElementName()
    {
        return 'gd:name';
    }
}
