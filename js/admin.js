(function( $ ) {
 
    // Add Color Picker to all inputs that have 'color-field' class
    $(function() {
      
        $('.wcn_edit_section > div').hide();
        //$('.wcn_mask').inputmask({ regex: '-?[0-9]+.?([0-9]+)?(px|em|rem|ex|%|in|cm|mm|pt|pc)' }); 
        $('.wcn_mask').inputmask({ regex: '-?[0-9]+([,.][0-9]+)?(px|em|rem|ex|%|in|cm|mm|pt|pc)' }); 
        
        $('.wcn_font_select').fontselect().change(function(){
                // replace + signs with spaces for css
                var font = $(this).val().replace(/\+/g, ' ');
                // split font into family and weight
                font = font.split(':');
                // set family on paragraphs
                var classToEdit = $(this).attr("wcn_class")
                ChangeStyle(classToEdit,"font-family",font[0]);
        });
            


        $.notify({
            title: "Welcome:",
            message: "This plugin has been provided to you by Robert McIntosh aka mouse0270"
        },
        {
            element: '#sampleContainer',
            position: "absolute",
            delay: "60000",
            template: GetOrderTemplate("Foo","","wcn-notify-visible")
        });
    });
     
})( jQuery );

var GetCssText = function(styleSheetId)
{
    var cssText = "";
    var cssRules = document.getElementById(styleSheetId).sheet.cssRules;
    for (var i = 0; i < cssRules.length; i++) {
        cssText += cssRules[i].cssText;
    }
    return cssText;
}

var GetRule = function(rules, ruleName)
{
    for (var i = 0; i < rules.length; i++) {
        if(rules[i].selectorText.match( ruleName))
        {
            return rules[i];
        }
    }
}

var ChangeStyle = function(rulename,style,value)
{
    var styleSheet = document.getElementById("wcn_style_sheet").sheet;
    var rule = GetRule(styleSheet.cssRules,rulename);

    rule.style[style] = value
}

var hideAllEditControls = function(rulename,style,value)
{
    var styleSheet = document.getElementById("wcn_style_sheet").sheet;
    var rule = GetRule(styleSheet.cssRules,rulename);

    rule.style[style] = value
}

var clicked = function(event)
{
    event.stopPropagation();


    //Change selections Frame
    $(".wcn_selected").removeClass("wcn_selected");
    $(event.currentTarget).addClass("wcn_selected");

    $('.wcn_edit_section > div').hide();

    var classToChange = event.currentTarget.attributes["wcn_class"].value;
    var propsToChange = event.currentTarget.attributes["wcn_style_props"].value.split(",");

    for (var i = 0; i < propsToChange.length; i++) {
        $("#wcn_" + propsToChange[i] + "_container input").attr("wcn_class",classToChange)        


        var cssCval = $(event.currentTarget).css(propsToChange[i]);
        if(propsToChange[i] === "color" || propsToChange[i] === "background-color" )
        {   
            var cp = $("#wcn_" + propsToChange[i] + "_container input").wpColorPicker({
                /**
                 * @param {Event} event - standard jQuery event, produced by whichever
                 * control was changed.
                 * @param {Object} ui - standard jQuery UI object, with a color member
                 * containing a Color.js object.
                 */
                change: function (event, ui) {
                    if(event.target.value !== "")
                        ChangeStyle(event.target.attributes.wcn_class.value, event.target.id.replace("wcn_",""), ui.color.toCSS());
    
                }})
            var c = new Color(cssCval);
            cssCval = c.toCSS();
            $(cp).wpColorPicker("color",cssCval)
        }
        else
        {
            $("#wcn_" + propsToChange[i] + "_container input").val(cssCval)
        }

        $("#wcn_" + propsToChange[i] + "_container").show();
    }
}



var changed = function(event)
{
    
    ChangeStyle(event.target.attributes.wcn_class.value, event.target.id.replace("wcn_",""), event.target.value);
} 

var isSpecialKey = function(element)
{
    return (element.match(/^{\w+}$/));
}

var getVal = function(key,keyVals)
{
    cleanKey = key.replace("{","").replace("}","");
    return keyVals[cleanKey];
}

var cleanLink = function(link)
{
    return link.replace("<","").replace(">","");

}

var getMessageArray = function(text,keyVals)
{
    var newtext = text.replace("{","_{");
    newtext = newtext.replace("}","}_")
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
        result.push({type: 'link', val: cleanLink(link[0])});
        if(texts.length > 1)
            result.push({type: 'text', val: texts[1]});

    }
    else
    {
        result.push({type: 'text', val: filledText });
    }

    return result;

} 

var ShowPreviewPopup = function()
{
    var id = "sn_admin_sample";
    var keyVals = {ProductName: "T-Shirt", GivenName: "Valérie"};
    var titleArray = getMessageArray($("#sn_title_content").val(),keyVals)
    var messageArray = getMessageArray($("#sn_message_content").val(),keyVals)

    var data = {
        'action': 'wcn_get_notify',
        'id': id,
        'title_content': JSON.stringify(titleArray),
        'message_content': JSON.stringify(messageArray)
		};
    SendAjaxSync(data).then((body) => {
        $("#" + id).remove();
        ShowPopup("","", 150000,body,"#wpbody-content","static");
    });
}


$('.wcn-editable').on('click', clicked );
$('.wcn_edit_section .wcn-edit-control').on('change', changed );
$(".button").click(function() {
    var data = {
        'action': 'wcn_save_style',
        'style': GetCssText("wcn_style_sheet")
		};
		SendAjaxSync(data, JSON.parse);
  });

  $('.notify-editor .wcn-edit-control').on('change', ShowPreviewPopup );








			