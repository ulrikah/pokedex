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
        
        //  cannot be null ? 
        if ($user === false) {
            return false;
        }
        
        // sha1 as hashing algo - 'test' is used as salt (check config.yml)
        $passwordHash = hash('sha1', Settings::getConfig()['salt'] . $password);
        
        if ($passwordHash === $this->userRep->getPasswordhash($username)){  // same query as above, then checks corresponding pw
            return true;
        }else{
            return false;
        }
    }
    
    public function isAdmin(){
        if ($this->isLoggedIn()){
            if ($_COOKIE['admin'] === 'yes'){
                return true;
            }else{
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
