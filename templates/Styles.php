<?php

include_once dirname( __FILE__ ) . '/../private/model/Layout.php';
include_once dirname( __FILE__ ) . '/../private/CssLoader.php';
include_once dirname( __FILE__ ) . '/CommonControls.php';

class Styles {
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    private $datastore;
    private $selectedStyle = "modern";
    
    function __construct($datastore){
        $this->datastore = $datastore;
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
         $currentStyle = Style::GetStyle($styleList,$this->selectedStyle);
         $cssLoader = new CssLoader($currentStyle->content);
         $cssLoader->Load();

    
        ?>

        <h2>Style</h2>
        <form method="post">


                    <div class="wcn_edit_section">
                    <?php
                    CommonControls::AddSelectBox("wcn_select-style",$styleList,$this->selectedStyle,true);
                    CommonControls::AddEditControl("wcn_background-color","","wcn-color-picker","Background color");
                    CommonControls::AddEditControl("wcn_border-radius","","wcn_mask","Border radius");
                    CommonControls::AddEditControl("wcn_color","","wcn-color-picker","Color");
                    CommonControls::AddEditControl("wcn_font-size","","wcn_mask","Font Size");
                    CommonControls::AddEditControl("wcn_font-family","","wcn_font_select","Font family", false);
                    $this->AddSlider("wcn_opacity","","","Opacity");
                    $this->JsCode();
                    ?>
                    </div>
                    <input  class="button" id="submit_btn" value="Send" />


                    <?php //submit_button(); ?>
        </form> <?php

        $layout = new Layout();
        $layout->AddToTitle(Layout::CreateText("Title "));
        $layout->AddToTitle(Layout::CreateLink("with Link"));
        $layout->AddToMessage(Layout::CreateText("Message "));
        $layout->AddToMessage(Layout::CreateLink("with Link"));
        
        echo $layout->Render();
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

    

}

