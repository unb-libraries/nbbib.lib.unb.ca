(function ($) {
  $(document).ready(function() {
    $('owl-carousel').owlCarousel({
      items: 6,
      slideBy: 'page',
      loop: true,
      autoplay: true,
      autoplayTimeout: 5000,
      autoplaySpeed: 5000,
      margin: 10,
      slideTransition: 'linear',
      dots: false,
      nav: false,
      autoplayHoverPause: true,
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
  });
})(jQuery);
