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

  loadElements(res) {
    const controllers = ControllerSerializer.deserialize(res);

    const first = new WfeEntryElement();
    first.registerElementAddedEvent(this.elementAdded.bind(this));
    first.initEvents();
    $('.droparea').append(first.getContent);

    let before = null;
    controllers.forEach(function(controller) {
      let e = controller.getEditElement;
      this.addElement(e);
      if (before === null) {
        $(first.getContent).after(e.getContent);
      } else {
        $(before).after(e.getContent);
      }
      before = e.getContent;
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
    $('#editorarea').append(o.controller.Editor.getContent());
    $('.selectpicker').selectpicker();
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
      let controller = null;

      const type = $(ui.draggable).attr('type');
      if (type === 'sleep') {
        controller = new WfeSleepController();
      } else if (type === 'notify') {
        const id = $(ui.draggable).attr('notify-id');
        controller = new WfeNotifyOrderController();
        controller.setId(id);
      } else if (type === 'condition') {
        controller = new WfeConditionController();
      }
      
      const newElement = controller.getEditElement;

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
      return element.controller.data.guid === id;
    });
    return found;
  };

  getItems(domItems) {
    const data = [];
    for (let i = 0; i < domItems.length; i++) {
      let item = adminWorkflowEditor.getItem(domItems[i].getAttribute('id'));
      if (item !== undefined) {
        data.push(item.getData.serialize());
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
    sendAjaxSync(d).then((res) => {
      CheckResponse(res, jumpToSource);
    });
  };

  load(callback) {
    const d = {
      'action': 'wcn_get_workflow',
    };
    sendAjaxSync(d).then(callback);
  }
}

let adminWorkflowEditor = undefined;
jQuery(document).ready(function($) {
  adminWorkflowEditor = new AdmninWorkflowEditor();
});

