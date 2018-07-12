(function( $ ) {
 
    // Add Color Picker to all inputs that have 'color-field' class
    $(function() {
        $('.wcn-color-picker').wpColorPicker();

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