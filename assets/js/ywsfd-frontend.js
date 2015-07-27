function get_coupon() {

    jQuery('#YWSFD_wrapper').fadeOut('slow');

    var data = {
        action : 'ywsfd_get_coupon',
        post_id: ywsfd.post_id
    };

    jQuery.post(ywsfd.ajax_url, data, function (response) {

        if (response.status == 'success') {

            set_coupon(response.coupon);

        } else {

            window.alert(response.error);

        }

    });

}

function set_coupon(coupon) {

    var data = {
        action     : 'woocommerce_apply_coupon',
        security   : ywsfd.apply_coupon_nonce,
        coupon_code: coupon
    };

    jQuery.post(ywsfd.ajax_url, data);

}

jQuery(function ($) {

    /**
     * If Facebook active
     */
    if (ywsfd.facebook == 'yes') {

        (function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {
                return;
            }
            js = d.createElement(s);
            js.id = id;
            js.src = '//connect.facebook.net/' + ywsfd.locale + '/sdk.js#xfbml=1&version=v2.4&appId=' + ywsfd.fb_app_id;
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));

        $('#ywsfd-facebook').click(function () {

            FB.ui({
                method: 'share',
                href  : $(this).prop('href')
            }, function (response) {

                if (response && response.post_id) {

                    get_coupon();

                }

            });

            return false;

        });

    }

    $(document).ready(function () {

        /**
         * If Twitter active
         */
        if (ywsfd.twitter == 'yes') {

            twttr.ready(function (twttr) {

                twttr.events.bind('tweet', function (event) {

                    get_coupon();

                });

            });

        }

    });

    /**
     * If Google+ active
     */
    if (ywsfd.google == 'yes') {

        $('#ywsfd-google').click(function () {

            var url = 'https://plus.google.com/share?url=' + $(this).prop('href');

            window.open(url, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');

            setTimeout(function () {
                get_coupon();

            }, 10000);

            return false;

        });

    }

});