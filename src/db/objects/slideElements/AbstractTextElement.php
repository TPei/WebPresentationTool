<?php
/**
 * @author Thomas Peikert
 */

namespace db\objects\slideElements;

abstract class AbstractTextElement extends SlideElement {

    /**
     * @var string
     */
    protected $text;
    const FIELD_TEXT = 'text';

    public function __construct($type) {
        parent::__construct($type);
    }

    /**
     * generates db document from object
     * @return array
     */
    public function toDocument() {
        $array = parent::toDocument();
        $array[self::FIELD_TEXT] = $this->text;
        return $array;
    }

    /**
     * fills object from db data
     * @param $document
     * @return TextElement
     */
    public function fromDocument($document) {
        parent::fromDocument($document);
        $this->text = $document[TextElement::FIELD_TEXT];
        return $this;
    }

    /**
     * @return string
     */
    public function getText() {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText($text) {
        $this->text = $text;
    }

}