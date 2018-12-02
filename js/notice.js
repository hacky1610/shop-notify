
function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}


function ScrollToProducts(className)
{
	jQuery('html, body').animate({
    scrollTop: jQuery("." + className).offset().top - 100
    }, 'slow');
}

function SwitchTabToReviews()
{
	jQuery(".woocommerce-tabs").tabs();
	jQuery(".woocommerce-tabs").tabs("option", "active", 1);
	jQuery(".reviews_tab").addClass("active");
	jQuery(".description_tab").removeClass("active");
	jQuery(".wcn-notify-review").removeClass("wcn-notify-visible");
	
	var all = jQuery(".woocommerce-review__author").map(function() {
    return this;
	}).get();
	
	jQuery('html, body').animate({
    scrollTop: jQuery(all[2]).offset().top - 100
    }, 'slow');
}

function GetReviewTemplate(title, linkSnippet)
{
		return	'<div data-notify="container" class="col-xs-11 col-sm-3 alert wcn-notify wcn-notify-review" role="alert">' +
					'<div class="wcn-notify-icon">' + 
						'<span data-notify="icon"></span> ' +
					'</div>' + 
					'<div class="wcn-notify-message">' + 
						'<span class="title" >' + title + '</span>' +	
						'<span class="message" data-notify="message">{2}</span>' +
						linkSnippet +
					'</div>' + 
						'<div class="wcn-notify-close">' + 
						'<button type="button" aria-hidden="true" class="close" data-notify="dismiss">x</button>' +
					'</div>' + 
					'</div>' ;
}

function ShowReviewPopup(title, message, icon, link)
{
	var template = 	GetReviewTemplate(title,'<a class="message link" href="' + link + '#tab-reviews">clique pour voir son commentaire</a>' );
	
	ShowPopup(message,icon, 35000,template)
	
	setTimeout(() => 
	{
		jQuery(".wcn-notify-review").addClass("wcn-notify-visible");
	}, 20000);
}


	function ShowReviewPopupSameSite(title, message, icon)
	{
		var template = 	GetReviewTemplate(title, '<span class="message link" onclick="SwitchTabToReviews()">clique pour voir son commentaire</span>');
		
		ShowPopup(message,icon, 35000,template)
		
		setTimeout(() => 
		{
			jQuery(".wcn-notify-review").addClass("wcn-notify-visible");
		}, 20000);
	}

	function GetNotifyObject(id) {
		var data = {
		'action': 'wcn_get_notify',
		'id': id     
		};
		return sendAjaxSync(data);
	}
	
	
	
	function GetProduct(id) {
		var data = {
		'action': 'get_product',
		'id': id     
		};
		return sendAjaxSync(data, JSON.parse);
	}
	
	function getLastOrders()
	{
		var data = {
		'action': 'get_last_orders'
		};
		return sendAjaxSync(data, JSON.parse);
	}
	
	function getLastReviews()
	{		
		var data = {
		'action': 'get_last_reviews'
		};
		return sendAjaxSync(data, JSON.parse);
	}
		
function getMillisecondsDiff(date1, date2) {
  if (date1 < date2) {
    return date2 - date1;
  } else {
    return date1 - date2;
  }
}

function getDays(date1, date2) {
  return Math.floor(getMillisecondsDiff(date1, date2) / 1000 / 60 / (60 * 24));
}

function getTimeString(date) {
  const datetime = new Date( date ).getTime();
  const now = new Date().getTime();
  const diff = new Date(getMillisecondsDiff(now, datetime));

  const days = getDays(datetime, now);
  const diffHours = days * 24 + diff.getHours();
  let diffFormated;
  if (diffHours <= 24) { // weniger als 24 stunden
    if (diffHours <= 1) {// weniger als eine Stunde
      diffFormated = 'il y a ' + diff.getMinutes() + ' minutes';
    } else { // zwischen einer und 24 Stunden
      diffFormated = 'il y a ' + diffHours + ' heures';
    }
  } else if (diffHours <= 48) {
    diffFormated = 'il y a ' + days + ' jour';
  } else if (diffHours <= 80) {
    diffFormated = 'il y a ' + days + ' jours';
  } else {
    diffFormated = 'récemment';
  }

  return diffFormated;
}
	
function getLastOrder(lastRange) {
  return new Promise(function(resolve, reject) {
    getLastOrders().then((body) => {
      if (lastRange === undefined) {
        lastRange = 1;
      }
      const max = Math.min(body.length, lastRange);
      resolve(body[Math.floor((Math.random() * max) + 0)]); // TODO: Random Order
    });
  });
}
	
	function getLastReview()
	{
		return new Promise(function(resolve,reject) {
			getLastReviews().then((body) => {
				resolve(body[body.length - 1]);
				
			});
		});
	}

function ShowOrder(callback, lastRange) {
  getLastOrder(lastRange).then((lastorder) => {
    const product = lastorder.items[0];

    //var productId = product.id;
    //var orderId = lastorder.id;
    
    //var shownOrders = getCookie("ShownOrder").split(",");
    //if(shownOrders.includes(orderId.toString()))
    //	return;
    
    //setCookie("ShownOrder",shownOrders + "," + orderId,2);

    let name = lastorder.name;
    name = name[0].toUpperCase() + name.substring(1);
    const image = product.productImage;
    const link = product.productPermalink;
    const time = getTimeString(lastorder.dateCreated);
    
    const keyVals = {ProductName: product.name, GivenName: name, Bought: time};

    callback(keyVals, link, image);

  });
}
	
	
	function ShowReview()
	{		
		getLastReview().then((review) => 
		{
			if(review != null)
			{
				var shownReviews = getCookie("ShownReview").split(",");
					//if(shownReviews.includes(review.id.toString()))
					//	return;
				setCookie("ShownReview",shownReviews + "," + review.id,2);

				
				GetProduct(review.comment_post_ID).then((prod) =>
				{
					var name =  review.comment_author;  
					var rating = review.meta_value;
					name =  name[0].toUpperCase() + name.substring(1);
					var message = "Note de " + rating + " étoiles par " + name + ", ";
					
					if(window.location.href == prod.productPermalink)
					{
						ShowReviewPopupSameSite(prod.name, message,prod.productImage);
					}
					else
					{ 
						ShowReviewPopup(prod.name, message,prod.productImage,prod.productPermalink);
					}
				}
			
			);
			
			}
		});
		
		
	}
	

	


