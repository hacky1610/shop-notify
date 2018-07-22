<?php

include_once dirname( __FILE__ ) . '/../private/Style.php';
include_once dirname( __FILE__ ) . '/../private/CssLoader.php';


class Styles {
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    private $datastore;
    private $layout;
    private $selectedStyle = "modern";
    
    function __construct($datastore){
        $this->datastore = $datastore;
        $this->layout = $this->DefaultContent();
    }

    function AddEditControl($id,$value,$class,$labeltext,$isWcnControl = true)
    {
        if($isWcnControl)
            $editControl = "wcn-edit-control";
        ?>
            
        <div id="<?php echo $id."_container"; ?>">
            <label><?php echo $labeltext . ":"; ?></label>
            <input type="text" id="<?php echo $id;; ?>" value="<?php echo $value;; ?>" class="<?php echo $editControl . " " . $class; ?>" >
            </br>
        </div>
        <?php
    }

    function AddSlider($id,$value,$class,$labeltext)
    {
        ?>
        <div id="<?php echo $id."_container"; ?>">
            <label><?php echo $labeltext; ?></label>
            <input type="range" id="<?php echo $id;; ?>"  min="0" max="1" step="0.01" value="<?php echo $value; ?>" class="<?php echo "slider wcn-edit-control " . $class; ?>">
            </br>
       </div>
        <?php

    }

    function Show()
    {
        if (isset($_POST['submit']) && !empty($_POST['submit'])) 
        {
            //$this->datastore->SetGlobalStyle($this->globalStyle);
        }

        if(!empty($_GET['style'])){
            $this->selectedStyle = sanitize_text_field($_GET['style']); 
        }

         $styleList  = $this->datastore->GetStyleList();
         $currentStyle = self::GetStyle($styleList,$this->selectedStyle);
         $cssLoader = new CssLoader($currentStyle->content);
         $cssLoader->Load();

    
        ?>

        <h2>Style</h2>
        <form method="post">


                    <div class="wcn_edit_section">
                    <?php
                    $this->AddSelectBox($styleList);
                    $this->AddEditControl("wcn_background-color","","wcn-color-picker","Background color");
                    $this->AddEditControl("wcn_border-radius","","wcn_mask","Border radius");
                    $this->AddEditControl("wcn_color","","wcn-color-picker","Color");
                    $this->AddEditControl("wcn_font-size","","wcn_mask","Font Size");
                    $this->AddEditControl("wcn_font-family","","wcn_font_select","Font family", false);
                    $this->AddSlider("wcn_opacity","","","Opacity");
                    $this->JsCode();
                    ?>
                    </div>
                    <input  class="button" id="submit_btn" value="Send" />


                    <?php //submit_button(); ?>
        </form> <?php

        echo $this->PrintElement($this->layout[0]);
     }

     private static function GetStyle($styleList,$id)
     {
         foreach($styleList as &$style)
         {
             if($style->id == $id)
                return $style;
         }
         return null;
     }

     private function PrintElement($element)
     {
         $type = $element['type'];
         if($type === "Text")
         {
            return $element['value'];
         }
         else
         {
            $attribs = $this->GetAttributes($element['attributes'] );
            $html = "<" . $type ." " . $attribs  . ">";
            foreach ($element['childs'] as &$child) {
                $html .= $this->PrintElement($child);
           }  
            $html .=   "</" . $type  . ">";
            return $html;
         }
        
     }


     private function GetAttributes($attributes)
     {
        $attr = "";
        foreach ($attributes as $key => $value) {
            $attr .= "{$key} = '{$value}' ";
        }

        return $attr;
     }

     private function AddSelectBox($styleList)
     {?>
        <select class="layout-content">
        <option selected value=""></option>                    
        <option value="create-new">Create New</option>
            <?php

            
           // $layout_content_list = $class_post_grid_functions->layout_content_list();
            foreach($styleList as $style){
                ?>
                <option <?php if($this->selectedStyle==$style->id) echo 'selected'; else "" ?> id="<?php echo $style->id; ?>" value="<?php echo $style->name; ?>"><?php echo $style->name; ?></option>
                <?php
                
                }
            ?>
        </select>
        <?php
     }

     private function JsCode()
     {?>
        <script>
        jQuery(document).ready(function($)
            {

                
                $(document).on('change', '.layout-content', function()
                    {
        
                        var style = $(this).children(":selected").attr("id");
                        
                        if(style=='create-new'){
                            
                            style = prompt('(Must be unique) Layout name ?');
                            
                            //layout = $.now();
                            
                            if(style!=null){
                                window.location.href = "<?php echo admin_url().'edit.php?post_type=post_grid&page=post_grid_layout_editor&layout_content=';?>"+style;
                                }
                            }
                        else{
                            window.location.href = "<?php echo admin_url().'edit.php?post_type=shop-notify&page=sn_style_editor&style=';?>"+style;
                            }
                    })
                
                })
        </script>
        <?php
     }

     private function DefaultContent(){
		
        $default = array
        (
            '0'=>array
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
                                'childs' => array
                                (
                                    '0'=>array
                                    (
                                        'type' => "Text",
                                        'value' => "Titel"
                                    )
                                )
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
                                'childs' => array
                                (
                                    '0'=>array
                                    (
                                        'type' => "Text",
                                        'value' => "Message"
                                    )
                                )
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
            )
        );
			
		return $default;
		}	

}

