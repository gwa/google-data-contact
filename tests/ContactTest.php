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
        $contact->setName('Doe', 'John')
            ->setNamePrefix('Dr.')
            ->setNameSuffix('MD');
        $contact->setNote('Lorem ipsum');
        $email0 = $contact->addEmail('doe@example.org', 'home', true);
        $email1 = $contact->addEmail('doe@example.com');
        $phonenumber0 = $contact->addPhoneNumber('012 3456 789', 'home', true);

        $address0 = $contact->addAddress('1600 Amphitheatre Parkway', 'work', true)
            ->setCity('Mountain View')
            ->setRegion('California')
            ->setCountry('U.S.A.', 'US');

        $organization0 = $contact->addOrganization('Google, Inc.')
            ->setType('other')
            ->setLabel('Volunteer')
            ->setPrimary()
            ->setTitle('Tech Writer')
            ->setJobDescription('Writes documentation')
            ->setDepartment('Software Development')
            ->setSymbol('GOOG');
        /*
<gd:organization rel="http://schemas.google.com/g/2005#other" label="Volunteer" primary="true"/>
  <gd:orgName>Google, Inc.</gd:orgName>
  <gd:orgTitle>Tech Writer</gd:orgTitle>
  <gd:orgJobDescription>Writes documentation</gd:orgJobDescription>
  <gd:orgDepartment>Software Development</gd:orgDepartment>
  <gd:orgSymbol>GOOG</gd:orgSymbol>
</gd:organization>
*/

        $this->assertSame($email0, $contact->email(0));
        $this->assertSame($email1, $contact->email(1));
        $this->assertNull($contact->email(2));

        $this->assertSame($phonenumber0, $contact->phoneNumber(0));
        $this->assertNull($contact->phoneNumber(1));
        $this->assertSame($address0, $contact->address(0));
        $this->assertNull($contact->address(1));

        $xmlstring = $contact->render();
        $this->assertEquals(trim(file_get_contents(__DIR__ . '/fixtures/contact.xml')), $xmlstring);
    }
}
