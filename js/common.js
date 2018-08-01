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

function ShowPopup(message, icon,delay, template,element = "body",position = "fixed")
{
	jQuery.notify(
		{
			message: message,
			icon: icon
		},
		{
			type: "info",
			icon_type: "img",
			placement: {
				from: "bottom",
				align: "left"
			},
			delay: delay,
			timer: 1000,
            template: template,
            element: element,
            position: position

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