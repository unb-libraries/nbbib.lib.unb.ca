(function($) {
  'use strict';

  Drupal.behaviors.contribAlphaPager = {
    attach: function (context, settings) {
      $(document).ready( function() {
        var url = window.location.href;
        var regex = /sort-initial=(.)/;
        var param = regex.exec(url);
        var letter = param[1];
        var target = ".initial-" + letter;
        $(target).addClass("is-active active");
      });
    },
  };
})(jQuery);
