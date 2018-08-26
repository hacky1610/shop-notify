
 function OpenStyleEditor()
 {
     var style = $("#sn_style_content").val();
     var url = notify_editor_vars.editor_url + "&style=" + style;
     window.open(url,'_self');
 }

 function ShowPreviewPopup()
{
    var id = "sn_admin_sample";
    var keyVals = {ProductName: "T-Shirt", GivenName: "Valérie"};
    ShowNotify(id,keyVals,$("#sn_title_content").val(),$("#sn_message_content").val(),"#","","#wpbody-content","static");
}
 
jQuery(document).ready(function($) {
    var changed = false;
  
    function allowDrop(ev) {
        ev.preventDefault();
    }
    
    function drag(ev) {
        ev.dataTransfer.setData("text", " " + ev.target.id + " ");
    }
    
    function drop(ev) {
        setTimeout(ShowPreviewPopup, 100)
    }

    function textBoxCanged()
    {
        changed = true;
    }

   

    function editButtonClicked()
    {
        if(changed)
        {
            $('#saveModal').modal('show');
            return;
        }
        OpenStyleEditor();
    }

    function loadNewStyle()
    {
        changed = true;
        var style = $(this).children(":selected").attr("id");
            
        var data = {
         'action': 'wcn_get_style',
         'style_id': style
         };
         SendAjaxSync(data).then((s) =>
         {
             $("#wcn_style_sheet").html(s);
         });
    }

    $(".sn-edit-button").on("click",editButtonClicked);
    $(".layout-content").on("click",loadNewStyle);
    $(".edit-control-container input").on("change",textBoxCanged);

    ShowPreviewPopup();

  
});