<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class OrderNotice {
    public $id;
    public $name;
    public $background;

    function __construct($id, $name){
        $this->id = $id;
        $this->name = $name;
    }
}

