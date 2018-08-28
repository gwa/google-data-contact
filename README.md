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
$contact->setName('Doe', 'John');

// Prefix and suffix can also be set
$contact->name()->setNamePrefix('Dr.');

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

// Render as XML.
$xmlstring = $contact->render();
```
