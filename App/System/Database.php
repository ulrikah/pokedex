<?php
namespace App\System;

use \PDO;

class Database {

    private $db_name;
    private $db_user;
    private $db_password;
    private $db_host;
    private $pdo;

    public function __construct($db_name, $db_user, $db_password, $db_host) {
        $this->db_name     = $db_name;
        $this->db_user     = $db_user;
        $this->db_password = $db_password;
        $this->db_host     = $db_host;
        $this->db_name     = $db_name;
    }

    //  this one may print out some ugly error messages i guess ? 
    private function getPDO() {
        if($this->pdo === null) {
            $this->pdo = new PDO("mysql:dbname={$this->db_name};host={$this->db_host}", $this->db_user, $this->db_password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        }

        return $this->pdo;
    }

    // Executes an SQL statement, returning a result set as a PDOStatement object
    //  ... might be an idea to check which queries that are prepared, and which that are not
    public function query($statement, $one = false) {
        $req  = $this->getPDO()->query($statement);

        if($one) {
            $data = $req->fetch();  //  fetch the next row from req (result set)
        }

        else {
            $data = $req->fetchAll();   //  returns all result set rows from req
        }

        return $data;
    }

    //  Prepares a statement for execution and returns a statement object
    public function prepare($statement, $attributes, $one = false) {
        $req = $this->getPDO()->prepare($statement);
        //echo $statement; echo $attributes; die;
        $req->execute($attributes);

        if($one) {
            $data = $req->fetch();
        }

        else {
            $data = $req->fetchAll();
        }

        return $data;
    }

    //  Executes a prepared statement
    public function execute($statement, $attributes = false) {
        if(!$attributes) {
            $this->getPDO()->query($statement);
        }

        else {
            $req = $this->getPDO()->prepare($statement);
            $req->execute($attributes);
        }
    }

}
