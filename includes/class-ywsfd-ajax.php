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
    exit; // Exit if accessed directly
}

if ( !class_exists( 'YWSFD_Ajax' ) ) {

    /**
     * Implements AJAX for YWSFD plugin
     *
     * @class   YWSFD_Ajax
     * @package Yithemes
     * @since   1.0.0
     * @author  Your Inspiration Themes
     *
     */
    class YWSFD_Ajax {

        /**
         * Single instance of the class
         *
         * @var \YWSFD_Ajax
         * @since 1.0.0
         */
        protected static $instance;

        /**
         * Returns single instance of the class
         *
         * @return \YWSFD_Ajax
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

            add_action( 'wp_ajax_ywsfd_get_coupon', array( $this, 'get_coupon' ) );
            add_action( 'wp_ajax_nopriv_ywsfd_get_coupon', array( $this, 'get_coupon' ) );

        }

        /**
         * Get a coupon
         *
         * @since   1.0.0
         * @return  void
         * @author  Alberto Ruggiero
         */
        public function get_coupon() {

            ob_start();

            $response = array();

            try {

                $user_data = YITH_WSFD()->get_user_data();

                $coupon_args = array(
                    'discount_type' => 'percent_product',
                    'coupon_amount' => 10,
                    'expiry_days'   => 1,
                    'product_ids'   => $_POST{'post_id'},
                );

                $response['status'] = 'success';
                $response['coupon'] = YITH_WSFD()->create_coupon( $user_data, $coupon_args );

            } catch ( Exception $e ) {

                $response['status'] = 'fail';
                $response['error']  = $e->getMessage();

            }

            wp_send_json( $response );

        }

    }

    /**
     * Unique access to instance of YWSFD_Ajax class
     *
     * @return \YWSFD_Ajax
     */
    function YWSFD_Ajax() {

        return YWSFD_Ajax::get_instance();

    }

}