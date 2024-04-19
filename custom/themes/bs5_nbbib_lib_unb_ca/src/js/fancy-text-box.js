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
        // On clicking the facet soft-limit link.
        exposedTextBox.on('blur', function() {
          // Append fontawesome icon to facet soft-limit link.
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
  