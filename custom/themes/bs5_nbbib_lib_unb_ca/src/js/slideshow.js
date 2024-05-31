(function ($) {
  $(document).ready(function() {
    var owl = $('.owl-carousel');
    owl.owlCarousel({
      items: 6,
      slideBy: 'page',
      loop: true,
      autoplay: true,
      autoplayTimeout: 5000,
      autoplaySpeed: 5000,
      slideTransition: 'linear',
      dots: false,
      nav: false,
      rewind: false,
      responsive: {
        0: {
          items:1
        },
        576: {
          items:3
        },
        992: {
          items:5
        }
      }
    });
    owl.on('initialized.owl.carousel', function() {
      owl.trigger('play.owl.autoplay');
    });
  });
  $('#block-bs5-nbbib-lib-unb-ca-views-block-nbbib-slideshow-block-1').on('mouseover', function() {
    var owl = $('.owl-carousel');
    owl.trigger('stop.owl.autoplay');
  });
})(jQuery);
