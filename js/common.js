function GetOrderTemplate(title, link, additionalClass)
{
    return 	'<div data-notify="container" class="col-xs-11 col-sm-3 alert wcn-notify wcn-notify-orders ' + additionalClass +'" role="alert">' +
    '<div class="wcn-notify-icon">' + 
        '<span data-notify="icon"></span> ' +
    '</div>' + 
    '<div class="wcn-notify-message">' + 
        '<a href="' + link + '" class="title link" >' + title + '</a>' +
        '<span class="message" data-notify="message">{2}</span>' +
    '</div>' + 
        '<div class="wcn-notify-close">' + 
        '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">x</button>' +
    '</div>' + 
    '</div>' 
}

function getUrlParam( name, url ) {
    if (!url) url = location.href;
    name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
    var regexS = "[\\?&]"+name+"=([^&#]*)";
    var regex = new RegExp( regexS );
    var results = regex.exec( url );
    return results == null ? null : results[1];
}

function isSpecialKey(element)
{
    return (element.match(/^\s*{\w+}\s*$/));
}

function getVal(key,keyVals)
{
    cleanKey = key.replace(/{/g,"").replace(/}/g,"");
    return keyVals[cleanKey];
}

function cleanLink(link)
{
    return link.replace(/</g,"").replace(/>/g,"");

}

function getMessageArray(text,keyVals,productLink)
{
    var newtext = text.replace(/{/g,"_{").replace(/}/g,"}_")
    var elements = newtext.split("_");

    var filledText = "";
    elements.forEach(element => {
        if(isSpecialKey(element))
        {
            filledText += getVal(element,keyVals);
        }
        else
        {
            filledText += element;
        }
    });

    var link = filledText.match(/<.+>/);

    var result = [];
    if(link)
    {
        texts = filledText.split(link[0]);
        result.push({type: 'text', val: texts[0]});
        result.push({type: 'link', val: cleanLink(link[0]), link: productLink });
        if(texts.length > 1)
            result.push({type: 'text', val: texts[1]});

    }
    else
    {
        result.push({type: 'text', val: filledText });
    }

    return result;

} 

function ShowPopup(message, icon, delay, template, element, position, closeEvent = null) {
  const notify = jQuery.notify( {
    message: message,
    icon: icon,
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
    timer: 1000,
    template: template,
    element: element,
    position: position,

  });
  return notify;
}

function ShowNotify(id, keyVals, title, message, productLink, pictureLink, style, element = 'body',position = 'fixed', closeEvent) {
  const titleArray = getMessageArray(title, keyVals, productLink);
  const messageArray = getMessageArray(message,keyVals,productLink);

  const data = {
    'action': 'wcn_get_notify_layout',
    'id': id,
    'productLink' : productLink,
    'pictureLink' : pictureLink,
    'style' : style,
    'title_content': JSON.stringify(titleArray),
    'message_content': JSON.stringify(messageArray)
  };
  return SendAjaxSync(data).then((body) => {
    ShowPopup("","", 150000,body,element,position,closeEvent);
  });  
}

function SendAjaxSync(data, parser) {
    return new Promise(function(resolve, reject) {
      /*stuff using username, password*/
      
      // We can also pass the url value separately from ajaxurl for front end AJAX implementations
      var ajaxurl = document.origin + "/wp-admin/admin-ajax.php"; //TODO
      jQuery.post(ajaxurl, data, function(response) {
          if(parser != null)
                  resolve(parser(response));
              else
                  resolve(response);
      });
    });
  }