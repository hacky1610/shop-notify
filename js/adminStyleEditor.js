 
jQuery(document).ready(function($) {
    var changed = false;

    $(document).on('change', '.layout-content', function()
    {

        var style = $(this).children(":selected").attr("id");
        
        if(style=='create-new'){
            
            style = prompt('(Must be unique) Layout name ?');
            
            //layout = $.now();
            
            if(style!=null){
                window.location.href = "<?php echo admin_url().'edit.php?post_type=post_grid&page=post_grid_layout_editor&layout_content=';?>"+style;
                }
            }
        else{
            window.location.href =  style_editor_vars.editor_url + style;
            }
    });

  
});