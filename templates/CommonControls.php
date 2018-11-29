<?php

class CommonControls {
    public static function Addbutton($id,$image,$link,$class)
    {?>
       <div class="<?php echo $class;?>"> <img src="<?php echo $image;?>"></img></div>
       <?php
    }

    public static function AddSelectBox($id, $styleList,$selectedStyle,$labeltext,$showCreateNew = false)
    {?>
    
    <div class="select-box-container">
        <label><?php echo $labeltext . ":"; ?></label>
        <select class="form-control layout-content" id="<?php echo $id; ?>" name="<?php echo $id; ?>">
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
       </div>
       <?php
    }

    public static function AddEditControl($id,$value,$class,$labeltext,$isWcnControl = true, $additionalAttribute = "")
    {
        if($isWcnControl)
            $editControl = "wcn-edit-control";
        ?>
        <div class="edit-control-container" id="<?php echo $id."_container"; ?>">
            <label><?php echo $labeltext . ":"; ?></label>
            <input type="text" id="<?php echo $id; ?>" name="<?php echo $id; ?>" value="<?php echo $value;; ?>" class="<?php echo $editControl . " " . $class; ?>" <?php echo $additionalAttribute; ?>>
            </br>
        </div>
        <?php
    }
    
}