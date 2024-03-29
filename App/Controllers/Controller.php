<?php
namespace App\Controllers;

use \App\System\App;
use \App\System\Auth;
use \App\Models\UsersModel;
use App\Models\CategoriesModel;
use App\Models\Model;
use App\Models\ProductsModel;
use \Datetime;

class Controller {
    
    protected $auth;
    protected $userRep;
    protected $categoryRep;
    protected $productRep;
    
    public function __construct(){
        $this->auth = new Auth;
        $this->userRep = new UsersModel;
        $this->categoryRep = new CategoriesModel;
        $this->productRep = new ProductsModel;
        $this->rep = new Model;
    }
    
    public function render($template, $attributes) {
        
        $adminPage = $this->auth->isAdminPage($template);
        $isAdmin = $this->auth->isAdmin();
        // T0D0 - the comment under indicates that something is wrong. Is this where cookies are stored in cleartext?
        //Remove after debugging is complete! <-- this comment was included by the TAs
        if (isset($_COOKIE['user'])){
            if ($this->userRep->getUserRow($_COOKIE['user'])){
                $attributes['passwordHash'] = $this->userRep->getPasswordHash($_COOKIE['user']);
                $attributes['username'] = $_COOKIE['user'];
            }
        }
        
        if ($isAdmin){
            $attributes['admin'] = 'true';
        }
        
        if ($adminPage && !($isAdmin)){
            App::error403();
        }else{
            echo App::getTwig()->render($template, $attributes);
        }
    }
}
