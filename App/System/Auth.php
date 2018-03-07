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
        
        //  cannot be null 
        if ($user === false) {
            return false;
        }
        
        // sha1 as hashing algo - 'test' is used as salt (check config.yml)
        // T0D0 - use another hash algo
        $passwordHash = hash('sha1', Settings::getConfig()['salt'] . $password);
        
        //  Since we can't re-hash the cleartext values of the already exisiting passwords hashed by SHA1, this has to perform a double check against the DB, i.e. first hash SHA1, then hash the SHA1-hash to f.ex. SHA256 to check against the DB
        if ($passwordHash === $this->userRep->getPasswordhash($username)){
            return true;
        }else{
            return false;
        }
    }
    
    public function isAdmin(){
        if ($this->isLoggedIn()){
            // T0D0 - does not seem to work
            if ($this->userRep->getAdmin($username)){ // evt if ($_SESSION['admin']
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
