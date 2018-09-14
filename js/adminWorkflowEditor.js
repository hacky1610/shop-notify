
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

      // pass options to plugin constructor
      var s1 = new WfeElement(new Sleep());
      var n1 = new WfeElement(new Notify());
      var s2 = new WfeElement(new Sleep());
  
      $("#workingarea").append(s1.render()) 
      s1.addAfter(n1);
      n1.addAfter(s2);
  
});