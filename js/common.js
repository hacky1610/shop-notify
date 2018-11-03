class SnNotify {
  constructor(id, keyVals, title, message, productLink, pictureLink, style) {
    this.id = id;
    this.keyVals = keyVals;
    this.title = title;
    this.message = message;
    this.productLink = productLink;
    this.pictureLink = pictureLink;
    this.style = style;
    this.element = 'body';
    this.position = 'fixed';
    this.closeEvent = () => {};
    this.loadEvent = () => {};
  }

  setElement(el) {
    this.element = el;
  }

  setPosition(pos) {
    this.position = pos;
  }

  registerLoadEvent(callback) {
    this.loadEvent = callback;
  }

  registerOnCloseEvent(callback) {
    this.closeEvent = callback;
  }


  show() {
    const titleArray = SnNotify.getMessageArray(this.title, this.keyVals, this.productLink);
    const messageArray = SnNotify.getMessageArray(this.message, this.keyVals, this.productLink);
    const data = {
      'action': 'wcn_get_notify_layout',
      'id': this.id,
      'productLink': this.productLink,
      'pictureLink': this.pictureLink,
      'style': this.style,
      'title_content': JSON.stringify(titleArray),
      'message_content': JSON.stringify(messageArray),
    };
    return sendAjaxSync(data).then((body) => {
      this.loadEvent();
      SnNotify.showPopup(150000, body, this.element, this.position, this.closeEvent);
    });
  }

  static showPopup(delay, template, element, position, closeEvent) {
    const notify = jQuery.notify( {
      message: '',
      icon: '',
    },
    {
      type: 'info',
      icon_type: 'img',
      placement: {
        from: 'bottom',
        align: 'left',
      },
      onClosed: closeEvent,
      delay: delay,
      template: template,
      element: element,
      position: position,
    });
    return notify;
  }

  static getMessageArray(text, keyVals, productLink) {
    const newtext = text.replace(/{/g, '_{').replace(/}/g, '}_');
    const elements = newtext.split('_');

    let filledText = '';
    elements.forEach((element) => {
      if (SnNotify.isSpecialKey(element)) {
        filledText += SnNotify.getVal(element, keyVals);
      } else {
        filledText += element;
      }
    });

    const link = filledText.match(/<.+>/);

    let result = [];
    if (link) {
      let texts = filledText.split(link[0]);
      result.push({type: 'text', val: texts[0]});
      result.push({type: 'link', val: SnNotify.cleanLink(link[0]), link: productLink });
      if (texts.length > 1) {
        result.push({type: 'text', val: texts[1]});
      }
    } else {
      result.push({type: 'text', val: filledText });
    }
    return result;
  }

  static cleanLink(link) {
    return link.replace(/</g, '').replace(/>/g, '');
  }

  static isSpecialKey(element) {
    return (element.match(/^\s*{\w+}\s*$/));
  }

  static getVal(key, keyVals) {
    const cleanKey = key.replace(/{/g, '').replace(/}/g, '');
    return keyVals[cleanKey];
  }
}

function getUrlParam( name, url ) {
  if (!url) url = location.href;
  name = name.replace(/[\[]/,'\\\[').replace(/[\]]/,'\\\]');
  const regexS = '[\\?&]'+name+'=([^&#]*)';
  const regex = new RegExp( regexS );
  const results = regex.exec( url );
  return results == null ? null : results[1];
}

function sendAjaxSync(data, parser) {
  return new Promise(function(resolve, reject) {
    const ajaxurl = document.origin + '/wp-admin/admin-ajax.php'; //TODO
    jQuery.post(ajaxurl, data, function(response) {
      if (parser != null) {
        resolve(parser(response));
      } else {
        resolve(response);
      }
    });
  });
}