/**
 * @file
 * Copies a reference's citation on button press.
 */
(function($) {
  $(document).ready(function (e) {
    // Transcription copy button.
    $('#cite').click(function() {
      // Copy html, convert to string,remove tags, replace breaks with spaces.
      navigator.clipboard.writeText($('.views-field-bibliographic-citation > .field-content').html().toString().replace(/<\/?[^>]+>/gi, '').replace(/\n/g, " ")).then(
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
    $('.ui-dialog-titlebar-close').click(function(e) {
      // Prevent default and reload on window close to reset.
      e.preventDefault();
      location.reload(true);
    });
  });
})(jQuery);