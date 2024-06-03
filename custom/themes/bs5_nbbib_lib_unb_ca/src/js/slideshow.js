(function ($) {
  $(document).ready(function() {
    var owl = $('.owl-carousel');
    owl.owlCarousel({
      loop: true,
      autoplay: true,
      autoplayTimeout: 4000,
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
    owl.on('changed.owl.carousel', function () {
      $('.owl-item').attr('style', 'width: fit-content !important');
    });
    $('.owl-item').attr('style', 'width: fit-content !important');
    setTimeout(() => {
      var carouselData = $carousel.data();
      var carouselOptions = carouselData['owl.carousel'].options;
        carouselOptions.autoplayTimeout = 6000;
      $carousel.trigger('refresh.owl.carousel');
    }, 1000);  
  });
})(jQuery);
  