(function($) {
  $(document).ready(function(){
    const PATH = $(location).attr('pathname');
    $(`#navbarSupportedContent a[href^="${PATH}"]`).addClass('is-active');
  });
})(jQuery);