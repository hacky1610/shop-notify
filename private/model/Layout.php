<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Layout {
    private $layout;
    private $title;
    private $message;
    private $pictureLink;
    private $id;

    function __construct($id = ""){
        $this->title = array();
        $this->message = array();
        $this->id = $id;
        $this->pictureLink = plugins_url( '/../../assets/preview_image.png', __FILE__ );
    }

    public function AddPicture($path)
    {
        if(!empty($path))
             $this->pictureLink = $path;
             

    }

    public function AddToTitle($element)
    {
        $element["attributes"]["wcn_class"] = ".title" . $element["attributes"]["wcn_class"];    
        $element["attributes"]["class"] = "title " . $element["attributes"]["class"];    

        array_push($this->title,$element);
    }
    

    public function AddToMessage($element)
    {
        $element["attributes"]["wcn_class"] = ".message" . $element["attributes"]["wcn_class"];    
        $element["attributes"]["class"] = "message " . $element["attributes"]["class"];    

        array_push($this->message,$element);
    }

    public function Render()
    {
        $this->layout = self::DefaultContent( $this->title,$this->message,$this->pictureLink,$this->id);
        return Layout::PrintElement($this->layout);
    }

    public static function PrintElement($element)
    {
        $type = $element['type'];
        if($type === "Text")
        {
           return $element['value'];
        }
        else
        {
            $attribs = "";
            if (isset($element['attributes']))
            {
                $attribs = self::GetAttributes($element['attributes'] );
            }
           $html = "<" . $type ." " . $attribs  . ">";
           if (isset($element['childs']))
           {
              foreach ($element['childs'] as &$child) {
                   $html .= self::PrintElement($child);
              }
           }
          }  
           $html .=   "</" . $type  . ">";
           return $html;
        
    }

    public static function CreateText($content)
    {
       return array
        (
            'type' => "p",
            'attributes' => array
            (
                'class'=>"text wcn-editable",
                'wcn_class' => '.text',
                'wcn_style_props' => "color,font-size,font-family"
            ),
            'childs' => $content
        );
    }

    public static function CreateLink($text,$dest = "#")
    {
       return array(
       'type' => "a",
       'attributes' => array
       (
           'href'=> $dest,
           'class' => "link wcn-editable",
           'wcn_class' => '.link',
           'wcn_style_props' => "color,font-size,font-family"
       ),
       'childs' => array(
        '0' => array(
            'type' => "Text",
            'value' => $text
        )
       )
        );
    }

    public static function CreateParagraph($text)
    {
       return  array(
        'type' => "Text",
        'value' => $text
        );
    }


    

    private static function GetAttributes($attributes)
    {
       $attr = "";
       foreach ($attributes as $key => $value) {
           $attr .= "{$key} = '{$value}' ";
       }

       return $attr;
    }

    public static function DefaultContent($title,$message,$pictureLink, $id){
		
        $default = array
        (
            
                'type' => "div",
                'attributes' => array
                (
                    'class'=>'col-xs-11 alert wcn-notify wcn-editable wcn_selected wcn-notify-orders',
                    'id' => $id,
                    'role'=> 'alert',
                    'data-notify' => "container",
                    'wcn_class' => '.wcn-notify',
                    'wcn_style_props' => "background-color,opacity,border-radius,width"

                ),
                'childs' => array
                (
                    '0'=>array
                    (
                        'type' => "div",
                        'attributes' => array
                        (
                            'class'=>'wcn-notify-icon'
                        ),
                        'childs' => array
                        (
                            '0'=>array
                            (
                                'type' => "span",
                                'attributes' => array
                                (
                                    'data-notify'=>"icon"
                                ),
                                'childs' => array
                                (
                                    '0'=>array
                                    (
                                        'type' => "img",
                                        'onlyAdmin' => true,
                                        'attributes' => array
                                        (
                                            'src'=> $pictureLink,
                                            'class' => "wcn-editable"
                                        ),
                                    )
                                )
                            )
                           
                        )
                    ),
                    '1'=>array
                    (
                        //Message Container Start
                        'type' => "div",
                        'attributes' => array
                        (
                            'class'=>'wcn-notify-message'
                        ),
                        'childs' => array
                        (
                            '0'=>array
                            (
                                //Title Start
                                'type' => "div",
                                'attributes' => array
                                (
                                    'class'=>"title-container",
                                ),
                                'childs' => $title
                                //Title End
                            ),
                            '1'=>array
                            (
                                //Message Start
                                'type' => "div",
                                'attributes' => array
                                (
                                    'class'=>"message",
                                ),
                                'childs' => $message
                                //Message End
                            ),
                        )
                        //Message Container End
                    ),
                    '2'=>array
                    (
                        'type' => "div",
                        'childs' => array
                        (
                            '0'=>array
                            (
                                'type' => "div",
                                'attributes' => array
                                (
                                    'class'=>"wcn-notify-close"
                                ),
                                'childs' => array
                                (
                                    '0'=>array
                                    (
                                        'type' => "button",
                                        'attributes' => array
                                        (
                                            'type'=>"button",
                                            "aria-hidden"=>"true",
                                            "class"=>"close wcn-editable",
                                            "data-notify"=>"dismiss"

                                        ),
                                        'childs' => array
                                        (
                                            '0'=>array
                                            (
                                                'type' => "Text",
                                                'value' => "x"
                                            )
                                        )
                                    )
                                )
                            )
                        )
                    )
                )
        );
			
		return $default;
		}	


}



