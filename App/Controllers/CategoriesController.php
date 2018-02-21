<?php
namespace App\Controllers;

use App\Models\ProductsModel;
use \App\System\App;
use \App\System\FormValidator;
use \App\System\Settings;
use \App\Controllers\Controller;
use \App\Models\CategoriesModel;
use \App\Models\RevisionsModel;
use \DateTime;

class CategoriesController extends Controller {

    public function all() {
        $model = new CategoriesModel();
        $data  = $model->all($_COOKIE['user']);

        $this->render('pages/categories.twig', [
            'title'       => 'Categories',
            'description' => 'Categories - Just a simple inventory management system.',
            'page'        => 'categories',
            'categories'  => $data
        ]);
    }

    public function add() {
        if(!empty($_POST)) {
            $title       = isset($_POST['title']) ? $_POST['title'] : '';
            $description = isset($_POST['description']) ? $_POST['description'] : '';

            $validator = new FormValidator();
            $validator->notEmpty('title', $title, "Your title must not be empty");
            $validator->notEmpty('description', $description, "Your description must not be empty");

            if($validator->isValid()) {
                $model = new CategoriesModel();
                $model->create([
                    'title'       => $title,
                    'description' => $description,
                    'created_at'  => date('Y-m-d H:i:s'),
                    'user'        => $_COOKIE['user']
                ]);

                App::redirect('categories');
            }

            else {
                $this->render('pages/categories_add.twig', [
                    'title'       => 'Add category',
                    'description' => 'Categories - Just a simple inventory management system.',
                    'page'        => 'categories',
                    'errors'      => $validator->getErrors(),
                    'data'        => [
                        'title'       => $title,
                        'description' => $description
                    ]
                ]);
            }
        }

        else {
            $this->render('pages/categories_add.twig', [
                'title'       => 'Add category',
                'description' => 'Categories - Just a simple inventory management system.',
                'page'        => 'categories'
            ]);
        }
    }

    public function edit($id) {
        if(!empty($_POST)) {
            $title       = isset($_POST['title']) ? $_POST['title'] : '';
            $description = isset($_POST['description']) ? $_POST['description'] : '';

            $validator = new FormValidator();
            $validator->notEmpty('title', $title, "Your title must not be empty");
            $validator->notEmpty('description', $description, "Your description must not be empty");

            if($validator->isValid()) {
                $model = new CategoriesModel();
                $model->update($id, [
                    'title'       => $title,
                    'description' => $description
                ]);

                $revisions = new RevisionsModel();
                $revisions->create([
                    'type'    => 'categories',
                    'type_id' => $id,
                    'user'    => $_SESSION['auth']
                ]);

                App::redirect('categories');
            }

            else {
                $model = new RevisionsModel();
                $revisions = $model->revisions($id, 'categories');

                $this->render('pages/categories_edit.twig', [
                    'title'       => 'Edit category',
                    'description' => 'Categories - Just a simple inventory management system.',
                    'page'        => 'categories',
                    'revisions'   => $revisions,
                    'errors'      => $validator->getErrors(),
                    'data'        => [
                        'title'       => $title,
                        'description' => $description
                    ]
                ]);
            }
        }

        else {
            $model = new CategoriesModel();
            $data = $model->find($id);

            $model2    = new RevisionsModel();
            $revisions = $model2->revisions($id, 'categories');

            $this->render('pages/categories_edit.twig', [
                'title'       => 'Edit category',
                'description' => 'Categories - Just a simple inventory management system.',
                'page'        => 'categories',
                'revisions'   => $revisions,
                'data'        => $data
            ]);
        }
    }

    public function delete($id) {
        $model2 = new ProductsModel($_COOKIE['user']);
        $products = $model2->getProductsByCategoryId($id);
        
        if(!empty($_POST)) {
            foreach($products as $product){
                $model2->delete($product->id);
            }
            
            $model = new CategoriesModel();
            $model->delete($id);
            App::redirect('categories');
        }

        else {
            $model = new CategoriesModel($_COOKIE['user']);
            $data = $model->find($id);
            $this->render('pages/categories_delete.twig', [
                'title'       => 'Delete category',
                'description' => 'Categories - Just a simple inventory management system.',
                'page'        => 'categories',
                'data'        => $data,
                'products'    => $products
            ]);
        }
    }

    public function single($id, $slug) {
        $model = new CategoriesModel();
        $data  = $model->find($id);

        if($data->slug === $slug) {
            $this->render('pages/single.twig', [
                'title'       => 'Single',
                'description' => 'Just a simple inventory management system.',
                'page'        => 'products',
                'post' => $data
            ]);
        }

        else {
            App::error();
        }
    }
    
    public function api($id = null) {
        if($id) {
            $model = new CategoriesModel();
            $data  = $model->find($id);
            header('Content-Type: application/json');
            echo json_encode($data);
        }
        else {
            $model = new CategoriesModel();
            $data  = $model->all();
            header('Content-Type: application/json');
            echo json_encode($data);
        }
    }
}
