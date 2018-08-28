<?php

namespace Gwa\GoogleContact\Test;

use Gwa\GoogleContact\PostalAddress;
use PHPUnit\Framework\TestCase;

final class PostalAddressTest extends TestCase
{
    public function testConstructable()
    {
        $name = new PostalAddress();
        $this->assertInstanceOf(PostalAddress::class, $name);
    }

    public function testGetDomElement()
    {
        $street = '1600 Amphitheatre Parkway';
        $city = 'Mountain View';
        $postcode = '94043';
        $country = 'U.S.A.';
        $countrycode = 'US';

        $address = new PostalAddress();
        $address->setDomDocument(new \DOMDocument('1.0', 'UTF-8'));

        $address->setStreet($street);
        $address->setCity($city);
        $address->setPostCode($postcode);
        $address->setCountry($country, $countrycode);

        $element = $address->createAndAppendDomElement();

        $this->assertInstanceOf('\DOMElement', $element);

        $address->getDomDocument()->formatOutput = true;
        $xmlstring = $address->getDomDocument()->saveXML();
        $xmlexpected = file_get_contents(__DIR__ . '/fixtures/address.xml');
        $this->assertEquals($xmlexpected, $xmlstring);
    }
}
