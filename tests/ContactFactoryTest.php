<?php

namespace Gwa\GoogleContact\Test;

use Gwa\GoogleContact\Contact;
use Gwa\GoogleContact\ContactFactory;
use PHPUnit\Framework\TestCase;

final class ContactFactoryTest extends TestCase
{
    public function testConstructable()
    {
        $factory = new ContactFactory();
        $this->assertInstanceOf(ContactFactory::class, $factory);
    }

    public function testCreateFromXmlString()
    {
        $xml = file_get_contents(__DIR__ . '/fixtures/contact-read.xml');
        $factory = new ContactFactory();
        $contact = $factory->createFromXmlString($xml);

        $this->assertInstanceOf(Contact::class, $contact);

        $this->assertEquals('623c70ec0f21715e', $contact->getId());
        $this->assertEquals('W/"A0EEQHY6fSt7I2A9XBVWGEs."', $contact->getEtag());
        $this->assertEquals('2018-09-01T07:37:20.038Z', $contact->getUpdated());

        $this->assertEquals('Dr.', $contact->name()->getNamePrefix());
        $this->assertEquals('John', $contact->name()->getGivenName());
        $this->assertEquals('Doe', $contact->name()->getFamilyName());
        $this->assertEquals('MD', $contact->name()->getNameSuffix());

        $this->assertEquals(2, $contact->numEmails());
        $this->assertEquals('doe@example.org', $contact->email(0)->getAddress());
        $this->assertEquals('home', $contact->email(0)->getType());
        $this->assertTrue($contact->email(0)->isPrimary());
        $this->assertEquals('doe@example.com', $contact->email(1)->getAddress());
        $this->assertEquals('work', $contact->email(1)->getType());
        $this->assertFalse($contact->email(1)->isPrimary());

        $this->assertEquals(1, $contact->numPhoneNumbers());
        $this->assertEquals('012 3456 789', $contact->phoneNumber(0)->getPhoneNumber());
        $this->assertEquals('home', $contact->phoneNumber(0)->getType());
        $this->assertTrue($contact->phoneNumber(0)->isPrimary());

        $this->assertEquals(1, $contact->numAddresses());
        $this->assertEquals('1600 Amphitheatre Parkway', $contact->address(0)->getStreet());
        $this->assertEquals('Mountain View', $contact->address(0)->getCity());
        $this->assertEquals('CA', $contact->address(0)->getRegion());
        $this->assertEquals('94043', $contact->address(0)->getPostCode());
        $this->assertEquals('U.S.A.', $contact->address(0)->getCountry());
        $this->assertEquals('US', $contact->address(0)->getCountryCode());
        $this->assertEquals('work', $contact->address(0)->getType());
        $this->assertTrue($contact->address(0)->isPrimary());

        $this->assertEquals(1, $contact->numOrganizations());
        $this->assertEquals('Google, Inc.', $contact->organization(0)->getName());
        $this->assertEquals('Tech Writer', $contact->organization(0)->getTitle());
        $this->assertEquals('Writes documentation', $contact->organization(0)->getJobDescription());
        $this->assertEquals('Software Development', $contact->organization(0)->getDepartment());
        $this->assertEquals('GOOG', $contact->organization(0)->getSymbol());
    }
}
