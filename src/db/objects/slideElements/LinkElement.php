<?php
/**
 * @author Thomas Peikert
 */

namespace db\objects\slideElements;

/**
 * Class LinkElement
 * @package db\slideElements
 * link element
 */
class LinkElement extends AbstractTextElement {

    /** @var string */
    protected $link;
    const FIELD_LINK = 'link';

    const Type = 'linkelement';

    public function __construct() {
        parent::__construct(self::Type);
    }

    /**
     * generates db document from object
     * @return array
     */
    public function toDocument() {
        $array = parent::toDocument();
        $array[self::FIELD_LINK] = $this->link;
        return $array;
    }

    /**
     * fills object from db data
     * @param $document
     * @return Linkelement
     */
    public function fromDocument($document) {
        parent::fromDocument($document);
        $this->link = $document[self::FIELD_LINK];
        return $this;
    }

    /**
     * @return string
     */
    public function getLink() {
        return $this->link;
    }

    /**
     * @param string $link
     */
    public function setLink($link) {
        $this->link = $link;
    }

}