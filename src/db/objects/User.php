<?php
/**
 * @author Thomas Peikert
 */

namespace db\objects;

use db\MongoAdapter;
use SimpleXMLElement;

/**
 * Class User
 * @package db
 * User
 * -> owns presentations
 */
class User extends DBObject {

    const COLLECTION_NAME = 'users';

    /** @var string */
    private $username;
    const FIELD_USERNAME = 'username';

    /** @var string */
    private $password;
    const FIELD_PASSWORD = 'password';

    /**
     * @var array
     */
    private $presentations = array();
    const FIELD_PRESENTATIONS = 'presentations';

    /**
     * generates db document from object
     * @return array
     */
    public function toDocument() {
        $array = parent::toDocument();
        $array[self::FIELD_USERNAME] = $this->username;
        $array[self::FIELD_PASSWORD] = $this->password;

        if (empty($this->presentations)) {
            return $array;
        }

        // create reference to all presentations in 'presentation' collection
        foreach ($this->presentations as $presentation) {
            $array[self::FIELD_PRESENTATIONS][] = MongoAdapter::instance()->createRef(Presentation::COLLECTION_NAME, $presentation);
        }

        return $array;
    }

    /**
     * fills object from db data
     * @param $document
     * @return $this|void
     */
    public function fromDocument($document) {
        parent::fromDocument($document);
        $this->username = $document[self::FIELD_USERNAME];
        $this->password = $document[self::FIELD_PASSWORD];

        if ($document[self::FIELD_PRESENTATIONS] == null)
            return $this;

        foreach ($document[self::FIELD_PRESENTATIONS] as $presentationRefDoc) {
            // post presentation document from collection, finding it by id
            $presentation = new Presentation($this);
            $presentationCollection = $presentationRefDoc['$ref'];
            $presentationId = $presentationRefDoc['$id'];
            $presentationDocument = MongoAdapter::instance()->findOne(array(Presentation::FIELD_ID => $presentationId), $presentationCollection);

            $presentation->fromDocument($presentationDocument);

            $this->addPresentation($presentation);

        }
        return $this;
    }

    /**
     * converts object to xml and adds as child to xml element
     * @param SimpleXMLElement $parent
     */
    public function toXml(SimpleXMLElement $parent) {
        $document = $this->toDocument();
        $child = $parent->addChild('User');
        foreach ($document as $key => $value)
            $child->addAttribute($key, $value);
    }

    /**
     * @param Presentation $presentation
     */
    public function addPresentation(Presentation $presentation) {
        $this->presentations[$presentation->getId()->__toString()] = $presentation;
    }

    /**
     * deletes a presentation
     * @param Presentation $presentation
     */
    public function deletePresentation(Presentation $presentation) {
        $presentationId = $presentation->getId();

        $element = array_search($this->presentations[$presentationId->__toString()], $this->presentations);
        unset($this->presentations[$element]); // removes it from user

        $criteria = array(DBObject::FIELD_ID => $presentationId);
        MongoAdapter::instance()->removeDocument(Presentation::COLLECTION_NAME, $criteria);

        MongoAdapter::instance()->save($this, User::COLLECTION_NAME);
    }

    /**
     * @param $id
     * @return Presentation
     */
    public function getPresentation($id) {
        return $this->presentations[$id];
    }

    /**
     * @param $username
     */
    public function setUsername($username) {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password) {
        $this->password = $password;
    }

    public function getPresentations() {
        return $this->presentations;
    }

    public function setPresentations($presentations) {
        $this->presentations = $presentations;
    }

}