<?php
/**
 * @author Thomas Peikert
 */

/**
 * SessionManager
 * handles all session
 * Singleton pattern
 */

use db\MongoAdapter;
use db\objects\User;

class SessionManager
{

    const SESSION_ACTIVE = 'active';
    const SESSION_PRESENTATION = 'presentation';
    const SESSION_SLIDE = 'slide';

    /** @var User */
    private $user;

    /** @var SessionManager */
    private static $instance;

    /**
     * Singleton time!
     * @return SessionManager
     */
    public static function instance()
    {
        if (self::$instance == null) {
            self::$instance = new SessionManager();
        }

        return self::$instance;
    }

    private function __construct()
    {
        if ($this->isActive()) {
            $this->user = new User();
            $document = MongoAdapter::instance()->findById($_SESSION[User::FIELD_ID], User::COLLECTION_NAME);
            $this->user->fromDocument($document);
        }

    }

    /**
     * create a users' session
     * @param User $user
     */
    public function createSession(User $user)
    {
        $this->user = $user;
        $_SESSION[self::SESSION_ACTIVE] = true;
        $_SESSION[User::FIELD_ID] = $user->getId()->__toString();
    }

    /**
     * destroy a users' session
     */
    public function destroySession()
    {
        $_SESSION = array();
    }

    /**
     * check if users' session is active
     * @return bool
     */
    public function isActive()
    {
        return $_SESSION[self::SESSION_ACTIVE] == true;
    }

    /**
     * @return \db\objects\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * saves active presentation's id to session
     * @return string
     */
    public function getActivePresentationId()
    {
        return $_SESSION[self::SESSION_PRESENTATION];
    }

    /**
     * gets active presentation's id from session
     * @param string
     */
    public function setActivePresentationId($presentationId)
    {
        // php automatically serializes an object when saving to session
        $_SESSION[self::SESSION_PRESENTATION] = $presentationId;
    }

    /**
     * saves active slide's id to session
     * @return string
     */
    public function getActiveSlideId()
    {
        return $_SESSION[self::SESSION_SLIDE];
    }

    /**
     * gets active slide's id from session
     * @param string
     */
    public function setActiveSlideId($slideId)
    {
        // php automatically serializes an object when saving to session
        $_SESSION[self::SESSION_SLIDE] = $slideId;
    }

}