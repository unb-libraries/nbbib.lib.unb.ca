(function ($) {
  $(document).ready(function() {
    // Randomize slide photo sizes.
    var mobile = window.matchMedia("(max-width: 991px)")
    var baseWidth = mobile.matches ? 46 : 56;
    var variation = 5;
    photos = $('.swiper-slide img');
    photos.each( function() {
      randomWidth = Math.floor((Math.random() * baseWidth * 1.5) + baseWidth * 2.5);
      $(this).attr('style', `width: ${randomWidth}px !important;`);
    });
    // Align slides in a wave and set to fit content width.
    slides = $('.swiper-slide');
    slides.each( function(index) {
      imgWidth = $(this).find('img').width();
      // If odd, align center.
      if (index % 2) {
        $(this).attr('style', `align-self: center !important;`);
      }
      // If multiple of 4, align bottom.
      else if (index % 4) {
        $(this).attr('style', `align-self: end !important;`);
      }
      // Otherwise it's an even non-multiple of 4, align top.
      else {
        $(this).attr('style', `align-self: start !important;`);
      }
    });
  });
})(jQuery);