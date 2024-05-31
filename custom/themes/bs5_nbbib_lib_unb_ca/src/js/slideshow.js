(function ($) {
  $(document).ready(function() {
    var owl = $('.owl-carousel');
    owl.owlCarousel({
      loop: true,
      autoplay: true,
      autoplayTimeout: 10000,
      autoplaySpeed: 10000,
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
    owl.on('changed.owl.carousel', function () {
      $('.owl-item').attr('style', 'width: fit-content !important');
    });
    $('.owl-item').attr('style', 'width: fit-content !important');
  });
})(jQuery);
  