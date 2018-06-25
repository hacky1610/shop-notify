<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function GetTab($id, $name,$active_tab)
{?>
    <a href="?page=Woocommerce_Notice&amp;tab=<?php echo $id; ?>" class="nav-tab <?php echo $active_tab == $id ? 'nav-tab-active' : ''; ?>"><?php echo $name; ?></a>
<?php
}
