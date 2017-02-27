<?php

/* Mega menu
 ========================================================================== */

// enqueue js
add_action( 'wp_enqueue_scripts', 'bmm_enqueue_script' );

function bmm_enqueue_script() {

    $menu_image_array = array(
        '../wp-content/uploads/asian-girl-glasses-right.jpg',
        '../wp-content/uploads/white-man-glasses-right.jpg',
        '../wp-content/uploads/black-woman-glasses-right.jpg'
    );

    foreach ( $menu_image_array as &$menu_image ) {

        $menu_image = beans_edit_image( $menu_image, array(
            'resize' => array( 500, 500, array( 'right', 'center' ) )
        ) );

    }

    unset( $menu_image ); // break the reference with the last element

    wp_register_script( 'bmm_script', get_stylesheet_directory_uri() . '/beans_megamenu.js', array(), '', true );
    wp_localize_script( 'bmm_script', 'megamenubgimg', json_encode( $menu_image_array ) );
    wp_enqueue_script( 'bmm_script' );

}

// Setup document fragements, markups and attributes
beans_add_smart_action( 'wp', 'bmm_remove_float' );

function bmm_remove_float() {

    beans_remove_attribute( 'beans_site_branding', 'class', 'uk-float-left' );
    beans_add_attribute( 'beans_site_branding', 'class', 'uk-float-right' );
    beans_remove_attribute( 'beans_primary_menu', 'class', 'uk-float-right' );
    beans_add_attribute( 'beans_primary_menu', 'class', 'uk-float-left' );

}

// Flag menu as having child
add_filter( 'wp_nav_menu_objects', 'bmm_add_menu_children_flag' );

function bmm_add_menu_children_flag( $menu_items ) {

    $menu_item = wp_filter_object_list( $menu_items, array( 'ID' => 38 ) );

    // Add children flag for the targeted menu item.
    if ( ! empty( $menu_item ) ) {

        $key = array_keys( $menu_item );
        $menu_items[ $key[0] ]->classes[] = 'menu-item-has-children';

    }

    return $menu_items;

}

// Register custom menus
add_action( 'init', 'bmm_register_custom_menus' );

function bmm_register_custom_menus() {

    register_nav_menu( 'mobile-menu', __( 'Mobile' ) );
    register_nav_menu( 'menu-panel-1', __( 'Megamenu panel 1' ) );
    register_nav_menu( 'menu-panel-2', __( 'Megamenu panel 2' ) );
    register_nav_menu( 'menu-panel-3', __( 'Megamenu panel 3' ) );

}

// Add content for dropdown
add_action( 'beans_menu_item_link[_38]_after_markup', 'bmm_add_dropdown_menu' );

function bmm_add_dropdown_menu() {

    ?>

    <div id="megamenu" class="uk-dropdown uk-dropdown-width-3 uk-dropdown-bottom" style="border: none; border-radius: 0; background-repeat: no-repeat; background-position-x: right; background-position-y: center; ">
        <div class="uk-grid uk-dropdown-grid" style="padding: 10px;">
            <div id="menu-group" class="uk-width-1-2">
                <?php wp_nav_menu( array( 
                    'menu' => 'Menu I',
                    'menu_class' => 'uk-nav uk-nav-dropdown uk-panel',
                    'container' => 'div',
                    'container_id' => 'menu-group-1',
                    'theme_location' => 'menu-panel-1',
                    'beans_type' => 'dropdown'
                ) ); ?>
                <?php wp_nav_menu( array( 
                    'menu' => 'Menu II',
                    'menu_class' => 'uk-nav uk-nav-dropdown uk-panel',
                    'container' => 'div',
                    'container_id' => 'menu-group-2',
                    'theme_location' => 'menu-panel-2',
                    'beans_type' => 'dropdown'
                ) ); ?>
                <?php wp_nav_menu( array( 
                    'menu' => 'Menu III',
                    'menu_class' => 'uk-nav uk-nav-dropdown uk-panel',
                    'container' => 'div',
                    'container_id' => 'menu-group-3',
                    'theme_location' => 'menu-panel-3',
                    'beans_type' => 'dropdown'
                ) ); ?>
            </div>
            <!-- <div id="menu-hover-bgimg" class="uk-width-1-2"> -->
            <div class="uk-width-1-2">
            </div>
        </div>
    </div>

    <?php

}

// Add header and divider for panel 1.
function bmm_add_header_divider_panel_1() {

    ?>

    <li class="uk-nav-header">Header 1</li>
    <li class="uk-nav-divider"></li>

    <?php

}

add_action( 'beans_menu_item[_25]_before_markup', 'bmm_add_header_divider_panel_1' );

// Add header and divider for panel 2.
function bmm_add_header_divider_panel_2() {

    ?>

    <li class="uk-nav-header">Header 2</li>
    <li class="uk-nav-divider"></li>

    <?php

}

add_action( 'beans_menu_item[_29]_before_markup', 'bmm_add_header_divider_panel_2' );

// Add header and divider for panel 3.
function bmm_add_header_divider_panel_3() {

    ?>

    <li class="uk-nav-header">Header 3</li>
    <li class="uk-nav-divider"></li>

    <?php

}

add_action( 'beans_menu_item[_33]_before_markup', 'bmm_add_header_divider_panel_3' );

// get rid of default offcanvas menu...
beans_remove_output( 'beans_primary_offcanvas_menu' );

// ... and replace with mobile menu
function bmm_create_mobile_menu() {

    wp_nav_menu( array(
        'theme_location' => 'mobile-menu',
        'fallback_cb' => 'beans_primary_menu_args',
        'container' => '',
        'beans_type' => 'sidenav' // This is giving the sidenav menu style for the sake of the example.
    ) );

}

add_action( 'beans_primary_offcanvas_menu_append_markup', 'bmm_create_mobile_menu' );