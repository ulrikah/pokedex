<?php
namespace App\Controllers;

use \App\System\App;
use \App\System\Settings;
use \App\System\FormValidator;
use \App\Controllers\Controller;
use \App\Models\UsersModel;
use \App\System\Auth;

class SessionsController extends Controller {

    public function login() {
        if(!empty($_POST)) {
            

            //  T0D0 - password is non-hashed in the POST-request. therefore we are able to sniff it in Wireshark etc.? 
            //  MAJOR FLAW
            $username = isset($_POST['username']) ? $_POST['username'] : '';
            //  $password = isset($_POST['password']) ? hash('sha1', Settings::getConfig()['salt'] . $_POST['password']) : ''; <-- by TAs
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            
            // choose which cookies we would like to set
            // set httponly somewhere
            if($this->auth->checkCredentials($username, $password)) {
                setcookie("user", $username);                   //  not $_POST['username'] - any significance?
                setcookie("password",  $_POST['password']);     //  unecnrypted into the cookie
                if ($this->userRep->getAdmin($username)){       //  getAdmin(username) does a lookup in th db => admin users are pre-defined
                    setcookie("admin", 'yes');
                }else{
                    setcookie("admin", 'no');
                }
                //  session_start() is triggered in index.php
                $_SESSION['auth']       = $username;
                $_SESSION['id']         = $this->userRep->getId($username);
                $_SESSION['email']      = $this->userRep->getEmail($username);
                $_SESSION['password']   = $password;
                $_SESSION['LAST_ACTIVITY'] = time();

                App::redirect('dashboard');
            }

            else {
                //  This error msg gets printed if checkCredidentials fails
                $errors = [
                    "Your username and your password don't match."
                ];
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
