<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Style {
    public $id;
    public $name;
    public $content;
    public $deletable;
    public $originalContent;

    function __construct($id,$name,$content,$deletable = true ){
        $this->id = $id;
        $this->name = $name;
        $this->content = $content;
        $this->originalContent = $content;
        $this->deletable = $deletable;
    }
   
    public static function GetDefaultStyles()
    {
        $default = array
        (
                new Style("modern","Modern",".wcn-notify { border-radius: 20px; background-color: rgb(211, 145, 31); opacity: 0.63; }.wcn-notify .title { color: rgb(10, 10, 10); font-size: 16px; font-family: \"\\\"Annie Use Your Telescope\\\"\"; }.wcn-notify .message { color: rgb(255, 255, 255); }" ),
                new Style("classic","Classic", ".wcn-notify { border-radius: 50px; background-color: rgb(21, 145, 31); opacity: 1; }.wcn-notify .title { color: rgb(10, 10, 10); font-size: 16px; font-family: \"\\\"Annie Use Your Telescope\\\"\"; }.wcn-notify .message { color: rgb(255, 255, 255); }")
        );
        return $default;
    }
 
}

