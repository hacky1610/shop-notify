// https://gist.github.com/leolux/c794fc63d9c362013448
/**
 * A class that can return the number 10
 */
class SleepEditor {
  constructor(controller) {
    this.controller = controller;
  };

  valueChanged(o) {
    this.controller.setTime(o.target.value);
  };

  getContent() {
    const inputTime = $(`<input type="text" value="${this.controller.Time}">`);
    inputTime.change(this.valueChanged.bind(this));
    const frame = $(`<div><p>Wait: </p></div>`);
    frame.append(inputTime);
    return frame;
  };
};

class ConditionEditor {
  constructor(controller) {
    this.controller = controller;
  };

  valueChanged(o) {
  };

  getContent() {
    const frame = $(`<div><p></p></div>`);
    return frame;
  };
};

class NotifyEditor {
  constructor(controller) {
    this.controller = controller;
  };

  timeValueChanged(o) {
    this.controller.setDuration(o.target.value);
  };

  getRow() {
    return $('<tr></tr>');
  }

  getColumn(content) {
    const column = $('<td></td>');
    if (content !== undefined) {
      column.append(content);
    }
    return column;
  }

  getLabelColumn(text) {
    const column = $(`<td class="label-column">${text}</td>`);
    return column;
  }

  getTable() {
    const table = $('<table class="property-table"> </table>');
    const row = this.getRow();
    table.append(row);

    const inputTime = $(`<input type="text" value="${this.controller.Duration}">`);
    inputTime.change(this.timeValueChanged.bind(this));

    row.append(this.getLabelColumn('Duration:'));
    row.append(inputTime);
    return table;
  };
};


class NotifyOrderEditor extends NotifyEditor {
  constructor(controller) {
    super(controller);
    this.optionContainer = $('<div><div>');
  };

  update(selectedItem) {
    this.optionContainer.empty();
    if (selectedItem === 'random') {
      this.optionContainer.append(`<br><p>Any of the last </p>`);
      this.optionContainer.append(this.getRandomSelectBox(this.controller.getRandomVal));
      this.optionContainer.append(`<p>Orders</p>`);
    }
    this.controller.setOrderAction(selectedItem);
    $('.selectpicker').selectpicker();
  }

  orderSelectionValueChanged(o) {
    //  this.controller.setDuration(o.target.value);
    const selectedItem = $(o.target.selectedOptions[0]).attr('id');
    this.update(selectedItem);
  };

  randomValueChanged(o) {
    //  this.controller.setDuration(o.target.value);
    const randomValue = $(o.target.selectedOptions[0]).val();
    this.controller.setRandomVal(randomValue);
    o.originalEvent.stopPropagation();
  };

  getRandomSelectBox(val) {
    const selectPickerContainer = $('<div class="selectpicker-container"></div>');
    const select = $('<select class="selectpicker" data-width="fit"></select>');
    for (let i = 0; i < 10; i++) {
      let selected = '';
      if (i == val) {
        selected = 'selected';
      }
      select.append(`<option ${selected}>${i}</option>`);
    }
    select.change(this.randomValueChanged.bind(this));
    selectPickerContainer.append(select);
    return selectPickerContainer;
  }

  getOrderSelectBox(val) {
    const select = $('<div class="selectpicker-container"><select class="selectpicker" data-width="fit"><option id="last-order">Last order</option><option id="random">Ramdom</option><option id="specific">Specific</option></select></div>');
    if (val !== undefined) {
      select.children('select').children(`#${val}`).attr('selected', '' );
      this.update(val);
    }
    return select;
  }

  getContent() {
    const table = super.getTable();

    const inputProductSelection = this.getOrderSelectBox(this.controller.getOrderAction);
    inputProductSelection.change(this.orderSelectionValueChanged.bind(this));
    inputProductSelection.append(this.optionContainer);


    const row = this.getRow();
    row.append(this.getLabelColumn('Order:'));
    row.append(this.getColumn(inputProductSelection));
    table.append(row);
    return table;
    
  }
}


class WfeBaseElement {
  constructor() {
    this.controller = {};
    this.frame = $(`<li id='notset' class='wfeElement draggable'></li>` );
    this.beforeLine = $( '<div class="droppable add-element  center"><div class="hl center"></div><div class="vl center"></div></div>' );
    this.afteline = $( '<div class="droppable add-element  center"><div class="vl center"></div><div class="hl center"></div>' );
  };

  updateGuid() {
    this.frame.attr('id', this.controller.data.guid);
  }

  get getContent() {
    return this.frame;
  };


