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

    function __construct(){
        $this->title = array();
        $this->message = array();
    }

    public function AddToTitle($element)
    {
        array_push($this->title,$element);
    }

    public function AddToMessage($element)
    {
        array_push($this->message,$element);
    }

    public function Render()
    {
        $this->layout = self::DefaultContent( $this->title,$this->message);
        echo Layout::PrintElement($this->layout);
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

    public static function CreateMessage($text)
    {
       return array
        (
            'type' => "Text",
            'value' => $text
        );
    }

    public static function CreateLink($text,$dest = "")
    {
       return array(
       'type' => "a",
       'attributes' => array
       (
           'src'=> $dest
       ),
       'childs' => array(self::CreateMessage($text)));
    }

    private static function GetAttributes($attributes)
    {
       $attr = "";
       foreach ($attributes as $key => $value) {
           $attr .= "{$key} = '{$value}' ";
       }

       return $attr;
    }

    public static function DefaultContent($title,$message){
		
        $default = array
        (
                'type' => "div",
                'attributes' => array
                (
                    'class'=>'col-xs-11 col-sm-3 alert wcn-notify wcn-editable wcn-notify-orders',
                    'role'=> 'alert',
                    'data-notify' => "container",
                    'wcn_class' => '.wcn-notify',
                    'wcn_style_props' => "background-color,opacity,border-radius"

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
                                            'src'=>"http://sharonne-design.com/wp-content/uploads/2017/11/Black-Ella-Ohrringe.jpg",
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
                                'type' => "a",
                                'attributes' => array
                                (
                                    'class'=>"title link wcn-editable",
                                    'wcn_class' => '.title',
                                    'wcn_style_props' => "color,font-size,font-family"
                                    
                                ),
                                'childs' => $title
                                //Title End
                            ),
                            '1'=>array
                            (
                                //Message Start
                                'type' => "span",
                                'attributes' => array
                                (
                                    'class'=>"message wcn-editable",
                                    'wcn_class' => '.message',
                                    'wcn_style_props' => "color,font-size,font-family"
                                ),
                                'childs' => $message /*array
                                (
                                    '0'=>array
                                    (
                                        'type' => "Text",
                                        'value' => "Message"
                                    ),
                                    '1' => array(
                                         //Message Start
                                        'type' => "a",
                                        'childs' => array(
                                            '0'=>array
                                            (
                                                'type' => "Text",
                                                'value' => "Link"
                                            ),
                                        )
                                       
                                    )
                                )*/
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



