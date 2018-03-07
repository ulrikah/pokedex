<?php
namespace App\Controllers;

use \App\System\App;
use \App\System\Settings;
use \App\System\FormValidator;
use \App\Controllers\Controller;
use \App\Models\UsersModel;
use \App\Models\IPModel;
use \App\System\Auth;



class SessionsController extends Controller {

    public function login() {
        if(!empty($_POST)) {

            // check for login attemps
            $ipModel = new IPModel();
            $isAllowedToLogin = $ipModel->loginAttempt();            

            // T0D0 - too strict check. May be a solution split up the code in loginAttempt
            if (!$isAllowedToLogin){
                $errors = ["You have attempted too many logins. Please wait and try again later."];
                $this->render('pages/signin.twig', [
                    'title'       => 'Sign in',
                    'description' => 'Sign in to the dashboard',
                    'errors'      => isset($errors) ? $errors : ''
                ]);
                exit;
            }

            //  T0D0 - password is non-hashed in the POST-request. therefore we are able to sniff it in Wireshark etc.? 
            $username = isset($_POST['username']) ? $_POST['username'] : '';
            //  $password = isset($_POST['password']) ? hash('sha1', Settings::getConfig()['salt'] . $_POST['password']) : ''; <-- by TAs
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            
            // T0D0 find a way to remove the user-cookie safely and what to do with PHPSESSID
            // set httponly somewhere
            if($this->auth->checkCredentials($username, $password)) {
                setcookie("user", $username);                   //  not $_POST['username'] - any significance?

                //  session_start() is triggered in index.php
                $_SESSION['auth']       = $username;
                $_SESSION['id']         = $this->userRep->getId($username);
                $_SESSION['email']      = $this->userRep->getEmail($username);
                $_SESSION['password']   = $password;
                $_SESSION['admin']      = $this->userRep->getAdmin($username);
                $_SESSION['LAST_ACTIVITY'] = time();

                App::redirect('dashboard');
            }

            else {
                $errors = ["Your username and your password don't match." ];

            }
        }

        $this->render('pages/signin.twig', [
            'title'       => 'Sign in',
            'description' => 'Sign in to the dashboard',
            'errors'      => isset($errors) ? $errors : ''
        ]);
    }

    public function logout() {
        App::redirect();
    }

}