  initEvents() {
    this.afteline.droppable({
      classes: {
        'ui-droppable-hover': 'ui-state-hover',
      },
      accept: '.draggable',
      drop: this.elementDroppedAfter.bind(this),
    });

    this.beforeLine.droppable({
      classes: {
        'ui-droppable-hover': 'ui-state-hover',
      },
      accept: '.draggable',
      drop: this.elementDroppedBefore.bind(this),
    });

    this.frame.draggable({
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
  }

  elementDroppedAfter(event, ui) {
    this.elementAddedCallback(event, ui, false);
  };

  elementDroppedBefore(event, ui) {
    this.elementAddedCallback(event, ui, true);
  };

  registerElementAddedEvent(callback) {
    this.elementAddedCallback = callback;
  };
};

class WfeEntryElement extends WfeBaseElement {
  constructor() {
    super();
    this.frame.append(this.afteline);
    this.frame.find('.vl').css('opacity', '0'); //TODO: nicht schön
  }
}

class WfeElement extends WfeBaseElement {
  constructor() {
    super();
    this.innerframe = $(`<div class="wfeElement inner-frame"></div>` );
    this.deleteIcon = $( '<div class="wfeElement delete-icon"><img src="' + workflow_element_vars.delete_icon + '"></div>' );
    this.that = this;
    this.selectedCallback = null;
    this.elementAddedCallback = null;
    this.data = {};
    this.initFrame();
  }

  initFrame() {
    this.frame.append(this.beforeLine);
    this.frame.append(this.innerframe);
    this.frame.append(this.afteline);
    this.frame.append(this.dropLine);
  }

  render() {
    if (this.frame.before()[0].className.includes('wfe')) {
    }
    if (this.innerframe.children().length === 0) {
      this.innerframe.append(this.item);
      this.innerframe.append(this.deleteIcon);
    }
  };

  get getGuid() {
    return this.guid;
  }

  get getData() {
    return this.controller;
  }

  setData(data) {
    this.data = data;
  }

  registerSelectedEvent(callback) {
    this.selectedCallback = callback;
  };

  registerDeleteEvent(callback) {
    this.deleteCallback = callback;
  }

  addAfter(element) {
    this.after = element;
    element.content().insertAfter(this.content());
  };

  delete() {
    this.frame.remove();
    this.deleteCallback(this);
  };

  itemClicked(event) {
    event.stopPropagation();
    if (this.selectedCallback !== null) {
      this.selectedCallback(this);
    }
  }

  initEvents() {
    super.initEvents();
    this.item.click(this.itemClicked.bind(this));
    this.deleteIcon.click(this.delete.bind(this));
  };

};

class Sleep extends WfeElement {
  constructor(controller) {
    super(); // call the super class constructor and pass in the name parameter
    this.item = $('<div class="action"></div>');
    this.controller = controller;
    this.controller.registerUpdateEvent(this.update.bind(this));
    this.initEvents();
    this.update();
    this.updateGuid();
  };

  update() {
    this.item.html(`Wait ${this.controller.Time} seconds`);
  };
};

class Condition extends WfeElement {
  constructor(controller) {
    super(); // call the super class constructor and pass in the name parameter
    this.item = $('<div class="condition"><div class="condition-header"></div><div class="condition-body"></div></div>');
    this.trueColumn = $('<div class="column condition-true">true</div>');
    this.falseColumn = $('<div class="column condition-false">false</div>');
    this.item.find('.condition-body').append(this.trueColumn);
    this.item.find('.condition-body').append(this.falseColumn);
    this.controller = controller;
    this.controller.registerUpdateEvent(this.update.bind(this));
    
    this.initEvents();

    this.firstTrue = new WfeEntryElement();
    this.firstTrue.initEvents();
    $(this.trueColumn).append(this.firstTrue.getContent);

    this.firstFalse = new WfeEntryElement();
    this.firstFalse.initEvents();
    $(this.falseColumn).append(this.firstFalse.getContent);

    this.fill();
    this.updateGuid();
  };

  registerElementAddedEvent(callback) {
    super.registerElementAddedEvent(callback);
    this.firstTrue.registerElementAddedEvent(callback);
    this.firstFalse.registerElementAddedEvent(callback);
  }

  fill() {
    this.addToColumn(this.controller.trueItems, this.firstTrue);
    this.addToColumn(this.controller.falseItems, this.firstFalse);
  };

  update() {
    
  }

  get getData() {
    this.controller.trueItems = this.getTrueItems;
    this.controller.falseItems = this.getFalseItems;
    return this.controller;
  }

  addToColumn(controllers, firstElement) {
    if (controllers !== undefined) {
      controllers.forEach(function(controller) {
        let element = controller.getEditElement;
        adminWorkflowEditor.addElement(element);
        let before = null;
        if (before === null) {
          $(firstElement.getContent).after(element.getContent);
        } else {
          before = element.getContent;
          $(before).after(e.getContent);
        }
      });
    }
  }

  get getTrueItems() {
    return adminWorkflowEditor.getItems(this.trueColumn.children('.wfeElement'));
  }

  get getFalseItems() {
    return adminWorkflowEditor.getItems(this.falseColumn.children('.wfeElement'));
  }
};


class Notify extends WfeElement {
  constructor(controller) {
    super(); // call the super class constructor and pass in the name parameter
    this._containerId = `notify_container_${controller.Id}`;
    this.item = $(`<div class="notify" id='${this._containerId}'><div class="loader"></div></div>`);
    this.controller = controller;
    this.initEvents();
    this.showPopup();
    this.updateGuid();
  };

  NotifyLoaded() {
    $(`#${this._containerId} .loader`).remove();
  };

  ShowNotifyCallback(keyVals, productLink, pictureLink) {
    let show = (body) => {
      const object = JSON.parse(body);
      const notify = new SnNotify(this.guid, keyVals, object.title, object.message, productLink, pictureLink, object.style);
      notify.setElement(`#${this._containerId}`);
      notify.setPosition('static');
      notify.registerLoadEvent(this.NotifyLoaded.bind(this));
      notify.show();
    };
    GetNotifyObject(this.controller.Id).then(show.bind(this));
  };

  showPopup() {
    const notice = new OrderNotice();
    notice.showOrder(this.ShowNotifyCallback.bind(this));
  };
};

