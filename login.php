<?php
/**
 * @author Thomas Peikert
 */
include 'bootstrap.php';

// post form data
$username = $_POST['username'];
$password = sha1($_POST['password']);
$action = $_POST['action'];

// create new loginmanager
$loginManager = new LoginManager();

if ($action == 'register')
{
    // try to create user, if false is returned, the username is already in use
    if($loginManager->createUser($username, $password))
        $response = array('registered'=>'User wurde erstellt!');
    else
        $response = array('error'=>'User exisitert bereits!');
}
else if ($action == 'login')
{
    // try to login, returns null if login fails
    $user = $loginManager->login($username, $password);

    if($user == null)
        $response = array('error'=>'Login inkorrekt!');
    else{ // login and load presentations
        SessionManager::instance()->createSession($user);
        $template = new Template(Template::VIEW_INDEX);
        $response = array('html'=>$template->getContent());
    }
}
// respond
echo json_encode($response);