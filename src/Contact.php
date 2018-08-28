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
     * Set the contact's name
     *
     * @param string $familyName
     * @param string $givenName
     * @param string $additionalName
     *
     * @return self
     */
    public function setName($familyName, $givenName, $additionalName = '')
    {
        $this->name()
            ->setFamilyName($familyName)
            ->setGivenName($givenName);

        if (!empty($additionalName)) {
            $this->name()->setAdditionalName($additionalName);
        }

        return $this;
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
     * @return self
     */
    public function addEmail($address, $type = '', $primary = false)
    {
        $email = new Email();
        $email->setAddress($address);
        $this->handleTypedPrimary($email, $type, $primary);
        $this->emails[] = $email;

        return $this;
    }

    /**
     * @param string $address
     * @param string $type
     * @param boolean $primary
     *
     * @return self
     */
    public function addPhoneNumber($number, $type = '', $primary = false)
    {
        $phonenumber = new PhoneNumber();
        $phonenumber->setPhoneNumber($number);
        $this->handleTypedPrimary($phonenumber, $type, $primary);
        $this->phoneNumbers[] = $phonenumber;

        return $this;
    }

    /**
     * @param string $street
     * @param string $type
     * @param boolean $primary
     *
     * @return self
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

        return $dom->saveXML($entry);
    }

    /**
     * @return \DOMElement
     */
    public function createEntryElement()
    {
        $dom = $this->getDomDocument();

        $entry = $dom->createElement('entry');
        $entry->setAttribute('xmlns', 'http://www.w3.org/2005/Atom');
        $entry->setAttribute('xmlns:gd', 'http://schemas.google.com/g/2005');

        $category = $dom->createElement('category');
        $category->setAttribute('scheme', 'http://schemas.google.com/g/2005#kind');
        $category->setAttribute('term', 'http://schemas.google.com/contact/2008#contact');

        $entry->appendChild($category);

        return $entry;
    }
}
