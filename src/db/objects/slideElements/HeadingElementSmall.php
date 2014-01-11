<?php
/**
 * @author Thomas Peikert
 */

namespace db\objects\slideElements;

/**
 * Class HeadingElement
 * @package db\slideElements
 * heading element
 */
class HeadingElementSmall extends AbstractTextElement {

    const Type = 'headingelementsmall';

    const defaultWidth = 50;

    public function __construct() {
        parent::__construct(self::Type);
        $this->setWidth(self::defaultWidth);
    }

}