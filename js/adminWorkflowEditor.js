
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


    $( ".sortable" ).sortable(({
        update: function( event, ui ) {renderAll();}
      }));
    $( ".sortable" ).disableSelection();

      // pass options to plugin constructor
      var s1 = new WfeElement(new Sleep());
      var n1 = new WfeElement(new Notify());
      var s2 = new WfeElement(new Sleep());
  
      $('.droparea').append(s1.content()) 
      s1.addAfter(n1);
      n1.addAfter(s2);

      items.push(s1);
      items.push(n1);
      items.push(s2);
      renderAll();

      $('.draggable').draggable({
        revert: "invalid",
        stack: ".draggable",
        helper: 'clone'
      });

     
  
});