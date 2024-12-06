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
    }
  };
})(jQuery, Drupal, once);
  