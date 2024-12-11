(function ($, Drupal, once) {
  Drupal.behaviors.customScroll = {
    attach: function (context, settings) {
      var banner = document.querySelector('header[role="banner"]');

      $(window).on('scroll', function() {
        if ($(window).scrollTop() > 0) {
          if (!(banner.classList.contains('scrolling'))) {
            banner.classList.add('scrolling');
          }
        }
        else {
          banner.classList.remove('scrolling');
        }
      });
      $(window).on('load', function() {
        if (window.innerWidth < 992) {
          banner.classList.remove('sticky-top');
        }
        else {
          banner.classList.add('sticky-top');

        }
      });
      $(window).on('resize', function() {
        if (window.innerWidth < 992) {
          banner.classList.remove('sticky-top');
        }
        else {
          banner.classList.add('sticky-top');

        }
      });
    }
  };
})(jQuery, Drupal, once);
  