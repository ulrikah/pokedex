<?php
namespace App\Controllers;

use App\Models\CommentsModel;
use \App\Controllers\Controller;
use \DateTime;
use App\System\App;

class CommentsController extends Controller {
    
    protected $table = "comments";

    public function add() {
        if(!empty($_POST)){
                $text  = isset($_POST['comment']) ? $_POST['comment'] : '';
                
                //Filtering av text input
                //$text = mysql_real_escape_string($text);
                $text = strip_tags($text);

                $model = new CommentsModel;
                $model->create([
                    'created_at' => date('Y-m-d H:i:s'),
                    'user'       => $_COOKIE['user'],
                    'text'       => $text
                ]);
            }
         App::redirect('dashboard');
       }
    }