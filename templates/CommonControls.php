<?php

class CommonControls {
    public static function AddSelectBox($styleList,$selectedStyle)
    {?>
       <select class="layout-content">
       <option selected value=""></option>                    
       <option value="create-new">Create New</option>
           <?php
           foreach($styleList as $style){
               ?>
               <option <?php if($selectedStyle==$style->id) echo 'selected'; else "" ?> id="<?php echo $style->id; ?>" value="<?php echo $style->name; ?>"><?php echo $style->name; ?></option>
               <?php
               
               }
           ?>
       </select>
       <?php
    }

}