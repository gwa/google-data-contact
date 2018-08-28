<?php

namespace Gwa\GoogleContact\Test;

use Gwa\GoogleContact\Email;
use PHPUnit\Framework\TestCase;

final class EmailTest extends TestCase
{
    public function testConstructable()
    {
        $name = new Email();
        $this->assertInstanceOf(Email::class, $name);
    }

    public function testGetDomElement()
    {
        $address = 'doe@example.com';

        $email = new Email();
        $email->setDomDocument(new \DOMDocument('1.0', 'UTF-8'));

        $email->setAddress($address);

        $element = $email->createAndAppendDomElement();

        $this->assertInstanceOf('\DOMElement', $element);

        $xmlstring = $email->getDomDocument()->saveXML();
        $xmlexpected = file_get_contents(__DIR__ . '/fixtures/email.xml');
        $this->assertEquals($xmlexpected, $xmlstring);
    }
}
