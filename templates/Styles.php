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
    private $globalStyle;
    
    function __construct($datastore){
        $this->datastore = $datastore;
        $this->globalStyle = $this->datastore->GetGlobalStyle();

        if(!isset($this->globalStyle))
        {
            $this->globalStyle = new Style();
            $this->datastore->SetGlobalStyle($this->globalStyle);
        }
        else
        {
            $this->globalStyle = (object)$this->globalStyle;
        }
       
    }
    
    function Show()
    {

       

        if (isset($_POST['submit']) && !empty($_POST['submit'])) 
        {
                $this->globalStyle->background = $_POST['background'];
                $this->datastore->SetGlobalStyle($this->globalStyle);
        }

        $cssloader = new CssLoader($this->globalStyle);
        $cssloader->Load();
        
        ?>

        <h2>Style</h2>
        <form method="post">
                    <input type="text" name="background" id="background" value="<?php echo $this->globalStyle->background;?>" class="wcn-color-picker" >
        
                    <div id="sampleContainer"></div>


                    <?php submit_button(); ?>
        </form> <?php
     }
}

