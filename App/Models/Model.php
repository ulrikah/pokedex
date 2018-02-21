<?php
namespace App\Models;

use \App\System\App;

class Model {
    
    protected $table;
    protected $username;
    
    
    public function __construct(){
        if (isset ($_COOKIE['user'])){ $this->username = $_COOKIE['user'];}
    }

    public function all($user = null) {
        if ($user){
            return $this->query("SELECT * FROM {$this->table} WHERE user = '{$user}'");
        }else{
            return $this->query('SELECT * FROM ' . $this->table);
        }
    }

    public function find($id) {
        return $this->query("SELECT * FROM {$this->table} WHERE id = {$id}", null, true);
    }

    public function update($id, $fields) {
        $sql_parts  = [];
        $attributes = [];

        foreach($fields as $k => $v) {
            $sql_parts[]  = "$k = ?";
            $attributes[] = $v;
        }

        $sql_part = implode(', ', $sql_parts);
        App::getDb()->execute("UPDATE {$this->table} SET $sql_part WHERE id = $id", $attributes);
    }

    public function delete($id){
        App::getDb()->execute("DELETE FROM {$this->table} WHERE id = $id");
    }

    public function create($fields) {
        $sql_parts  = [];
        $attributes = [];

        foreach($fields as $k => $v) {
            $sql_parts[]  = "$k = ?";
            $attributes[] = $v;
        }

        $sql_part = implode(', ', $sql_parts);

        App::getDb()->execute("INSERT INTO {$this->table} SET $sql_part", $attributes);
    }
    
    public function query($statement, $attributes = null, $one = false) {
        if($attributes){
            return App::getDb()->prepare(
                $statement,
                $attributes,
                $one
            );
        }

        //elseif (strpos($statement, 'WHERE id =')) {echo $statement; die;}
        else{
            return App::getDb()->query(
                $statement,
                $one
            );
        }
    }

}
