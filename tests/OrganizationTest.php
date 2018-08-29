<?php

namespace Gwa\GoogleContact\Test;

use Gwa\GoogleContact\Organization;
use PHPUnit\Framework\TestCase;

final class OrganizationTest extends TestCase
{
    public function testConstructable()
    {
        $name = new Organization();
        $this->assertInstanceOf(Organization::class, $name);
    }

    public function testGetDomElement()
    {
        $name = 'Google, Inc.';
        $title = 'Tech Writer';
        $jobDescription = 'Writes documentation';
        $department = 'Software Development';
        $symbol = 'GOOG';
        $type = 'other';
        $label = 'Volunteer';

        $organization = new Organization();
        $organization->setDomDocument(new \DOMDocument('1.0', 'UTF-8'));

        $organization->setName($name)
            ->setTitle($title)
            ->setJobDescription($jobDescription)
            ->setDepartment($department)
            ->setSymbol($symbol)
            ->setType($type)
            ->setLabel($label)
            ->setPrimary();

        $element = $organization->createAndAppendDomElement();

        $this->assertInstanceOf('\DOMElement', $element);

        $organization->getDomDocument()->formatOutput = true;
        $xmlstring = $organization->getDomDocument()->saveXML();
        $xmlexpected = file_get_contents(__DIR__ . '/fixtures/organization.xml');
        $this->assertEquals($xmlexpected, $xmlstring);
    }
}
