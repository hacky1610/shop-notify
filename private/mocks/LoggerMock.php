<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once dirname( __FILE__ ) . '/../interfaces/ILogger.php';


class LoggerMock implements ILogger{
    private $messages;

    function __construct(){
        $this->messages = "";
    }


    public function Info($message) {
        $this->messages .= $message;
        echo $message;
    }

    public function Call($message) {
        $this->messages .= $message;  
        echo $message;

    }

    public function GetMessages() {
        return $this->messages;
    }

}

