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
class HeadingElement extends AbstractTextElement {

    const Type = 'headingelement';

    const defaultWidth = 75;

    public function __construct() {
        parent::__construct(self::Type);
        $this->setWidth(self::defaultWidth);

    }

}