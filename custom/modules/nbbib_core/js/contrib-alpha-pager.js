(function($) {
  'use strict';

  // Adds Bootstrap theme compatible classes for active list items in alpha pager.
  Drupal.behaviors.contribAlphaPager = {
    attach: function (context, settings) {
      $(document).ready( function() {

        // Create extra list item to display reset contributor list.
        var resetLi = $("<li></li>");

        if (!$(".view-display-id-attachment_1 li a.is-active").length) {
          resetLi.addClass("is-active active");
        }

        $("<a>", {
          text: "All",
          href: "/contributors/all",
        }).appendTo(resetLi);

        // Prepend new reset item.
        $(".view-display-id-attachment_1 ul").prepend(resetLi);

        // Configure classes to macth bootstrap4 pagination.
        $(".view-display-id-attachment_1 .item-list").addClass(
          "pager");
        $(".view-display-id-attachment_1 .item-list").removeClass(
          "item-list");
        $(".view-display-id-attachment_1 ul").addClass(
          "pagination js-pager__items");
        $(".view-display-id-attachment_1 li").addClass(
          "page-item");
        $(".view-display-id-attachment_1 li a").addClass(
          "page-link");
        $(".view-display-id-attachment_1 li").has("a.is-active").addClass(
          "is-active active");
      });
    },
  };
})(jQuery);
