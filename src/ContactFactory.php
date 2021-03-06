<?php

namespace Gwa\GoogleContact;

/**
 * Creates Google Contact instances.
 */
class ContactFactory
{
    /**
     * Returns an array of Contact instances from feed XML.
     *
     * @param string $xml
     *
     * @return []Contact
     */
    public function createFromFeedXmlString($xml)
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->loadXML($xml);

        $feed = $dom->getElementsByTagName('feed')[0];
        $entries = $feed->getElementsByTagName('entry');
        $contacts = [];

        for ($i=0, $l=$entries->length; $i<$l; $i++) {
            $contacts[] = $this->createFromDomElement($entries[$i]);
        }

        return $contacts;
    }

    /**
     * Return a single Contact instance from an "entry" XML fragment.
     *
     * @param string $xml
     *
     * @return Contact
     */
    public function createFromXmlString($xml)
    {
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
        $id = $element->getElementsByTagName('id');
        if ($id->length) {
            $contact->setId($id[0]->nodeValue);
        }

        $links = $element->getElementsByTagName('link');
        if ($len = $links->length) {
            for ($i=0; $i < $len; $i++) {
                $rel = $links[$i]->getAttribute('rel');
                $href = $links[$i]->getAttribute('href');

                if ($rel === 'self') {
                    $contact->setLinkSelf($href);
                    continue;
                }

                if ($rel === 'edit') {
                    $contact->setLinkEdit($href);
                    continue;
                }

                // TODO Handle image link.
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
