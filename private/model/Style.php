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

    public function GetFontList()
    {
        preg_match_all('/font-family:\W*([^;]+)/', $this->content, $matches);
        if(isset($matches))
        {
            return $matches[1];
        }
        return array();
    }
   
    public static function GetDefaultStyles()
    {
        $default = array
        (
                new Style("modern",
                            "Modern",
                            ".wcn-notify.modern 
                            { 
                                    border-radius: 20px; 
                                    background-color: rgb(211, 145, 31); 
                                    opacity: 0.63; 
                            }
                            .wcn-notify.modern .title.text 
                            { 
                                color: rgb(10, 10, 10); 
                                font-size: 16px; 
                                font-family: \"Annie Use Your Telescope\"; 
                            } 
                            .wcn-notify.modern .title.link 
                            { 
                                color: rgb(255, 255, 255); 
                            } 
                            .wcn-notify.modern .message.text 
                            {  
                                color: rgb(255, 255, 255); 
                            } 
                            .wcn-notify.modern .message.link{ 
                                color: rgb(255, 255, 255); 
                            }" ),
                new Style("classic",
                            "Classic",
                            ".wcn-notify.classic 
                            { 
                                border-radius: 50px; 
                                background-color: rgb(21, 145, 31); 
                                opacity: 1; 
                            }
                            .wcn-notify.classic .title.text 
                            { 
                                color: rgb(10, 10, 10); 
                                font-size: 16px; 
                                font-family: \"Annie Use Your Telescope\"; 
                            } .wcn-notify.classic .title.link 
                            { 
                                color: rgb(255, 255, 255);
                             } 
                             .wcn-notify.classic .message.text 
                             { 
                                 color: rgb(255, 255, 255); 
                            } 
                            .wcn-notify.classic .message.link 
                            { 
                                color: rgb(255, 255, 255); 
                            }")
        );
        
        return $default;
    }

    public function GetId()
    {
        return $this->id;
    }
    
    public static function GetStyle($styleList,$id)
    {
        if(isset($styleList))
        {
            foreach($styleList as &$style)
            {
                if($style->GetId() == $id)
                return $style;
            }
        }
        return null;
    }

    public static function SaveStyle($styleList,$id, $content)
    {
        if(isset($styleList))
        {
            foreach($styleList as &$style)
            {
                if($style->id == $id)
                    $style->content = $content;
            }
        }
    }
 
}

