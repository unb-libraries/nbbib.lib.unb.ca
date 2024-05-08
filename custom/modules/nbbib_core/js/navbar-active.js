(function($) {
  $(document).ready(function(){
    const PATH = $(location).attr('pathname');
    if (PATH != '/') {
      $(`#navbarSupportedContent a[href^="${PATH}"]`).addClass('is-active');
    }
  });
})(jQuery);