(function ($) {
  $(document).ready(function() {
    var owl = $('.owl-carousel');
    owl.owlCarousel({
      items: 6,
      loop: true,
      autoplay: true,
      autoplayTimeout: 5000,
      autoplaySpeed: 5000,
      margin: 10,
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
          items:5
        }
      }
    });
    $('#block-bs5-nbbib-lib-unb-ca-views-block-nbbib-slideshow-block-1').on('mouseover', function() {
      owl.trigger('stop.owl.autoplay');
    });  
    $('#block-bs5-nbbib-lib-unb-ca-views-block-nbbib-slideshow-block-1').on('mouseout', function() {
      owl.trigger('play.owl.autoplay');
    });  
  });
})(jQuery);
  