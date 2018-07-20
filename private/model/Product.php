<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Product {
    public $id;
    public $name;

    function __construct($id, $name,$productPermalink,$productImage){
        $this->id = $id;
        $this->name = $name;
        $this->productImage = $productImage;
        $this->productPermalink = $productPermalink;
    }
}

