<?php
namespace App\Controllers;

use \App\System\App;
use \App\System\ImageUpload;
use \App\System\Settings;
use \App\Controllers\Controller;
use \App\Models\RevisionsModel;
use \App\Models\CategoriesModel;
use \App\Models\ProductsModel;
use \App\Models\ReportsModel;
use \App\System\FormValidator;
use \App\Models\CommentsModel;

class ProductsController extends Controller {

    public function blank() {
        $this->render('pages/index.twig', [
            'title'       => 'Home',
            'description' => 'Just a simple inventory management system.'
        ]);
    }

    public function index() {
        $model = new ProductsModel();
        $value = $model->value();
        $count = $model->count();
        $average_quantity = $model->average('quantity');
        $average_price    = $model->average('price');
        $lows_products    = $model->low(5);

        $model2 = new CategoriesModel();
        $categories_value = $model2->allotment();

        $model3  = new ReportsModel();
        $reports = $model3->all($_COOKIE['user']);
        
        $model4 = new CommentsModel();
        $comments = $model4->getShoutBox();
        foreach ($comments as $comment){
            $comment->created_at = substr($comment->created_at, 11, 18);
        }
        //echo var_dump($comments);die;
        
        $stats = [
            'value' => $value,
            'count' => $count,
            'average_quantity' => round($average_quantity),
            'average_price'    => '$' .round($average_price, 2),
            'lows_products'    => $lows_products,
            'reports'          => $reports
        ];

        $this->render('pages/dashboard.twig', [
            'title'       => 'Dashboard',
            'description' => 'Dashboard - Just a simple inventory management system.',
            'page'        => 'dashboard',
            'stats'       => $stats,
            'comments'    => $comments
        ]);
    }

    public function all() {
        $model = new ProductsModel();
        $data  = $model->all();
        $count = count($data);

        $this->render('pages/products.twig', [
            'title'       => 'Products',
            'description' => 'Products - Just a simple inventory management system.',
            'page'        => 'products',
            'products'    => $data,
            'count'       => $count,
            'admin'       => $this->auth->isAdmin()
        ]);
    }

    public function add() {
        if(!empty($_POST)) {
            $title       = isset($_POST['title']) ? $_POST['title'] : '';
            $description = isset($_POST['description']) ? $_POST['description'] : '';
            $category    = isset($_POST['category']) ? $_POST['category'] : '';
            $price       = isset($_POST['price']) ? (int) $_POST['price'] : '';
            $quantity    = isset($_POST['quantity']) ? (int) $_POST['quantity'] : '';
            $media       = isset($_FILES['media']) ? $_FILES['media'] : '';

            $validator = new FormValidator();
            $validator->notEmpty('title', $title, "Your title must not be empty");
            $validator->notEmpty('description', $description, "Your description must not be empty");
            $validator->validCategory('category', $category, "Your category must be valid");
            $validator->isNumeric('price', $price, "Your price must be a number");
            $validator->isInteger('quantity', $quantity, "Your quantity must be a number");
            $validator->validImage('media', $media, "You didn't provided a media or it is invalid");

            if($validator->isValid()) {
                $upload    = new ImageUpload();
                $media_url = $upload->add($media);

                $model = new ProductsModel();
                $model->create([
                    'title'       => $title,
                    'description' => $description,
                    'category'    => $category,
                    'price'       => $price,
                    'quantity'    => $quantity,
                    'media'       => $media_url,
                    'created_at'  => date('Y-m-d H:i:s'),
                    'user'        => $_COOKIE['user']
                ]);

                App::redirect('products');
            }

            else {
                $model = new CategoriesModel();
                $categories  = $model->all($_COOKIE['user']);
                $this->render('pages/products_add.twig', [
                    'title'       => 'Add product',
                    'description' => 'Products - Just a simple inventory management system.',
                    'page'        => 'products',
                    'errors'      => $validator->getErrors(),
                    'categories'  => $categories,
                    'data'        => [
                        'title'       => $title,
                        'description' => $description,
                        'price'       => $price,
                        'quantity'    => $quantity
                    ]
                ]);
            }
        }

        else {
            $model = new CategoriesModel();
            $categories  = $model->all($_COOKIE['user']);
            
            if ($categories){
                $this->render('pages/products_add.twig', [
                    'title'       => 'Add product',
                    'description' => 'Products - Just a simple inventory management system.',
                    'page'        => 'products',
                    'categories'  => $categories
                ]);
            }else{
                $this->render('pages/products_add.twig', [
                    'title'       => 'Add product',
                    'description' => 'Products - Just a simple inventory management system.',
                    'page'        => 'products',
                    'errors'      => ['You need to add at least one category before you can add a product.']
                ]);
            }
        }
    }

