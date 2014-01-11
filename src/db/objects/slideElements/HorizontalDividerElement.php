<?php
/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 12/11/13
 * Time: 8:58 AM
 */

namespace db\objects\slideElements;


use SimpleXMLElement;

class HorizontalDividerElement extends AbstractTextElement{

    const Type = 'dividerelement';

    public function __construct() {
        parent::__construct(self::Type);
    }

    /**
     * generates db document from object
     * @return array
     */
    public function toDocument() {
        $array = parent::toDocument();
        return $array;
    }

    /**
     * fills object from db data
     * @param $document
     * @return HorizontalDividerElement
     */
    public function fromDocument($document) {
        parent::fromDocument($document);
        return $this;
    }

    public function toXml(SimpleXMLElement $parent) {
        $document = $this->toDocument();
        $child = $parent->addChild('horizontaldiverelemement');
        foreach ($document as $key => $value) {
            if (!is_array($value)) {
                $child->addAttribute($key, $value);
            }
        }
    }
} 