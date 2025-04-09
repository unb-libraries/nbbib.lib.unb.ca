(function (Drupal, once) {
  Drupal.behaviors.customScroll = {
    attach: function (context, settings) {
      var banner = document.querySelector('header[role="banner"]');
      
      // Throttle function to improve performance
      function throttle(func, limit) {
        let lastFunc;
        let lastRan;
        return function () {
          const context = this;
          const args = arguments;
          if (!lastRan) {
            func.apply(context, args);
            lastRan = Date.now();
          } else {
            clearTimeout(lastFunc);
            lastFunc = setTimeout(function () {
              if ((Date.now() - lastRan) >= limit) {
                func.apply(context, args);
                lastRan = Date.now();
              }
            }, limit - (Date.now() - lastRan));
          }
        };
      }

      // Scroll event handler with throttling
      const handleScroll = throttle(function () {
        if (window.scrollY > 0) {
          if (!banner.classList.contains('scrolling')) {
            banner.classList.add('scrolling');
          }
        } else {
          banner.classList.remove('scrolling');
        }
      }, 100); // Adjust the limit (100ms) as needed

      // Load and resize event handler
      const handleLoadResize = function () {
        if (window.innerWidth < 992) {
          banner.classList.remove('sticky-top');
        } else {
          banner.classList.add('sticky-top');
        }
      };

      window.addEventListener('scroll', handleScroll);
      window.addEventListener('load', handleLoadResize);
      window.addEventListener('resize', handleLoadResize);
    }
  };
})(Drupal, once);