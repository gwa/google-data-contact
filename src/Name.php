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
     * @return string
     */
    public function getFamilyName()
    {
        return $this->familyName;
    }

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
     * @return string
     */
    public function getGivenName()
    {
        return $this->givenName;
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
     * @return string
     */
    public function getAdditionalName()
    {
        return $this->additionalName;
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
     * @return string
     */
    public function getNamePrefix()
    {
        return $this->namePrefix;
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
     * @return string
     */
    public function getNameSuffix()
    {
        return $this->nameSuffix;
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
