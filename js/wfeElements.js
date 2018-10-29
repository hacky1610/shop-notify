// https://gist.github.com/leolux/c794fc63d9c362013448
/**
 * A class that can return the number 10
 */
class SleepEditor {
  constructor(element) {
    this.sleep = element;
  };

  valueChanged(o) {
    this.sleep.setTime(o.target.value);
  };

  get getContent() {
    const inputTime = $(`<input type="text" name="FirstName" value="${this.sleep.Time}">`);
    inputTime.change(this.valueChanged.bind(this));
    const frame = $(`<div><p>Wait: </p></div>`);
    frame.append(inputTime);
    return frame;
  };
};

class ConditionEditor {
  constructor(element) {
    this.sleep = element;
  };

  valueChanged(o) {
  };

  get getContent() {
    const frame = $(`<div><p></p></div>`);
    return frame;
  };
};

class NotifyEditor {
  constructor(element) {
    this.notify = element;
  };

  valueChanged(o) {
    this.notify.setDuration(o.target.value);
  };

  get getContent() {
    const inputTime = $(`<input type="text" name="FirstName" value="${this.notify.Duration}">`);
    inputTime.change(this.valueChanged.bind(this));
    const frame = $(`<div><p>Duration: </p></div>`);
    frame.append(inputTime);
    return frame;
  };
};

class WfeBaseElement {
  constructor() {
    this.guid = this.createUUID();
    this.frame = $(`<li id='${this.guid}' class='wfeElement'></li>` );
    this.beforeLine = $( '<div class="droppable add-element  center"><div class="hl center"></div><div class="vl center"></div></div>' );
    this.afteline = $( '<div class="droppable add-element  center"><div class="vl center"></div><div class="hl center"></div>' );
  
  };

  createUUID() {
    // http://www.ietf.org/rfc/rfc4122.txt
    let s = [];
    const hexDigits = '0123456789abcdef';
    for (let i = 0; i < 36; i++) {
      s[i] = hexDigits.substr(Math.floor(Math.random() * 0x10), 1);
    }
    s[14] = '4'; // bits 12-15 of the time_hi_and_version field to 0010
    s[19] = hexDigits.substr((s[19] & 0x3) | 0x8, 1); // bits 6-7 of the clock_seq_hi_and_reserved to 01
    s[8] = s[13] = s[18] = s[23] = '-';

    const uuid = s.join('');
    return uuid;
  };

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
    return {
      type: this.constructor.name,
      data: this.data,
    };
  }

  setData(data) {
    this.data = data;
  }

  get getEditor() {
    return this.editor;
  };

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

  itemClicked() {
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
  constructor() {
    super(); // call the super class constructor and pass in the name parameter
    this.item = $('<div class="action"></div>');
    this.editor = new SleepEditor(this);
    this.data.time = '10';
    this.initEvents();
    this.update();
  };

  get Time() {
    return this.data.time;
  };

  setTime(t) {
    this.data.time = t;
    this.update();
  };

  update() {
    this.item.html(`Wait ${this.Time} seconds`);
  };
};

class Condition extends WfeElement {
  constructor() {
    super(); // call the super class constructor and pass in the name parameter
    this.item = $('<div class="condition"><div class="condition-header"></div><div class="condition-body"></div></div>');
    this.trueColumn = $('<div class="column condition-true">true</div>');
    this.falseColumn = $('<div class="column condition-false">false</div>');
    this.item.find('.condition-body').append(this.trueColumn);
    this.item.find('.condition-body').append(this.falseColumn);


    this.editor = new ConditionEditor(this);
    
    this.initEvents();

    this.firstTrue = new WfeEntryElement();
    this.firstTrue.initEvents();
    $(this.trueColumn).append(this.firstTrue.getContent);

    this.firstFalse = new WfeEntryElement();
    this.firstFalse.initEvents();
    $(this.falseColumn).append(this.firstFalse.getContent);

    this.update();
  };

  registerElementAddedEvent(callback) {
    super.registerElementAddedEvent(callback);
    this.firstTrue.registerElementAddedEvent(callback);
    this.firstFalse.registerElementAddedEvent(callback);
  }

  update() {

  };

  get getData() {
    this.data.trueItems = this.getTrueItems;
    this.data.falseItems = this.getFalseItems;
    return super.getData;
  }

  addToColumn(jsonElements, firstElement) {
    if (jsonElements !== undefined) {
      jsonElements.forEach(function(e) {
        let element = adminWorkflowEditor.addElementToList(e);
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

  setData(data) {
    super.setData(data);
    this.addToColumn(data.trueItems, this.firstTrue);
    this.addToColumn(data.falseItems, this.firstFalse);
  }

  get getTrueItems() {
    return adminWorkflowEditor.getItems(this.trueColumn.children('.wfeElement'));
  }

  get getFalseItems() {
    return adminWorkflowEditor.getItems(this.falseColumn.children('.wfeElement'));
  }
};


class Notify extends WfeElement {
  constructor(notifyId) {
    super(); // call the super class constructor and pass in the name parameter
    this.editor = new NotifyEditor(this);
    this._containerId = `notify_container_${this.guid}`;
    this.item = $(`<div class="notify" id='${this._containerId}'><div class="loader"></div></div>`);
    this.data.notifyId = notifyId;
    this.data.duration = 60;
    this.initEvents();
    this.showPopup();
  };

  NotifyLoaded() {
    $(`#${this._containerId} .loader`).remove();
  };

  ShowNotifyCallback(keyVals, productLink, pictureLink) {
    let show = (body) => {
      const object = JSON.parse(body);
      ShowNotify(this.guid, keyVals, object.title, object.message, productLink, pictureLink, object.style, `#${this._containerId}`, 'static').then(this.NotifyLoaded.bind(this));
    };
    GetNotifyObject(this.data.notifyId).then(show.bind(this));
  };

  showPopup() {
    ShowOrder(this.ShowNotifyCallback.bind(this));
  };

  get Duration() {
    return this.data.duration;
  };

  setDuration(t) {
    this.data.duration = t;
  };
};
