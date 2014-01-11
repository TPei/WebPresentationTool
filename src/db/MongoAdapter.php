<?php
/**
 * @author Thomas Peikert
 */

/**
 * Mongo Adapter
 * handles ALL MongoDB requests
 * singleton pattern used
 */

namespace db;

use db\objects\DBObject;
use MongoClient;
use MongoCursor;
use MongoDB;
use MongoId;

class MongoAdapter {

    const DB_HOST = 'DB_HOST';
    const DB_NAME = 'DB_NAME';
    const RESOURCE_DB_NAME = 'RESOURCE_DB_NAME';

    private static $DB_HOST;
    private static $DB_NAME;
    private static $RESOURCE_DB_NAME;

    /** @var MongoDB */
    private $mongoDB;

    /** @var MongoDB */
    private $resourceDB;

    /** @var MongoAdapter */
    private static $instance;

    /**
     * Setup db connection parameter
     * @param array $settings
     */
    public static function settings(array $settings) {
        self::$DB_HOST = $settings[self::DB_HOST];
        self::$DB_NAME = $settings[self::DB_NAME];
        self::$RESOURCE_DB_NAME = $settings[self::RESOURCE_DB_NAME];
    }

    /** @return MongoAdapter */
    public static function instance() {
        if (self::$instance == null) {
            self::$instance = new MongoAdapter();
        }

        return self::$instance;
    }

    private function __construct() {
        $client = new MongoClient(self::$DB_HOST);
        $this->mongoDB = $client->selectDB(self::$DB_NAME);
        $this->resourceDB = $client->selectDB(self::$RESOURCE_DB_NAME);
    }

    /**
     * @return MongoDB
     */
    public function getResourceDb() {
        return $this->resourceDB;
    }

    /**
     * returns collection document found by id
     * @param $id
     * @param $collectionName
     * @return array|null
     */
    public function findById($id, $collectionName) {
        return $this->findOne(array(DBObject::FIELD_ID => new MongoId($id)), $collectionName);
    }

    /**
     * @param array $query
     * @param $collectionName
     * @return array|null
     */
    public function findOne(array $query, $collectionName) {
        return $this->mongoDB->selectCollection($collectionName)->findOne($query);
    }

    /**
     * @param $collectionName
     * @return MongoCursor
     */
    public function findAll($collectionName) {
        return $this->mongoDB->selectCollection($collectionName)->find();
    }

    /**
     * @param $collectionName
     */
    public function dropCollection($collectionName) {
        $collection = $this->mongoDB->selectCollection($collectionName);
        $collection->drop();

    }

    /**
     * drops database
     */
    public function dropDatabase() {
        $this->mongoDB->drop();
    }

    /**
     * checks if dbobject already exists in collection
     * if yes, object is updated,
     * if not, object is added to database and ID is added to dbobject
     * @param \db\objects\DBObject $dbObject
     * @param $collectionName
     * @return \db\objects\DBObject
     */
    public function save(DBObject $dbObject, $collectionName) {
        $document = $dbObject->toDocument();

        // remove all null values
        foreach ($document as $key => $value) {
            if (is_null($value) || $value == '')
                unset($document[$key]);
        }

        $this->mongoDB->selectCollection($collectionName)->save($document);

        return $dbObject;
    }

    public function createRef($collectionName, DBObject $dbObject) {
        $id = $dbObject->getId();
        return $this->mongoDB->createDBRef($collectionName, $id);
    }

    public function removeFromEmbeddedDocument($collectionName, array $criteria, array $query) {
        $query = array('$pull' => $query);
        $collection = $this->mongoDB->selectCollection($collectionName);
        return $collection->update($criteria, $query);
    }

    /**
     * removes top-level element (not embedded)
     * @param $collectionName
     * @param array $criteria
     */
    public function removeDocument($collectionName, array $criteria) {
        $collection = $this->mongoDB->selectCollection($collectionName);
        $collection->remove($criteria);
    }

}