( function( $ ) {

    // prevent route on megamenu toggle
    $('.bmm-overlay-trigger').click( function(e) {
        e.preventDefault();
    } );

    // trigger click event for UIKit toggle
    $('.bmm-megamenu-cta-item a').hover( function() {
        $(this).trigger( 'click' );
    } );

} ( jQuery ) );