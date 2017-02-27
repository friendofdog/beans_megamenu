( function( $ ) {

    var megamenuBgImgArray = ( $.parseJSON( megamenubgimg ) );

    $( '#menu-group div' ).hover( function() {

        var hoverGroup = ( $( this ).prop( 'id' ) );

        if ( hoverGroup == 'menu-group-1' ) var hoverBgImg = 'url("' + megamenuBgImgArray[0] + '")';
        if ( hoverGroup == 'menu-group-2' ) var hoverBgImg = 'url("' + megamenuBgImgArray[1] + '")';
        if ( hoverGroup == 'menu-group-3' ) var hoverBgImg = 'url("' + megamenuBgImgArray[2] + '")';

        $( '#megamenu' ).css( 'background-image', hoverBgImg );

    }, function() {

        $( '#megamenu' ).css( 'background-image', 'none' );

    } );

} ( jQuery ) );