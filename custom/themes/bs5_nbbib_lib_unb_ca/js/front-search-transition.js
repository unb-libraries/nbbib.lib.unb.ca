(function($) {
    'use strict';
  
    // Add event responding to frontpage search label click.
    Drupal.behaviors.frontSearchTransition = {
      attach: function (context, settings) {
        // On page ready.
        $(document).ready( function() {
          // Input box click event removes active class from label, resetting.
          // Click event adds active class to label, triggering transition.
          $('label[for="edit-search-api-fulltext"]:not(.active)').on('click', function(e) {
            $('label[for="edit-search-api-fulltext"]').addClass('active');
            e.stopPropagation();
          });
          $('input#edit-search-api-fulltext').on('click', function() {
            //$('label[for="edit-search-api-fulltext"].active').removeClass('active');
          })
        });
      },
    };
  })(jQuery);