<?php

namespace Gwa\GoogleContact;

/**
 * Organization GD element.
 */
class Organization extends AbstractElement
{
    use TraitHasPrimary;
    use TraitHasType;
    use TraitHasLabel;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $department;

    /**
     * @var string
     */
    private $jobDescription;

    /**
     * @var string
     */
    private $symbol;

    /**
     * @var string
     */
    private $where;

    /**
     * @param string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $title
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param string $department
     * @return self
     */
    public function setDepartment($department)
    {
        $this->department = $department;
        return $this;
    }

    /**
     * @param string $jobDescription
     * @return self
     */
    public function setJobDescription($jobDescription)
    {
        $this->jobDescription = $jobDescription;
        return $this;
    }

    /**
     * @param string $symbol
     * @return self
     */
    public function setSymbol($symbol)
    {
        $this->symbol = $symbol;
        return $this;
    }

    /**
     * @param string $where
     * @return self
     */
    public function setWhere($where)
    {
        $this->where = $where;
        return $this;
    }

    protected function populateDomElement(\DOMElement $element, \DOMDocument $dom)
    {
        // Add child elements.
        if (isset($this->name)) {
            $element->appendChild($dom->createElement('gd:orgName', $this->name));
        }

        if (isset($this->title)) {
            $element->appendChild($dom->createElement('gd:orgTitle', $this->title));
        }

        if (isset($this->jobDescription)) {
            $element->appendChild($dom->createElement('gd:orgJobDescription', $this->jobDescription));
        }

        if (isset($this->department)) {
            $element->appendChild($dom->createElement('gd:orgDepartment', $this->department));
        }

        if (isset($this->symbol)) {
            $child = $dom->createElement('gd:orgSymbol', $this->symbol);
            $element->appendChild($child);
        }

        if (isset($this->where)) {
            $child = $dom->createElement('gd:where');
            $child->setAttribute('valueString', $this->where);
            $element->appendChild($child);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getDomElementName()
    {
        return 'gd:organization';
    }
}
