
function getCookie(cname) {
  let name = cname + '=';
  let decodedCookie = decodeURIComponent(document.cookie);
  let ca = decodedCookie.split(';');
  for (let i = 0; i <ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return '';
}

function setCookie(cname, cvalue, exdays) {
  let d = new Date();
  d.setTime(d.getTime() + (exdays*24*60*60*1000));
  let expires = 'expires='+ d.toUTCString();
  document.cookie = cname + '=' + cvalue + ';' + expires + ';path=/';
}


function ScrollToProducts(className) {
  jQuery('html, body').animate({
    scrollTop: jQuery('.' + className).offset().top - 100,
  }, 'slow');
}

function SwitchTabToReviews() {
  jQuery('.woocommerce-tabs').tabs();
  jQuery('.woocommerce-tabs').tabs('option', 'active', 1);
  jQuery('.reviews_tab').addClass('active');
  jQuery('.description_tab').removeClass('active');
  jQuery('.wcn-notify-review').removeClass('wcn-notify-visible');

  let all = jQuery('.woocommerce-review__author').map(function() {
    return this;
  }).get();

  jQuery('html, body').animate({
    scrollTop: jQuery(all[2]).offset().top - 100,
  }, 'slow');
}

function GetReviewTemplate(title, linkSnippet) {
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
					'</div>';
}

function ShowReviewPopup(title, message, icon, link) {
  let template = 	GetReviewTemplate(title, '<a class="message link" href="' + link + '#tab-reviews">clique pour voir son commentaire</a>' );

  ShowPopup(message, icon, 35000, template);

  setTimeout(() =>
  {
    jQuery('.wcn-notify-review').addClass('wcn-notify-visible');
  }, 20000);
}


function ShowReviewPopupSameSite(title, message, icon) {
  let template = 	GetReviewTemplate(title, '<span class="message link" onclick="SwitchTabToReviews()">clique pour voir son commentaire</span>');

  ShowPopup(message, icon, 35000, template);

  setTimeout(() =>
  {
    jQuery('.wcn-notify-review').addClass('wcn-notify-visible');
  }, 20000);
}

function GetNotifyObject(id) {
  let data = {
    'action': 'wcn_get_notify',
    'id': id,
  };
  return sendAjaxSync(data);
}



function GetProduct(id) {
  let data = {
    'action': 'get_product',
    'id': id,
  };
  return sendAjaxSync(data, JSON.parse);
}



function getLastReviews() {
  let data = {
    'action': 'get_last_reviews',
  };
  return;
  (data, JSON.parse);
}

function getLastReview() {
  return new Promise(function(resolve, reject) {
    getLastReviews().then((body) => {
      resolve(body[body.length - 1]);
    });
  });
}

class Notice {
  constructor() {
    this.minutesText = 'il y a {value} minutes';
    this.hoursText = 'il y a {value} heures';
    this.dayText = 'il y a {value} jour';
    this.daysText = 'il y a {value} jours';
    this.recently = 'récemment';
  };

  getMillisecondsDiff(date1, date2) {
    if (date1 < date2) {
      return date2 - date1;
    } else {
      return date1 - date2;
    }
  }

  replaceTime(template, value) {
    return template.replace('{value}', value);
  }

  getDays(date1, date2) {
    return Math.floor(this.getMillisecondsDiff(date1, date2) / 1000 / 60 / (60 * 24));
  }

  getTimeString(date) {
    const datetime = new Date( date ).getTime();
    const now = new Date().getTime();
    const diff = new Date(this.getMillisecondsDiff(now, datetime));

    const days = this.getDays(datetime, now);
    const diffHours = days * 24 + diff.getHours();
    let diffFormated;
    if (diffHours <= 24) { // weniger als 24 stunden
      if (diffHours <= 1) {// weniger als eine Stunde
        diffFormated = this.replaceTime(this.minutesText, diff.getMinutes());
      } else { // zwischen einer und 24 Stunden
        diffFormated = this.replaceTime(this.hoursText, diffHours);
      }
    } else if (diffHours <= 48) {
      diffFormated = this.replaceTime(this.dayText, days);
    } else if (diffHours <= 80) {
      diffFormated = this.replaceTime(this.daysText, days);
    } else {
      diffFormated = this.recently;
    }

    return diffFormated;
  }
}

class OrderNotice extends Notice {
  getLastOrders() {
	  const data = {
      'action': 'get_last_orders',
    };
	  return sendAjaxSync(data, JSON.parse);
  }

  getLastOrder(lastRange) {
    return new Promise(function(resolve, reject) {
      this.getLastOrders().then((body) => {
        if (lastRange === undefined) {
          lastRange = 1;
        }
        const max = Math.min(body.length, lastRange);
        resolve(body[Math.floor((Math.random() * max) + 0)]); // TODO: Random Order
      });
    }.bind(this));
  }

  showOrder(callback, lastRange) {
    this.getLastOrder(lastRange).then((lastorder) => {
      const product = lastorder.items[0];

      // var productId = product.id;
      // var orderId = lastorder.id;

      // var shownOrders = getCookie("ShownOrder").split(",");
      // if(shownOrders.includes(orderId.toString()))
      //	return;

      // setCookie("ShownOrder",shownOrders + "," + orderId,2);

      let name = lastorder.name;
      name = name[0].toUpperCase() + name.substring(1);
      const image = product.productImage;
      const link = product.productPermalink;
      const time = this.getTimeString(lastorder.dateCreated);

      const keyVals = {ProductName: product.name, GivenName: name, Bought: time};

      callback(keyVals, link, image);
    });
  }
}


function ShowReview() {
  getLastReview().then((review) =>
  {
    if (review != null) {
      let shownReviews = getCookie('ShownReview').split(',');
      // if(shownReviews.includes(review.id.toString()))
      //	return;
      setCookie('ShownReview', shownReviews + ',' + review.id, 2);


      GetProduct(review.comment_post_ID).then((prod) => {
        let name = review.comment_author;
        let rating = review.meta_value;
        name = name[0].toUpperCase() + name.substring(1);
        let message = 'Note de ' + rating + ' étoiles par ' + name + ', ';

        if (window.location.href == prod.productPermalink) {
          ShowReviewPopupSameSite(prod.name, message, prod.productImage);
        } else {
          ShowReviewPopup(prod.name, message, prod.productImage, prod.productPermalink);
        }
      }

      );
    }
  });
}





