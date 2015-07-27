<?php
/**
 * This file belongs to the YIT Plugin Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

/**
 * Main class
 *
 * @class   YITH_WC_Share_For_Discounts
 * @package Yithemes
 * @since   1.0.0
 * @author  Your Inspiration Themes
 */

if ( !class_exists( 'YITH_WC_Share_For_Discounts' ) ) {

    class YITH_WC_Share_For_Discounts {

        /**
         * Single instance of the class
         *
         * @var \YITH_WC_Share_For_Discounts
         * @since 1.0.0
         */
        protected static $instance;

        /**
         * Panel object
         *
         * @var     /Yit_Plugin_Panel object
         * @since   1.0.0
         * @see     plugin-fw/lib/yit-plugin-panel.php
         */
        protected $_panel = null;

        /**
         * @var $_premium string Premium tab template file name
         */
        protected $_premium = 'premium.php';

        /**
         * @var string Premium version landing link
         */
        protected $_premium_landing = 'http://yithemes.com/themes/plugins/yith-woocommerce-share-for-discounts/';

        /**
         * @var string Plugin official documentation
         */
        protected $_official_documentation = 'http://yithemes.com/docs-plugins/yith-woocommerce-share-for-discounts/';

        /**
         * @var string Yith WooCommerce Share For Discounts panel page
         */
        protected $_panel_page = 'yith-wc-share-for-discounts';

        /**
         * Returns single instance of the class
         *
         * @return \YITH_WC_Share_For_Discounts
         * @since 1.0.0
         */
        public static function get_instance() {

            if ( is_null( self::$instance ) ) {

                self::$instance = new self( $_REQUEST );

            }

            return self::$instance;

        }

        /**
         * Constructor
         *
         * @since   1.0.0
         * @return  mixed
         * @author  Alberto Ruggiero
         */
        public function __construct() {

            if ( !function_exists( 'WC' ) ) {
                return;
            }

            //Load plugin framework
            add_action( 'after_setup_theme', array( $this, 'plugin_fw_loader' ), 1 );
            add_filter( 'plugin_action_links_' . plugin_basename( YWSFD_DIR . '/' . basename( YWSFD_FILE ) ), array( $this, 'action_links' ) );
            add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 4 );
            add_action( 'admin_menu', array( $this, 'add_menu_page' ), 5 );
            add_action( 'yith_share_for_discounts_premium', array( $this, 'premium_tab' ) );
            $this->includes();

            if ( get_option( 'ywsfd_enable_plugin' ) == 'yes' ) {

                $this->session = new YWSFD_Session();

                YWSFD_Ajax();

                if ( is_admin() ) {


                    add_action( 'woocommerce_update_option', array( $this, 'check_active_options' ), 10, 1 );

                }
                else {

                    add_action( 'woocommerce_before_main_content', array( $this, 'show_ywsfd_product_page' ), 5 );
                    add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );
                    add_action( 'woocommerce_add_to_cart', array( $this, 'check_coupon' ), 10, 2 );

                }

                add_action( 'wp_login', array( $this, 'switch_to_logged_user' ) );

            }

        }

        /**
         * Files inclusion
         *
         * @since   1.0.0
         * @return  void
         * @author  Alberto Ruggiero
         */
        private function includes() {

            include_once( 'includes/class-ywsfd-ajax.php' );
            include_once( 'includes/class-ywsfd-session.php' );

        }

        /**
         * ADMIN FUNCTIONS
         */

        /**
         * Add a panel under YITH Plugins tab
         *
         * @since   1.0.0
         * @return  void
         * @author  Alberto Ruggiero
         * @use     /Yit_Plugin_Panel class
         * @see     plugin-fw/lib/yit-plugin-panel.php
         */
        public function add_menu_page() {

            if ( !empty( $this->_panel ) ) {
                return;
            }

            $admin_tabs = array();

            if ( defined( 'YWSFD_PREMIUM' ) ) {
                //$admin_tabs['premium-general'] = __( 'General Settings', 'ywsfd' );
            }
            else {
                $admin_tabs['general']         = __( 'General Settings', 'ywsfd' );
                //$admin_tabs['premium-landing'] = __( 'Premium Version', 'ywsfd' );
            }


            $args = array(
                'create_menu_page' => true,
                'parent_slug'      => '',
                'page_title'       => __( 'Share For Discounts', 'ywsfd' ),
                'menu_title'       => __( 'Share For Discounts', 'ywsfd' ),
                'capability'       => 'manage_options',
                'parent'           => '',
                'parent_page'      => 'yit_plugin_panel',
                'page'             => $this->_panel_page,
                'admin-tabs'       => $admin_tabs,
                'options-path'     => YWSFD_DIR . 'plugin-options'
            );

            $this->_panel = new YIT_Plugin_Panel_WooCommerce( $args );

        }

        /**
         * Check if active options have at least a social network selected
         *
         * @since   1.0.0
         *
         * @param   $option
         *
         * @return  void
         * @author  Alberto Ruggiero
         */
        public function check_active_options( $option ) {

            if ( 'ywsfd_enable_plugin' == $option['id'] && isset( $_POST[$option['id']] ) && '1' == $_POST[$option['id']] ) {

                $facebook = ( isset( $_POST['ywsfd_enable_facebook'] ) && '1' == $_POST['ywsfd_enable_facebook'] );
                $twitter  = ( isset( $_POST['ywsfd_enable_twitter'] ) && '1' == $_POST['ywsfd_enable_twitter'] );
                $google   = ( isset( $_POST['ywsfd_enable_google'] ) && '1' == $_POST['ywsfd_enable_google'] );

                if ( !$facebook && !$twitter && !$google ) :

                    ?>
                    <div class="error">
                        <p>
                            <?php _e( 'You need to select at least one social network', 'ywsfd' ); ?>
                        </p>
                    </div>
                <?php

                endif;

            }

            if ( 'ywsfd_enable_facebook' == $option['id'] && isset( $_POST[$option['id']] ) && '1' == $_POST[$option['id']] ) {

                if ( $_POST['ywsfd_appid_facebook'] == '' ) :

                    ?>
                    <div class="error">
                        <p>
                            <?php _e( 'You need to add a Facebook App ID', 'ywsfd' ); ?>
                        </p>
                    </div>
                <?php

                endif;

            }

        }

        /**
         * FRONTEND FUNCTIONS
         */

        /**
         * Initializes CSS and javascript
         *
         * @since   1.0.0
         * @return  void
         * @author  Alberto Ruggiero
         */
        public function frontend_scripts() {

            global $post;

            $params = array(
                'ajax_url'           => str_replace( array( 'https:', 'http:' ), '', admin_url( 'admin-ajax.php' ) ),
                'apply_coupon_nonce' => wp_create_nonce( 'apply-coupon' ),
                'post_id'            => isset( $post->ID ) ? $post->ID : '',
                'locale'             => get_locale(),
                'facebook'           => 'no',
                'twitter'            => 'no',
                'google'             => 'no'
            );

            if ( get_option( 'ywsfd_enable_facebook' ) == 'yes' && get_option( 'ywsfd_appid_facebook' ) != '' ) {

                $params['facebook']  = 'yes';
                $params['fb_app_id'] = get_option( 'ywsfd_appid_facebook' );

            }

            if ( get_option( 'ywsfd_enable_twitter' ) == 'yes' ) {

                $params['twitter'] = 'yes';

                wp_register_script( 'twittersdk', 'https://platform.twitter.com/widgets.js' );
                wp_enqueue_script( 'twittersdk' );

            }

            if ( get_option( 'ywsfd_enable_google' ) == 'yes' ) {

                $params['google'] = 'yes';

            }

            $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

            wp_register_style( 'font-awesome', YWSFD_ASSETS_URL . '/css/font-awesome' . $suffix . '.css', array(), '4.3.0' );
            wp_enqueue_style( 'font-awesome' );

            wp_enqueue_script( 'ywsfd-frontend', YWSFD_ASSETS_URL . '/js/ywsfd-frontend' . $suffix . '.js', array( 'jquery' ) );

            $template = get_option( 'ywsfd_template', '1' );
            wp_enqueue_style( 'ywsfd-frontend', YWSFD_ASSETS_URL . '/css/ywsfd-style-' . $template . '.css' );


            wp_localize_script( 'ywsfd-frontend', 'ywsfd', apply_filters( 'ywsfd_scripts_filter', $params ) );

        }

        /**
         * Get the position and show YWSFD in product page
         *
         * @since   1.0.0
         * @return  void
         * @author  Alberto Ruggiero
         */
        public function show_ywsfd_product_page() {

            $args = apply_filters( 'ywsfd_showing_position_product', array(
                'hook'     => 'single_product',
                'priority' => 25 ) );

            add_action( 'woocommerce_' . $args['hook'] . '_summary', array( $this, 'add_ywsfd_product' ), $args['priority'] );

        }

        /**
         * Add YWSFD to product page
         *
         * @since   1.0.0
         * @return  void
         * @author  Alberto Ruggiero
         */
        public function add_ywsfd_product() {

            if ( !$this->product_already_shared() && $this->check_social_active() ):?>
                <div id="YWSFD_wrapper">
                    <h2>
                        <?php echo apply_filters( 'ywsfd_title', __( 'Share the product and get 10% discount!', 'ywsfd' ) ); ?>
                    </h2>

                    <div class="ywsfd-social">
                        <?php

                        global $post;

                        $social_params = apply_filters( 'ywsfd_social_params', array(
                            'url'              => get_permalink( $post->id ),
                            'title'            => get_the_title( $post->id ),
                            'twitter_username' => get_option( 'ywsfd_user_twitter' ),
                            'facebook'         => get_option( 'ywsfd_enable_facebook' ),
                            'twitter'          => get_option( 'ywsfd_enable_twitter' ),
                            'google'           => get_option( 'ywsfd_enable_google' ),
                        ) );

                        include( YWSFD_TEMPLATE_PATH . '/frontend/social-buttons.php' );

                        apply_filters( 'ywsfd_social_buttons', '', $social_params );

                        ?>

                    </div>
                </div>
            <?php endif; ?>

            <?php if ( get_option( 'ywsfd_enable_facebook' ) == 'yes' ): ?>
                <div id="fb-root"></div>
            <?php endif;

        }

        public function check_social_active() {

            $socials = apply_filters( 'ywsfd_available_socials', array(
                'facebook',
                'twitter',
                'google'
            ) );

            $active = false;

            foreach ( $socials as $social ) {

                if ( get_option( 'ywsfd_enable_' . $social ) == 'yes' ) {

                    $active = true;

                }

            }

            return $active;

        }

        /**
         * Get current user data
         *
         * @since   1.0.0
         * @return  array
         * @author  Alberto Ruggiero
         */
        public function get_user_data() {

            $user_data = array(
                'nickname' => '',
                'email'    => '',
            );

            if ( is_user_logged_in() ) {

                global $current_user;
                get_currentuserinfo();

                $user_data['nickname'] = get_user_meta( $current_user->ID, 'nickname', true );
                $user_data['email']    = get_user_meta( $current_user->ID, 'billing_email', true );

            }
            else {

                $guest_id = $this->session->get( 'guest_id' );

                if ( empty( $guest_id ) ) {

                    $guest_id = uniqid( rand(), false );
                    $this->session->set( 'guest_id', $guest_id );

                }

                $user_data['nickname'] = __( 'Guest', 'ywsfd' );
                $user_data['email']    = $guest_id;

            }

            return $user_data;

        }

        /**
         * When re-assign coupon when user is logged
         *
         * @since   1.0.0
         * @return  void
         * @author  Alberto Ruggiero
         */
        public function switch_to_logged_user() {

            $guest_id = $this->session->get( 'guest_id' );

            if ( !empty( $guest_id ) ) {

                if ( isset( $_POST['username'] ) ) {

                    $user_id = $_POST['username'];

                }
                else {

                    $user_id = $_POST['log'];

                }

                $user = get_user_by( 'login', $user_id );

                $found_ids = array();
                $args      = array(
                    'post_type'   => 'shop_coupon',
                    'post_status' => 'publish',
                    'meta_query'  => array(
                        array(
                            'key'     => 'customer_email',
                            'value'   => $guest_id,
                            'compare' => '=',
                        ),
                    ),
                    'date_query'  => array(
                        array(
                            'year'  => date( 'Y' ),
                            'month' => date( 'm' ),
                            'day'   => date( 'd' ),
                        ),
                    ),
                );

                $query = new WP_Query( $args );

                if ( $query->have_posts() ) {

                    while ( $query->have_posts() ) {

                        $query->the_post();
                        $found_ids[] = $query->post->ID;

                    }

                }

                wp_reset_query();
                wp_reset_postdata();


                if ( !empty( $found_ids ) ) {

                    foreach ( $found_ids as $coupon_id ) {

                        update_post_meta( $coupon_id, 'customer_email', $user->user_email );

                    }

                }

                $this->session->destroy_session();
            }

        }

        /**
         * Creates a coupon with specific settings
         *
         * @since   1.0.0
         *
         * @param   $user_data
         * @param   $coupon_args
         *
         * @return  string
         * @author  Alberto Ruggiero
         */
        public function create_coupon( $user_data, $coupon_args = array() ) {

            $coupon_code = $user_data['nickname'] . '-' . current_time( 'YmdHis' );

            $coupon_data = array(
                'post_title'   => $coupon_code,
                'post_excerpt' => apply_filters( 'ywsfd_coupon_description', __( '10% off for the shared product', 'ywsfd' ) ),
                'post_content' => '',
                'post_status'  => 'publish',
                'post_author'  => 1,
                'post_type'    => 'shop_coupon'
            );

            $coupon_id     = wp_insert_post( $coupon_data );
            $coupon_option = apply_filters( 'ywsfd_coupon_options', $coupon_args );
            $expiry_date   = ( $coupon_option['expiry_days'] != '' ) ? date( 'Y-m-d', strtotime( '+' . $coupon_option['expiry_days'] . ' days' ) ) : '';

            update_post_meta( $coupon_id, 'discount_type', $coupon_option['discount_type'] );
            update_post_meta( $coupon_id, 'coupon_amount', $coupon_option['coupon_amount'] );
            update_post_meta( $coupon_id, 'free_shipping', ( isset( $coupon_option['free_shipping'] ) && $coupon_option['free_shipping'] != '' ? 'yes' : 'no' ) );
            update_post_meta( $coupon_id, 'expiry_date', $expiry_date );
            update_post_meta( $coupon_id, 'minimum_amount', ( isset( $coupon_option['minimum_amount'] ) ? $coupon_option['minimum_amount'] : '' ) );
            update_post_meta( $coupon_id, 'maximum_amount', ( isset( $coupon_option['maximum_amount'] ) ? $coupon_option['maximum_amount'] : '' ) );
            update_post_meta( $coupon_id, 'individual_use', ( isset( $coupon_option['individual_use'] ) && $coupon_option['individual_use'] != '' ? 'yes' : 'no' ) );
            update_post_meta( $coupon_id, 'exclude_sale_items', ( isset( $coupon_option['exclude_sale_items'] ) && $coupon_option['exclude_sale_items'] != '' ? 'yes' : 'no' ) );
            update_post_meta( $coupon_id, 'product_ids', ( isset( $coupon_option['product_ids'] ) ? $coupon_option['product_ids'] : '' ) );
            update_post_meta( $coupon_id, 'exclude_product_ids', ( isset( $coupon_option['exclude_product_ids'] ) ? $coupon_option['exclude_product_ids'] : '' ) );
            update_post_meta( $coupon_id, 'product_categories', ( isset( $coupon_option['product_categories'] ) ? $coupon_option['product_categories'] : '' ) );
            update_post_meta( $coupon_id, 'exclude_product_categories', ( isset( $coupon_option['exclude_product_categories'] ) ? $coupon_option['exclude_product_categories'] : '' ) );
            update_post_meta( $coupon_id, 'customer_email', $user_data['email'] );
            update_post_meta( $coupon_id, 'usage_limit', '1' );
            update_post_meta( $coupon_id, 'usage_limit_per_user', '1' );
            update_post_meta( $coupon_id, 'limit_usage_to_x_items', '' );

            return $coupon_code;

        }

        /**
         * Check if current product was shared from user and get coupon code
         *
         * @since   1.0.0
         *
         * @param   $product_id
         *
         * @return  boolean|string
         * @author  Alberto Ruggiero
         */
        public function product_already_shared( $product_id = false ) {

            if ( !$product_id ) {
                global $post;
                $product_id = $post->ID;

            }

            $result    = false;
            $user_data = $this->get_user_data();

            $args = array(
                'post_type'   => 'shop_coupon',
                'post_status' => 'publish',
                'meta_query'  => array(
                    'relation' => 'AND',
                    array(
                        'key'     => 'product_ids',
                        'value'   => $product_id,
                        'compare' => '=',
                    ),
                    array(
                        'key'     => 'customer_email',
                        'value'   => $user_data['email'],
                        'compare' => '=',
                    ),
                ),
                'date_query'  => array(
                    array(
                        'year'  => date( 'Y' ),
                        'month' => date( 'm' ),
                        'day'   => date( 'd' ),
                    ),
                ),
            );

            $query = new WP_Query( $args );

            if ( $query->have_posts() ) {

                while ( $query->have_posts() ) {

                    $query->the_post();
                    $result = $query->post->post_title;

                }

            }

            wp_reset_query();
            wp_reset_postdata();

            return $result;

        }

        /**
         * Check if the coupon for current product needs to be added after adding product to cart
         *
         * @since   1.0.0
         *
         * @param $cart_item_key
         * @param $product_id
         *
         * @return  void
         * @author  Alberto Ruggiero
         */
        public function check_coupon( $cart_item_key, $product_id ) {

            $coupon_id = $this->product_already_shared( $product_id );

            if ( $coupon_id && !in_array( strtolower( $coupon_id ), WC()->cart->applied_coupons ) ) {

                WC()->cart->add_discount( $coupon_id );

            }

        }

        /**
         * YITH FRAMEWORK
         */

        /**
         * Load plugin framework
         *
         * @since   1.0.0
         * @return  void
         * @author  Andrea Grillo
         * <andrea.grillo@yithemes.com>
         */
        public function plugin_fw_loader() {
            if ( !defined( 'YIT' ) || !defined( 'YIT_CORE_PLUGIN' ) ) {
                require_once( 'plugin-fw/yit-plugin.php' );
            }
        }

        /**
         * Premium Tab Template
         *
         * Load the premium tab template on admin page
         *
         * @since   1.0.0
         * @return  void
         * @author  Andrea Grillo
         * <andrea.grillo@yithemes.com>
         */
        public function premium_tab() {
            $premium_tab_template = YWSFD_TEMPLATE_PATH . '/admin/' . $this->_premium;
            if ( file_exists( $premium_tab_template ) ) {
                include_once( $premium_tab_template );
            }
        }

        /**
         * Get the premium landing uri
         *
         * @since   1.0.0
         * @return  string The premium landing link
         * @author  Andrea Grillo
         * <andrea.grillo@yithemes.com>
         */
        public function get_premium_landing_uri() {
            return defined( 'YITH_REFER_ID' ) ? $this->_premium_landing . '?refer_id=' . YITH_REFER_ID : $this->_premium_landing;
        }

        /**
         * Action Links
         *
         * add the action links to plugin admin page
         * @since   1.0.0
         *
         * @param   $links | links plugin array
         *
         * @return  mixed
         * @author  Andrea Grillo
         * <andrea.grillo@yithemes.com>
         * @use     plugin_action_links_{$plugin_file_name}
         */
        public function action_links( $links ) {

            $links[] = '<a href="' . admin_url( "admin.php?page={$this->_panel_page}" ) . '">' . __( 'Settings', 'ywsfd' ) . '</a>';

            if ( defined( 'YWSFD_FREE_INIT' ) ) {
                $links[] = '<a href="' . $this->get_premium_landing_uri() . '" target="_blank">' . __( 'Premium Version', 'ywsfd' ) . '</a>';
            }

            return $links;
        }

        /**
         * Plugin row meta
         *
         * add the action links to plugin admin page
         *
         * @since   1.0.0
         *
         * @param   $plugin_meta
         * @param   $plugin_file
         * @param   $plugin_data
         * @param   $status
         *
         * @return  Array
         * @author  Andrea Grillo
         * <andrea.grillo@yithemes.com>
         * @use     plugin_row_meta
         */
        public function plugin_row_meta( $plugin_meta, $plugin_file, $plugin_data, $status ) {
            if ( ( defined( 'YWSFD_INIT' ) && ( YWSFD_INIT == $plugin_file ) ) ||
                ( defined( 'YWSFD_FREE_INIT' ) && ( YWSFD_FREE_INIT == $plugin_file ) )
            ) {

                $plugin_meta[] = '<a href="' . $this->_official_documentation . '" target="_blank">' . __( 'Plugin Documentation', 'ywsfd' ) . '</a>';
            }

            return $plugin_meta;
        }

    }

}

if ( !function_exists( 'check_coupon_ajax' ) ) {

    /**
     * Check if the coupon for current product needs to be added after adding product to cart (AJAX)
     *
     * @since   1.0.0
     *
     * @param $product_id
     *
     * @return  void
     * @author  Alberto Ruggiero
     */
    function check_coupon_ajax( $product_id ) {

        if ( get_option( 'ywsfd_enable_plugin' ) == 'yes' ) {

            YITH_WSFD()->check_coupon( '', $product_id );

        }

    }

    add_action( 'woocommerce_ajax_added_to_cart', 'check_coupon_ajax' );

}