class ControllerSerializer {

  static deserializeObject(object) {
    let e = undefined;
    if (object.type === 'WfeSleepController') {
      e = new WfeSleepController();
      e.setData(object.data);
    } else if (object.type === 'WfeNotifyOrderController') {
      e = new WfeNotifyOrderController();
      e.setData(object.data);
    } else if (object.type === 'WfeConditionController') {
      e = new WfeConditionController();
      e.setData(object.data);
      e.trueItems = this.deserializeObjectList(object.trueItems);
      e.falseItems = this.deserializeObjectList(object.falseItems);
    }

    if (e === undefined) {
      // throw new Error(`Cant create object from type ${object.type}`);
    }
    return e;
  }

  static deserializeObjectList(list) {
    const resList = [];
    if (list !== undefined) {
      list.forEach(function(element) {
        let e = this.deserializeObject(element);
        if (e !== undefined) {
          resList.push(e);
        }
      }.bind(this));
    }
    return resList;
  }

  static deserialize(json) {
    const elements = JSON.parse(json.replace(/\\/g, ''));
    return this.deserializeObjectList(elements);
  }
}

class WfeBaseController {
  constructor() {
    this.data = {};
    this.data.guid = this.createUUID();
  }

  setData(data) {
    if (data != undefined) {
      this.data = data;
    }
  }

  registerUpdateEvent(callback) {
    this.updateEvent = callback;
  }

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

  get Type() {
    return this.constructor.name;
  }

  serialize() {
    return {
      type: this.Type,
      data: this.data,
    };
  }
}

class WfeSleepController extends WfeBaseController {
  constructor() {
    super();
    this.data.time = 10;
  }

  get getEditElement() {
    return new Sleep(this);
  }

  get Editor() {
    return new SleepEditor(this);
  }


  get Time() {
    return this.data.time;
  };

  setTime(t) {
    this.data.time = t;
    this.updateEvent();
  };

  run() {
    const milliseconds = this.Time * 1000;
    return new Promise((resolve) => setTimeout(resolve, milliseconds));
  }
}

class WfeNotifyController extends WfeBaseController {
  constructor() {
    super();
    this.data.duration = 20;
    this.data.lastOrderRange = 4;
  }

  setId(id) {
    this.data.notifyId = id;
  };

  get Id() {
    return this.data.notifyId;
  };

  get Duration() {
    return this.data.duration;
  };

  setDuration(t) {
    this.data.duration = t;
    this.updateEvent();
  };

  get getEditElement() {
    return new Notify(this);
  }

  ShowNotifyCallback(keyVals, productLink, pictureLink) {
    let show = (body) => {
      const object = JSON.parse(body);
      const notify = new SnNotify(this.guid, keyVals, object.title, object.message, productLink, pictureLink, object.style);
      notify.registerOnCloseEvent(this.notifyClosed.bind(this));
      notify.setDuration(this.Duration * 1000);
      notify.setPlacement(this.getPlacement(object.placement));
      notify.setEnterAnimation(object.enterAnimation);
      notify.setExitAnimation(object.exitAnimation);
      notify.show();
    };
    GetNotifyObject(this.Id).then(show.bind(this));
  };

  getPlacement(placementText) {
    return {
      from: placementText.split('-')[0],
      align: placementText.split('-')[1],
    };
  }

  notifyClosed() {
    this.notifyClosedEvent();
  }
}

class WfeNotifyOrderController extends WfeNotifyController {

  run() {
    return new Promise((resolve) => this.showPopup(resolve));
  }

  get Editor() {
    return new NotifyOrderEditor(this);
  }

  setOrderAction(action) {
    this.data.orderAction = action;
  }

  get getOrderAction() {
    return this.data.orderAction;
  }

  setRandomVal(val) {
    this.data.randomVal = val;
  }

  get getRandomVal() {
    return this.data.randomVal;
  }

  showPopup(notifyClosed) {
    this.notifyClosedEvent = notifyClosed;
    let orderRange = 1;
    if (this.getOrderAction === 'random') {
      orderRange = this.data.lastOrderRange;
    }

    const notice = new OrderNotice();
    notice.showOrder(this.ShowNotifyCallback.bind(this), orderRange);
  };
}

class WfeConditionController extends WfeBaseController {
  constructor() {
    super();
  }

  get getEditElement() {
    return new Condition(this);
  }

  get Editor() {
    return new ConditionEditor(this);
  }

  serialize() {
    let retval = super.serialize();
    retval.trueItems = this.trueItems;
    retval.falseItems = this.falseItems;
    return retval;
  }

  run() {
  }
}
