
 function allowDrop(ev) {
    ev.preventDefault();
}

function drag(ev,o) {
    ev.dataTransfer.setData(o);
}

function drop(ev) {
    setTimeout(ShowPreviewPopup, 100)
}

function selectionCanged(element)
{
    $("#editorarea").append(element.getEditor());
}


jQuery(document).ready(function($) {
    var changed = false;
    var items = [];

    var renderAll = function()
    {
        items.forEach(function(element) {
            element.render();
        });
    }

    var addElement = function(el, render = false)
    {
        items.push(el);
        el.elementAdded(addElement);
        if(render == true)
            renderAll();
    }

    var elementSelected = function(o)
    {
        $("#editorarea").append(o.editor());
    };

    $( ".sortable" ).sortable(({
        update: function( event, ui ) {renderAll();}
      }));
    $( ".sortable" ).disableSelection();

      // pass options to plugin constructor
      var s1 = new WfeElement(new Sleep());
      s1.selected(elementSelected);

      addElement(s1);
      
      $('.droparea').append(s1.content()) 

      renderAll();

      $('.draggable').draggable({
        revert: "invalid",
        stack: ".draggable",
        helper: 'clone',
        cursor: "move",
        
        start: function(event, ui){
            $(this).draggable('instance').offset.click = {
            left: Math.floor(ui.helper.width() / 2),
            top: Math.floor(ui.helper.height() / 2)
        };} 
      });

     
  
});