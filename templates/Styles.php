<?php

include_once dirname( __FILE__ ) . '/../private/model/Layout.php';
include_once dirname( __FILE__ ) . '/../private/logger.php';
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
    private $source = null;
    /**
     * Action argument used by the nonce validating the AJAX request.
     *
     * @var Logger
     */
    private $logger;
    
    function __construct($datastore,$logger){
        $this->datastore = $datastore;
        $this->logger = $logger;
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
        $this->logger->Call("Show StyleEditor");
        if (isset($_POST['submit']) && !empty($_POST['submit'])) 
        {
            //$this->datastore->SetGlobalStyle($this->globalStyle);
        }

        if(!empty($_GET['style'])){
            $this->selectedStyle = sanitize_text_field($_GET['style']); 
        }

        if(!empty($_GET['source'])){
            $this->source = $_GET['source']; 
        }

         $styleList  = $this->datastore->GetStyleList();
         $currentStyle = Style::GetStyle($styleList,$this->selectedStyle);
         $this->logger->Info("Selected Style: $($this->selectedStyle)");
         $this->logger->Info("Style content");
         $this->logger->Info($currentStyle);
         $cssLoader = new CssLoader();
         $cssLoader->AddStyle($currentStyle);
         $cssLoader->Load();

    
        ?>

        <h2>Style</h2>
        
        <div class="sn_style_editor">
        <form method="post">
        <?php
                    CommonControls::AddSelectBox("wcn_select-style",$styleList,$this->selectedStyle,"Style",true);
                    ?>
                    <div class="wcn_edit_section">
                    <?php

                    CommonControls::AddEditControl("wcn_background-color","","wcn-color-picker","Background color");
                    CommonControls::AddEditControl("wcn_border-radius","","wcn_mask","Border radius");
                    CommonControls::AddEditControl("wcn_width","","wcn_mask","Width");
                    CommonControls::AddEditControl("wcn_color","","wcn-color-picker","Color");
                    CommonControls::AddEditControl("wcn_font-size","","wcn_mask","Font Size");
                    CommonControls::AddEditControl("wcn_font-family","","wcn_font_select","Font family", false);
                    $this->AddSlider("wcn_opacity","","","Opacity");
                    $this->JsCode();
                    ?>
                    </div>
                    <input  class="button" id="style-editor-save-button" value="Save" />

                    <?php //submit_button(); ?>
                    
        </form> <?php

        $layout = new Layout("",$this->selectedStyle);

        $title = array(
            Layout::CreateParagraph("A title"),
            Layout::CreateLink("with link")
        );

        $message = array(
            Layout::CreateParagraph("A message"),
            Layout::CreateLink("with link")
        );
        
        $layout->AddToTitle(Layout::CreateText($title));
        $layout->AddToMessage(Layout::CreateText($message));
        
        echo $layout->Render();
        ?>
        </div> 
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

    

}

