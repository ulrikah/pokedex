<?php
namespace App\Controllers;

use \App\System\App;
use \App\System\Settings;
use \App\System\FormValidator;
use \App\Controllers\Controller;
use \App\Models\UsersModel;
use \App\System\Mailer;

class UsersController extends Controller {

    public function all() {
        $model = new UsersModel();
        $data  = $model->all();

        $this->render('pages/admin/users.twig', [
            'title'       => 'Users',
            'description' => 'Users - Just a simple inventory management system.',
            'page'        => 'users',
            'users'    => $data
        ]);
    }


    /*
    This function is used when the administrator adds a user from the administrator dashboard
    */
    public function add() {
        if(!empty($_POST)) {
            $username              = isset($_POST['username']) ? $_POST['username'] : '';
            $email                 = isset($_POST['email']) ? $_POST['email'] : '';
            $password              = isset($_POST['password']) ? $_POST['password'] : '';
            $password_verification = isset($_POST['password_verification']) ? $_POST['password_verification'] : '';

            $validator = new FormValidator();
            $validator->validUsername('username', $username, "Your username is not valid (no spaces, uppercase, special character)");
            $validator->availableUsername('username', $username, "Your username is not available");
            $validator->validEmail('email', $email, "Your email is not valid");
            $validator->validPassword('password', $password, $password_verification, "You didn't write the same password twice");

            if($validator->isValid()) {
                $model = new UsersModel();
                $model->create([
                    'username'   => $username,
                    'email'      => $email,
                    'password'   => hash('sha256', Settings::getConfig()['salt'] . $password),
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                App::redirect('admin/users');
            }

            else {
                $this->render('pages/admin/users_add.twig', [
                    'title'       => 'Add user',
                    'description' => 'Users - Just a simple inventory management system.',
                    'page'        => 'users',
                    'errors'      => $validator->getErrors(),
                    'data'        => [
                        'username' => $username,
                        'email'    => $email
                    ]
                ]);
            }
        }

        else {
            $this->render('pages/admin/users_add.twig', [
                'title'       => 'Add user',
                'description' => 'Users - Just a simple inventory management system.',
                'page'        => 'users'
            ]);
        }
    }
    
    public function registrationIsValid($validator, $username, $password, $password_verification): bool { 
        
            if ($validator->notEmpty('username',$username, "Your username can't be empty")){
                $validator->validUsername('username2', $username, "Your username is not valid (no spaces, uppercase, special character)");
            }
           
            $validator->availableUsername('username', $username, "Your username is not available");
            
            if ($validator->notEmpty('password',$password, "Your password can't be empty")){
                $validator->validPassword('password2', $password, $password_verification, "You didn't write the same password twice");
            }
            
            if($validator->isValid()) {
                return true;
            }else{
                return false;
            }
    }
    
    public function createNewUser($username, $password, $password_verification){
        $model = new UsersModel();
        
                $model->create([
                    'username'   => $username,
                    'password'   => hash('sha1', Settings::getConfig()['salt'] . $password),
                    'created_at' => date('Y-m-d H:i:s'),
                    'admin'      => 0
                ]);
    }
    
    
    /* This function is used when a non-administrator registers a new user*/
    public function registrateUser() {
        $validator = New FormValidator;
        if(!empty($_POST)) {
            $username              = isset($_POST['username']) ? $_POST['username'] : '';
            $password              = isset($_POST['password']) ? $_POST['password'] : '';
            $password_verification = isset($_POST['password_verification']) ? $_POST['password_verification'] : '';

            if($this->registrationIsValid($validator, $username, $password, $password_verification)) {
                
                $this->createNewUser($username, $password, $password_verification);
                
                $this->render('pages/registration.twig', [
                'title'       => 'Registrate',
                'description' => 'Registrate a new user',
                'errors'      => $validator->getErrors(),
                'message'     => ('Registration successful!')
                ]);
            }

            else {
                $this->render('pages/registration.twig', [
                'title'       => 'Registrate',
                'description' => 'Registrate a new user',
                'errors'      => $validator->getErrors()
        ]);
            }
        }

        else {
            $this->render('pages/registration.twig', [
            'title'       => 'Registrate',
            'description' => 'Registrate a new user',
            'errors'      => $validator->getErrors(),
        ]);
        }
    }

    public function edit($id) {
        if(!empty($_POST)) {
            $username = isset($_POST['username']) ? $_POST['username'] : '';
            $email    = isset($_POST['email']) ? $_POST['email'] : '';

            $validator = new FormValidator();
            $validator->validUsername('username', $username, "Your username is not valid (no spaces, uppercase, special character)");
            $validator->validEmail('email', $email, "Your email is not valid");

            if($validator->isValid()) {
                $model = new UsersModel();
                $model->update($id, [
                    'username' => $username,
                    'email'    => $email
                ]);

                if($_SESSION['id'] == $id) {
                    $this->logout();
                    App::redirect('signin');
                }

                else {
                    App::redirect('admin/users');
                }
            }

            else {
                $this->render('pages/admin/users_edit.twig', [
                    'title'       => 'Edit user',
                    'description' => 'Users - Just a simple inventory management system.',
                    'page'        => 'users',
                    'errors'      => $validator->getErrors(),
                    'data'        => [
                        'username' => $username,
                        'email'    => $email
                    ]
                ]);
            }
        }

        else {
            $model = new UsersModel();
            $data = $model->find($id);

            $this->render('pages/admin/users_edit.twig', [
                'title'       => 'Edit user',
                'description' => 'Users - Just a simple inventory management system.',
                'page'        => 'users',
                'data'        => $data
            ]);
        }
    }

    public function delete($id) {
        if(!empty($_POST)) {
            $model = new UsersModel();
            $model->delete($id);

            App::redirect('admin/users');
        }

        else {
            $model = new UsersModel();
            $data = $model->find($id);
            $this->render('pages/admin/users_delete.twig', [
                'title'       => 'Delete user',
                'description' => 'Users - Just a simple inventory management system.',
                'page'        => 'users',
                'data'        => $data
            ]);
        }
    }
    
    public function viewSQL($id) {
        echo var_dump($this->userRep->find($id)); die;
    }

    public function logout() {
        session_destroy();
        App::redirect();
    }

}
