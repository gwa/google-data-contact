<?php

namespace Gwa\GoogleContact;

/**
 * Postal address GD element.
 */
class PostalAddress extends AbstractElement
{
    use TraitHasPrimary;
    use TraitHasType;

    /**
     * @var string
     */
    private $street;

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $postcode;

    /**
     * @var string
     */
    private $region;

    /**
     * @var string
     */
    private $country;

    /**
     * @var string
     */
    private $countryCode;

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param string $street
     * @return self
     */
    public function setStreet($street)
    {
        $this->street = $street;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return self
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string
     */
    public function getPostCode()
    {
        return $this->postcode;
    }

    /**
     * @param string $postcode
     * @return self
     */
    public function setPostCode($postcode)
    {
        $this->postcode = $postcode;
        return $this;
    }

    /**
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param string $region
     * @return self
     */
    public function setRegion($region)
    {
        $this->region = $region;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * @param string $country
     * @param string $countrycode
     *
     * @return self
     */
    public function setCountry($country, $countrycode = '')
    {
        $this->country = $country;
        if (isset($countrycode)) {
            $this->countryCode = $countrycode;
        }
        return $this;
    }

    protected function populateDomElement(\DOMElement $element, \DOMDocument $dom)
    {
        // Add child elements.
        if (isset($this->street)) {
            $element->appendChild($dom->createElement('gd:street', $this->street));
        }

        if (isset($this->city)) {
            $element->appendChild($dom->createElement('gd:city', $this->city));
        }

        if (isset($this->postcode)) {
            $element->appendChild($dom->createElement('gd:postcode', $this->postcode));
        }

        if (isset($this->region)) {
            $element->appendChild($dom->createElement('gd:region', $this->region));
        }

        if (isset($this->country)) {
            $child = $dom->createElement('gd:country', $this->country);
            if (isset($this->countryCode)) {
                $child->setAttribute('code', $this->countryCode);
            }
            $element->appendChild($child);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getDomElementName()
    {
        return 'gd:structuredPostalAddress';
    }
}
