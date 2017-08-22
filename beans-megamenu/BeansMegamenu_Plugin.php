<?php

include_once('BeansMegamenu_LifeCycle.php');

class Walker_Megamenu_Top extends Walker {

    var $db_fields = array(
        'parent' => 'menu_item_parent', 
        'id'     => 'db_id' 
    );

    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

        // print_r($item->classes);

        $output .= sprintf( "\n
            <li id='menu-item-%s' class='%s bmm-megamenu-cta-item menu-item-%s'>
                <a data-uk-toggle='target: &#39;.bmm-megamenu-cta-%s-show&#39;' id='bmm-megamenu-cta-%s'>
                    <i class='uk-icon-large uk-margin-right %s'></i><span>%s</span>
                </a>
            </li>\n",
            $item->ID,
            implode( " ", $item->classes),
            $item->ID,
            $item->ID,
            $item->ID,
            $item->post_excerpt,
            $item->title
        );

    }

}

class Walker_Megamenu_Description extends Walker {

    var $db_fields = array(
        'parent' => 'menu_item_parent', 
        'id'     => 'db_id' 
    );

    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

        if ( ! $item->menu_item_parent && $item->post_content != ' ' ) {

            $output .= sprintf( "\n
                <li class='%s bmm-description bmm-megamenu-cta-%s-show uk-hidden'>
                    <p>%s</p>
                    <a href='%s'>%s</a>
                </li>\n",
                implode( " ", $item->classes),
                $item->ID,
                $item->post_content,
                $item->url,
                $item->title
            );

        } else if ( $item->menu_item_parent ) {

            $output .= sprintf( "\n
                <li class='%s bmm-megamenu-cta-%s-show uk-width-1-2 uk-float-left uk-hidden'>
                    <a href='%s'>%s</a>
                </li>\n",
                implode( " ", $item->classes),
                $item->menu_item_parent,
                $item->url,
                $item->title
            );

        } else {

            $output .= sprintf( "\n
                <li class='%s bmm-description bmm-megamenu-cta-%s-show uk-hidden'>
                    <a href='%s'>%s</a>
                </li>\n",
                implode( " ", $item->classes),
                $item->ID,
                $item->url,
                $item->title
            );

        }

    }

}

class BeansMegamenu_Plugin extends BeansMegamenu_LifeCycle {

    /**
     * See: http://plugin.michael-simpson.com/?page_id=31
     * @return array of option meta data.
     */
    public function getOptionMetaData() {
        //  http://plugin.michael-simpson.com/?page_id=31
        return array(
            //'_version' => array('Installed Version'), // Leave this one commented-out. Uncomment to test upgrades.
            'ATextInput' => array(__('Enter in some text', 'my-awesome-plugin')),
            'AmAwesome' => array(__('I like this awesome plugin', 'my-awesome-plugin'), 'false', 'true'),
            'CanDoSomething' => array(__('Which user role can do something', 'my-awesome-plugin'),
                                        'Administrator', 'Editor', 'Author', 'Contributor', 'Subscriber', 'Anyone')
        );
    }

//    protected function getOptionValueI18nString($optionValue) {
//        $i18nValue = parent::getOptionValueI18nString($optionValue);
//        return $i18nValue;
//    }

    protected function initOptions() {
        $options = $this->getOptionMetaData();
        if (!empty($options)) {
            foreach ($options as $key => $arr) {
                if (is_array($arr) && count($arr > 1)) {
                    $this->addOption($key, $arr[1]);
                }
            }
        }
    }

    public function getPluginDisplayName() {
        return 'Beans MegaMenu';
    }

    protected function getMainPluginFileName() {
        return 'beans-megamenu.php';
    }

    /**
     * See: http://plugin.michael-simpson.com/?page_id=101
     * Called by install() to create any database tables if needed.
     * Best Practice:
     * (1) Prefix all table names with $wpdb->prefix
     * (2) make table names lower case only
     * @return void
     */
    protected function installDatabaseTables() {
        //        global $wpdb;
        //        $tableName = $this->prefixTableName('mytable');
        //        $wpdb->query("CREATE TABLE IF NOT EXISTS `$tableName` (
        //            `id` INTEGER NOT NULL");
    }

    /**
     * See: http://plugin.michael-simpson.com/?page_id=101
     * Drop plugin-created tables on uninstall.
     * @return void
     */
    protected function unInstallDatabaseTables() {
        //        global $wpdb;
        //        $tableName = $this->prefixTableName('mytable');
        //        $wpdb->query("DROP TABLE IF EXISTS `$tableName`");
    }


    /**
     * Perform actions when upgrading from version X to version Y
     * See: http://plugin.michael-simpson.com/?page_id=35
     * @return void
     */
    public function upgrade() {
    }

