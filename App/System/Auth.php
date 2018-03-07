<?php
namespace App\System;

use \App\Models\UsersModel;

class Auth{
    
    protected $userRep;
    
    public function __construct(){
        $this->userRep = new UsersModel;
    }
    
    public function checkCredentials($username, $password)
    {   
        // user must be in db
        $user = $this->userRep->getUserRow($username);  //  query('SELECT * FROM users WHERE username = "' . $username .'"', true)
        
        if ($user === false) {
            return false;
        }
        
        $passwordHash = hash('sha512', Settings::getConfig()['salt'] . $password);
        if ($passwordHash === $this->userRep->getPasswordhash($username)){
            return true;
        }else{
            return false;
        }
    }
    
    public function isAdmin(){
        if ($this->isLoggedIn()){
            if ($_SESSION['admin']){
                    return true;
            }
            else{
                return false;
            }
        }
    }
    
    //  only checks if a cookie 'user' has been set. Not a _major_ flaw, since isLoggedIn() is only used in isAdmin()
    public function isLoggedIn(){
        if (isset($_COOKIE['user'])){
            return true;
        }
    }
    
    public function isAdminPage($template){
        if (strpos($template, 'admin') == '6'){ //  strpos() — Find the position of the first occurrence of a substring in a string
            return true;;
        }else{
            return false;
        }
        
    }
}
