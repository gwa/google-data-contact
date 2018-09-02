<?php

namespace Gwa\GoogleContact;

/**
 * Represents a Google contact.
 *
 * https://developers.google.com/gdata/docs/2.0/elements#gdContactKind
 */
class Contact
{
    /**
     * @var \DOMDocument
     */
    private $document;

    /**
     * The Google ID for this contact.
     *
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $etag;

    /**
     * Format: 2018-09-01T07:37:20.038Z
     *
     * @var string
     */
    private $updated;

    /**
     * @var Name
     */
    private $name;

    /**
     * @var string
     */
    private $note;

    /**
     * @var []Email
     */
    private $emails = [];

    /**
     * @var []PhoneNumber
     */
    private $phoneNumbers = [];

    /**
     * @var []PostalAddress
     */
    private $addresses = [];

    /**
     * @var []Organization
     */
    private $organizations = [];

    /**
     * Get the Google ID.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the Google ID.
     *
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get the ETAG.
     *
     * @return string
     */
    public function getEtag()
    {
        return $this->etag;
    }

    /**
     * Set the ETAG.
     *
     * @param string $etag
     */
    public function setEtag($etag)
    {
        $this->etag = $etag;
    }

    /**
     * @return string
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param string $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * Set the contact's name
     *
     * @param string $familyName
     * @param string $givenName
     * @param string $additionalName
     *
     * @return Name
     */
    public function setName($familyName, $givenName, $additionalName = '')
    {
        $this->name()
            ->setFamilyName($familyName)
            ->setGivenName($givenName);

        if (!empty($additionalName)) {
            $this->name()->setAdditionalName($additionalName);
        }

        return $this->name();
    }

    /**
     * Returns the name element.
     *
     * @return Name
     */
    public function name()
    {
        if (!isset($this->name)) {
            $this->name = new Name();
            $this->name->setDomDocument($this->getDomDocument());
        }

        return $this->name;
    }

    /**
     * @param string $note
     *
     * @return self
     */
    public function setNote($note)
    {
        $this->note = new Note();
        $this->note->setDomDocument($this->getDomDocument());
        $this->note->setNote($note);

        return $this;
    }

    /**
     * @param string $address
     * @param string $type
     * @param boolean $primary
     *
     * @return Email
     */
    public function addEmail($address, $type = '', $primary = false)
    {
        $email = new Email();
        $email->setAddress($address);
        $this->handleTypedPrimary($email, $type, $primary);
        $this->emails[] = $email;

        return $email;
    }

    /**
     * @return int
     */
    public function numEmails()
    {
        return count($this->emails);
    }

    /**
     * @param int $index
     * @return Email|NULL
     */
    public function email($index)
    {
        return array_key_exists($index, $this->emails) ?
            $this->emails[$index] :
            null;
    }

    /**
     * @param string $number
     * @param string $type
     * @param boolean $primary
     *
     * @return PhoneNumber
     */
    public function addPhoneNumber($number, $type = '', $primary = false)
    {
        $phonenumber = new PhoneNumber();
        $phonenumber->setPhoneNumber($number);
        $this->handleTypedPrimary($phonenumber, $type, $primary);
        $this->phoneNumbers[] = $phonenumber;

        return $phonenumber;
    }

    /**
     * @return int
     */
    public function numPhoneNumbers()
    {
        return count($this->phoneNumbers);
    }

    /**
     * @param int $index
     * @return PhoneNumber|NULL
     */
    public function phoneNumber($index)
    {
        return array_key_exists($index, $this->phoneNumbers) ?
            $this->phoneNumbers[$index] :
            null;
    }

    /**
     * @param string $street
     * @param string $type
     * @param boolean $primary
     *
     * @return PostalAddress
     */
    public function addAddress($street, $type = '', $primary = false)
    {
        $address = new PostalAddress();
        $address->setStreet($street);

        $this->handleTypedPrimary($address, $type, $primary);
        $this->addresses[] = $address;

        return $address;
    }

    /**
     * @return int
     */
    public function numAddresses()
    {
        return count($this->addresses);
    }

    /**
     * @param int $index
     * @return PostalAddress|NULL
     */
    public function address($index)
    {
        return array_key_exists($index, $this->addresses) ?
            $this->addresses[$index] :
            null;
    }

    /**
     * @param string $name
     * @param string $type
     * @param boolean $primary
     *
     * @return Organization
     */
    public function addOrganization($name, $type = '', $primary = false)
    {
        $organization = new Organization();
        $organization->setName($name);

        $this->handleTypedPrimary($organization, $type, $primary);
        $this->organizations[] = $organization;

        return $organization;
    }

    /**
     * @return int
     */
    public function numOrganizations()
    {
        return count($this->organizations);
    }

    /**
     * @param int $index
     * @return Organization|NULL
     */
    public function organization($index)
    {
        return array_key_exists($index, $this->organizations) ?
            $this->organizations[$index] :
            null;
    }

    /**
     * @param AbstractElement $element
     * @param string $type
     * @param boolean $primary
     */
    private function handleTypedPrimary(AbstractElement $element, $type, $primary)
    {
        $element->setDomDocument($this->getDomDocument());

        if ($type) {
            $element->setType($type);
        }

        if ($primary) {
            $element->setPrimary();
        }
    }

    /**
     * @return \DOMDocument
     */
    public function getDomDocument()
    {
        if (!isset($this->document)) {
            $this->document = new \DOMDocument('1.0', 'UTF-8');
            $this->document->formatOutput = true;
        }

        return $this->document;
    }

    /**
     * Renders the XML string.
     *
     * @return string
     */
    public function render()
    {
        $dom = $this->getDomDocument();
        $entry = $this->createEntryElement();
        $dom->appendChild($entry);

        // Add name.
        if (isset($this->name)) {
            $this->name->createAndAppendDomElement($entry);
        }

        // Add note.
        if (isset($this->note)) {
            $this->note->createAndAppendDomElement($entry);
        }

        // Add emails.
        foreach ($this->emails as $email) {
            $email->createAndAppendDomElement($entry);
        }

        // Add phone numbers.
        foreach ($this->phoneNumbers as $phonenumber) {
            $phonenumber->createAndAppendDomElement($entry);
        }

        // Add addresses.
        foreach ($this->addresses as $address) {
            $address->createAndAppendDomElement($entry);
        }

        // Add organizations.
        foreach ($this->organizations as $organization) {
            $organization->createAndAppendDomElement($entry);
        }

        return $dom->saveXML($entry);
    }

    /**
     * @return \DOMElement
     */
    public function createEntryElement()
    {
        $dom = $this->getDomDocument();

        $entry = $dom->createElement('atom:entry');
        $entry->setAttribute('xmlns:atom', 'http://www.w3.org/2005/Atom');
        $entry->setAttribute('xmlns:gd', 'http://schemas.google.com/g/2005');

        $category = $dom->createElement('atom:category');
        $category->setAttribute('scheme', 'http://schemas.google.com/g/2005#kind');
        $category->setAttribute('term', 'http://schemas.google.com/contact/2008#contact');

        $entry->appendChild($category);

        return $entry;
    }
}
