<?php
require('../vendor/autoload.php');

use \App\System\App;
use \App\System\Router\Router;
use \App\System\Settings;
use \App\Models\UsersModel;

session_start();

$app    = new App();
$router = new Router($_GET);


$router->get('/', function() {
    $controller = new \App\Controllers\ProductsController();
    $controller->blank();
});

$router->get('/dashboard/', function() {
    App::secured();
    $controller = new \App\Controllers\ProductsController();
    $controller->index();
});

$router->get('/about/', function() {
    App::secured();
    $controller = new \App\Controllers\Controller();
    $controller->render('pages/about.twig', [
        'title'       => 'About',
        'description' => 'About us',
        'page'        => 'about'
    ]);
});

$router->post('/comment/add', function() {
    App::secured();
    $controller = new \App\Controllers\CommentsController();
    $controller->add();
});

$router->get('/products/', function() {
    App::secured();
    $controller = new \App\Controllers\ProductsController();
    $controller->all();
});

$router->get('/products/view/:id/', function($id) {
    App::secured();
    $controller = new \App\Controllers\ProductsController();
    $controller->viewSQL($id);
})->with('id', null);

$router->get('/products/add/', function() {
    App::secured();
    $controller = new \App\Controllers\ProductsController();
    $controller->add();
});

$router->post('/products/add/', function() {
    App::secured();
    $controller = new \App\Controllers\ProductsController();
    $controller->add();
});

$router->get('/products/:id/edit/', function($id) {
    App::secured();
    $controller = new \App\Controllers\ProductsController();
    $controller->edit($id);
})->with('id', '[0-9]+');

$router->post('/products/:id/edit/', function($id) {
    App::secured();
    $controller = new \App\Controllers\ProductsController();
    $controller->edit($id);
})->with('id', '[0-9]+');

$router->get('/products/:id/delete/', function($id) {
    App::secured();
    $controller = new \App\Controllers\ProductsController();
    $controller->delete($id);
})->with('id', '[0-9]+');

$router->post('/products/:id/delete/', function($id) {
    App::secured();
    $controller = new \App\Controllers\ProductsController();
    $controller->delete($id);
})->with('id', '[0-9]+');

$router->get('/categories/', function() {
    App::secured();
    $controller = new \App\Controllers\CategoriesController();
    $controller->all();
});

$router->get('/categories/add/', function() {
    App::secured();
    $controller = new \App\Controllers\CategoriesController();
    $controller->add();
});

$router->post('/categories/add/', function() {
    App::secured();
    $controller = new \App\Controllers\CategoriesController();
    $controller->add();
});

$router->get('/categories/:id/edit/', function($id) {
    App::secured();
    $controller = new \App\Controllers\CategoriesController();
    $controller->edit($id);
})->with('id', '[0-9]+');

$router->post('/categories/:id/edit/', function($id) {
    App::secured();
    $controller = new \App\Controllers\CategoriesController();
    $controller->edit($id);
})->with('id', '[0-9]+');

$router->get('/categories/:id/delete/', function($id) {
    App::secured();
    $controller = new \App\Controllers\CategoriesController();
    $controller->delete($id);
})->with('id', '[0-9]+');

$router->post('/categories/:id/delete/', function($id) {
    App::secured();
    $controller = new \App\Controllers\CategoriesController();
    $controller->delete($id);
})->with('id', '[0-9]+');

$router->get('/admin/users/:id/', function($id) {
    App::secured();
    $controller = new \App\Controllers\UsersController();
    $controller->viewSQL($id);
})->with('id', '[0-9]+');

$router->get('/admin/users/', function() {
    App::secured();
    $controller = new \App\Controllers\UsersController();
    $controller->all();
});

$router->get('/admin/users/add/', function() {
    App::secured();
    $controller = new \App\Controllers\UsersController();
    $controller->add();
});

$router->post('/admin/users/add/', function() {
    App::secured();
    $controller = new \App\Controllers\UsersController();
    $controller->add();
});

$router->get('/admin/users/:id/edit/', function($id) {
    App::secured();
    $controller = new \App\Controllers\UsersController();
    $controller->edit($id);
})->with('id', '[0-9]+');

$router->post('/admin/users/:id/edit/', function($id) {
    App::secured();
    $controller = new \App\Controllers\UsersController();
    $controller->edit($id);
})->with('id', '[0-9]+');

$router->get('/admin/users/:id/delete/', function($id) {
    App::secured();
    $controller = new \App\Controllers\UsersController();
    $controller->delete($id);
})->with('id', '[0-9]+');

$router->post('/admin/users/:id/delete/', function($id) {
    App::secured();
    $controller = new \App\Controllers\UsersController();
    $controller->delete($id);
})->with('id', '[0-9]+');

$router->get('/reports/', function() {
    App::secured();
    $controller = new \App\Controllers\ReportsController();
    $controller->all();
});

$router->get('/reports/add/', function() {
    App::secured();
    $controller = new \App\Controllers\ReportsController();
    $controller->add();
});

$router->post('/reports/add/', function() {
    App::secured();
    $controller = new \App\Controllers\ReportsController();
    $controller->add();
});

$router->get('/reports/:id/delete/', function($id) {
    App::secured();
    $controller = new \App\Controllers\ReportsController();
    $controller->delete($id);
})->with('id', '[0-9]+');

$router->post('/reports/:id/delete/', function($id) {
    App::secured();
    $controller = new \App\Controllers\ReportsController();
    $controller->delete($id);
})->with('id', '[0-9]+');

$router->get('/signin/', function() {
    $controller = new \App\Controllers\SessionsController();
    $controller->login();
});

$router->post('/signin/', function() {
    $controller = new \App\Controllers\SessionsController();
    $controller->login();
});

//start routes for registration
$router->get('/registration/', function() {
    $controller = new \App\Controllers\UsersController();
    $controller->registrateUser();
});

$router->post('/registration/', function() {
    $controller = new \App\Controllers\UsersController();
    $controller->registrateUser();
});
//end routes for registration

$router->get('/signout/', function() {
    $controller = new \App\Controllers\UsersController();
    $controller->logout();
});

$router->get('/api/products', function() {
    App::secured();
    $controller = new \App\Controllers\ProductsController();
    $controller->search($_GET);
});
    
$router->get('/api/stats', function() {
    App::secured();
    $controller = new \App\Controllers\ProductsController();
    $controller->stats();
});
        
$router->get('/api/index', function() {
    App::secured();
    echo Settings::getConfig()['url'];
});
    
$router->get('/api/products/', function() {
    $controller = new \App\Controllers\ProductsController();
    $controller->api();
});
        
$router->get('/api/products/:id/', function($id) {
    $controller = new \App\Controllers\ProductsController();
    $controller->api($id);
});
                        
$router->get('/api/categories/', function() {
    $controller = new \App\Controllers\CategoriesController();
    $controller->api();
});
                            
$router->get('/api/categories/:id/', function($id) {
    $controller = new \App\Controllers\CategoriesController();
    $controller->api($id);
});

$router->error(function() {
    App::error();
});

$router->run();
