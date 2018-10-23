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

    this.load();

    $('#saveButton').click(this.save.bind(this));
  };


  renderAll() {
    this.items.forEach(function(element) {
      element.render();
    });
  }

  addElement(el, render = false) {
    this.items.push(el);
    el.selected(this.elementSelected);
    el.elementAdded(this.elementAdded.bind(this));
    if (render == true) {
      this.renderAll();
    }
  }

  elementSelected(o) {
    $('#editorarea').empty();
    $('#editorarea').append(o.editor.getContent);
  };

  elementAdded(event, ui) {
    const draggable = ui.draggable;
    let newElement = null;

    const type = $(ui.draggable).attr('type');
    if (type === 'sleep') {
      newElement = new Sleep();
    } else if (type === 'notify') {
      const id = $(ui.draggable).attr('notify-id');
      newElement = new Notify(id);
    }

    $(event.target).after(newElement.getContent);
    this.addElement(newElement, true);
    draggable.css({
      float: 'left',
    });
  }

  save() {
    const data = [];
    this.items.forEach(function(e) {
      data.push(e.getData);
    });
    const d = {
      'action': 'wcn_save_workflow',
      'workflow_content': JSON.stringify(data),
    };
    SendAjaxSync(d).then((res) => {
      CheckResponse(res, jumpToSource);
    });
  };

  load() {
    const d = {
      'action': 'wcn_get_workflow',
    };
    SendAjaxSync(d).then((res) => {
      const c = JSON.parse(res.replace(/\\/g, ''));
      console.log(c);
      
    });
  }


}

jQuery(document).ready(function($) {
  new AdmninWorkflowEditor();
});
