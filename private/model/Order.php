<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Order {
    public $id;
    public $name;
    public $items;
    public $country;
    public $dateCreated;

    function __construct($id, $name,$country,$dateCreated, $items){
        $this->id = $id;
        $this->name = $name;
        $this->items = $items;
        $this->country = $country;
        $this->dateCreated = $dateCreated;

    }


}

