<?php
/**
 * Plugin Name: YITH WooCommerce Share For Discounts
 * Plugin URI: http://yithemes.com/themes/plugins/yith-woocommerce-share-for-discounts/
 * Description: YITH WooCommerce Share For Discounts gives you the perfect tool to reward your users when they share the products they are going to purchase.
 * Author: YIThemes
 * Text Domain: ywsfd
 * Version: 1.0.1
 * Author URI: http://yithemes.com/
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

if ( !function_exists( 'is_plugin_active' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

function ywsfd_install_free_admin_notice() {
    ?>
    <div class="error">
        <p><?php _e( 'You can\'t activate the free version of YITH WooCommerce Share For Discounts while you are using the premium one.', 'ywsfd' ); ?></p>
    </div>
<?php
}

function ywsfd_install_woocommerce_admin_notice() {
    ?>
    <div class="error">
        <p><?php _e( 'YITH WooCommerce Share For Discounts is enabled but not effective. It requires WooCommerce in order to work.', 'ywsfd' ); ?></p>
    </div>
<?php
}

if ( !defined( 'YWSFD_VERSION' ) ) {
    define( 'YWSFD_VERSION', '1.0.1' );
}

if ( !defined( 'YWSFD_FREE_INIT' ) ) {
    define( 'YWSFD_FREE_INIT', plugin_basename( __FILE__ ) );
}

if ( !defined( 'YWSFD_FILE' ) ) {
    define( 'YWSFD_FILE', __FILE__ );
}

if ( !defined( 'YWSFD_DIR' ) ) {
    define( 'YWSFD_DIR', plugin_dir_path( __FILE__ ) );
}

if ( !defined( 'YWSFD_URL' ) ) {
    define( 'YWSFD_URL', plugins_url( '/', __FILE__ ) );
}

if ( !defined( 'YWSFD_ASSETS_URL' ) ) {
    define( 'YWSFD_ASSETS_URL', YWSFD_URL . 'assets' );
}

if ( !defined( 'YWSFD_TEMPLATE_PATH' ) ) {
    define( 'YWSFD_TEMPLATE_PATH', YWSFD_DIR . 'templates' );
}

function ywsfd_free_init() {

    /* Load text domain */
    load_plugin_textdomain( 'ywsfd', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

    /* === Global YITH WooCommerce Share For Discounts  === */
    YITH_WSFD();

}

add_action( 'ywsfd_init', 'ywsfd_free_init' );

function ywsfd_free_install() {

    if ( !function_exists( 'WC' ) ) {
        add_action( 'admin_notices', 'ywsfd_install_woocommerce_admin_notice' );
    }
    elseif ( defined( 'YWSFD_PREMIUM' ) ) {
        add_action( 'admin_notices', 'ywsfd_install_free_admin_notice' );
        deactivate_plugins( plugin_basename( __FILE__ ) );
    }
    else {
        do_action( 'ywsfd_init' );
    }

}

add_action( 'plugins_loaded', 'ywsfd_free_install', 11 );

/**
 * Init default plugin settings
 */
if ( !function_exists( 'yith_plugin_registration_hook' ) ) {
    require_once 'plugin-fw/yit-plugin-registration-hook.php';
}

register_activation_hook( __FILE__, 'yith_plugin_registration_hook' );

if ( !function_exists( 'YITH_WSFD' ) ) {

    /**
     * Unique access to instance of YITH_WC_Share_For_Discounts
     *
     * @since   1.0.0
     * @return  YITH_WC_Share_For_Discounts|YITH_WC_Share_For_Discounts_Premium
     * @author  Alberto Ruggiero
     */
    function YITH_WSFD() {

        // Load required classes and functions
        require_once( YWSFD_DIR . 'class.yith-wc-share-for-discounts.php' );

        if ( defined( 'YWSFD_PREMIUM' ) && file_exists( YWSFD_DIR . 'class.yith-wc-share-for-discounts-premium.php' ) ) {


            require_once( YWSFD_DIR . 'class.yith-wc-share-for-discounts-premium.php' );
            return YITH_WC_Share_For_Discounts_Premium::get_instance();
        }

        return YITH_WC_Share_For_Discounts::get_instance();

    }

}