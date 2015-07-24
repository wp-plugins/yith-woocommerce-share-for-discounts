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

return array(
    'general' => array(
        'ywsfd_main_section_title'   => array(
            'name' => __( 'Share For Discounts settings', 'ywsfd' ),
            'type' => 'title',
            'desc' => '',
            'id'   => 'ywsfd_main_section_title',
        ),
        'ywsfd_enable_plugin'        => array(
            'name'    => __( 'Enable YITH WooCommerce Share For Discounts', 'ywsfd' ),
            'type'    => 'checkbox',
            'desc'    => '',
            'id'      => 'ywsfd_enable_plugin',
            'default' => 'yes',
        ),
        'ywsfd_main_section_end'     => array(
            'type' => 'sectionend',
            'id'   => 'ywsfd_main_section_end'
        ),


        'ywsfd_section_facebook'     => array(
            'name' => __( 'Facebook', 'ywsfd' ),
            'type' => 'title',
            'desc' => '',
            'id'   => 'ywsfd_section_facebook',
        ),
        'ywsfd_enable_facebook'      => array(
            'name'    => __( 'Enable Facebook sharing', 'ywsfd' ),
            'type'    => 'checkbox',
            'desc'    => '',
            'id'      => 'ywsfd_enable_facebook',
            'default' => 'no',
        ),
        'ywsfd_appid_facebook'       => array(
            'name'    => __( 'Facebook App ID', 'ywsfd' ),
            'type'    => 'text',
            'desc'    => '',
            'id'      => 'ywsfd_appid_facebook',
            'default' => '',
        ),
        'ywsfd_section_end_facebook' => array(
            'type' => 'sectionend',
            'id'   => 'ywsfd_section_end_facebook'
        ),


        'ywsfd_section_twitter'      => array(
            'name' => __( 'Twitter', 'ywsfd' ),
            'type' => 'title',
            'desc' => '',
            'id'   => 'ywsfd_section_twitter',
        ),
        'ywsfd_enable_twitter'       => array(
            'name'    => __( 'Enable Twitter sharing', 'ywsfd' ),
            'type'    => 'checkbox',
            'desc'    => '',
            'id'      => 'ywsfd_enable_twitter',
            'default' => 'no',
        ),
        'ywsfd_user_twitter'         => array(
            'name'    => __( 'Twitter username', 'ywsfd' ),
            'type'    => 'text',
            'desc'    => __( 'Set this option if you want to include "via @YourUsername" to your tweets', 'ywsfd' ),
            'id'      => 'ywsfd_user_twitter',
            'default' => '',
        ),
        'ywsfd_section_end_twitter'  => array(
            'type' => 'sectionend',
            'id'   => 'ywsfd_section_end_twitter'
        ),

        'ywsfd_section_google'      => array(
            'name' => __( 'Google+', 'ywsfd' ),
            'type' => 'title',
            'desc' => '',
            'id'   => 'ywsfd_section_google',
        ),
        'ywsfd_enable_google'       => array(
            'name'    => __( 'Enable Google+ sharing', 'ywsfd' ),
            'type'    => 'checkbox',
            'desc'    => '',
            'id'      => 'ywsfd_enable_google',
            'default' => 'no',
        ),
        'ywsfd_section_end_google'  => array(
            'type' => 'sectionend',
            'id'   => 'ywsfd_section_end_google'
        ),

    )

);