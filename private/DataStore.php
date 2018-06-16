<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Datastore {
    static $consumerKeyName = "wcn_consumerKey";
    static $consumerSecret = "wcn_consumerSecret";

    public function GetConsumerKey() {
        return get_option(self::$consumerKeyName);
    }

    public function SetConsumerKey($value) {
       update_option(self::$consumerKeyName,$value);
    }

    public function GetConsumerSecret() {
        return get_option(self::$consumerSecret);
    }

    public function SetConsumerSecret($value) {
        update_option(self::$consumerSecret,$value);
    }
}

