class ControllerSerializer {

  static deserializeObject(object) {
    let e = undefined;
    if (object.type === 'WfeSleepController') {
      e = new WfeSleepController();
      e.setData(object.data);
    } else if (object.type === 'WfeNotifyController') {
      e = new WfeNotifyController();
      e.setData(object.data);
    } else if (object.type === 'WfeConditionController') {
      e = new WfeConditionController();
      e.setData(object.data);
      e.trueItems = this.deserializeObjectList(object.trueItems);
      e.falseItems = this.deserializeObjectList(object.falseItems);
    }

    if (e === undefined) {
      throw new Error(`Cant create object from type ${object.type}`);
    }
    return e;
  }

  static deserializeObjectList(list) {
    const resList = [];
    if (list !== undefined) {
      list.forEach(function(element) {
        resList.push(this.deserializeObject(element));
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

  get Editor() {
    return new NotifyEditor(this);
  }

  run() {
    return new Promise((resolve) => this.showPopup(resolve));
  }

  ShowNotifyCallback(keyVals, productLink, pictureLink) {
    let show = (body) => {
      const object = JSON.parse(body);
      ShowNotify(this.guid, keyVals, object.title, object.message, productLink, pictureLink, object.style, 'body', 'fixed', this.notifyClosed.bind(this));
    };
    GetNotifyObject(this.Id).then(show.bind(this));
  };

  notifyClosed() {
    this.notifyClosedEvent();
  }
  
  showPopup(notifyClosed) {
    this.notifyClosedEvent = notifyClosed;
    ShowOrder(this.ShowNotifyCallback.bind(this));
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
