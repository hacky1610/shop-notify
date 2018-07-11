<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class CssLoader {
    public $globalStyle;
 

    function __construct($globalStyle){
        $this->globalStyle = $globalStyle;
    }

    public function Load()
    {
        $bg = $this->globalStyle->background;
        $html = "";
        $html .= '<style type="text/css">';
        $html .= ".wcn-notify {background-color:$bg}";
        $html .= '</style>';
        echo $html;
    }
}

