<?php
/*
   Plugin Name: Beans MegaMenu
   Plugin URI: http://wordpress.org/extend/plugins/beans-megamenu/
   Version: 0.1
   Author: friendofdog
   Description: Full-screen mega menu for Beans and UIKit
   Text Domain: beans-megamenu
   License: GPLv3
  */

$BeansMegamenu_minimalRequiredPhpVersion = '5.0';

/**
 * Check the PHP version and give a useful error message if the user's version is less than the required version
 * @return boolean true if version check passed. If false, triggers an error which WP will handle, by displaying
 * an error message on the Admin page
 */
function BeansMegamenu_noticePhpVersionWrong() {
    global $BeansMegamenu_minimalRequiredPhpVersion;
    echo '<div class="updated fade">' .
      __('Error: plugin "Beans MegaMenu" requires a newer version of PHP to be running.',  'beans-megamenu').
            '<br/>' . __('Minimal version of PHP required: ', 'beans-megamenu') . '<strong>' . $BeansMegamenu_minimalRequiredPhpVersion . '</strong>' .
            '<br/>' . __('Your server\'s PHP version: ', 'beans-megamenu') . '<strong>' . phpversion() . '</strong>' .
         '</div>';
}


function BeansMegamenu_PhpVersionCheck() {
    global $BeansMegamenu_minimalRequiredPhpVersion;
    if (version_compare(phpversion(), $BeansMegamenu_minimalRequiredPhpVersion) < 0) {
        add_action('admin_notices', 'BeansMegamenu_noticePhpVersionWrong');
        return false;
    }
    return true;
}


/**
 * Initialize internationalization (i18n) for this plugin.
 * References:
 *      http://codex.wordpress.org/I18n_for_WordPress_Developers
 *      http://www.wdmac.com/how-to-create-a-po-language-translation#more-631
 * @return void
 */
function BeansMegamenu_i18n_init() {
    $pluginDir = dirname(plugin_basename(__FILE__));
    load_plugin_textdomain('beans-megamenu', false, $pluginDir . '/languages/');
}


//////////////////////////////////
// Run initialization
/////////////////////////////////

// Initialize i18n
add_action('plugins_loadedi','BeansMegamenu_i18n_init');

// Run the version check.
// If it is successful, continue with initialization for this plugin
if (BeansMegamenu_PhpVersionCheck()) {
    // Only load and run the init function if we know PHP version can parse it
    include_once('beans-megamenu_init.php');
    BeansMegamenu_init(__FILE__);
}
