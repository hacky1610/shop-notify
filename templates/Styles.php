<?php

include_once dirname( __FILE__ ) . '/../private/OrderNotice.php';

class Styles {
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    private $datastore;
    private $orderList;
    
    function __construct($datastore){
        $this->datastore = $datastore;
        $this->orderList = $this->datastore->GetShowOrderList();
    }
    
    function Show()
    {
        $o = (object)$this->orderList[0];
        if (isset($_POST['submit']) && !empty($_POST['submit'])) 
        {
                $o->background = $_POST['background'];
                $this->datastore->SetShowOrderList($this->orderList);
        }
        
        ?>

        <h2>Style</h2>
        <form method="post">
                    <input type="text" name="background" id="background" value="<?php echo $o->background;?>" class="wcn-color-picker" >
        
                    <?php submit_button(); ?>
        </form> <?php
     }
}

