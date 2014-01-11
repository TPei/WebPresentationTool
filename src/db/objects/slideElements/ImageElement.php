<?php
/**
 * @author Thomas Peikert
 */

namespace db\objects\slideElements;

use db\ImageManager;
use File;
use MongoId;

class ImageElement extends SlideElement {

    const Type = 'imageelement';

    /** @var MongoId */
    private $imageRef;
    const FIELD_IMAGE_REF = 'imageRef';

    public function __construct() {
        parent::__construct(self::Type);
    }

    /**
     * @return File
     */
    public function getFile(){
        return ImageManager::getImage($this->imageRef);
    }

    /**
     * generates db document from object
     * @return array
     */
    public function toDocument() {
        $array = parent::toDocument();
        $array[self::FIELD_IMAGE_REF] = $this->imageRef;
        return $array;
    }

    /**
     * fills object from db data
     * @param $document
     * @return Linkelement
     */
    public function fromDocument($document) {
        parent::fromDocument($document);
        $this->imageRef = $document[self::FIELD_IMAGE_REF];
        return $this;
    }

    /**
     * @return \MongoId
     */
    public function getImageRef() {
        return $this->imageRef;
    }

    /**
     * @param \MongoId $imageRef
     */
    public function setImageRef($imageRef) {
        $this->imageRef = $imageRef;
    }

} 