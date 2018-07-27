<?php

include_once dirname( __FILE__ ) . '/../private/model/Layout.php';
include_once dirname( __FILE__ ) . '/../private/CssLoader.php';
include_once dirname( __FILE__ ) . '/CommonControls.php';



class NotifySettings {
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    private $datastore;
    private $selectedStyle; 
    
    function __construct($datastore){
        $this->datastore = $datastore;
    }


    public function Show($post)
    {
        echo "Show";
        $styleList  = $this->datastore->GetStyleList();
        $currentStyle = Style::GetStyle($styleList,$this->selectedStyle);

        CommonControls::AddSelectBox($styleList,$this->selectedStyle);


        $cssLoader = new CssLoader($currentStyle->content);
        $cssLoader->Load();


        $layout = new Layout();
        $layout->AddToTitle(Layout::CreateMessage("Title"));
        $layout->AddToMessage(Layout::CreateMessage("Message"));
        $layout->Render();

        print_r("Show live preview");
        print_r("Text editor");
        print_r("Type");
        print_r("Display Time");
        print_r("Effects");

        $this->JsCode();
    }

    public function Save( $post_id, $post, $update)
    {
        $this->logger->Call("Save");

        /*
         * In production code, $slug should be set only once in the plugin,
         * preferably as a class property, rather than in each function that needs it.
         */
        $post_type = get_post_type($post_id);
        $this->logger->Info("Post Type: $post_type");
        // If this isn't a 'book' post, don't update it.
        if ( "shop-notify" != $post_type ) return;
    
        // - Update the post's metadata.
        $this->logger->Info("Update Post Meta");
    
        update_post_meta( $post_id, 'selected_style', "hello" );
    }

    private function JsCode()
    {?>
       <script>
       jQuery(document).ready(function($)
           {

               
               $(document).on('change', '.layout-content', function()
                   {
       
                       var style = $(this).children(":selected").attr("id");
                       
                       var data = {
                        'action': 'wcn_get_style',
                        'style_id': style
                        };
                        SendAjaxSync(data).then((s) =>
                        {
                            $("#wcn_style_sheet").html(s);
                        });
                   })
               
               })
       </script>
       <?php
    }
 
  





   
    

}

