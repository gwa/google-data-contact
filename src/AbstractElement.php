<?php

namespace Gwa\GoogleContact;

/**
 * Abstract GD element.
 */
abstract class AbstractElement
{
    /**
     * @var \DOMDocument
     */
    private $document;

    /**
     * @var \DOMElement
     */
    private $element;

    /**
     * @var array
     */
    private $attributes = [];

    /**
     * @var string
     */
    private $content;

    /**
     * @param \DOMDocument $document
     * @return self
     */
    public function setDomDocument(\DOMDocument $document)
    {
        $this->document = $document;
        return $this;
    }

    /**
     * @return \DOMDocument
     */
    public function getDomDocument()
    {
        return $this->document;
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return self
     */
    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    /**
     * @param string $content
     *
     * @return self
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Creates the DOM Element, appends it to the DOM, and returns it.
     *
     * @param \DOMElement $parent
     *
     * @return \DOMElement
     */
    public function createAndAppendDomElement($parent = null)
    {
        $dom = $this->getDomDocument();
        if (!isset($parent)) {
            $parent = $dom;
        }

        $element = $this->createDomElement();

        if (isset($this->content)) {
            $element->nodeValue = $this->content;
        }

        if (method_exists($this, 'addTypeAttribute')) {
            $this->addTypeAttribute($element);
        }
        if (method_exists($this, 'addLabelAttribute')) {
            $this->addLabelAttribute($element);
        }
        if (method_exists($this, 'addPrimaryAttribute')) {
            $this->addPrimaryAttribute($element);
        }

        foreach ($this->attributes as $key => $value) {
            $element->setAttribute($key, $value);
        }

        $this->populateDomElement($element, $dom);

        $parent->appendChild($element);

        return $element;
    }

    /**
     * @return \DOMElement
     */
    protected function createDomElement()
    {
        $element = $this->document->createElement($this->getDomElementName());
        return $element;
    }

    /**
     * @param \DOMElement $element
     * @param \DOMDocument $dom
     */
    protected function populateDomElement(\DOMElement $element, \DOMDocument $dom)
    {
        // Do nothing in base class. Can be overridden.
    }

    /**
     * @return string
     */
    abstract protected function getDomElementName();
}
