<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Datastore {
    static $consumerKeyName = "wcn_consumerKey";
    static $consumerSecret = "wcn_consumerSecret";
    private $wpDataStore;

    function __construct($wpDataStore){
        $this->wpDataStore = $wpDataStore;
    }

    public function GetConsumerKey() {
        return $this->wpDataStore->Get(self::$consumerKeyName);
    }

    public function SetConsumerKey($value) {
        $this->wpDataStore->Set(self::$consumerKeyName,$value);
    }

    public function GetConsumerSecret() {
        return $this->wpDataStore->Get(self::$consumerSecret);
    }

    public function SetConsumerSecret($value) {
        $this->wpDataStore->Set(self::$consumerSecret,$value);
    }
}

