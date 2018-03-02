<?php
namespace App\Models;

use \App\System\App;
use \App\Models\Model;

class IPModel extends Model {

    protected $table = "ip";

    // check if login atempts during last 10 minutes > 3
    public function loginAttempt($ip){
    	$timestamp = date("Y-m-d H.m.s", time());

    	// INSERT QUERY DOES NOT WORK YET. Something wrong with DB connection? 

    	//$this->query("INSERT INTO {this->table} (address, timestamp) VALUES ($ip, $timestamp)");
    	//$this->query("INSERT INTO ip (address, timestamp) VALUES ($ip, $timestamp)");
    	//$this->query("INSERT INTO ip SET address=$ip, timestamp=$timestamp");

    	/*
    	
    	$result = $this->query("SELECT COUNT(*) FROM `ip` WHERE `address` LIKE '$ip' AND `timestamp` > ($currentTime - interval 10 minute)")
    	$count = $result->fetchAll();

    	// T0D0 - do something else than echo
    	if($count[0] > 3){
		  echo "Your are allowed 3 attempts in 10 minutes";
		}

		*/
    }

}
