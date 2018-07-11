<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Datastore {
    static $consumerKeyName = "wcn_consumerKey";
    static $consumerSecret = "wcn_consumerSecret";
    static $showOrderList = "wcn_showOrderList";
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

    public function GetShowOrderList() {
        return $this->wpDataStore->Get(self::$showOrderList);
    }

    public function SetShowOrderList($value) {
        $this->wpDataStore->Set(self::$showOrderList,$value);
    }
}

