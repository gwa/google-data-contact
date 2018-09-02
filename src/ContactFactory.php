<?php

namespace Gwa\GoogleContact;

/**
 * Creates Google Contact instances.
 */
class ContactFactory
{
    /**
     * @param string $xml
     *
     * @return Contact
     */
    public function createFromXmlString($xml)
    {
        // Wrap in a root "feed" element with namespaces.
        $xml = sprintf(
            '<feed xmlns="http://www.w3.org/2005/Atom" xmlns:gd="http://schemas.google.com/g/2005">%s</feed>',
            $xml
        );
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->loadXML($xml);

        $element = $dom->getElementsByTagName('entry')[0];

        return $this->createFromDomElement($element);
    }

    /**
     * @param \DOMElement $element
     *
     * @return Contact
     */
    public function createFromDomElement(\DOMElement $element)
    {
        $contact = new Contact();

        // ID.
        $links = $element->getElementsByTagName('link');
        if ($len = $links->length) {
            for ($i=0; $i < $len; $i++) {
                if ($links[$i]->getAttribute('rel') === 'self') {
                    // Extract ID.
                    $pattern = '/\/([a-z0-9]+)$/';
                    if (preg_match($pattern, $links[$i]->getAttribute('href'), $matches)) {
                        $contact->setId($matches[1]);
                    }
                }
            }
        }

        // Etag.
        if ($etag = $element->getAttribute('gd:etag')) {
            $contact->setEtag($etag);
        }

        // Updated.
        if ($element->getElementsByTagName('updated')->length) {
            if ($updated = $this->getFirstChildValue($element, 'updated')) {
                $contact->setUpdated($updated);
            }
        }

        // Name.
        if ($element->getElementsByTagName('name')->length) {
            $name = $element->getElementsByTagName('name')[0];
            if ($familyName = $this->getFirstChildValue($name, 'familyName')) {
                $contact->name()->setFamilyName($familyName);
            }
            if ($givenName = $this->getFirstChildValue($name, 'givenName')) {
                $contact->name()->setGivenName($givenName);
            }
            if ($namePrefix = $this->getFirstChildValue($name, 'namePrefix')) {
                $contact->name()->setNamePrefix($namePrefix);
            }
            if ($nameSuffix = $this->getFirstChildValue($name, 'nameSuffix')) {
                $contact->name()->setNameSuffix($nameSuffix);
            }
        }

        // Emails.
        $emails = $element->getElementsByTagName('email');
        if ($len = $emails->length) {
            for ($i=0; $i < $len; $i++) {
                $item = $emails[$i];

                $address = $item->getAttribute('address');
                $email = $contact->addEmail($address);

                $type = $this->parseTypeFromRelAttribute($item);
                $email->setType($type);

                $email->setPrimary($item->getAttribute('primary') === 'true');
            }
        }

        // Phone numbers.
        $phoneNumbers = $element->getElementsByTagName('phoneNumber');
        if ($len = $phoneNumbers->length) {
            for ($i=0; $i < $len; $i++) {
                $item = $phoneNumbers[$i];

                $number = $item->nodeValue;
                $phonenumber = $contact->addPhoneNumber($number);

                $type = $this->parseTypeFromRelAttribute($item);
                $phonenumber->setType($type);

                $phonenumber->setPrimary($item->getAttribute('primary') === 'true');
            }
        }

        // Addresses.
        $addresses = $element->getElementsByTagName('structuredPostalAddress');
        if ($len = $addresses->length) {
            for ($i=0; $i < $len; $i++) {
                $item = $addresses[$i];

                $street = $this->getFirstChildValue($item, 'street');
                $address = $contact->addAddress($street);

                if ($city = $this->getFirstChildValue($item, 'city')) {
                    $address->setCity($city);
                }
                if ($region = $this->getFirstChildValue($item, 'region')) {
                    $address->setRegion($region);
                }
                if ($postCode = $this->getFirstChildValue($item, 'postcode')) {
                    $address->setPostCode($postCode);
                }
                if ($country = $this->getFirstChildValue($item, 'country')) {
                    $code = $item->getElementsByTagName('country')[0]->getAttribute('code');
                    $address->setCountry($country, $code);
                }

                $type = $this->parseTypeFromRelAttribute($item);
                $address->setType($type);

                $address->setPrimary($item->getAttribute('primary') === 'true');
            }
        }

        // Organizations.
        $organizations = $element->getElementsByTagName('organization');
        if ($len = $organizations->length) {
            for ($i=0; $i < $len; $i++) {
                $item = $organizations[$i];

                $name = $this->getFirstChildValue($item, 'orgName');
                $organization = $contact->addOrganization($name);

                if ($orgTitle = $this->getFirstChildValue($item, 'orgTitle')) {
                    $organization->setTitle($orgTitle);
                }
                if ($orgDepartment = $this->getFirstChildValue($item, 'orgDepartment')) {
                    $organization->setDepartment($orgDepartment);
                }
                if ($orgJobDescription = $this->getFirstChildValue($item, 'orgJobDescription')) {
                    $organization->setJobDescription($orgJobDescription);
                }
                if ($orgSymbol = $this->getFirstChildValue($item, 'orgSymbol')) {
                    $organization->setSymbol($orgSymbol);
                }

                $type = $this->parseTypeFromRelAttribute($item);
                $organization->setType($type);

                $organization->setPrimary($item->getAttribute('primary') === 'true');
            }
        }

        return $contact;
    }

    /**
     * @param \DOMElement $element
     * @param string $tag
     *
     * @return string|NULL
     */
    private function getFirstChildValue(\DOMElement $element, $tag)
    {
        $nodelist = $element->getElementsByTagName($tag);

        if ($nodelist->length) {
            return $nodelist[0]->nodeValue;
        }

        return null;
    }

    /**
     * @param \DOMElement $element
     *
     * @return string
     */
    private function parseTypeFromRelAttribute(\DOMElement $element)
    {
        $type = 'work';

        if ($rel = $element->getAttribute('rel')) {
            $pattern = '/#([a-z]+)$/';
            if (preg_match($pattern, $rel, $matches)) {
                $type = $matches[1];
            }
        }

        return $type;
    }
}
