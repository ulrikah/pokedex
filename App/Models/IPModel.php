<?php
namespace App\Models;

use \App\System\App;
use \App\Models\Model;

class IPModel extends Model {

    protected $table = "ip";

    // check if login atempts during last 10 minutes > 3
    public function loginAttempt(){
        $ip = $_SERVER['REMOTE_ADDR'];  // ::1 is the default IP for localhost
    	$timestamp = date("Y-m-d H:i:s", time());

        $this->insertIP($ip, $timestamp);        
        $attempts = $this->countIP($ip, $timestamp);

        if ($attempts > 3){
            return false;
        }  
        return true;
    }

    // returns number of logins the last 10 minutes
    public function countIP($ip, $timestamp) {
        $elements = $this->query("  SELECT * 
                                    FROM {$this->table} 
                                    WHERE address LIKE '{$ip}' 
                                    AND timestamp > ('{$timestamp}' - INTERVAL 10 MINUTE)
                                ");
        return count($elements);

    }

    // insert row with IP and time of login
    public function insertIP($ip, $timestamp) {
        $this->create(['address' => $ip,
                       'timestamp' => $timestamp]);

    }
}
