// https://gist.github.com/leolux/c794fc63d9c362013448
const SleepEditor = function(element) {
  const sleep = element;
  const valueChanged = function(o) {
    sleep.setTime(o.target.value);
  };
  // Public method - can be called from client code
  this.getElement = function() {
    const inputTime = $(`<input type="text" name="FirstName" value="${element.getTime()}">`);
    inputTime.change(valueChanged);
    const frame = $(`<div></div>`);
    frame.append(inputTime);
    return frame;
  };
};

const WfeElement = function(c) {
  this.render = function() {
    frame.empty();
    if (frame.before()[0].className.includes('wfe')) {
      frame.append(beforeLine);
    }
    frame.append(content.content());
    frame.append(afteline);
    frame.append(dropLine);
    if (frame.next().length == 0 ) {
      frame.append(afterIcon);
    }
  };

  this.content = function() {
    return frame;
  };

  this.editor = function() {
    return content.getEditor();
  };

  this.selected = function(callback) {
    this.selectedCallback = callback;
  };

  this.elementAdded = function(callback) {
    elementAddedCallback = callback;
  };

  this.addAfter = function(element) {
    after = element;
    element.content().insertAfter(this.content());
  };

  const createUUID = function() {
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

  this.elementDropped = function(event, ui) {
    const droppable = $(this);
    const draggable = ui.draggable;
    let newElement = null;

    const type = $(ui.draggable).attr('type');
    if (type === 'sleep') {
      newElement = new WfeElement(new Sleep());
    } else if (type === 'notify') {
      const id = $(ui.draggable).attr('notify-id');
      newElement = new WfeElement(new Notify(guid, id));
    }

    newElement.selected(that.selectedCallback);
    droppable.after(newElement.content());
    elementAddedCallback(newElement, true);
    draggable.css({
      float: 'left',
    });
  };

  this.initEvents = function() {
    const that = this;
    content.content().click(() => {
      if (that.selectedCallback !== null) {
        that.selectedCallback(that);
      }
    }).bind(this);

    frame.droppable({
      classes: {
        'ui-droppable-hover': 'ui-state-hover',
      },
      accept: '.draggable',
      drop: this.elementDropped,
    });


    afterIcon.on('drop', function(event) {
      alert();
    });

    afterIcon.on('dragover', function(event) {
      event.preventDefault();
      event.stopPropagation();
      $(this).addClass('dragging');
    });
  };

  const guid = createUUID();
  const frame = $(`<li id='${guid}' class='wfeElement droppable'></li>` );
  const beforeLine = $( '<div class="wfeElement vl center">' );
  const afteline = $( '<div class="wfeElement vl center">' );
  const dropLine = $( '<div class="wfeElement hl center">' );
  const afterIcon = $( '<div class="wfeElement plus center">+</div>' );
  const content = c;
  const that = this;
  this.selectedCallback = null;
  let elementAddedCallback = null;
  this.initEvents();
};

const Sleep = function() {
  const elem = $('<div class="action"></div>');
  const editor = new SleepEditor(this);
  let time = '10';

  this.content = function() {
    return elem;
  };

  this.getEditor = function() {
    return editor.getElement();
  };

  this.getTime = function() {
    return time;
  };

  this.setTime = function(t) {
    time = t;
    update();
  };

  const update = function() {
    elem.html(`Wait ${time} seconds`);
  };
  update();
};

const Notify = function(id, notifyId) {
  let _notifyId = notifyId;
  let _id = id;
  const _containerId = `notify_container_${_id}`;
  const elem = $(`<div class="notify" id='${_containerId}'><div class="loader"></div></div>`);

  this.content = function() {
    return elem;
  };

  this.getEditor = function() {
    return editor.getElement();
  };

  const NotifyLoaded = function() {
    $(`#${_containerId} .loader`).remove();
  };

  const ShowNotifyCallback = function(keyVals, productLink, pictureLink) {
    GetNotifyObject(_notifyId).then((body) => {
      const object = JSON.parse(body);
      ShowNotify(_id, keyVals, object.title, object.message, productLink, pictureLink, object.style, `#${_containerId}`, 'static').then(NotifyLoaded);
    });
  };

  this.showPopup = function() {
    ShowOrder(ShowNotifyCallback);
  };

  this.showPopup();
};
