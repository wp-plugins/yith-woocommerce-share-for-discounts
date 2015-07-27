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

?>

<?php if ( $social_params['facebook'] == 'yes' ) : ?>

    <a id="ywsfd-facebook"
       title="<?php _e( 'Facebook', 'ywsfd' ) ?>"
       rel="external nofollow"
       class="ywsfd-facebook"
       href="<?php echo $social_params['url']; ?>"
       >
        <i class="fa fa-facebook"></i>
        <span>
            <?php _e( 'Facebook', 'ywsfd' ) ?>
       </span>
    </a>
<?php endif; ?>

<?php if ( $social_params['twitter'] == 'yes' ) : ?>

    <a id="ywsfd-twitter"
       title="<?php _e( 'Twitter', 'ywsfd' ) ?>"
       rel="external nofollow"
       class="ywsfd-twitter"
       href="http://twitter.com/intent/tweet/?text=<?php echo $social_params['title']; ?>&url=<?php echo $social_params['url'] . ( ( !empty( $social_params['twitter_username'] ) ) ? '&via=' . $social_params['twitter_username'] : '' ) ?>"
       >
        <i class="fa fa-twitter"></i>
        <span>
            <?php _e( 'Twitter', 'ywsfd' ) ?>
       </span>
    </a>

<?php endif; ?>

<?php if ( $social_params['google'] == 'yes' ) : ?>

    <a id="ywsfd-google"
       title=" <?php _e( 'Google+', 'ywsfd' ) ?>"
       rel="external nofollow"
       class="ywsfd-google"
       href="<?php echo $social_params['url']; ?>"
       >
        <i class="fa fa-google-plus"></i>
        <span>
            <?php _e( 'Google+', 'ywsfd' ) ?>
       </span>
    </a>

<?php endif; ?>








