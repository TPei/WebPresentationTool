<?php

namespace db;

use db\objects\DBObject;
use db\objects\Presentation;
use db\objects\User;
use MongoId;

/**
 * @author Thomas Peikert
 * PresentationManager to shorten and simplify common Presentation actions
 */
class PresentationManager {

    /**
     * find a presentation by id and create a Presentation object from the found document
     * @param $id
     * @param $user
     * @return Presentation
     */
    public static function findPresentationById($id, $user = null) {
        if ($user == null)
            $user = new User();

        $answer = MongoAdapter::instance()->findOne(array(DBObject::FIELD_ID => new MongoId($id)), Presentation::COLLECTION_NAME);

        $presentation = new Presentation($user);
        $presentation->fromDocument($answer);
        return $presentation;
    }

} 