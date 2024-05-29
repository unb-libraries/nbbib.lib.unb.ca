(function($) {
    'use strict';
  
    Drupal.behaviors.slideshow = {
      attach: function (context, settings) {
        $(document).ready( function() {
          const mySiema = new Siema({
            duration: 10000,
            loop: true,
            easing: 'linear',
            perPage: 6,
          });
          
          // listen for keydown event
          mySiema.next();0
          setInterval(() => mySiema.next(), 10000)
        });
      },
    };
  })(jQuery);
  