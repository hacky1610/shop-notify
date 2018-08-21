<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class CssLoader {
    public $styles = array();
    static $url = "https://fonts.googleapis.com/css?family=";
 

    function __construct(){

    }

    public function AddStyle($stlye)
    {
        array_push($this->styles,$stlye);
    }

 
    public function Load()
    {
        foreach ($this->styles as &$style) {
            self::PrintStyle($style);
        }
        self::PrintFontLinks(self::GetFonts($this->styles));
    }

    public static function GetFonts($styles)
    {
        $fonts = array();
        foreach($styles as &$style){
            foreach($style->GetFontList() as &$font){
                if(!in_array($font, $fonts)){
                    array_push($fonts,$font);
                }
            }
        }
        return $fonts;
    }

    private static function PrintFontLinks($fonts)
    {
        foreach ($fonts as &$font) {
            self::PrintFontLink($font);
        }
    }

    private static function PrintFontLink($font)
    {
        $fontUrl = self::$url . $font;
        echo '<link href="' . $fontUrl .'" rel="stylesheet" type="text/css">';
    }

    private static function PrintStyle($stlye)
    {
        $html = "";
        $html .= '<style id="wcn_style_sheet" type="text/css">';
        $html .= $stlye->content ;
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

