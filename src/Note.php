<?php

namespace Gwa\GoogleContact;

/**
 * Note element.
 */
class Note extends AbstractElement
{
    /**
     * @param string $note
     * @return self
     */
    public function setNote($note)
    {
        $this->setContent($note);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function getDomElementName()
    {
        return 'content';
    }
}
