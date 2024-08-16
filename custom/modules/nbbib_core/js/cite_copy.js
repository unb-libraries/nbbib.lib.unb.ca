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
      navigator.clipboard.writeText($('.views-field-bibliographic-citation > .field-content')
      .html()
      .toString()
      .replace(/<\/?[^>]+>/gi, '')
      .replace(/\n/g, ' ')
      .replace(/\s+/g, ' ')
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
})(jQuery);