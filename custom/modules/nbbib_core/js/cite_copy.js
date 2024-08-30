/**
 * @file
 * Copies a reference's citation on button press.
 */
(function($, navigator, once) {
  'use strict';

  Drupal.behaviors.citeCopy = {
    attach: function (context, settings) {
      // On document ready.
      once('citeCopy', 'html').forEach(function (element) {
        $('html').on('dialog:aftercreate', function (dialog, element, settings) {
          // Transcription copy button.
          $('#cite').keypress(function(e) {
            // Copy html, convert to string,remove tags, replace breaks with spaces.
            navigator.clipboard.writeText($('#drupal-modal .views-field > .field-content')
            .html()
            .toString()
            .replace(/<\/?[^>]+>/gi, '')
            .replace(/\n/g, ' ')
            .replace(/\s+/g, ' ')
            .replace('&amp;', '&')
            .trim()).then(
              function() {
                // Clipboard successfully set.
                $('#cite-success').removeClass('visually-hidden');
              }, 
              function() {
                // Clipboard write failed.
                $('#cite-error').removeClass('visually-hidden');
              }
            );
        });
          $('#cite').click(function() {
            // Copy html, convert to string,remove tags, replace breaks with spaces.
            navigator.clipboard.writeText($('#drupal-modal .views-field > .field-content')
            .html()
            .toString()
            .replace(/<\/?[^>]+>/gi, '')
            .replace(/\n/g, ' ')
            .replace(/\s+/g, ' ')
            .replace('&amp;', '&')
            .trim()).then(
              function() {
                // Clipboard successfully set.
                $('#cite-success').removeClass('visually-hidden');
              }, 
              function() {
                // Clipboard write failed.
                $('#cite-error').removeClass('visually-hidden');
              }
            );
          });
        });
      });
    }
  }
}(jQuery, navigator, once));