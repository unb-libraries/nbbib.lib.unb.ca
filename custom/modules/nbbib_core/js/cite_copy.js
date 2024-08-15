/**
 * @file
 * Copies a reference's citation on button press.
 */
(function($) {
  'use strict';
  $(window).on('dialog:aftercreate', function (dialog, $element, settings) {
    // Transcription copy button.
    $('#cite').click(function() {
      // Copy html, convert to string,remove tags, replace breaks with spaces.
      navigator.clipboard.writeText($('.views-field-bibliographic-citation > .field-content').html().toString().replace(/<\/?[^>]+>/gi, '').replace(/\n/g, " ")).then(
        function() {
          // Clipboard successfully set.
          $('#cite-success').removeClass('d-none');
        }, 
        function() {
          // Clipboard write failed.
          $('#cite-error').removeClass('d-none');
        }
  );
    });
    $('.ui-dialog-titlebar-close').click(function(e) {
      // Prevent default and reload on window close to reset.
      e.preventDefault();
      location.reload(true);
    });
  });
})(jQuery);

/*
(function ($, Drupal) {
  Drupal.behaviors.citeCopy = {
    attach: function(context) {

      $(window).on('dialog:aftercreate', function(dialog, $element, settings) {
        // Transcription copy button.
        $('#cite').click(function() {
          // Copy html, convert to string,remove tags, replace breaks with spaces.
          navigator.clipboard.writeText($('.views-field-bibliographic-citation > .field-content').html().toString().replace(/<\/?[^>]+>/gi, '').replace(/\n/g, " ")).then(
            function() {
              // Clipboard successfully set.
              $('#cite-success').removeClass('d-none');
            }, 
            function() {
              // Clipboard write failed.
              $('#cite-error').removeClass('d-none');
            }
          );
        });
      });
    }
  };
})(jQuery, Drupal);
*/