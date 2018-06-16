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
              $this->datastore->SetConsumerKey($_POST['ConsumerKey']);
              $this->datastore->SetConsumerSecret($_POST['ConsumerSecret']);
        }?>

        <h2>Woocommerce API</h2>
        <form method="post">
                    Consumer Key: <input type="text" name="ConsumerKey" id="ConsumerKey" value="<?php echo $this->datastore->GetConsumerKey();?>"><br>
                    Consumer Secret: <input type="text" name="ConsumerSecret" id="ConsumerSecret"  value="<?php echo $this->datastore->GetConsumerSecret();?>"><br>
                    <?php submit_button(); ?>
        </form> <?php
     }
}

