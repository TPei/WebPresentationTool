<?php
/**
 * @author Thomas Peikert
 */

namespace db\objects\slideElements;

/**
 * Class TextElement
 * @package db\slideElements
 * text element
 */
class TextElement extends AbstractTextElement {

    const Type = 'textelement';

    const defaultWidth = 40;

    public function __construct() {
        parent::__construct(self::Type);
        $this->setWidth(self::defaultWidth);

    }

}