    public function addActionsAndFilters() {

        // Add options administration page
        // http://plugin.michael-simpson.com/?page_id=47
        add_action('admin_menu', array(&$this, 'addSettingsSubMenuPage'));

        // Example adding a script & style just for the options administration page
        // http://plugin.michael-simpson.com/?page_id=47
        //        if (strpos($_SERVER['REQUEST_URI'], $this->getSettingsSlug()) !== false) {
        //            wp_enqueue_script('my-script', plugins_url('/js/my-script.js', __FILE__));
        //            wp_enqueue_style('my-style', plugins_url('/css/my-style.css', __FILE__));
        //        }


        // Add Actions & Filters

        // set up document
        add_action( 'wp', array(&$this, 'bmm_document_setup') );

        // sticks menu to top
        add_action( 'beans_before_load_document', array(&$this, 'bmm_page_actions') );

        // register custom menus
        add_action( 'init', array(&$this, 'bmm_register_custom_menus') );

        // adds hamburger menu with static "MENU" text (need to make WPML-friendly)
        add_action( 'beans_menu[_navbar][_primary]_prepend_markup', array(&$this, 'bmm_megamenu_hamburger') );

        // replace mobile menu
        add_action( 'beans_primary_offcanvas_menu_append_markup', array(&$this, 'bmm_create_mobile_menu') );

        // load the megamenu
        add_action( 'beans_header_prepend_markup', array(&$this, 'bmm_load_megamenu') );

        // Adding scripts & styles to all pages
        // Examples:
        // wp_enqueue_script('jquery');

        // Beans assets
        // add_action( 'beans_uikit_enqueue_scripts', 'beans_child_enqueue_uikit_assets' );
        add_action( 'beans_uikit_enqueue_scripts', array(&$this, 'beans_child_enqueue_uikit_assets') );

        // Register short codes
        // http://plugin.michael-simpson.com/?page_id=39


        // Register AJAX hooks
        // http://plugin.michael-simpson.com/?page_id=41

    }

    public function bmm_document_setup() {

        beans_remove_attribute( 'beans_site_branding', 'class', 'uk-float-left' );
        beans_add_attribute( 'beans_site_branding', 'class', 'uk-float-right' );
        beans_remove_attribute( 'beans_primary_menu', 'class', 'uk-float-right' );
        beans_add_attribute( 'beans_primary_menu', 'class', 'uk-float-left' );
        beans_remove_output( 'beans_primary_offcanvas_menu' ); // get rid of default offcanvas menu
        beans_add_attribute( 'beans_menu_item', 'data-uk-dropdown', "{mode:'click'}" );

    }

    public function bmm_page_actions() {

        beans_add_attribute( 'beans_header', 'data-uk-sticky', 'top:0' ); // sticky menu

    }

    public function bmm_register_custom_menus() {

        register_nav_menu( 'mobile-menu', __( 'Mobile' ) );
        register_nav_menu( 'megamenu-cta', __( 'MegaMenu CTA' ) );
        register_nav_menu( 'megamenu-main', __( 'MegaMenu Main' ) );

    }

    public function bmm_megamenu_hamburger() {

        // this is <a> with preventDefault(); ideally it would be <span> but for some reason you have to click twice to trigger menu open

        ?>

        <li class="menu-item bmm-overlay-trigger" itemprop="name">
            <a href="" data-uk-toggle="{target: '#megamenu-overlay', animation: 'uk-animation-fade'}"><i class="uk-icon-large uk-icon-navicon"></i><span class="uk-h3 uk-margin-left">MENU</span></a>
        </li>

        <?php

    }

    public function bmm_create_mobile_menu() {

        wp_nav_menu( array(
            'theme_location' => 'mobile-menu',
            'fallback_cb' => 'beans_primary_menu_args',
            'container' => '',
            'beans_type' => 'sidenav'
        ) );

    }

    public function bmm_load_megamenu() {

        ?>

        <div id="megamenu-overlay" class="uk-overlay-panel uk-hidden bmm-megamenu">
            <div class="uk-grid">
                <div class="uk-width-2-5">
                    <?php wp_nav_menu( array( 
                        'menu' => 'Megamenu top',
                        'menu_class' => '',
                        'container' => 'nav',
                        'container_class' => 'uk-nav bmm-megamenu-cta',
                        'theme_location' => 'megamenu-cta',
                        'walker' => new Walker_Megamenu_Top(),
                        'depth' => 1,
                    ) ); ?>
                </div>
                <div class="uk-width-3-5">
                    <?php wp_nav_menu( array( 
                        'menu' => 'Megamenu top',
                        'menu_class' => '',
                        'container' => 'div',
                        'container_class' => 'bmm-megamenu-description',
                        'theme_location' => 'megamenu-cta',
                        'before' => '<div>',
                        'after' => '</div>',
                        'walker' => new Walker_Megamenu_Description(),
                    ) ); ?>
                </div>
            </div>
            <hr>
            <?php wp_nav_menu( array( 
                'menu' => 'Megamenu main',
                'menu_class' => '',
                'container' => 'nav',
                'container_class' => 'uk-nav bmm-megamenu-main',
                'theme_location' => 'megamenu-main',
            ) ); ?>
        </div>

        <?php

    }

    public function beans_child_enqueue_uikit_assets() {

        beans_compiler_add_fragment( 'uikit', plugins_url('/less/beans-megamenu.less', __FILE__ ), 'less' ); // NOT WORKING
        beans_uikit_enqueue_components( array( 'overlay', 'toggle', 'animation' ) ); // all good
        beans_uikit_enqueue_components( array( 'sticky' ), 'add-ons' ); // all good
        wp_enqueue_script( 'beans-megamenu', plugins_url('/js/beans-megamenu.js', __FILE__ ), array('jquery'), '', true ); // all good

    }

}
