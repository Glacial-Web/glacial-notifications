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

$content         = $glacial_notifications_options['content'];
$button          = $glacial_notifications_options['button'];
$cookie          = $glacial_notifications_options['cookie']['cookie'] ?? 0;
$closed_duration = $glacial_notifications_options['cookie']['closed_duration'] ?? 0;
$colors          = $glacial_notifications_options['colors'];
$style           = 'style="background-color:' . $colors['background'] . '; color:' . $colors['text'] . '"';

unset( $glacial_notifications_options['cookie'] );
unset( $glacial_notifications_options['content'] );
unset( $glacial_notifications_options['button'] );
unset( $glacial_notifications_options['front_page_only'] );

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
	'devices'             => 'all',
	'cookie'              => $cookie,
	'closed_duration'     => $closed_duration,
);

if ( ! empty( $glacial_notifications_options ) ) {
	$settings = wp_parse_args( $glacial_notifications_options, $defaults );
	$settings = array_merge( $defaults, $glacial_notifications_options );
} else {
	$settings = $defaults;
}

$settings_json = json_encode( $settings );
$data_props    = "data-props=$settings_json"; ?>

<div class="gla-noti-group gla-noti-sticky gla-noti-pos-top gla-noti-sticky-header">
    <div id="stickyNotification" class="gla-noti gla-noti-inner" <?php echo $data_props . ' ' . $style; ?> >
        <div class="gla-noti-content">

			<?php
			echo $content;

			if ( $button['text'] ):
				$target = isset($button['target']) ? '_blank' : '_self'; ?>

                <a href="<?php echo $button['url']; ?>" class="ui-button"
                   target="<?php echo $target; ?>"><?php echo $button['text']; ?></a>

			<?php endif; ?>

        </div>

        <a href="#" class="gla-noti-close-button" aria-label="Close this notification">
            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" class="gla-noti-close-icon"
                 viewBox="0 0 352 512">
                <path fill="<?php echo $colors['text']; ?>"
                      d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path>
            </svg>
        </a>
    </div>
</div>

