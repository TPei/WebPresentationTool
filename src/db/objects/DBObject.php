<?php
/**
 * @author Thomas Peikert
 */

namespace db\objects;

use MongoId;
use SimpleXMLElement;

/**
 * Class DBObject
 * @package db
 * abstract parent class for all objects that can be saved into the database
 */
abstract class DBObject {

    /** @var MongoId */
    protected $id;
    const FIELD_ID = '_id';

    /**
     * generate id for object
     */
    public function __construct() {
        $this->id = new MongoId();
    }

    /**
     * generates db document from object
     * @return array
     */
    public function toDocument() {
        return array(
            self::FIELD_ID => $this->id
        );
    }

    /**
     * @param SimpleXMLElement $parent
     */
    abstract public function toXML(SimpleXMLElement $parent);

    /**
     * fills object from db data
     * @param $document
     */
    public function fromDocument($document) {
        $this->id = $document[DBObject::FIELD_ID];
    }

    /**
     * @param $id MongoId
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return MongoId
     */
    public function getId() {
        return $this->id;
    }

}