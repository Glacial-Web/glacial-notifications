<?php
/**
 * The template for the sticky header notification.
 *
 *
 * @since         1.0.0
 * @package       Glacial_Notifications
 * @subpackage    Glacial_Notifications/includes
 * @author        Glacial Multimedia
 */

$glacial_notifications_options = get_option( 'glacial_notifications' );

$content = $glacial_notifications_options['content'];
unset( $glacial_notifications_options['content'] );


$defaults = array(
	'status'              => 'active',
	'display'             => 'immediate',
	'show_on'             => 'page_open',
	'show_after_duration' => '0',
	'open_animation'      => 'slide',
	'position'            => 'top',
	'sticky'              => 'yes',
	'close_animation'     => 'fade',
	'close_content_click' => 'yes',
	'auto_close'          => '0',
	'closed_duration'     => '0',
	'devices'             => 'all',
);

if ( ! empty( $glacial_notifications_options ) ) {
	$settings = wp_parse_args( $glacial_notifications_options, $defaults );
	$settings = array_merge( $defaults, $glacial_notifications_options );
} else {
	$settings = $defaults;
}

$settings_json = json_encode( $settings );
$data_props    = "data-props=$settings_json";
?>

<div class="gla-noti-group gla-noti-sticky gla-noti-pos-top gla-noti-sticky-header">
    <div id="stickyNotification" class="gla-noti gla-noti-inner" <?php echo $data_props; ?>>
		<?php echo $content; ?>
        <a href="#" class="ui-button">Click me</a>

        <a href="#" class="gla-noti-close-button" aria-label="Close this notification">
            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" class="gla-noti-close-icon"
                 viewBox="0 0 352 512">
                <path fill="currentColor"
                      d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path>
            </svg>
        </a>
    </div>
</div>

