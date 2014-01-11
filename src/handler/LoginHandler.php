<?php
/**
 * @author Thomas Peikert
 */
namespace handler;

use LoginManager;
use SessionManager;
use Template;

class LoginHandler extends AjaxHandler
{

    /**
     * login
     */
    public function loginAction()
    {
        // post form data
        $username = $this->ajaxData('username');
        $password = sha1($this->ajaxData('password'));

        // create new loginmanager
        $loginManager = new LoginManager();

        // try to login, returns null if login fails
        $user = $loginManager->login($username, $password);

        if ($user == null)
            $response = array('error' => 'Login inkorrekt!');
        else
        {
            // login and load presentations
            SessionManager::instance()->createSession($user);
            $template = new Template(Template::VIEW_INDEX);
            $response = array('html' => $template->getContent());
        }

        // respond
        echo json_encode($response);
    }

    /**
     * register
     */
    public function registerAction()
    {
        // post form data
        $username = $this->ajaxData('username');
        $password = sha1($this->ajaxData('password'));

        // create new loginmanager
        $loginManager = new LoginManager();
        // try to create user, if false is returned, the username is already in use
        if ($loginManager->createUser($username, $password))
            $response = array('registered' => 'User wurde erstellt!');
        else
            $response = array('error' => 'User exisitert bereits!');

        echo json_encode($response);
    }

} 