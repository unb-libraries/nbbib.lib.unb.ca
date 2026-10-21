(function ($) {
  $(document).ready(function() {
    // Randomize slide photo sizes.
    var mobile = window.matchMedia("(max-width: 991px)")
    var baseWidth = mobile.matches ? 46 : 56;
    var maxWidth = baseWidth * 4;
    $('.swiper-slide img').each(function() {
      var randomWidth = Math.floor((Math.random() * baseWidth * 1.5) + baseWidth * 2.5);
      this.style.setProperty('width', randomWidth + 'px', 'important');
    });
    // Align slides in a wave. Set single properties rather than the whole
    // style attribute so the width/spacing Swiper puts on slides survives.
    $('.swiper-slide').each(function(index) {
      // If odd, align center.
      if (index % 2) {
        this.style.setProperty('align-self', 'center', 'important');
      }
      // If multiple of 4, align bottom.
      else if (index % 4) {
        this.style.setProperty('align-self', 'end', 'important');
      }
      // Otherwise it's an even non-multiple of 4, align top.
      else {
        this.style.setProperty('align-self', 'start', 'important');
      }
    });
    // Fix the carousel height to the tallest a photo could ever be, so it
    // doesn't change with each page load's random sizes. Photos are capped by
    // the slide width when Swiper uses fixed slides (mobile).
    function fixHeight(swiper) {
      var slideWidth = swiper.params.slidesPerView === 'auto' ? maxWidth : swiper.slidesSizesGrid[0];
      var ratio = 0;
      $(swiper.wrapperEl).find('img').each(function() {
        ratio = Math.max(ratio, this.getAttribute('height') / this.getAttribute('width') || 0);
      });
      swiper.wrapperEl.style.minHeight = (Math.min(maxWidth, slideWidth) * ratio) + 'px';
    }
    // Swiper is initialized by a Drupal behavior, after document ready.
    setTimeout(function() {
      $('.swiper-slider').each(function() {
        var swiper = this.swiper;
        if (swiper) {
          // Slides are sized to their photos on desktop, so let Swiper
          // re-measure in case it initialized before the sizes above.
          swiper.update();
          fixHeight(swiper);
          swiper.on('resize', function() {
            fixHeight(swiper);
          });
        }
      });
    });
  });
})(jQuery);
