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

class WfeElement {
  constructor() {
    this.guid = this.createUUID();
    this.frame = $(`<li id='${this.guid}' class='wfeElement droppable'></li>` );
    this.innerframe = $(`<div class="wfeElement inner-frame"></div>` );
    this.beforeLine = $( '<div class="wfeElement vl center">' );
    this.afteline = $( '<div class="wfeElement vl center">' );
    this.dropLine = $( '<div class="wfeElement hl center">' );
    this.deleteIcon = $( '<div class="wfeElement delete-icon"><img src="http://icons.iconarchive.com/icons/oxygen-icons.org/oxygen/256/Actions-window-close-icon.png"></div>' );
    this.afterIcon = $( '<div class="wfeElement plus center">+</div>' );
    this.that = this;
    this.selectedCallback = null;
    this.elementAddedCallback = null;
    this.data = {};
  }

  render() {
    this.frame.empty();
    if (this.frame.before()[0].className.includes('wfe')) {
      this.frame.append(this.beforeLine);
    }
    this.frame.append(this.innerframe);
    this.innerframe.append(this.item);
    this.innerframe.append(this.deleteIcon);
    this.frame.append(this.afteline);
    this.frame.append(this.dropLine);
    if (this.frame.next().length == 0 ) {
      this.frame.append(this.afterIcon);
    }
  };

  get getData() {
    return {
      type: this.constructor.name,
      data: this.data,
    };
  }

  setData(data) {
    this.data = data;
  }

  get getContent() {
    return this.frame;
  };

  get getEditor() {
    return this.editor;
  };

  selected(callback) {
    this.selectedCallback = callback;
  };

  elementAdded(callback) {
    this.elementAddedCallback = callback;
  };

  addAfter(element) {
    this.after = element;
    element.content().insertAfter(this.content());
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

  elementDropped(event, ui) {
    this.elementAddedCallback(event, ui);
  };


  initEvents() {
    const that = this;
    this.item.click(() => {
      if (that.selectedCallback !== null) {
        that.selectedCallback(that);
      }
    }).bind(this);

    this.frame.droppable({
      classes: {
        'ui-droppable-hover': 'ui-state-hover',
      },
      accept: '.draggable',
      drop: this.elementDropped.bind(this),
    });


    this.afterIcon.on('drop', function(event) {
      alert();
    });

    this.afterIcon.on('dragover', function(event) {
      event.preventDefault();
      event.stopPropagation();
      $(this).addClass('dragging');
    });
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
