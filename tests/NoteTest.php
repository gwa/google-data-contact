<?php

namespace Gwa\GoogleContact\Test;

use Gwa\GoogleContact\Note;
use PHPUnit\Framework\TestCase;

final class NoteTest extends TestCase
{
    public function testConstructable()
    {
        $name = new Note();
        $this->assertInstanceOf(Note::class, $name);
    }

    public function testGetDomElement()
    {
        $content = 'Lorem ipsum';

        $note = new Note();
        $note->setDomDocument(new \DOMDocument('1.0', 'UTF-8'));

        $note->setNote($content);

        $element = $note->createAndAppendDomElement();
        $this->assertInstanceOf('\DOMElement', $element);

        $xmlstring = $note->getDomDocument()->saveXML();
        $xmlexpected = file_get_contents(__DIR__ . '/fixtures/note.xml');
        $this->assertEquals($xmlexpected, $xmlstring);
    }
}
