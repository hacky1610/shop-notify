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
    private $wpAdapter;
    
    function __construct($datastore,$logger,$wpAdapter){
        $this->datastore = $datastore;
        $this->logger = $logger;
        $this->wpAdapter = $wpAdapter;
      
    }

    function AddSlider($id,$value,$class,$labeltext)
    {
        ?>
        <div class="sn_edit_container" id="<?php echo $id."_container"; ?>">
            <label><?php echo $labeltext; ?></label>
            <input type="range" id="<?php echo $id;; ?>"  min="0" max="1" step="0.01" value="<?php echo $value; ?>" class="<?php echo "custom-range wcn-edit-control " . $class; ?>">
            </br>
       </div>
        <?php

    }

    function ShowEditSection($styleList, $electedStyle) {
      ?>
      <form method="post">
      <?php
          CommonControls::AddSelectBox("wcn_select-style",$styleList,$electedStyle,"Style",true);
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
          ?>
          </div>
          <input  class="button" id="style-editor-save-button" value="Save" />

          <?php //submit_button(); ?>
                  
      </form> 
      <?php
    }

    function ShowPreviewSection($selectedStyle) {
      $layout = new Layout("",$selectedStyle);
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
    }

    function Show()
    {
        $this->logger->Call("Show StyleEditor");
        $this->wpAdapter->EnqueueStyle( 'workflow-editor',  'css/adminStyleEditor.css?');
        $this->wpAdapter->RegisterScript( 'style-editor-script',  'js/adminStyleEditor.js?');
        $this->wpAdapter->LocalizeScript('style-editor-script', 'style_editor_vars', array(
            'editor_url' => admin_url().'edit.php?post_type=shop-notify&page=sn_style_editor&style='
        ));
        $this->wpAdapter->EnqueueRegisteredScript( 'style-editor-script' );


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
        <div class="sn_container">
          <div class="panel panel-default section sn_panel edit">
            <div class="panel-heading">Edit</div>
            <div class="panel-body">
              <div class="sn_style_editor">
                <?php
                  $this->ShowEditSection($styleList, $this->selectedStyle); 
                ?>
              </div>
             </div>
           </div>
          <div class="panel panel-default section sn_panel preview">
            <div class="panel-heading">Preview</div>
              <div class="panel-body">
              <?php
              $this->ShowPreviewSection($this->selectedStyle); 
              ?>
            </div>
          </div>
         
         </div> 
        <?php
     }
}

