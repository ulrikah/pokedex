<?php
namespace App\Models;

use \App\System\App;
use \App\Models\Model;

class ProductsModel extends Model {

    protected $table = "products";

    public function all($user = null) {
        return $this->query("SELECT products.id, products.title, products.price, products.quantity, products.media, categories.title AS category
                             FROM {$this->table}
                             LEFT JOIN categories
                             ON products.category = categories.id
                             WHERE products.user = '{$this->username}'
                             ORDER BY products.id");
    }

    public function value() {
        $elements = $this->query("SELECT * FROM {$this->table} WHERE user = '{$this->username}'");
        return count($elements);
    }

    public function count() {
        $count = 0;

        foreach($this->query("SELECT quantity FROM {$this->table} WHERE user = '{$this->username}'") as $item) {
            $count+= $item->quantity;
        }

        return $count;
    }

    public function average($column) {
        $count = $this->value();
        $column_total = 0;

        foreach($this->query("SELECT $column FROM {$this->table} WHERE user = '{$this->username}'") as $item) {
            $column_total+= $item->$column;
        }

        if($count == 0) {
            return 0;
        }

        else {
            return $column_total / $count;
        }
    }
    
    public function getProductsByCategoryId($id){
        /*echo "SELECT *
                             FROM {$this->table}
                             WHERE products.user = '{$this->username}'
                             AND products.category = {$id}"; die;*/
        return $this->query("SELECT *
                             FROM {$this->table}
                             WHERE products.user = '{$this->username}'
                             AND products.category = {$id}");
        }

    public function low($count) {
        return $this->query("SELECT products.id, products.title, products.price, products.quantity, products.media, categories.title AS category
                             FROM {$this->table}
                             LEFT JOIN categories
                             ON products.category = categories.id
                             WHERE products.user = '{$this->username}'
                             ORDER BY products.quantity ASC
                             LIMIT $count");
    }

}
