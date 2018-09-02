# google-data-contact

PHP library to generate **Google Data Contact XML** used for adding contacts via the [Domain Shared Contacts API](https://developers.google.com/admin-sdk/domain-shared-contacts/).

See https://developers.google.com/gdata/docs/2.0/elements

## Usage

NOTE: Not all fields are currently implemented.

### Implemented

* `gd:name`
* `atom:content` (note)
* `gd:email`
* `gd:phoneNumber`
* `gd:structuredPostalAddress`
* `gd:organization`

### Creating a Contact from scratch

Annotated example:

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

// Add one or more organizations.
// Arguments: name, type (opt.), is primary? (opt.)
$contact->addOrganization('Google, Inc.')
    ->setType('other')
    ->setLabel('Volunteer')
    ->setPrimary()
    ->setTitle('Tech Writer')
    ->setJobDescription('Writes documentation')
    ->setDepartment('Software Development')
    ->setSymbol('GOOG');

// Access existing elements using 0-based index:
$email1 = $contact->email(0);
$email2 = $contact->email(1);
$phonenumber1 = $contact->phoneNumber(0);
$address1 = $contact->address(0);
$organization1 = $contact->organization(0);

// Render as XML.
$xmlstring = $contact->render();
```

The following XML is rendered:

```xml
<atom:entry xmlns:atom="http://www.w3.org/2005/Atom" xmlns:gd="http://schemas.google.com/g/2005">
  <atom:category scheme="http://schemas.google.com/g/2005#kind" term="http://schemas.google.com/contact/2008#contact"/>
  <gd:name>
    <gd:givenName>John</gd:givenName>
    <gd:familyName>Doe</gd:familyName>
    <gd:namePrefix>Dr.</gd:namePrefix>
    <gd:nameSuffix>MD</gd:nameSuffix>
  </gd:name>
  <atom:content type="text">Lorem ipsum</atom:content>
  <gd:email rel="http://schemas.google.com/g/2005#home" primary="true" address="doe@example.org"/>
  <gd:email rel="http://schemas.google.com/g/2005#work" address="doe@example.com"/>
  <gd:phoneNumber rel="http://schemas.google.com/g/2005#home" primary="true">012 3456 789</gd:phoneNumber>
  <gd:structuredPostalAddress rel="http://schemas.google.com/g/2005#work" primary="true">
    <gd:street>1600 Amphitheatre Parkway</gd:street>
    <gd:city>Mountain View</gd:city>
    <gd:region>California</gd:region>
    <gd:country code="US">U.S.A.</gd:country>
  </gd:structuredPostalAddress>
  <gd:organization rel="http://schemas.google.com/g/2005#other" label="Volunteer" primary="true">
    <gd:orgName>Google, Inc.</gd:orgName>
    <gd:orgTitle>Tech Writer</gd:orgTitle>
    <gd:orgJobDescription>Writes documentation</gd:orgJobDescription>
    <gd:orgDepartment>Software Development</gd:orgDepartment>
    <gd:orgSymbol>GOOG</gd:orgSymbol>
  </gd:organization>
</atom:entry>
```

### Creating from XML return from the Google Domain Shared Contacts API

The XML for feed looks as follows (source: https://developers.google.com/admin-sdk/domain-shared-contacts/)

```xml
<feed xmlns='http://www.w3.org/2005/Atom'
    xmlns:openSearch='http://a9.com/-/spec/opensearchrss/1.0/'
    xmlns:gd='http://schemas.google.com/g/2005'
    xmlns:gContact='http://schemas.google.com/contact/2008'
    xmlns:batch='http://schemas.google.com/gdata/batch'>
  <id>https://www.google.com/m8/feeds/contacts/example.com/base</id>
  <updated>2008-03-05T12:36:38.836Z</updated>
  <category scheme='http://schemas.google.com/g/2005#kind'
    term='http://schemas.google.com/contact/2008#contact' />
  <title type='text'>example.com's Contacts</title>
  <link rel='http://schemas.google.com/g/2005#feed'
    type='application/atom+xml'
    href='https://www.google.com/m8/feeds/contacts/example.com/full' />
  <link rel='http://schemas.google.com/g/2005#post'
    type='application/atom+xml'
    href='https://www.google.com/m8/feeds/contacts/example.com/full' />
  <link rel='http://schemas.google.com/g/2005#batch'
    type='application/atom+xml'
    href='https://www.google.com/m8/feeds/contacts/example.com/full/batch' />
  <link rel='self' type='application/atom+xml'
    href='https://www.google.com/m8/feeds/contacts/example.com/full?max-results=25' />
  <author>
    <name>example.com</name>
    <email>example.com</email>
  </author>
  <generator version='1.0' uri='https://www.google.com/m8/feeds/contacts'>
    Contacts
  </generator>
  <openSearch:totalResults>1</openSearch:totalResults>
  <openSearch:startIndex>1</openSearch:startIndex>
  <openSearch:itemsPerPage>25</openSearch:itemsPerPage>
  <entry>
    <id>
      https://www.google.com/m8/feeds/contacts/example.com/base/c9012de
    </id>
    <updated>2008-03-05T12:36:38.835Z</updated>
    <category scheme='http://schemas.google.com/g/2005#kind'
      term='http://schemas.google.com/contact/2008#contact' />
    <title type='text'>Fitzgerald</title>
    <gd:name>
      <gd:fullName>Fitzgerald</gd:fullName>
    </gd:name>
    <link rel="http://schemas.google.com/contacts/2008/rel#photo" type="image/*"
      href="http://google.com/m8/feeds/photos/media/example.com/c9012de"/>
    <link rel='self' type='application/atom+xml'
      href='https://www.google.com/m8/feeds/contacts/example.com/full/c9012de' />
    <link rel='edit' type='application/atom+xml'
      href='https://www.google.com/m8/feeds/contacts/example.com/full/c9012de/1204720598835000' />
    <gd:phoneNumber rel='http://schemas.google.com/g/2005#home'
      primary='true'>
      456
    </gd:phoneNumber>
    <gd:extendedProperty name="pet" value="hamster" />
  </entry>
</feed>
```

Given the XML string, `Contact` instances can be created using the `ContactFactory`.

```php
use Gwa\GoogleContact\ContactFactory;

$factory = new ContactFactory();
// XML above is in $xml. Returns an array of Contact objects.
$contacts = $factory->createFromFeedXmlString($xml);
```

Given the XML for single entry, a single `Contact` instance can be created.

```php
$factory = new ContactFactory();
// XML above is in $xml. Returns a single Contact object.
$contact = $factory->createFromXmlString($xml);
```
