(function($) {
  $(document).ready(function(){
    const PATH = $(location).attr('pathname');
    $(`a[href="${PATH}"]`).addClass('is-active');
  });
})(jQuery);