    public function edit($id) {
        if(!empty($_POST)) {
            $title       = isset($_POST['title']) ? $_POST['title'] : '';
            $description = isset($_POST['description']) ? $_POST['description'] : '';
            $category    = isset($_POST['category']) ? $_POST['category'] : '';
            $price       = isset($_POST['price']) ? (int) $_POST['price'] : '';
            $quantity    = isset($_POST['quantity']) ? (int) $_POST['quantity'] : '';

            $validator = new FormValidator();
            $validator->notEmpty('title', $title, "Your title must not be empty");
            $validator->notEmpty('description', $description, "Your description must not be empty");
            $validator->validCategory('category', $category, "Your category must be valid");
            $validator->isNumeric('price', $price, "Your price must be a number");
            $validator->isInteger('quantity', $quantity, "Your quantity must be a number");

            if($validator->isValid()) {
                $model = new ProductsModel();
                $model->update($id, [
                    'title'       => $title,
                    'description' => $description,
                    'category'    => $category,
                    'price'       => $price,
                    'quantity'    => $quantity
                ]);

                $revisions = new RevisionsModel();
                $revisions->create([
                    'type'    => 'products',
                    'type_id' => $id,
                    'user'    => $_SESSION['auth']
                ]);

                App::redirect('products');
            }

            else {
                $model = new CategoriesModel();
                $categories  = $model->all();

                $model2 = new RevisionsModel();
                $revisions = $model2->revisions($id, 'products');

                $this->render('pages/products_add.twig', [
                    'title'       => 'Edit product',
                    'description' => 'Products - Just a simple inventory management system.',
                    'page'        => 'products',
                    'errors'      => $validator->getErrors(),
                    'revisions'   => $revisions,
                    'categories'  => $categories,
                    'data'        => [
                        'title'       => $title,
                        'description' => $description,
                        'price'       => $price,
                        'quantity'    => $quantity,
                        'category'    => $category
                    ]
                ]);
            }
        }

        else {
            $model = new CategoriesModel();
            $categories  = $model->all();

            $model2 = new ProductsModel();
            $data   = $model2->find($id);

            $model3 = new RevisionsModel();
            $revisions = $model3->revisions($id, 'products');

            $this->render('pages/products_edit.twig', [
                'title'       => 'Edit product',
                'description' => 'Products - Just a simple inventory management system.',
                'page'        => 'products',
                'revisions'   => $revisions,
                'data'        => $data,
                'categories'  => $categories
            ]);
        }
    }

    public function delete($id) {
        if(!empty($_POST)) {
            $model = new ProductsModel();
            $file  = $model->find($id)->media;
            unlink(__DIR__ . '/../../public/uploads/' . $file);
            $model->delete($id);

            App::redirect('products');
        }

        else {
            $model = new ProductsModel();
            $data  = $model->find($id);
            $this->render('pages/products_delete.twig', [
                'title'       => 'Delete product',
                'description' => 'Products - Just a simple inventory management system.',
                'page'        => 'products',
                'data'        => $data
            ]);
        }
    }

    public function stats() {
        $model = new ProductsModel();
        $value = $model->value();
        $count = $model->count();
        $average_quantity = $model->average('quantity');
        $average_price    = $model->average('price');
        $lows_products    = $model->low(5);

        $model2 = new CategoriesModel();
        $categories_value = $model2->allotment();

        $stats = [
            'value' => $value,
            'count' => $count,
            'average_quantity' => $average_quantity,
            'average_price'    => $average_price,
            'categories'       => $categories_value,
            'lows_products'    => $lows_products
        ];

        echo json_encode($stats);
    }
    
    public function api($id = null) {
        if($id) {
            $model = new ProductsModel();
            $data  = $model->find($id);
            $data->media = Settings::getConfig()['url'] . 'uploads/' . $data->media;;
            header('Content-Type: application/json');
            echo json_encode($data);
        }
        else {
            $model = new ProductsModel();
            $data  = $model->all();
            foreach($data as $key => $element) {
                $data[$key]->media = Settings::getConfig()['url'] . 'uploads/' . $data[$key]->media;
            }
            header('Content-Type: application/json');
            echo json_encode($data);
        }
    }
    
    public function viewSQL($id) {
        echo var_dump($this->productRep->find($id)); die;
    }
    
}
