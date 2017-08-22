( function( $ ) {

    // prevent route on megamenu toggle
    $('.bmm-overlay-trigger').click( function(e) {
        e.preventDefault();
    } );

    // adds uk-hidden to most recently opened megamenu item
    var currentMegaMenuItem = '';

    $('.bmm-megamenu-cta-item a').click( function(e) {
        e.preventDefault();
        $( currentMegaMenuItem ).addClass( 'uk-hidden' );
        currentMegaMenuItem = '.' + this.id + '-show';
    } );

    // trigger click event for UIKit toggle
    $('.bmm-megamenu-cta-item a').hover( function() {
        $(this).trigger( 'click' );
    } );

} ( jQuery ) );