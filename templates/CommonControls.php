<?php

class CommonControls {
    public static function AddSelectBox($id, $styleList,$selectedStyle,$showCreateNew = false)
    {?>
       <select class="layout-content" id="<?php echo $id; ?>" name="<?php echo $id; ?>">
       <?php if($showCreateNew) { ?>                  
       <option value="create-new">Create New</option>
           <?php
        }
           foreach($styleList as $style){
               ?>
               <option <?php if($selectedStyle==$style->id) echo 'selected'; else "" ?> id="<?php echo $style->id; ?>" value="<?php echo $style->id; ?>"><?php echo $style->name; ?></option>
               <?php
               
               }
           ?>
       </select>
       <?php
    }

    public static function AddEditControl($id,$value,$class,$labeltext,$isWcnControl = true)
    {
        if($isWcnControl)
            $editControl = "wcn-edit-control";
        ?>
            
        <div id="<?php echo $id."_container"; ?>">
            <label><?php echo $labeltext . ":"; ?></label>
            <input type="text" id="<?php echo $id; ?>" name="<?php echo $id; ?>" value="<?php echo $value;; ?>" class="<?php echo $editControl . " " . $class; ?>" >
            </br>
        </div>
        <?php
    }

}