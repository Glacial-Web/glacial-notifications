/*------------------------ 
Backend related javascript
------------------------*/

(function ($) {

    "use strict";

    $(document).ready(function () {

        const $cookie = $('#cookie-0');
        const $cookie_length = $('#cookie-length');

        function checkRadio() {
            if ($cookie.is(':checked')) {
                $cookie_length.show();
            } else {
                console.log($cookie_length);
                $cookie_length.hide();
            }
        }

        checkRadio();

        $cookie.on('click', function () {
            checkRadio();
        });

        $('.glacial-color-field').wpColorPicker({
                width: 300,
                clear: false,
                palettes: true,



            }
        );

    });

})(jQuery);
