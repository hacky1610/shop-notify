<?php
class GeneralSettings {
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    private $datastore;
    
    function __construct($datastore){
        $this->datastore = $datastore;
    }
    
    function Show()
    {
        if (isset($_POST['submit']) && !empty($_POST['submit'])) 
        {
        }?>

        <h2>Woocommerce API</h2>
        <form method="post">
                    <?php submit_button(); ?>
        </form> <?php
     }
}

