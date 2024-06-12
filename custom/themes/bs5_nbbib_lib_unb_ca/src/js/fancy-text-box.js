(function($) {
    'use strict';
  
    Drupal.behaviors.fancyTextBox = {
      attach: function (context, settings) {
        var exposedTextBox = $(".views-exposed-form input[type='text']");
        // On documeny ready.
        $(document).ready( function() {
          // Append empty class if empty.
          if ( !exposedTextBox.val() ) {
            exposedTextBox.addClass("empty");
          } 
          else {
            exposedTextBox.removeClass("empty");
          }
        });
        // Set empty class when leaving the box (blur).
        exposedTextBox.on('blur', function() {
          if ( !$(this).val() ) {
            $(this).addClass("empty");
          } 
          else {
            $(this).removeClass("empty");
          }        
        });
      },
    };
  })(jQuery);
  