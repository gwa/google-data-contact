<?php

namespace Gwa\GoogleContact\Test;

use Gwa\GoogleContact\Name;
use PHPUnit\Framework\TestCase;

final class NameTest extends TestCase
{
    public function testConstructable()
    {
        $name = new Name();
        $this->assertInstanceOf(Name::class, $name);
    }

    public function testGetDomElement()
    {
        $givenName = 'William';
        $additionalName = 'Bradley';
        $familyName = 'Pitt';
        $namePrefix = 'Sir';
        $nameSuffix = 'MBE';

        $name = new Name();
        $name->setDomDocument(new \DOMDocument('1.0', 'UTF-8'));

        $name->setGivenName($givenName)
            ->setAdditionalName($additionalName)
            ->setFamilyName($familyName)
            ->setNamePrefix($namePrefix)
            ->setNameSuffix($nameSuffix);

        $element = $name->createAndAppendDomElement();

        $this->assertInstanceOf('\DOMElement', $element);

        $this->assertEquals(1, $element->getElementsByTagName('gd:givenName')->length);
        $this->assertEquals($givenName, $element->getElementsByTagName('gd:givenName')->item(0)->nodeValue);

        $this->assertEquals(1, $element->getElementsByTagName('gd:additionalName')->length);
        $this->assertEquals($additionalName, $element->getElementsByTagName('gd:additionalName')->item(0)->nodeValue);

        $this->assertEquals(1, $element->getElementsByTagName('gd:familyName')->length);
        $this->assertEquals($familyName, $element->getElementsByTagName('gd:familyName')->item(0)->nodeValue);

        $this->assertEquals(1, $element->getElementsByTagName('gd:namePrefix')->length);
        $this->assertEquals($namePrefix, $element->getElementsByTagName('gd:namePrefix')->item(0)->nodeValue);

        $this->assertEquals(1, $element->getElementsByTagName('gd:nameSuffix')->length);
        $this->assertEquals($nameSuffix, $element->getElementsByTagName('gd:nameSuffix')->item(0)->nodeValue);

        $xmlstring = $name->getDomDocument()->saveXML();
        $xmlexpected = file_get_contents(__DIR__ . '/fixtures/name.xml');
        $this->assertEquals($xmlexpected, $xmlstring);
    }
}
