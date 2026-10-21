(function ($) {
  $(document).ready(function() {
    // Randomize slide photo sizes.
    var mobile = window.matchMedia("(max-width: 991px)")
    var baseWidth = mobile.matches ? 46 : 56;
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
    // Slides are sized to their photos on desktop, so let Swiper re-measure
    // if it initialized before the sizes above were applied.
    $('.swiper-slider').each(function() {
      if (this.swiper) {
        this.swiper.update();
      }
    });
  });
})(jQuery);
