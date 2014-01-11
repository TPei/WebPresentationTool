<?php
/**
 * @author Thomas Peikert
 */

namespace db\objects\slideElements;

use db\objects\DBObject;
use SimpleXMLElement;

/**
 * Class SlideElement
 * @package db\slideElements
 * abstract parent class for all slide elements
 * provides position, size, type etc
 */
abstract class SlideElement extends DBObject {

    /** @var int */
    protected $x;
    const FIELD_X = 'x';

    /** @var int */
    protected $y;
    const FIELD_Y = 'y';

    /** @var int */
    protected $z;
    const FIELD_Z = 'z';

    /** @var int */
    protected $height;
    const FIELD_HEIGHT = 'height';

    /** @var int */
    protected $width;
    const FIELD_WIDTH = 'width';

    /** @var string */
    protected $type;
    const FIELD_TYPE = 'type';

    public function __construct($type) {
        parent::__construct();
        $this->type = $type;
    }

    /**
     * generates db document from object
     * @return array
     */
    public function toDocument() {
        $array = parent::toDocument();
        $array[self::FIELD_X] = $this->x;
        $array[self::FIELD_Y] = $this->y;
        $array[self::FIELD_Z] = $this->z;
        $array[self::FIELD_HEIGHT] = $this->height;
        $array[self::FIELD_WIDTH] = $this->width;
        $array[self::FIELD_TYPE] = $this->type;
        return $array;
    }

    /**
     * fills object from db data
     * @param $document
     * @return SlideElement
     */
    public function fromDocument($document) {
        parent::fromDocument($document);
        $this->x = $document[SLideElement::FIELD_X];
        $this->y = $document[SLideElement::FIELD_Y];
        $this->z = $document[SLideElement::FIELD_Z];
        $this->height = $document[SLideElement::FIELD_HEIGHT];
        $this->width = $document[SLideElement::FIELD_WIDTH];
        $this->type = $document[SLideElement::FIELD_TYPE];
        return $this;
    }

    public function toXml(SimpleXMLElement $parent) {
        $document = $this->toDocument();
        $child = $parent->addChild($this->type);
        foreach ($document as $key => $value)
            $child->addAttribute($key, $value);
    }

    /**
     * @return int
     */
    public function getX() {
        return $this->x;
    }

    /**
     * @param int $x
     */
    public function setX($x) {
        $this->x = $x;
    }

    /**
     * @return int
     */
    public function getY() {
        return $this->y;
    }

    /**
     * @param int $y
     */
    public function setY($y) {
        $this->y = $y;
    }

    /**
     * @return int
     */
    public function getZ() {
        return $this->z;
    }

    /**
     * @param int $z
     */
    public function setZ($z) {
        $this->z = $z;
    }

    /**
     * @return int
     */
    public function getHeight() {
        return $this->height;
    }

    /**
     * @param int $height
     */
    public function setHeight($height) {
        $this->height = $height;
    }

    /**
     * @return int
     */
    public function getWidth() {
        return $this->width;
    }

    /**
     * @param int $width
     */
    public function setWidth($width) {
        $this->width = $width;
    }

    /**
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @param $type string
     */
    public function setType($type) {
        $this->type = $type;
    }


}