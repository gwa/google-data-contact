# google-data-contact

PHP library to generate **Google Data Contact XML** used for adding contacts via the [Domain Shared Contacts API](https://developers.google.com/admin-sdk/domain-shared-contacts/).

See https://developers.google.com/gdata/docs/2.0/elements

## Usage

NOTE: Not all fields are currently implemented.

### Implemented

* `content` (note)
* `gd:name`
* `gd:email`
* `gd:phoneNumber`
* `gd:structuredPostalAddress`

### Annotated example

```php
use Gwa\GoogleContact\Contact;

// Create a new contact.
$contact = new Contact();

// Set the name.
// Arguments: last, first, middle
$contact->setName('Doe', 'John')
    // Prefix and suffix can also be set
    ->setNamePrefix('Dr.')
    ->setNameSuffix('MD');

// Add a note.
$contact->setNote('Lorem ipsum');

// Add one or more email addresses.
// Arguments: address, type (opt.), is primary? (opt.)
$contact->addEmail('doe@example.org', 'home', true);
// Type defaults to "work"
$contact->addEmail('doe@example.com');

// Add one or more phone numbers.
// Arguments: number, type (opt.), is primary? (opt.)
$contact->addPhoneNumber('012 3456 789', 'home', true);

// Add one or more addresses.
$contact->addAddress('1600 Amphitheatre Parkway', 'work', true)
    ->setCity('Mountain View')
    ->setRegion('California')
    ->setPostCode('94043')
    // Arguments: County, Country code (opt.) https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2
    ->setCountry('U.S.A.', 'US');

// Access existing elements using 0-based index:
$email1 = $contact->email(0);
$email2 = $contact->email(1);
$phonenumber1 = $contact->phoneNumber(0);
$address1 = $contact->address(0);

// Render as XML.
$xmlstring = $contact->render();
```

The following XML is rendered:

```xml
<entry xmlns="http://www.w3.org/2005/Atom" xmlns:gd="http://schemas.google.com/g/2005">
  <category scheme="http://schemas.google.com/g/2005#kind" term="http://schemas.google.com/contact/2008#contact"/>
  <gd:name>
    <gd:givenName>John</gd:givenName>
    <gd:familyName>Doe</gd:familyName>
    <gd:namePrefix>Dr.</gd:namePrefix>
    <gd:nameSuffix>MD</gd:nameSuffix>
  </gd:name>
  <content>Lorem ipsum</content>
  <gd:email rel="http://schemas.google.com/g/2005#home" primary="true" address="doe@example.org"/>
  <gd:email rel="http://schemas.google.com/g/2005#work" address="doe@example.com"/>
  <gd:phoneNumber rel="http://schemas.google.com/g/2005#home" primary="true">012 3456 789</gd:phoneNumber>
  <gd:structuredPostalAddress rel="http://schemas.google.com/g/2005#work" primary="true">
    <gd:street>1600 Amphitheatre Parkway</gd:street>
    <gd:city>Mountain View</gd:city>
    <gd:region>California</gd:region>
    <gd:country code="US">U.S.A.</gd:country>
  </gd:structuredPostalAddress>
</entry>
```
