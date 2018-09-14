//https://gist.github.com/leolux/c794fc63d9c362013448
var SleepEditor = function(element, options) {
  
  var elem = $("<div>Editor</div>");
  

  // Public method - can be called from client code
  this.getElement = function() {
    return elem;
  };



};

    var WfeElement = function(c)
    {
       var frame = $("<div class='wfeElement'></div>");
       var beforeLine = $( "<div class='wfeElement vl'>" )
       var afterIcon = $( "<div class='wfeElement vl'></div><div class='wfeElement plus'>+</div>" )
       var content = c;
       var before = null;
       var after = null;
       var selectedCallback = null;
 

      this.render = function() {
        frame.empty();
        if(before === null)
          frame.append(beforeLine);
        frame.append(content.render())
        if(after === null)
          frame.append(afterIcon);

        return frame;
      };

      this.selected = function(callback)
      {
        selectedCallback = callback;
      };

      this.setBefore = function()
      {

      };

      this.addAfter = function(element)
      {
          after = element;
          element.setBefore(this);
          element.render().insertAfter(this.render())
      };

      content.render().click(() => 
      {
        selectedCallback(this);
      });

      afterIcon.on("drop",function(event){
        alert();
      });

      afterIcon.on("dragover", function(event) {
        event.preventDefault();  
        event.stopPropagation();
        $(this).addClass('dragging');
    });
    
    }


    var Sleep = function() {
  
      var elem = $("<div class='action'></div>");
      var editor = new SleepEditor();
      var time = "10";

      this.render = function()
      {
        return elem;
      }

      this.getEditor = function()
      {
        return editor.getElement();
      }
      
      this.getTime = function()
      {
        return time;
      }

      this.setTime = function(t)
      {
        time = t;
      }

      var updateTime = function()
      {
        elem.html(`Wait ${time} seconds`);
      }
      updateTime();

    };

    var Notify = function() {
  
      var elem = $("<div class='notify'>Notify</div>");

      this.render = function()
      {
        return elem;
      }

      this.getEditor = function()
      {
        return editor.getElement();
      }
      
     

    };
