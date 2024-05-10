/**
 * @file
 * Makes menu items active if the current URL starts with a matching path.
 */
(function($) {
  $(document).ready(function (e) {
    const PATH = $(location).attr('pathname');
    if (PATH != '/') {
      $(`#navbarSupportedContent a[href^="${PATH}"]`).addClass('is-active');
    }
  });
})(jQuery);