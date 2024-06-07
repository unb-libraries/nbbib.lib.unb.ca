(function ($) {
  $(document).ready(function() {
    // Initial carousel instancing and setup.
    var owl = $('.owl-carousel');
    owl.owlCarousel({
      items: 12,
      loop: true,
      autoplay: true,
      autoplayTimeout: 2680,
      autoplaySpeed: 4000,
      autoplayHoverPause: true,
      slideTransition: 'linear',
      dots: false,
      nav: false,
      responsive: {
        0: {
          items:1
        },
        576: {
          items:3
        },
        992: {
          items:6
        }
      },
    });
    // Randomize slide photo sizes.
    photos = $('.owl-item img');
    photos.each( function() {
      randomWidth = Math.floor(Math.random() * 70) + 180;
      $(this).attr('style', `width: ${randomWidth}px !important;`);
    });
    // Align slides in a wave and set to fit content width.
    slides = $('.owl-item');
    slides.each( function(index) {
      // If odd, align center.
      if (index % 2) {
        $(this).attr('style', 'align-items: center !important; width: fit-content !important;');
      }
      // If multiple of 4, align bottom.
      else if (index % 4) {
        $(this).attr('style', 'align-items: end !important; width: fit-content !important;');
      }
      // Otherwise it's an even non-multiple of 4, align top.
      else {
        $(this).attr('style', 'align-items: start !important; width: fit-content !important');
      }
    });
    // Start autoplay ASAP.
    owl.autoplayTimeout = 0;
    owl.trigger('play.autoplay.owl');
    owl.autoplayTimeout = 2680;
  });
})(jQuery);