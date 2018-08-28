<?php

namespace Gwa\GoogleContact\Test;

use Gwa\GoogleContact\PhoneNumber;
use PHPUnit\Framework\TestCase;

final class PhoneNumberTest extends TestCase
{
    public function testConstructable()
    {
        $name = new PhoneNumber();
        $this->assertInstanceOf(PhoneNumber::class, $name);
    }

    public function testGetDomElement()
    {
        $phoneNumber = '012 3456 789';

        $number = new PhoneNumber();
        $number->setDomDocument(new \DOMDocument('1.0', 'UTF-8'));

        $number->setPhoneNumber($phoneNumber);

        $element = $number->createAndAppendDomElement();

        $this->assertInstanceOf('\DOMElement', $element);

        $xmlstring = $number->getDomDocument()->saveXML();
        $xmlexpected = file_get_contents(__DIR__ . '/fixtures/phonenumber.xml');
        $this->assertEquals($xmlexpected, $xmlstring);
    }

    public function testGetDomElementMobilePrimary()
    {
        $phoneNumber = '012 3456 789';

        $number = new PhoneNumber();
        $number->setDomDocument(new \DOMDocument('1.0', 'UTF-8'));

        $number->setPhoneNumber($phoneNumber)
            ->setPrimary()
            ->setType('mobile');

        $element = $number->createAndAppendDomElement();

        $this->assertInstanceOf('\DOMElement', $element);

        $xmlstring = $number->getDomDocument()->saveXML();
        $xmlexpected = file_get_contents(__DIR__ . '/fixtures/phonenumber-2.xml');
        $this->assertEquals($xmlexpected, $xmlstring);
    }
}
