(function($) {
  'use strict';

  // Adds Bootstrap theme compatible classes for active list items in alpha pager.
  Drupal.behaviors.contribAlphaPager = {
    attach: function (context, settings) {
      $(document).ready( function() {
        $(".view-display-id-attachment_1 ul").addClass(
          "pagination js-pager__items");
        $(".view-display-id-attachment_1 li").addClass(
          "pager__item");
        $(".view-display-id-attachment_1 li").has("a.is-active").addClass(
          "is-active active");
      });
    },
  };
})(jQuery);
