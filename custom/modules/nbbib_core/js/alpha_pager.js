(function($) {
  'use strict';
  // Adds Bootstrap theme compatible classes for active list items in alpha pager.
  $(document).ready( function() {
    // Preserve is-active class in menu when alpha facet is engaged.
    var url = window.location.href;
    // Create extra list item to display pager reset option.
    var resetLi = $("<li></li>");

    if (!$(".view-display-id-attachment_1 li a.is-active").length) {
      resetLi.addClass("is-active active");
    }
    
    // Add reset option to pager and is-active to "Contributors" if URL includes "/Contributors/".
    if (url.includes("/contributors/")) {
      $(".region-nav-main .nav-link[title='Contributors']").addClass('is-active');
      $("<a>", {
        text: "All",
        href: "/contributors/all",
      }).appendTo(resetLi);
    }
    // Add is-active to "Topics" if URL includes "/Topics/".
    if (url.includes("/topics/")) {
      $(".region-nav-main .nav-link[title='Topics']").addClass('is-active');
      $("<a>", {
        text: "All",
        href: "/topics/all",
      }).appendTo(resetLi);
    }

    // Prepend new reset item.
    $(".view-display-id-attachment_1 ul").prepend(resetLi);

    // Configure classes to macth bootstrap5 pagination.
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
})(jQuery);
