class AdmninWorkflowEditor {
  constructor() {
    this.items = [];
    $( '.sortable' ).sortable(({
      update: this.update.bind(this),
    }));
    $( '.sortable' ).disableSelection();
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

    this.load(this.loadElements.bind(this));

    $('#saveButton').click(this.save.bind(this));
  };

  update(event, ui) {
    this.renderAll();
  }

  loadElements(res) {
    const elements = JSON.parse(res.replace(/\\/g, ''));
    let before = null;

    const first = new WfeEntryElement();

    first.elementAdded(this.elementAdded.bind(this));
    first.initEvents();
    $('.droparea').append(first.getContent);


    elements.forEach(function(o) {
      let e = undefined;
      if (o.type === 'Sleep') {
        e = new Sleep();
      } else if (o.type === 'Notify') {
        e = new Notify();
      }
      e.setData(o.data);
      this.addElement(e);
      if (before === null) {
        $(first.getContent).after(e.getContent);
      } else {
        before = e.getContent;
        $(before).after(e.getContent);
      }
    }.bind(this));

    this.renderAll();
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
    el.deleteEvent(this.elementDeleted.bind(this));
    if (render == true) {
      this.renderAll();
    }
  }

  elementSelected(o) {
    $('#editorarea').empty();
    $('#editorarea').append(o.editor.getContent);
  };

  elementDeleted(element) {
    const index = this.items.indexOf(element);
    this.items.splice(index, 1);
    this.renderAll();
  }

  elementAdded(event, ui) {
    const draggable = ui.draggable;
    let newElement = null;

    const type = $(ui.draggable).attr('type');
    if (type === 'sleep') {
      newElement = new Sleep();
    } else if (type === 'notify') {
      const id = $(ui.draggable).attr('notify-id');
      newElement = new Notify(id);
    } else if (type === 'condition') {
      newElement = new Condition();
    }

    $(event.target).parent().after(newElement.getContent);
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

  load(callback) {
    const d = {
      'action': 'wcn_get_workflow',
    };
    SendAjaxSync(d).then(callback);
  }


}

jQuery(document).ready(function($) {
  new AdmninWorkflowEditor();
});
