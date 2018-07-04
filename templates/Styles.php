<?php
class Styles {
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    private $datastore;
    
    function __construct($datastore){
        echo "Cons";
        $this->datastore = $datastore;
    }
    
    function Show()
    {
        if (isset($_POST['submit']) && !empty($_POST['submit'])) 
        {
              echo "Save";
        }?>

        <h2>Style</h2>

        <input type="text" name="cpa_settings_options[background]" value="Color" class="cpa-color-picker" >
     
        <?php
     }
}

