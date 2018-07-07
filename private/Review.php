<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Review {
    public $id;
    public $name;
    public $rating;
    public $time;
    public $productName;
    public $productImage;
    public $productPermalink;

    function __construct($id, $name, $rating, $time, $productName, $productImage, $productPermalink){
        $this->id = $id;
        $this->name = $name;
        $this->rating = $rating;
        $this->time = $time;
        $this->productName = $productName;
        $this->productImage = $productImage;
        $this->productPermalink = $productPermalink;

    }
}

