(function ($) {
    $(document).ready(function () {

        const glacialPopupID = 'glacialPopup' // ID of the popup

        if ($('#' + glacialPopupID).length > 0) {
            const cookie_name = 'glacial_notification_closed';
            const props = $('#glacialPopup').data('props');

            function show_popup() {

                MicroModal.show(glacialPopupID, {
                    onClose: set_cookie,
                    openTrigger: 'data-micromodal-open',
                    closeTrigger: 'data-micromodal-close',
                    openClass: 'is-open',
                    disableScroll: true,
                    disableFocus: false,
                    awaitOpenAnimation: true,
                    awaitCloseAnimation: true,
                });
            }

            function set_cookie() {

                if (props.cookie === '1') {

                    const expiry_days = props.closed_duration;
                    const value = 1;
                    const path = '; path=/';
                    let expires = '';

                    if (expiry_days !== '0') {
                        let date = new Date();
                        date.setTime(date.getTime() + (expiry_days * 24 * 60 * 60 * 1000));
                        expires = "; expires=" + date.toUTCString();
                    }

                    document.cookie = cookie_name + '=' + value + expires + path;
                }
            }

            function has_cookie(name) {

                if (props.cookie === '1') {

                    const cookieValue = document.cookie
                        .split('; ')
                        .find((row) => row.startsWith(name + '='))
                        ?.split('=')[1];

                    if (cookieValue === '1') {
                        return true;
                    }
                }

                return false;
            }

            if (has_cookie(cookie_name) === false) {
                show_popup();
            }

            $('#glacialPopup a').on('click', function () {
                MicroModal.close(glacialPopupID)
            });

        }

    });

})(jQuery);