(function ($) {
  $(document).ready(function() {
    var owl = $('.owl-carousel');
    owl.owlCarousel({
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
    slides = $('.owl-item img');
    slides.each( function() {
      randomWidth = Math.floor(Math.random() * 50) + 200;
      $(this).attr('style', `width: ${randomWidth}px !important;`);
    });
    owl.on('changed.owl.carousel', function () {
      $('.owl-item').attr('style', 'width: fit-content !important');
    });
    $('.owl-item').attr('style', 'width: fit-content !important');
    owl.autoplayTimeout = 0;
    owl.trigger('play.autoplay.owl');
    owl.autoplayTimeout = 2680;
  });
})(jQuery);