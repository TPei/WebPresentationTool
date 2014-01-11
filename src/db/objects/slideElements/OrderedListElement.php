<?php
/**
 * @author Thomas Peikert
 */

namespace db\objects\slideElements;

use SimpleXMLElement;

class OrderedListElement extends ListElement {

    const Type = 'olistelement';

    public function __construct() {
        parent::__construct(self::Type);
    }

    public function toXml(SimpleXMLElement $parent) {
        $document = $this->toDocument();
        $child = $parent->addChild('olistelement');
        foreach ($document as $key => $value) {
            if (!is_array($value)) {
                $child->addAttribute($key, $value);
            }
            else
            {
                foreach($value as $element)
                {
                    $grandchild = $child->addChild('oli');
                    $grandchild->addAttribute('listelement', $element);
                }
            }
        }
    }
} 