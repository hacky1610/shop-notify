class AdmninWorkflowEditor {
  constructor() {
    this.items = [];
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

  addElementToList(json) {
    let e = undefined;
    if (json.type === 'Sleep') {
      e = new Sleep();
    } else if (json.type === 'Notify') {
      e = new Notify();
    } else if (json.type === 'Condition') {
      e = new Condition();
    }

    if (e === undefined) {
      throw new Error(`Cant create object from type ${json.type}`);
    }

    e.setData(json.data);
    this.addElement(e);
    return e;
  }

  loadElements(res) {
    const elements = JSON.parse(res.replace(/\\/g, ''));
    let before = null;

    const first = new WfeEntryElement();

    first.registerElementAddedEvent(this.elementAdded.bind(this));
    first.initEvents();
    $('.droparea').append(first.getContent);


    elements.forEach(function(o) {
      let e = this.addElementToList(o);
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
    el.registerSelectedEvent(this.elementSelected);
    el.registerElementAddedEvent(this.elementAdded.bind(this));
    el.registerDeleteEvent(this.elementDeleted.bind(this));
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

  elementAdded(event, ui, before) {
    if (ui.helper[0].className.includes('wfeElement')) {
      if (before) {
        $(event.target).parent().before(ui.draggable);
      } else {
        $(event.target).parent().after(ui.draggable);
      }
    } else {
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

      if (before) {
        $(event.target).parent().before(newElement.getContent);
      } else {
        $(event.target).parent().after(newElement.getContent);
      }
      this.addElement(newElement, true);
      draggable.css({
        float: 'left',
      });
    }
  }

  getItem(id) {
    const found = this.items.find(function(element) {
      return element.getGuid === id;
    });
    return found;
  };

  getItems(domItems) {
    const data = [];
    for (let i = domItems.length-1; i >= 0; i--) {
      let item = adminWorkflowEditor.getItem(domItems[i].getAttribute('id'));
      if (item !== undefined) {
        data.push(item.getData);
      }
    }
    return data;
  }

  save() {
    const data = this.getItems($('.droparea').children('.wfeElement'));

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

let adminWorkflowEditor = undefined;
jQuery(document).ready(function($) {
  adminWorkflowEditor = new AdmninWorkflowEditor();
});
