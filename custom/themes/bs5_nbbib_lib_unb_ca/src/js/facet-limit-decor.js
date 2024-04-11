(function($) {
    'use strict';
  
    Drupal.behaviors.facetLimitDecor = {
      attach: function (context, settings) {
        // On documeny ready.
        $(document).ready( function() {
          // Append fontawesome icon to facet soft-limit link.
          $(".facets-soft-limit-link").append('<i class="fa fa-caret-down"></i>');
        });
        // On clicking the facet soft-limit link.
        $(".facets-soft-limit-link").click( function() {
          // Append fontawesome icon to facet soft-limit link.
          $(this).append('<i class="fa fa-caret-down"></i>');
        });
      },
    };
  })(jQuery);
  