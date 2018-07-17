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
        $html = "";
        $html .= '<style id="wcn_style_sheet" type="text/css">';
        $html .= $this->globalStyle ;
        $html .= '</style>';
        echo $html;
    }

    public function GetDefaultStyle()
    {
        $html = "";
        $html .= ".wcn-notify 
        {
            
          border-radius:20px;
         }";

         $html .= ".wcn-notify .title
         {
            color: #ffffff;  
                         
          }";
          $html .= ".wcn-notify .message
          {
             color: #ffffff;  
                          
          }";
         return $html;
    }
}

