<?php
namespace App\System;

use \PHPMailer;

class Mailer extends PHPMailer {

    public function __construct() {
        parent::__construct();
        $this->isSMTP();
        $this->Host        = Settings::getConfig()['mail']['host'];
        $this->Username    = Settings::getConfig()['mail']['username'];
        $this->Password    = Settings::getConfig()['mail']['password'];
        $this->Port        = Settings::getConfig()['mail']['port'];
        $this->SMTPOptions = array(
            'ssl' => array(
                'verify_peer'       => false,
                'verify_peer_name'  => false,
                'allow_self_signed' => true
            )
        );
    }

}
