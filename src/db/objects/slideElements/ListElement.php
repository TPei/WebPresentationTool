<?php
/**
 * @author Thomas Peikert
 */

namespace db\objects\slideElements;
use SimpleXMLElement;

/**
 * Class ListElement
 * @package db\slideElements
 * list element (can be ul or ol)
 */
class ListElement extends SlideElement {

    /** @var array */
    private $elements = array();
    const FIELD_ELEMENTS = 'elements';

    const Type = 'listelement';

    public function __construct($type) {
        parent::__construct($type);
    }

    /**
     * generates db document from object
     * @return array
     */
    public function toDocument() {
        $array = parent::toDocument();
        foreach ($this->elements as $element) {
            $array[self::FIELD_ELEMENTS][] = $element;
        }
        return $array;
    }

    /**
     * fills object from db data
     * @param $document
     * @return ListElement
     */
    public function fromDocument($document) {
        parent::fromDocument($document);
        foreach ($document[self::FIELD_ELEMENTS] as $element) {
            $this->elements[] = $element;
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getElements() {
        return $this->elements;
    }

    /**
     * @param array $elements
     */
    public function setElements($elements) {
        $this->elements = $elements;
    }

}