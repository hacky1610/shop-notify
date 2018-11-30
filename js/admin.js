var fontSelector;

class SnFontSelector {
    constructor(selector,callback) {
        this.internalSelector = $(selector).fontselect();
        this.internalSelector.Original().change(function(){
                // replace + signs with spaces for css
                var font = $(this).val().replace(/\+/g, ' ');
                // split font into family and weight
                font = font.split(':');
                // set family on paragraphs
               callback(font[0],$(this).attr('wcn_class'));
        });
    }

    selectFontByName(font)
    {
        this.internalSelector.selectFontByName(font);
    }

  }

  class StyleHelper {
    GetRule(rules, ruleName)
    {
        for (var i = 0; i < rules.length; i++) {
            if(rules[i].selectorText.match( ruleName))
            {
                return rules[i];
            }
        }
    }

     ChangeStyle(sheetname,rulename,style,value)
    {
        var styleSheet = document.getElementById(sheetname).sheet;
        var rule = this.GetRule(styleSheet.cssRules,rulename);

        rule.style[style] = value
    }

  }

function SaveStyle() {
  const data = {
    'action': 'wcn_save_style',
    'style_id': $('#wcn_select-style').val(),
    'style_content': GetCssText('wcn_style_sheet')
  };
  sendAjaxSync(data).then((res) => {
      CheckResponse(res, jumpToSource);
  });
}


 
(function( $ ) {
 
    // Add Color Picker to all inputs that have 'color-field' class
    $(function() {
      
        $('.wcn_mask').inputmask({ regex: '-?[0-9]+([,.][0-9]+)?(px|em|rem|ex|%|in|cm|mm|pt|pc)' }); 
        
        fontSelector = new SnFontSelector('.wcn_font_select', (font,classToEdit) => {
            var styleHelper = new StyleHelper();
            styleHelper.ChangeStyle('wcn_style_sheet',classToEdit,'font-family',font);
        } );
            

        $('.wcn-editable').on('click', clicked );
        $('.wcn_edit_section .wcn-edit-control').on('change', changed );
        $('#style-editor-save-button').click(SaveStyle);

        //$('.notify-editor .wcn-edit-control').on('change', () => {ShowPreviewPopup($("#sn_style_content").val());} );

        $('.wcn-notify-orders').click();
     
    });
     
})( jQuery );

function ShowPreviewPopup(style) {
  const id = 'sn_admin_sample';
  const keyVals = {ProductName: 'T-Shirt', GivenName: 'Valérie'};
  $(`#${id}`).remove();
  const notify = new SnNotify(id, keyVals, $('#sn_title_content').val(), $('#sn_message_content').val(), '#', '', style);
  notify.setElement('.notify-preview .panel-body');
  notify.setPosition('static');
  notify.show();
}

var GetCssText = function(styleSheetId)
{
    var cssText = '';
    var cssRules = document.getElementById(styleSheetId).sheet.cssRules;
    for (var i = 0; i < cssRules.length; i++) {
        cssText += cssRules[i].cssText;
    }

    return cssText.replace(/"/g,'');

}

var hideAllEditControls = function(rulename,style,value)
{
    var styleSheet = document.getElementById('wcn_style_sheet').sheet;
    var rule = GetRule(styleSheet.cssRules,rulename);

    rule.style[style] = value
}

var clicked = function(event)
{
    event.stopPropagation();


    //Change selections Frame
    $('.wcn_selected').removeClass('wcn_selected');
    $(event.currentTarget).addClass('wcn_selected');

    $('.wcn_edit_section > div').hide();

    var classToChange = event.currentTarget.attributes['wcn_class'].value;
    var propsToChange = event.currentTarget.attributes['wcn_style_props'].value.split(',');

    for (var i = 0; i < propsToChange.length; i++) {
        $('#wcn_' + propsToChange[i] + '_container input').attr('wcn_class',classToChange)        


        var cssCval = $(event.currentTarget).css(propsToChange[i]);
        if(propsToChange[i] === 'color' || propsToChange[i] === 'background-color' )
        {   
            var cp = $('#wcn_' + propsToChange[i] + '_container input').wpColorPicker({
                /**
                 * @param {Event} event - standard jQuery event, produced by whichever
                 * control was changed.
                 * @param {Object} ui - standard jQuery UI object, with a color member
                 * containing a Color.js object.
                 */
                change: function (event, ui) {
                    if(event.target.value !== '')
                    {
                        var styleHelper = new StyleHelper();
                        styleHelper.ChangeStyle('wcn_style_sheet',event.target.attributes.wcn_class.value, event.target.id.replace('wcn_',''), ui.color.toCSS());
                    }
    
                }})
            var c = new Color(cssCval);
            cssCval = c.toCSS();
            $(cp).wpColorPicker('color',cssCval)
        }
        else if(propsToChange[i] === 'font-family')
        {
            fontSelector.selectFontByName(cssCval.replace(/\"/g,''));
        }
        else
        {
            $('#wcn_' + propsToChange[i] + '_container input').val(cssCval)
        }

        $('#wcn_' + propsToChange[i] + '_container').show();
    }
}

function CheckResponse(res,callback)
{
    if(res !== 'OK')
    {
        alert('ERROR: ' +res);
    }
    else
        callback();
}



var changed = function(event)
{
    var styleHelper = new StyleHelper();
    styleHelper.ChangeStyle('wcn_style_sheet',event.target.attributes.wcn_class.value, event.target.id.replace('wcn_',''), event.target.value);
} 




function jumpToSource()
{
    var source = getUrlParam('source');
    if (source != null)
    {
        var url = 'http://sharonne-design.com/wp-admin/post.php?post=' + source + '&action=edit';
        window.open (url,'_self',false)
    }
    else
    {
        alert('Saved');
    }
}














			