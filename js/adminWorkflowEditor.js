class AdmninWorkflowEditor {
  constructor() {
    this.items = [];
    $( '.sortable' ).sortable(({
      update: function( event, ui ) {
        renderAll();
      },
    }));
    $( '.sortable' ).disableSelection();

    // pass options to plugin constructor
    const s1 = new Sleep();
    s1.selected(this.elementSelected);

    this.addElement(s1);
    $('.droparea').append(s1.getContent);

    this.renderAll();

    $('.draggable').draggable({
      revert: 'invalid',
      stack: '.draggable',
      helper: 'clone',
      cursor: 'move',
      start: function(event, ui) {
        $(this).draggable('instance').offset.click = {
        left: Math.floor(ui.helper.width() / 2),
        top: Math.floor(ui.helper.height() / 2),
        };
      },
    });
  };


  renderAll() {
    this.items.forEach(function(element) {
      element.render();
    });
  }

  addElement(el, render = false) {
    this.items.push(el);
    el.elementAdded(this.addElement.bind(this));
    if (render == true) {
      this.renderAll();
    }
  }

  elementSelected(o) {
    $('#editorarea').empty();
    $('#editorarea').append(o.editor.getContent);
  };
}

jQuery(document).ready(function($) {
  new AdmninWorkflowEditor();
});