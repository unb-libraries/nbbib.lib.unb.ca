/**
 * @file
 * Adds complete listing links for select facets reaching hard limit, where appropriate.
 */
(function($) {
    $(document).ready(function (e) {
        const base = document.location;

        // Contributors facet.
        const con_facet_block = '.block-facet-blockcontributor-s-';
        const con_listing_url = (new URL("/contributors/all", base)).href;

        // Topics facet.
        const top_facet_block = '.block-facet-blocktopic-names-string';
        const top_listing_url = (new URL("/topics/all", base)).href; // TO DO.

        const con_facet_block_child = top_facet_block_child = '.facets-widget-checkbox';
        const con_facet_item = top_facet_item = '.facet-item';
        const con_facet_hard_limit = top_facet_hard_limit = 50;

        // Check & generate 'complete' link for known hard-limited facets.
        if( $(con_facet_block + ' ' + con_facet_item).length === con_facet_hard_limit) {
            generateFacetLink(con_facet_block, con_facet_block_child, con_listing_url);
        }

        if( $(top_facet_block + ' ' + top_facet_item).length === top_facet_hard_limit) {
            generateFacetLink(top_facet_block, top_facet_block_child, top_listing_url);
        }
    });
})(jQuery);

/**
 * Add the markup for a link to the corresponding complete listing page of a hard-limited facet.
 *
 * @param facet_block_selector
 *   The facet block parent selector.
 * @param facet_block_child_selector
 *   The facet widget child selector.
 * @param url
 *   The url of the page listing of complete facet terms.
 * @param $
 *   jQuery HTMLElement.
 */
function generateFacetLink(facet_block_selector, facet_block_child_selector, url) {
    this.$ = jQuery;
    let widget = $(facet_block_selector + ' ' + facet_block_child_selector);
    $(widget).append(
        '<a href="' + url +
        '" class="badge fw-normal lh-lg text-bg-light facet-complete-link float-end">' +
        'Complete list<i class="fa-solid fa-angles-right fa-2xs"></i></a>'
    );
}
