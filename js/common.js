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