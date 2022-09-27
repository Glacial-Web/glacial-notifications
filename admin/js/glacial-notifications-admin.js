/*------------------------ 
Backend related javascript
------------------------*/

(function ($) {

    "use strict";

    $(document).ready(function () {

        function checkRadio() {
            let $cookie = $('#cookie-0');
            let $cookie_length = $('#cookie-length');
            if ($cookie.is(':checked')) {
                $cookie_length.show();
            } else {
                console.log($cookie_length);
                $cookie_length.hide();
            }
        }

        checkRadio();
        $('#cookie-radios input').on('click', function () {
           checkRadio();
        });

        /*	$.ajax({
                type : "post",
                dataType : "json",
                url : glanotifications.ajaxurl,
                data : {
                    action: "my_demo_ajax_call",
                    demo_data : 'test_data',
                    ajax_nonce_parameter: glanotifications.security_nonce
                },
                success: function(response) {
                    console.log( response );
                }
            });*/
    });

})(jQuery);
