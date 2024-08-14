/**
 * @file
 * Copies a reference's citation on button press.
 */
(function($) {
  $(document).ready(function (e) {
    // Transcription copy button.
    $('#cite').click(function() {
      navigator.clipboard.writeText($('.views-field-bibliographic-citation > .field-content').html().toString().replace(/<\/?[^>]+>/gi, '')).then(
        function() {
          // Clipboard successfully set.
          window.alert('Citation copied to clipboard') 
        }, 
        function() {
          // Clipboard write failed.
          window.alert('ERROR: Clipboard API unsupported by browser')
        }
      );
    });
  });
})(jQuery);