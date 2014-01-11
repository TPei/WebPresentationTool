<?php
/**
 * @author Thomas Peikert
 */

namespace db\objects\slideElements;

/**
 * Class QuoteElement
 * @package db\slideElements
 * quote element
 */
class QuoteElement extends AbstractTextElement {

    const Type = 'quoteelement';

    const defaultWidth = 40;

    public function __construct() {
        parent::__construct(self::Type);
        $this->setWidth(self::defaultWidth);
    }

}