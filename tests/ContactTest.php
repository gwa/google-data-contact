<?php

namespace Gwa\GoogleContact\Test;

use Gwa\GoogleContact\Contact;
use PHPUnit\Framework\TestCase;

final class ContactTest extends TestCase
{
    public function testConstructable()
    {
        $contact = new Contact();
        $this->assertInstanceOf(Contact::class, $contact);
    }

    public function testCreateEntryElement()
    {
        $contact = new Contact();
        $entry = $contact->createEntryElement();

        $this->assertInstanceOf('\DOMElement', $entry);

        $xmlstring = $contact->getDomDocument()->saveXML($entry);
        $this->assertEquals(trim(file_get_contents(__DIR__ . '/fixtures/entry.xml')), $xmlstring);
    }

    public function testRender()
    {
        $contact = new Contact();
        $contact->setName('Doe', 'John');
        $contact->setNote('Lorem ipsum');
        $contact->addEmail('doe@example.org', 'home', true);
        $contact->addEmail('doe@example.com');
        $contact->addPhoneNumber('012 3456 789', 'home', true);

        $contact->addAddress('1600 Amphitheatre Parkway', 'work', true)
            ->setCity('Mountain View')
            ->setRegion('California')
            ->setCountry('U.S.A.', 'US');

        $xmlstring = $contact->render();
        $this->assertEquals(trim(file_get_contents(__DIR__ . '/fixtures/contact.xml')), $xmlstring);
    }
}
