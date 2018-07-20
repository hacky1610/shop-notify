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
    static $globalStyle = "wcn_globalStyle";

    private $wpDataStore;

    function __construct($wpDataStore){
        $this->wpDataStore = $wpDataStore;
    }

    public function GetShowOrderList() {
        return $this->wpDataStore->Get(self::$showOrderList);
    }

    public function SetShowOrderList($value) {
        $this->wpDataStore->Set(self::$showOrderList,$value);
    }

    public function GetGlobalStyle() {
        return $this->wpDataStore->Get(self::$globalStyle);
    }

    public function SetGlobalStyle($value) {
        $this->wpDataStore->Set(self::$globalStyle,$value);
    }


}

